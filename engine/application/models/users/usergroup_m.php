<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Usergroup_m
 *
 * @author Marwan Saleh <marwan.saleh@ymail.com>
 */
class Usergroup_m extends MY_Model {
    protected $_table_name = 'user_groups';
    protected $_primary_key = 'group_id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'sort';
    
    private $_ADMIN_GROUP_ID = 1;
    
    public $rules = array(
        'group_name' => array(
            'field' => 'group_name', 
            'label' => 'group name', 
            'rules' => 'trim|required|xss_clean'
        ),
        'is_removable' => array(
            'field' => 'is_removable', 
            'label' => 'removable', 
            'rules' => 'trim|numeric|xss_clean'
        )
    );
}

/*
 * file location: engine/application/models/users/usergroup_m.php
 */