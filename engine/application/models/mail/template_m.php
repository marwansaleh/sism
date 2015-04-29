<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Template_m
 *
 * @author Marwan Saleh <marwan.saleh@ymail.com>
 */
class Template_m extends MY_Model {
    protected $_table_name = 'mail_template';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'name asc';
    
}

/*
 * file location: engine/application/models/mail/template_m.php
 */