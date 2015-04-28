<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Google_service for Ajax Call
 *
 * @author Marwan Saleh
 * @email amazzura.biz@gmail.com
 */
class Google_service extends MY_Ajax {
    
    protected $GOOGLE;
    function __construct() {
        parent::__construct();
        
        $this->GOOGLE = new GoogleShortener();
    }
    function shortener(){
        $input_url = $this->input->post('url');
        
        if (!$input_url){
            echo 'Not a valid URL';
        }else{
            $output = $this->GOOGLE->shortener($input_url);
            if (isset($output->id)){
                echo $output->id;
            }else if (isset($output->error)){
                echo $output->error->message;
            }
        }
    }
    
    function expander(){
        $input_url = $this->input->post('url');
        
        if (!$input_url){
            echo 'Not a valid URL';
        }else{
            $output = $this->GOOGLE->expand($input_url);
            if (isset($output->status) && $output->status=='OK'){
                echo $output->longUrl;
            }else{
                echo $output->message;
            }
        }
    }
    
    function analytic(){
        $input_url = $this->input->post('url');
        
        $data = array('result'=>0);
        
        if (!$input_url){
            $data['error_message'] = 'Not valid long URL';
        }else{
            $output = $this->GOOGLE->analytic($input_url);
            if (isset($output->status) && $output->status=='OK'){
                $data['result'] = 1;
                $data['analytics'] = $this->GOOGLE->parseAnalytics($output->analytics);
            }else{
                $data['error_message'] = $output->message;
            }
        }
        
        $this->send_output($data);
    }
}

/*
 * file location: ./application/controllers/ajax/google_service.php
 */