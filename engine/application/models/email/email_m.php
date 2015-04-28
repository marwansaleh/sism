<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Email_m
 *
 * @author Marwan
 * @email amazzura.biz@gmail.com
 */
class Email_m extends MY_Model {
    protected $_table_name = 'email_jobs';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'inserted desc';
    
    public $rules = array(
        'recipient_email' => array(
            'field' => 'recipient_email', 
            'label' => 'Recipient email', 
            'rules' => 'trim|required|xss_clean'
        ),
        'subject' => array(
            'field' => 'subject', 
            'label' => 'Subject', 
            'rules' => 'trim|required|xss_clean'
        )
    );
}

/*
 * file location: /application/models/email/email_m.php
 */
