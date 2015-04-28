<?php

class Welcome extends CI_Controller {
    function index(){
        echo current_url();
    }
}