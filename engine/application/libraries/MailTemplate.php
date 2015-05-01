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
    public function get_autotexts(){
        if (!isset($this->ci->autotext_m)){
            $this->ci->load->model('mail/autotext_m');
        }
        
        $result = $this->ci->autotext_m->get();
        $autotexts = array();
        foreach ($result as $autotext){
            $autotexts [$autotext->name] = $this->_prepare($autotext);
        }
        
        return $autotexts;
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
        
        return $this->_prepare($autotext);
    }


    /**
     * Prepare autotext record
     * @param stdclass $autotext
     * @return stdClass of prepared autotext record
     */
    private function _prepare($autotext=NULL){
        if (!$autotext){
            return NULL;
        }
        //give marks for name
        $autotext->code = '{'.$autotext->name.'}';
        switch ($autotext->content_type){
            case AUTOTEXT_TYPE_IMAGE:
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
}

/*
 * file location: /application/libraries/MailTemplate.php
 */
