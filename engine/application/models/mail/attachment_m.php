<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Attachment_m
 *
 * @author Marwan Saleh <marwan.saleh@ymail.com>
 */
class Attachment_m extends MY_Model {
    protected $_table_name = 'mail_attachments';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'created';
    
}

/*
 * file location: engine/application/models/mail/attachment_m.php
 */