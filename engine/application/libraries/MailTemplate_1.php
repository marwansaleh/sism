<?php

/**
 * Description of MailTemplate
 *
 * @author marwansaleh
 */
class MailTemplate extends Library {
    private static $instance;
    
    function __construct() {
        parent::__construct();
    }
    
    public static function getInstance(){
        if (!self::$instance){
            self::$instance = new MailTemplate();
        }
        
        return self::$instance;
    }
    
    /**
     * Register new autotexts
     * @param string $name
     * @param string $title
     * @param string $content
     * @param tinyint $editable 0:non editable 1:editable
     * @return boolean TRUE if success
     */
    public function autotext_register($name,$title,$content=NULL,$callback=NULL,$editable=1,$generate_code=FALSE){
        if (!isset($this->ci->autotext_m)){
            $this->ci->load->model('mail/autotext_m');
        }
        //ensure name is valid
        $name = url_title($name, '_', TRUE);
        if ($this->ci->autotext_m->get_count(array('name'=>$name))){
            return FALSE;
        }else{
            $result = $this->ci->autotext_m->save(array(
                'name'              => $name,
                'title'             => $title,
                'content'           => $content,
                'content_callback'  => $callback,
                'editable'          => $editable
            ));
            if ($result && $generate_code){
                //generate PHP code
                $this->_generate_autotext_php($name);
            }
            
            return $result;
        }
    }
    
    /**
     * Unregister the autotext and delete the php class file if exists
     * @param type $name
     * @return boolean TRUE if success
     */
    public function autotext_unreg ($name){
        if (!isset($this->ci->autotext_m)){
            $this->ci->load->model('mail/autotext_m');
        }
        
        $autotext = $this->ci->autotext_m->get_by(array('name'=>$name),TRUE);
        if ($autotext){
            //remove from database
            $this->ci->autotext_m->delete($autotext->id);
            
            //check if doesnt have call back, we assume this autotext has package need to be deleted
            if (!$autotext->content_callback){
                //check if file exits, delete
                $file = APPPATH . 'third_party/autotext/libraries/'.ucfirst($name).'.php';
                if (file_exists($file)){
                    unlink($file);
                }
            }
            return TRUE;
        }
        
        return FALSE;
    }
    
    /**
     * Get autotext by key (id || name)
     * @param mixed $key can be id (int) or name (string)
     * @return stdclass autotext
     */
    public function get_autotext($key){
        if (!isset($this->ci->autotext_m)){
            $this->ci->load->model('mail/autotext_m');
        }
        if (is_int($key)){
            $autotext = $this->ci->autotext_m->get($key);
        }else{
            $autotext =  $this->ci->autotext_m->get_by(array('name'=>$key), TRUE);
        }
        
        return $this->_autotext_prepare($autotext);
    }
    
    /**
     * Get list of autotexts
     * @return mixed array of autotext
     */
    public function get_autotexts(){
        if (!isset($this->ci->autotext_m)){
            $this->ci->load->model('mail/autotext_m');
        }
        
        $result = $this->ci->autotext_m->get();
        $autotexts = array();
        foreach ($result as $autotext){
            $autotexts [$autotext->name] = $this->_autotext_prepare($autotext);
        }
        
        return $autotexts;
    }
    
    /**
     * Get template by key
     * @param mixed $id can be int id or string name
     * @return stdclass template
     */
    public function get_template($id){
        if (!isset($this->ci->template_m)){
            $this->ci->load->model('mail/template_m');
        }
        if (intval($id)){
            $template = $this->ci->template_m->get($id);
        }else{
            $template = $this->ci->template_m->get_by(array('name'=>$id), TRUE);
        }
        if (!$template){
            return NULL;
        }
        //extract template parts
        $content = json_decode($template->content);
        $template->header = isset ($content->header) ? $content->header:'';
        $template->body = isset($content->body) ? $content->body:'';
        $template->footer = isset($content->footer) ? $content->footer:'';
        
        //extract print attributes
        $print_attributes = json_decode($template->print_attributes);
        if ($print_attributes){
            $template->print_attributes = $print_attributes;
        }else{
            $template->print_attributes = NULL;
        }
        
        return $template;
    }
    
    public function get_template_print_attributes($key=NULL){
        $default = array(
            'paper'=> array('label'=>'Paper','callback'=>'prt_attribute_paper','value'=>'A4'),
            'orientation'=>array('label'=>'Orientation','callback'=>'prt_attribute_orientation','value'=>'P'),
            'unit'=>array('label'=>'Measure units','callback'=>'prt_attribute_unit','value'=>'cm'),
            'margin'=>array('label'=>'Margin','callback'=>'prt_attribute_margin','value'=>1),
            'font_name'=>array('label'=>'Font name','callback'=>'prt_attribute_font_name','value'=>'Arial'),
            'font_size'=>array('label'=>'Font size','callback'=>'prt_attribute_font_size','value'=>12),
            'font_style'=>array('label'=>'Font style','callback'=>'prt_attribute_font_style','value'=>'')
        );
        
        if ($key){
            if (isset($default[$key])){ return $default[$key]; }
        }else{
            return $default;
        }
    }
    
