<?php

/**
 * Description of Layout for Ajax Call
 *
 * @author marwan
 * @email amazzura.biz@gmail.com
 */
class Layout extends MY_Ajax {
    
    function __construct() {
        parent::__construct();
    }
    
    function sidebar(){
        $display = $this->input->post('display');
        
        $this->session->set_userdata('sidebar_collapse', $display!=1);
    }
}

/*
 * file location: ./application/controllers/ajax/access.php
 */