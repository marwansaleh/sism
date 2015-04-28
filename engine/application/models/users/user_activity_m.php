<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of User_activity_m
 *
 * @author Marwan Saleh <marwan.saleh@ymail.com>
 */
class User_activity_m extends MY_Model {
    protected $_table_name = 'user_activity';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'date desc';
    
}

/*
 * file location: engine/application/models/users/user_activity_m.php
 */