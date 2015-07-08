<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Profile
 *
 * @author marwansaleh
 */
class Profile extends MY_AdminController {
    function __construct() {
        parent::__construct();
        $this->data['active_menu'] = 'users';
        $this->data['page_title'] = '<i class="fa fa-users"></i> User Profile';
        $this->data['page_description'] = 'Profile selected user';
        
        //Loading model
        $this->load->model(array('users/usergroup_m'));
    }
    
    function index(){
        $userid = $this->input->get('id', TRUE) ? $this->input->get('id', TRUE) : $this->users->get_userid();
        $me = $this->users->me();
        
        //get user info
        $user = $this->users->get_user_info($userid);
        $this->data['user'] = $user;
        
        //get last mail
        $latest_num_records = 10;
        
        if (!isset($this->incoming_m)){
            $this->load->model('mail/incoming_m');
        }
        $last_incomings = $this->incoming_m->get_offset('*', $this->users->has_access('INCOMING_VIEW_ALL') ? NULL : array('receiver'=>$me->id),0,$latest_num_records);
        $this->data['last_incomings'] = array();
        foreach ($last_incomings as $incoming){
            $incoming->receiver_name = $incoming->receiver==$me->id ? 'You' : $this->user_m->get_value('full_name', array('id'=>$incoming->receiver));
            $incoming->status_name = mail_status($incoming->status, MAIL_TYPE_INCOMING, SIDE_RECEIVER);
            
            $this->data['last_incomings'] [] = $incoming;
        }
        
        if (!isset($this->disposition_m)){
            $this->load->model('mail/disposition_m');
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
        
        if (!isset($this->outgoing_m)){
            $this->load->model('mail/outgoing_m');
        }
        $last_outgoings = $this->outgoing_m->get_offset('*', $this->users->has_access('OUTGOING_VIEW_ALL') ? NULL : array('sender'=>$me->id),0,$latest_num_records);
        $this->data['last_outgoings'] = array();
        foreach ($last_outgoings as $outgoing){
            $outgoing->sender_name = $outgoing->sender == $me->id ? 'You' : $this->user_m->get_value('full_name', array('id'=>$outgoing->sender));
            $outgoing->receiver_name = $outgoing->receiver>0 ? $this->user_m->get_value('full_name', array('id'=>$outgoing->receiver)):$outgoing->literally_receiver;
            $outgoing->status_name = mail_status($outgoing->status, MAIL_TYPE_OUTGOING, SIDE_SENDER);
            
            $this->data['last_outgoings'] [] = $outgoing;
        }
        
        
        
        //get supported data
        if ($this->users->get_userid()==$userid){
            $avatars = $this->users->get_default_avatars();
            $this->data['avatars'] = array_merge($avatars, $this->users->get_my_avatars());
        }
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], 'Users', site_url('users'));
        breadcumb_add($this->data['breadcumb'], 'Profile', site_url('profile'), TRUE);
        
        $this->data['subview'] = 'cms/users/profile/index';
        $this->load->view('_layout_admin', $this->data);
    }
    
    function save(){
        $id = $this->input->get('id', TRUE);
        
        if ($this->users->get_userid()!=$id){
            redirect('cms/profile/index?id='.$id);
        }
        
        $rules = $this->user_m->rules_profile;
        $this->form_validation->set_rules($rules);
        //exit(print_r($rules));
        if ($this->form_validation->run() != FALSE) {
            $postdata = $this->user_m->array_from_post(array('full_name','username','password','change_password','email','mobile','phone','avatar','about'));
            if ($postdata['change_password'] && !$postdata['password']){
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', 'Password can not blank');
                
                redirect('cms/profile/index?id='.$id);
            }else if (!$postdata['change_password']){
                unset($postdata['password']);
            }
            //unset not user data model attribute
            unset($postdata['change_password']);
            
            if (isset($postdata['password'])){
                $postdata['password'] = $this->users->hash($postdata['password']);
            }
            
            if (($this->user_m->save($postdata, $id))){
                $this->session->set_flashdata('message_type','success');
                $this->session->set_flashdata('message', 'Data user saved successfully');
                
                $this->users->update_session_me($postdata['full_name'], $postdata['username'], NULL, $postdata['avatar']);
                redirect('cms/profile/index?page='.$page);
            }else{
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', $this->user_m->get_last_message());
            }
        }
        
        if (validation_errors()){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', validation_errors());
        }
        
        redirect('cms/profile/index?id='.$id);
    }
    
}

/*
 * file location: engine/application/controllers/profile.php
 */
