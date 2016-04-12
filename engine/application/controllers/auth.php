<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Home
 *
 * @author marwansaleh
 */
class Auth extends MY_Controller {
    
    function __construct() {
        parent::__construct();
        $this->data['body_class'] = 'login-body';
    }
    
    function index(){
        $this->_check_body_cover();
        
        $this->load->helper('cookie');
        
        if ($this->users->isLoggedin()){
            redirect(site_url('dashboard'));
        }
        
        if ($this->session->flashdata('message')){
            $this->data['message_error'] = create_alert_box($this->session->flashdata('message'), $this->session->flashdata('message_type'));
        }
        
        $cookie_login = $this->input->cookie('cookie-login');
        if ($cookie_login){
            $this->data['remember'] = json_decode($cookie_login);
        }else{
            $this->data['remember'] = NULL;
        }
        
        $this->data['submit'] = site_url('auth/login');
        $this->data['subview'] = 'login/index';
        //var_dump($cookie_login);
        $this->load->view('_layout_login', $this->data);
    }
    
    private function _check_body_cover(){
        $body_cover = $this->get_sysvar_value('LOGIN_PAGE_BG_IMAGE');
        if ($body_cover){
            if ($body_cover != 'NULL' || $body_cover != 'none'){
                //set body cover in global style
                if (file_exists($body_cover)){
                    $this->_global_style($body_cover);
                }
            }
        }
        return NULL;
    }
    
    private function _global_style($cover){
        $global_style = '<style>
                .login-body{
                    background: url("'.$cover.'") no-repeat center center fixed; 
                    -webkit-background-size: cover;
                    -moz-background-size: cover;
                    -o-background-size: cover;
                    background-size: cover;
                }
                </style>';
        $this->data['global_style'] = $global_style;
        $this->data['body_class'] = 'login-body';
    }
    
    function login(){
        $this->load->helper('cookie');
        $this->load->library('form_validation');
        
        $rules = $this->user_m->rules_login;
        $this->form_validation->set_rules($rules);
        
        //exit(print_r($rules));
        if ($this->form_validation->run() != FALSE) {
            
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $remember = $this->input->post('remember');
        
            $this->_write_log('Try to validate using username: '.$username);
            //check flag remember me to create cookie
            if ($remember){
                $cookie = array(
                    'name'   => 'cookie-login',
                    'value'  => json_encode(array('username'=>$username, 'password'=>$password)),
                    'expire' => strtotime('December 31 2020')
                );

                $this->input->set_cookie($cookie);
            }else{
                delete_cookie('cookie-login');
            }
            
            $user = $this->users->login($username, $password);
            
            if (!$user){
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', $this->users->get_message());
                
                $this->_write_log('Login failed using username '.$username);
                
                redirect('auth');exit;
            }else{
                $this->_write_log('Success login for username '.  $username.'...redirecting to admin page...');
                
                $this->user_activity('Success login using '. ($this->is_device('DESKTOP') ? 'Desktop Browser':'Mobile'), $this->users->get_userid());
                redirect('dashboard');exit;
            }
        }
        
        if (validation_errors()){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', validation_errors());
        }
        
        redirect('auth');
    }
    
    function logout(){
        $this->user_activity("Logout from application", $this->users->get_userid());
        
        $this->users->logout();
        redirect('auth');
    }
    
    
    function hashit(){
        $subject = $this->input->get('subject');
        
        echo $this->users->hash($subject);
    }
}

/*
 * file location: engine/application/controllers/auth.php
 */
