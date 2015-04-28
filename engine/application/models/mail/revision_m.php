<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Revision_m
 *
 * @author Marwan Saleh <marwan.saleh@ymail.com>
 */
class Revision_m extends MY_Model {
    protected $_table_name = 'outgoing';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'revision desc';
    
}

/*
 * file location: engine/application/models/mail/revision_m.php
 */