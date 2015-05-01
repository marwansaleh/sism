<?php

/**
 * Description of MailTemplate
 *
 * @author marwansaleh
 */
class MailTemplate extends Library {
    function __construct() {
        parent::__construct();
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
    
    private function _prepare($autotext=NULL){
        if (!$autotext){
            return NULL;
        }
        switch ($autotext->content_type){
            case AUTOTEXT_TYPE_IMAGE:
        }
        
        return $autotext;
    }
}

/*
 * file location: /application/libraries/MailTemplate.php
 */
