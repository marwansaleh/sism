<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Template_m
 *
 * @author Marwan Saleh <marwan.saleh@ymail.com>
 */
class Template_m extends MY_Model {
    protected $_table_name = 'mail_templates';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'name asc';
    
    public $rules = array(
        'name' => array(
            'field' => 'name', 
            'label' => 'Template name', 
            'rules' => 'trim|required|xss_clean|callback__unique_template_name'
        )
    );
}

/*
 * file location: engine/application/models/mail/template_m.php
 */