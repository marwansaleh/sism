<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Incoming_m
 *
 * @author Marwan Saleh <marwan.saleh@ymail.com>
 */
class Incoming_m extends MY_Model {
    protected $_table_name = 'incoming';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'created desc';
    
    public $rules = array(
        'subject' => array(
            'field' => 'subject', 
            'label' => 'Subjek', 
            'rules' => 'trim|required|xss_clean'
        ),
        'mail_date' => array(
            'field' => 'mail_date', 
            'label' => 'Tanggal', 
            'rules' => 'trim|required|xss_clean'
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
        ),
        'receiver' => array(
            'field' => 'receiver', 
            'label' => 'Penerima', 
            'rules' => 'trim|numeric|required|xss_clean'
        )
    );
    
    
    public function save($data, $id = NULL) {
        //save to sender_name
        if (isset($data['sender_name']) && $data['sender_name']){
            $this->db->insert('sender_name', array('sender_name'=>$data['sender_name']));
        }
        return parent::save($data, $id);
    }
}

/*
 * file location: engine/application/models/mail/incoming_m.php
 */