<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Division_m
 *
 * @author Marwan Saleh <marwan.saleh@ymail.com>
 */
class Division_m extends MY_Model {
    protected $_table_name = 'division';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'sort, division';
    
}

/*
 * file location: engine/application/models/users/division_m.php
 */