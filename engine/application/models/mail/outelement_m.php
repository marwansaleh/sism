<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Outelement_m
 *
 * @author Marwan Saleh <marwan.saleh@ymail.com>
 */
class Outelement_m extends MY_Model {
    protected $_table_name = 'outgoing_elements';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'outgoing_id, last_update desc';
}

/*
 * file location: engine/application/models/mail/outelement_m.php
 */