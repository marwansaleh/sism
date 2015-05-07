<?php

/**
 * Description of Autotexts
 *
 * @author marwansaleh
 */
class Autotexts extends Library {
    private $_autotexts = array();
    private $_dictionary = array();
    
    private static $instance;
    
    function __construct($dictionary=NULL) {
        parent::__construct();
        if ($dictionary && is_array($dictionary)){
            $this->_dictionary = $dictionary;
        }
    }
    
    public static function getInstance(){
        if (!self::$instance){
            self::$instance = new Autotexts();
        }
        
        return self::$instance;
    }
    
    /**
     * Parse autotext to get the real value
     * @param string $autotext
     * @param int $mail_id
     * @return string
     */
    public function parse_autotext($autotext, $mail_id=NULL){
        $autotext_content = $this->get_autotext_value($autotext, $mail_id);
        if ($autotext_content){
            return $autotext_content;
        }else {
            $autotext_content = $this->get_dictionary_value($autotext);
            if ($autotext_content){
                return $autotext_content;
            }
        }
        
        return $autotext;
    }
    
    /**
     * Set dictionary to be used in parsing
     * @param array $dictionary associative array
     */
    public function set_dictionary($dictionary){
        if (is_array($dictionary)){
            $this->_dictionary = $dictionary;
        }
    }
    
    /**
     * Add new dictionary to the cache
     * @param type $array associative array
     */
    public function add_dictionary($array){
        foreach ($array as $key=> $value){
            if (!isset($this->_dictionary[$key])){
                $this->_dictionary[$key] = $value;
            }
        }
    }
    
    /**
     * Get value from dictionary
     * @param string $autotext
     * @return string if found or NULL if not found
     */
    public function get_dictionary_value($autotext){
        //cleaning up before proccess
        $autotext_name = str_replace(array('{','}'), array('',''),$autotext);
        
        if (isset($this->_dictionary[$autotext_name])){
            return $this->_dictionary[$autotext_name];
        }
        
        return NULL;
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
        
        return $autotext;
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
            $autotexts [$autotext->name] = $autotext;
        }
        
        return $autotexts;
    }
    
    public function get_autotext_value($autotext, $mail_id=NULL){
        //cleaning up before proccess
        $autotext_name = str_replace(array('{','}'), array('',''),$autotext);
        
        if (isset($this->_autotexts[$autotext_name])){
            return $this->_autotexts[$autotext_name];
        }
        
        //get autotext from database and prepare
        $autotext_item_prepared = $this->_autotext_prepare($this->get_autotext($autotext_name), $mail_id);
        //update cache if content success
        if ($autotext_item_prepared){
            $this->_autotexts[$autotext_name] = $autotext_item_prepared->content;
            
            return $autotext_item_prepared->content;
        }
        
        return NULL;
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
 * file location: /application/libraries/Autotexts.php
 */
