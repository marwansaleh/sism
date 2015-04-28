<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Useraccess_u_m
 *
 * @author Marwan Saleh <marwan.saleh@ymail.com>
 */
class Useraccess_u_m extends MY_Model {
    protected $_table_name = 'user_access';
    protected $_primary_key = 'access_id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'user_id';
    
    public $rules = array(
        'user_id'  => array(
            'field' => 'user_id', 
            'label' => 'user id', 
            'rules' => 'trim|required|xss_clean'
        )
    );
}

/*
 * file location: engine/application/models/users/useraccess_u_m.php
 */