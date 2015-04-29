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
    
    public $rules = array(
        'name' => array(
            'field' => 'name', 
            'label' => 'Template name', 
            'rules' => 'trim|required|xss_clean'
        ),
        'header' => array(
            'field' => 'header', 
            'label' => 'Header', 
            'rules' => 'trim|required|xss_clean'
        ),
        'body' => array(
            'field' => 'body', 
            'label' => 'Body', 
            'rules' => 'trim|xss_clean'
        ),
        'footer' => array(
            'field' => 'footer', 
            'label' => 'Footer', 
            'rules' => 'trim|xss_clean'
        )
    );
}

/*
 * file location: engine/application/models/mail/template_m.php
 */