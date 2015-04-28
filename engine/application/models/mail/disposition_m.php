<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Disposition_m
 *
 * @author Marwan Saleh <marwan.saleh@ymail.com>
 */
class Disposition_m extends MY_Model {
    protected $_table_name = 'disposition';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'created desc';
    
    public $rules = array(
        'mail_id' => array(
            'field' => 'mail_id', 
            'label' => 'Mail ID', 
            'rules' => 'trim|required|xss_clean'
        ),
        'mail_type' => array(
            'field' => 'mail_type', 
            'label' => 'Tipe', 
            'rules' => 'trim|required|xss_clean'
        ),
        'receiver' => array(
            'field' => 'receiver[]', 
            'label' => 'Penerima', 
            'rules' => 'trim|required|xss_clean'
        ),
        'notes' => array(
            'field' => 'content', 
            'label' => 'Isi surat', 
            'rules' => 'trim|xss_clean'
        )
    );
}

/*
 * file location: engine/application/models/mail/disposition_m.php
 */