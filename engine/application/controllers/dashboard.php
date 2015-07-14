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
        $this->data['disposition_receive_count'] = $this->disposition_m->get_count(array('receiver'=>$me->id));
        
        $latest_num_records = 10;
        $last_incomings = $this->incoming_m->get_offset('*', $this->users->has_access('INCOMING_VIEW_ALL') ? NULL : array('receiver'=>$me->id),0,$latest_num_records);
        $this->data['last_incomings'] = array();
        foreach ($last_incomings as $incoming){
            $incoming->receiver_name = $incoming->receiver==$me->id ? 'You' : $this->user_m->get_value('full_name', array('id'=>$incoming->receiver));
            $incoming->status_name = mail_status($incoming->status, MAIL_TYPE_INCOMING, SIDE_RECEIVER);
            
            $this->data['last_incomings'] [] = $incoming;
        }
        
        $this->db->where('receiver', $me->id)->or_where('sender', $me->id);
        $last_dispositions = $this->disposition_m->get_offset('*', NULL,0,$latest_num_records);
        $this->data['last_dispositions'] = array();
        foreach ($last_dispositions as $disposition){
            $disposition->status_name = mail_status($disposition->status, $disposition->mail_type, $disposition->sender==$this->users->get_userid()?SIDE_SENDER:SIDE_RECEIVER);
            if ($disposition->mail_type==MAIL_TYPE_INCOMING){
                $disposition->subject = $this->incoming_m->get_value('subject', array('id' => $disposition->mail_id));
            } else {
                $disposition->subject = $this->outgoing_m->get_value('subject', array('id' => $disposition->mail_id));
            }
            if ($disposition->sender == 0) {
                if ($disposition->mail_type == MAIL_TYPE_INCOMING) {
                    $disposition->sender_name = $this->incoming_m->get_value('sender_name', array('id' => $disposition->mail_id));
                } else {
                    $disposition->sender_name = '';
                }
            } else {
                $disposition->sender_name = $disposition->sender==$me->id ? 'You' : $this->user_m->get_value('full_name', array('id' => $disposition->sender));
            }
            $disposition->receiver_name = $disposition->receiver == $me->id ? 'You' : $this->user_m->get_value('full_name', array('id'=>$disposition->receiver));
            
            $this->data['last_dispositions'] [] = $disposition;
        }
        
        $last_outgoings = $this->outgoing_m->get_offset('*', $this->users->has_access('OUTGOING_VIEW_ALL') ? NULL : array('sender'=>$me->id),0,$latest_num_records);
        $this->data['last_outgoings'] = array();
        foreach ($last_outgoings as $outgoing){
            $outgoing->sender_name = $outgoing->sender == $me->id ? 'You' : $this->user_m->get_value('full_name', array('id'=>$outgoing->sender));
            $outgoing->receiver_name = $outgoing->receiver>0 ? $this->user_m->get_value('full_name', array('id'=>$outgoing->receiver)):$outgoing->literally_receiver;
            $outgoing->status_name = mail_status($outgoing->status, MAIL_TYPE_OUTGOING, SIDE_SENDER);
            
            $this->data['last_outgoings'] [] = $outgoing;
        }
        
        //get visitors
        $this->data['user_count'] = $this->user_m->get_count(array('is_active'=>1));
        
        //get users
        $this->data['user_onlines'] = array();
        $users = $this->user_m->get_select_where('id,session_id,full_name',array('is_active'=>1));
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