    /**
     * Get parsed html content
     * @param type $content
     * @return string parsed template
     */
    public function parse_template($content=NULL, $type=TPL_INPUT_FORM, $values=NULL){
        $parsed = '';
        
        //get all autotext
        $autotexts = $this->get_autotexts();
        if ($autotexts){
            $autoText_array = array('search'=>array(),'replace'=>array());
            foreach ($autotexts as $autotext){
                $autoText_array['search'] [] = $autotext->code;
                $autoText_array['replace'] [] = $autotext->content;
            }
            
            $parsed = str_replace($autoText_array['search'], $autoText_array['replace'], $content);
        }
        
        return $parsed;
    }
    
    
    /**
     * Parse content into html page
     * @param string $content
     * @param array $meta
     * @return string html page
     */
    public function parse_page_html($template, $meta=NULL){
        $data = array(
            'header'    => $this->parse_template($template->header),
            'body'      => $this->parse_template($template->body),
            'footer'    => $this->parse_template($template->footer),
            'meta'      => $meta
        );
        $view_template = $this->ci->load->view('cms/mail/template/template',$data, TRUE);
        
        return $view_template;
    }


    /**
     * Prepare autotext record
     * @param stdclass $autotext
     * @return stdClass of prepared autotext record
     */
    private function _autotext_prepare($autotext=NULL, $id=NULL){
        if (!$autotext){
            return NULL;
        }
        //give marks for name
        $autotext->code = '{'.$autotext->name.'}';
        
        if (!$autotext->content){
            //check if has call_back, if use package
            if ($autotext->content_callback){
                //check if is it an array
                if (strpos($autotext->content_callback, ',')!== FALSE){
                    $class_method = explode(',', str_replace(' ', '', $autotext->content_callback));
                    if (is_callable($class_method)){
                        $autotext = call_user_func_array($class_method, array($autotext, $id));
                    }
                }else if (is_callable($autotext->content_callback)){
                    $autotext = call_user_func_array($autotext->content_callback, array($autotext, $id));
                }
            }else{
                //check if a package exists
                $package = $autotext->name;
                if (file_exists(APPPATH .'third_party/autotext/libraries/' . ucfirst($package).'.php')){
                    //load the package
                    if (!isset($this->ci->$package)){
                        $this->ci->load->add_package_path(APPPATH .'third_party/autotext/');
                        $this->ci->load->library(ucfirst($package));
                        //$this->ci->load->remove_package_path();
                    }
                    $this->ci->$package->getValue($autotext, $id);
                }
            }
        }
        
        return $autotext;
    }
    
    /**
     * Get full image url
     * @param string $image_name
     * @return string full image url
     */
    private function _get_image_autotext($image_name=NULL){
        if (!$image_name){
            return NULL;
        }
        return $image_name;
    }
    
    /**
     * Generate basic PHP package library
     * @param string $name package name
     * @return boolean TRUE if success
     */
    private function _generate_autotext_php($name){
        $file = APPPATH . 'third_party/autotext/libraries/'.ucfirst($name).'.php';
        
        //do not procces if file exists
        if (file_exists($file)){
            return FALSE;
        }
        
        //create helper function for identation
        if (!function_exists('identation')){
            function identation($ident_level=1){
                $multiplier = 4;
                return str_repeat(' ', $ident_level * $multiplier);
            }
        }
        
        $str = array(
            '<?php',
            '/* This class generated by system using basic template */',
            '/* Edit the code to your need */',
            '',
            'class '.ucfirst($name) .'{',
            identation(1).'//must at least has one public method getValue',
            identation(1) .'function getValue(&$autoText){',
            identation(2).'//Do something here',
            identation(2). '$autoText->content="";',
            identation(1).'}',
            '}'
        );
        $result = file_put_contents($file, implode(PHP_EOL, $str));
        //make it editable
        if ($result){
            chmod($file, 0775);
            return TRUE;
        }
    }
}

class Autotext {
    function horizontal_line($autotext=NULL, $id=NULL){
        if ($autotext){
            $autotext->content = '<hr class="mail-header-line">';
        }
        
        return $autotext;
    }
    
    function sifat_surat($autotext=NULL, $id=NULL){
        if ($autotext){
            $autotext->content = 'penting';
        }
        
        return $autotext;
    }
    
    function lampiran_surat($autotext=NULL, $id=NULL){
        if ($autotext){
            $autotext->content = '-';
        }
        
        return $autotext;
    }
    
    function subjek_surat($autotext=NULL, $id=NULL){
        if ($autotext){
            if ($id){
                $ci =& get_instance();
                $ci->load->model('mail/outgoing_m');
                $item = $ci->outgoing_m->get($id);
                if (!$item){
                    return $autotext;
                }
                //get subject
                $autotext->content = $item->subject;
            }else{
                $autotext->content = 'Perihal surat default';
            }
        }
        
        return $autotext;
    }
    
    function kepada_yth($autotext=NULL, $id=NULL){
        if ($autotext){
            $autotext->content = 'Bp. bla..bla';
        }
        
        return $autotext;
    }
    
    function kepada_yth_di($autotext=NULL, $id=NULL){
        if ($autotext){
            $autotext->content = 'Tempat';
        }
        
        return $autotext;
    }
}

/*
 * file location: /application/libraries/MailTemplate.php
 */
