<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Dashboard
 *
 * @author marwansaleh
 */
class Dashboard extends MY_AdminController {
    function __construct() {
        parent::__construct();
        $this->data['active_menu'] = 'dashboard';
        $this->data['page_title'] = '<i class="fa fa-home"></i> Dashboard';
        //$this->data['page_description'] = 'List and update articles';
    }
    
    function index(){
        //Load models
        $this->load->model(array('mail/incoming_m','mail/disposition_m','mail/outgoing_m','system/visitor_m'));
        
        $me = $this->users->me();
        
        //get num of incoming
        $this->data['incoming_total_count'] = $this->incoming_m->get_count(NULL);
        $this->data['incoming_count'] = $this->incoming_m->get_count(array('receiver'=>$me->id));
        //get num of outgoing
        $this->data['outgoing_total_count'] = $this->outgoing_m->get_count(NULL);
        $this->data['outgoing_count'] = $this->outgoing_m->get_count(array('sender'=>$me->id));
        //get num of disposition
        $this->data['disposition_send_count'] = $this->disposition_m->get_count(array('sender'=>$me->id));
        $this->data['disposition_receive_count'] = $this->disposition_m->get_count(array('receive'=>$me->id));
        
        //get visitors
        $this->data['user_count'] = $this->user_m->get_count();
        
        //get users
        $this->data['user_onlines'] = array();
        $users = $this->user_m->get_select_where('id,session_id,full_name',NULL);
        foreach ($users as $user){
            $user->is_online = $this->users->is_online($user->session_id);
            $this->data['user_onlines'] [] = $user;
        }
        
        $this->data['subview'] = 'cms/dashboard/index';
        $this->load->view('_layout_admin', $this->data);
    }
    
}

/*
 * file location: engine/application/controllers/dashboard.php
 */
