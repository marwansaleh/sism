<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Home
 *
 * @author marwansaleh
 */
class Log extends MY_Controller {
    
    function index($lines=10){
        echo '<pre>'.$this->read_log($lines).'</pre>';
    }
}

/*
 * file location: engine/application/controllers/log.php
 */
