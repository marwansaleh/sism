<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Email_cid_m
 *
 * @author Marwan
 * @email amazzura.biz@gmail.com
 */
class Email_cid_m extends MY_Model {
    protected $_table_name = 'email_image_cid';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = 'email_job_id';
}

/*
 * file location: /application/models/email/email_cid_m.php
 */
