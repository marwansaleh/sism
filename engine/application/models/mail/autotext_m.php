<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Autotext_m
 *
 * @author Marwan Saleh <marwan.saleh@ymail.com>
 */
class Autotext_m extends MY_Model {
    protected $_table_name = 'autotexts';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'name asc';
}

/*
 * file location: engine/application/models/mail/autotext_m.php
 */