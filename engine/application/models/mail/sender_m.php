<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Incoming_m
 *
 * @author Marwan Saleh <marwan.saleh@ymail.com>
 */
class Sender_m extends MY_Model {
    protected $_table_name = 'sender_name';
    protected $_primary_key = 'sender_name';
    protected $_primary_filter = 'strval';
    protected $_order_by = 'sender_name';
    
    function get_array(){
        $names = array();
        $result = $this->get();
        
        if ($result){
            foreach ($result as $name){
                $names [] = $name->sender_name;
            }
        }
        return $names;
    }
}

/*
 * file location: engine/application/models/mail/sender_m.php
 */