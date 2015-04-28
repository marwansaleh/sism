<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Outgoing_m
 *
 * @author Marwan Saleh <marwan.saleh@ymail.com>
 */
class Outgoing_m extends MY_Model {
    protected $_table_name = 'outgoing';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'created desc';
    
    public $rules = array(
        'subject' => array(
            'field' => 'subject', 
            'label' => 'Subjek', 
            'rules' => 'trim|required|xss_clean'
        ),
        'receiver' => array(
            'field' => 'receiver', 
            'label' => 'Penerima', 
            'rules' => 'trim|numeric|required|xss_clean'
        ),
        'sender' => array(
            'field' => 'sender', 
            'label' => 'Pengirim', 
            'rules' => 'trim|numeric|required|xss_clean'
        ),
        'mail_date' => array(
            'field' => 'mail_date', 
            'label' => 'Tanggal', 
            'rules' => 'trim|xss_clean'
        ),
        'mail_no' => array(
            'field' => 'mail_no', 
            'label' => 'No. surat', 
            'rules' => 'trim|xss_clean'
        ),
        'content' => array(
            'field' => 'content', 
            'label' => 'Isi surat', 
            'rules' => 'trim|xss_clean'
        )
    );
    
}

/*
 * file location: engine/application/models/mail/outgoing_m.php
 */