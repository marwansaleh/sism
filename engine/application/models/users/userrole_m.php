<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Useraccess_m
 *
 * @author Marwan Saleh <marwan.saleh@ymail.com>
 */
class Userrole_m extends MY_Model {
    protected $_table_name = 'user_roles';
    protected $_primary_key = 'role_id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'role_name';
    
    public $rules = array(
        'role_name'  => array(
            'field' => 'role_name', 
            'label' => 'Nama rule', 
            'rules' => 'trim|required|xss_clean'
        )
    );
    
}

/*
 * file location: engine/application/models/users/userrole_m.php
 */