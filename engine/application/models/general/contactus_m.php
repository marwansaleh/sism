<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Contactus_m
 *
 * @author Marwan
 * @email amazzura.biz@gmail.com
 */
class Contactus_m extends MY_Model {
    protected $_table_name = 'contactus';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'date desc';
    
    public $rules = array(
        'subject' => array(
            'field' => 'subject', 
            'label' => 'Subjek', 
            'rules' => 'trim|required|xss_clean'
        ),
        'name' => array(
            'field' => 'name', 
            'label' => 'Nama', 
            'rules' => 'trim|required|xss_clean'
        ),
        'email' => array(
            'field' => 'email', 
            'label' => 'Email', 
            'rules' => 'required|valid_email|xss_clean'
        ),
        'content' => array(
            'field' => 'content', 
            'label' => 'Isi pesan', 
            'rules' => 'trim|required|xss_clean'
        ),
        'captcha' => array(
            'field' => 'captcha', 
            'label' => 'Kode captcha', 
            'rules' => 'trim|required|xss_clean'
        )
    );
}

/*
 * file location: /application/models/general/contactus_m.php
 */
