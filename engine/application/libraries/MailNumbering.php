<?php

/**
 * Description of MailNumbering
 *
 * @author marwansaleh
 */
class MailNumbering extends Library {
    private static $instance;
    private $_format = array();
    
    const TB_HISTORY = 'mail_number_history';
    const TB_MASTER = 'mail_number_master';
    const TB_DIVISION = 'division';
    
    function __construct() {
        parent::__construct();
    }
    
    public function getInstance(){
        if (!self::$instance){
            self::$instance = new MailNumbering();
        }
        
        return self::$instance;
    }
    
    public function generate_number($division, $month=NULL, $year=NULL){
        $generated_number = 0;
        
        if (!$month){ $month = date('m'); }
        if (!$year){ $year = date('Y'); }
        
        return $generated_number;
    }
    
    public function last_number ($division, $month = NULL, $year = NULL){
        $last_number = 0;
        if (!$month){ $month = date('m'); }
        if (!$year){ $year = date('Y'); }
        
        return ($last_number+1);
    }
    
    public function get_formatted_number($division, $month=NULL, $year=NULL){
        if (!$month){ $month = date('m'); }
        if (!$year){ $year = date('Y'); }
    }
}
