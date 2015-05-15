<?php
/**
 * Description of Outgoinglib
 *
 * @author marwansaleh
 */
class Outgoinglib extends Library {
    private static $instance;
    
    function __construct() {
        parent::__construct();
        
        $this->ci->load->model(array('mail/template_m','mail/outelement_m'));
        
    }
    
    function getInstance(){
        if (!self::$instance){
            self::$instance = new Outgoinglib();
        }
        
        return self::$instance;
    }
    
    public function get($mail_id, $template_id){
        //$template = 
    }
}
