<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Disposition
 *
 * @author marwansaleh
 */
class Disposition extends MY_AdminController {
    function __construct() {
        parent::__construct();
        $this->data['active_menu'] = 'disposition';
        $this->data['page_title'] = '<i class="fa fa-mail-forward"></i> Surat Disposisi';
        $this->data['page_description'] = 'List and update disposition';
        
        //Loading model
        $this->load->model('mail/incoming_m', 'incoming_m');
        $this->load->model('mail/outgoing_m', 'outgoing_m');
        $this->load->model('mail/disposition_m', 'disposition_m');
    }
    
    function index(){
        $page = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE):1;
        
        $this->data['page'] = $page;
        $offset = ($page-1) * $this->REC_PER_PAGE;
        $this->data['offset'] = $offset;
        
        $current_user_id = $this->users->get_userid();
        $where = "receiver = $current_user_id OR sender=$current_user_id";
        
        //count totalRecords
        $this->data['totalRecords'] = $this->disposition_m->get_count($where);
        //count totalPages
        $this->data['totalPages'] = ceil ($this->data['totalRecords']/$this->REC_PER_PAGE);
        $this->data['items'] = array();
        if ($this->data['totalRecords']>0){
            $items = $this->disposition_m->get_offset('*',$where,$offset,  $this->REC_PER_PAGE);
            if ($items){
                foreach($items as $item){
                    $item->status_name = mail_status($item->status, $item->mail_type, $item->sender==$this->users->get_userid()?SIDE_SENDER:SIDE_RECEIVER);
                    
                    if ($item->mail_type==MAIL_TYPE_INCOMING){
                        $item->subject = $this->incoming_m->get_value('subject', array('id'=>$item->mail_id));
                    }else{
                        $item->subject = $this->outgoing_m->get_value('subject', array('id'=>$item->mail_id));
                    }
                    if ($item->sender==0){
                        if ($item->mail_type==MAIL_TYPE_INCOMING){
                            $item->sender_name = $this->incoming_m->get_value('sender_name', array('id'=>$item->mail_id));
                        }else{
                            $item->sender_name = '';
                        }
                    }else{
                        $item->sender_name = $this->user_m->get_value('full_name', array('id'=>$item->sender));
                    }
                    $item->receiver_name = $this->user_m->get_value('full_name', array('id'=>$item->receiver));
                    $item->priority_name = mail_priority($item->priority);
                    
                    //Set allow action
                    //check if it already responded by the receiver, if yes, can not edit or delete
                    $responded = $this->disposition_m->get_count(array('mail_id'=>$item->mail_id,'mail_type'=>$item->mail_type,'history_ref_id'=>$item->id));
                    $item->responded = $responded > 0;
                    $item->can_post = ($item->status!=MAIL_STATUS_SIGNED) && ($item->receiver==$this->users->get_userid() && $this->users->has_access('DISPOSITION_CREATE'));
                    $item->can_edit = !$item->responded && $this->users->has_access('DISPOSITION_CREATE') && $item->sender==$this->users->get_userid();
                    $item->can_delete = !$item->responded && $this->users->has_access('DISPOSITION_DELETE')  && $item->sender==$this->users->get_userid();
                    
                    //need sign ?
                    if ($item->mail_type==MAIL_TYPE_OUTGOING && $item->receiver == $this->users->get_userid()){
                        if ($this->outgoing_m->get_value('status', array('id'=>$item->mail_id))==MAIL_STATUS_SIGN){
                            $item->can_sign = TRUE;
                            $item->can_post = FALSE;
                        }
                    }
                    
                    $this->data['items'][] = $item;
                }
                $url_format = site_url('disposition/index?page=%i');
                $this->data['pagination'] = smart_paging($this->data['totalPages'], $page, $this->_pagination_adjacent, $url_format, $this->_pagination_pages, array('records'=>count($items),'total'=>$this->data['totalRecords']));
            }
        }
        $this->data['pagination_description'] = smart_paging_description($this->data['totalRecords'], count($this->data['items']));
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], 'Disposisi', site_url('disposition'), TRUE);
        
        $this->data['subview'] = 'cms/mail/disposition/index';
        $this->load->view('_layout_admin', $this->data);
    }
    
    function post(){
        $page = $this->input->get('page', TRUE);
        $back_url = $this->input->get('url', TRUE);
        
        $this->data = array_merge($this->data, array(
            'mail_id'       => $this->input->get('mail', TRUE),
            'type'          => $this->input->get('type', TRUE)?$this->input->get('type', TRUE):MAIL_TYPE_INCOMING,
            'ref'           => $this->input->get('ref', TRUE)?$this->input->get('ref', TRUE):0,
            'url'           => $back_url
        ));
        
        //test if mail exists
        if ($this->data['type'] == MAIL_TYPE_INCOMING){
            $mail = $this->incoming_m->get($this->data['mail_id']);
        }else{
            $mail = $this->outgoing_m->get($this->data['mail_id']);
        }
        //redirect if no mail or no access
        if (!$mail || !$this->users->has_access('DISPOSITION_CREATE')){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
            if ($back_url){
                redirect(urldecode($back_url));
            }else{
                redirect('disposition/index?page='.$page);
            }
        }
        $this->data['mail'] = $mail;
        $this->data['histories'] = $this->_history($mail->id, $this->data['type']);
        
        $item = $this->disposition_m->get_new();
        $item->priority = $mail->priority;
        
        $this->data['item'] = $item;
        
        //suporting data
        $this->data['users'] = array();
        $users = $this->user_m->get_select_where('id,full_name,position',array('id !='=> $this->users->get_userid()),FALSE);
        foreach ($users as $user){
            $user->incoming = $this->incoming_m->get_count(array('receiver'=>$user->id,'status'=>MAIL_STATUS_NEW));
            $user->disposition = $this->disposition_m->get_count(array('receiver'=>$user->id,'status'=>MAIL_STATUS_NEW));
            $user->outgoing = $this->outgoing_m->get_count(array('sender'=>$user->id,'status'=>MAIL_STATUS_NEW));
            $this->data['users'] []= $user;
        }
        $this->data['priorities'] = mail_priority();
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], 'Disposisi', site_url('disposition'));
        breadcumb_add($this->data['breadcumb'], 'Update', NULL, TRUE);
        
        $this->data['submit_url'] = site_url('disposition/save?page='.$page);
        $this->data['back_url'] = $back_url ? urldecode($back_url) : site_url('disposition/index?page='.$page);
        $this->data['subview'] = 'cms/mail/disposition/post';
        $this->load->view('_layout_admin', $this->data);
    }
    
    private function _history($mail_id, $mail_type=MAIL_TYPE_INCOMING){
        //get mail record
        if ($mail_type==MAIL_TYPE_INCOMING){
            $mail = $this->incoming_m->get($mail_id);
        }else{
            $mail = $this->outgoing_m->get($mail_id);
        }
        
        $histories = array();
        
        //insert the parent mail
        $parent = new stdClass();
        $parent->mail_id = $mail_id;
        $parent->mail_type = $mail_type;
        $parent->priority = $mail->priority;
        $parent->sender = isset($mail->sender)?$mail->sender:0;
        $parent->sender_name = $parent->sender ? $this->user_m->get_value('full_name', array('id'=>$parent->sender)) : $mail->sender_name;
        $parent->receiver = $mail->receiver;
        $parent->receiver_name = $this->user_m->get_value('full_name', array('id'=>$mail->receiver));
        $parent->status = $mail->status;
        $parent->status_name = mail_status($mail->status, $mail_type, $mail_type==MAIL_TYPE_INCOMING?SIDE_RECEIVER:SIDE_SENDER);
        $parent->notes = $mail->content ? strip_tags($mail->content,"<p>") : $mail->subject;
        $parent->created = $mail->created;
        $histories [] = $parent;
        
        //get history
        $this->db->order_by('created asc');
        $history_result = $this->disposition_m->get_by(array('mail_id'=>$mail_id, 'mail_type'=>$mail_type));
        foreach ($history_result as $item){
            if ($item->sender==0){
                $item->sender_name = $mail->sender_name;
            }else{
                $item->sender_name = $this->user_m->get_value('full_name', array('id'=>$item->sender));
            }
            $item->receiver_name = $this->user_m->get_value('full_name', array('id'=>$item->receiver));
            $item->status_name = mail_status($item->status);
            $histories [] = $item;
        }
        
        return $histories;
    }
    
    function save(){
        $page = $this->input->get('page', TRUE);
        
        $mail_id = $this->input->post('mail_id');
        $mail_type = $this->input->post('mail_type');
        $history_ref_id = $this->input->post('history_ref_id');
        $back_url = $this->input->post('url');
        
        
        if (!$this->users->has_access('DISPOSITION_CREATE')){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
            
            if ($back_url){
                redirect(urldecode($back_url));
            }else{
                redirect('disposition/index?page='.$page);
            }
        }
        
        
        $rules = $this->disposition_m->rules;
        $this->form_validation->set_rules($rules);
        //exit(print_r($rules));
        if ($this->form_validation->run() != FALSE) {
            $postdata = $this->disposition_m->array_from_post(array('priority','receiver','notes'));
            $post_batch = array();
            foreach ($postdata['receiver'] as $receiver){
                $post_batch [] = array(
                    'mail_id'           => $mail_id,
                    'mail_type'         => $mail_type,
                    'priority'          => $postdata['priority'],
                    'sender'            => $this->users->get_userid(),
                    'receiver'          => $receiver,
                    'notes'             => $postdata['notes'],
                    'history_ref_id'    => $history_ref_id,
                    'created'           => time()
                );
            }
            //print_r($post_batch);exit;
            $this->disposition_m->save_batch($post_batch);
            if ($this->disposition_m->get_last_message()){
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', $this->disposition_m->get_last_message());
            }else{
                $this->session->set_flashdata('message_type','success');
                $this->session->set_flashdata('message', 'Data disposition sent successfully');
            }
            
            //get last receiver for update parent mail
            if ($mail_type==MAIL_TYPE_INCOMING){
                $this->incoming_m->save(array('status'=>$history_ref_id>0?MAIL_STATUS_REPOSTED:MAIL_STATUS_POSTED,'last_position'=>$postdata['receiver'][count($postdata['receiver'])-1]), $mail_id);
            }else{
                //is target is to sign ?
                $out = $this->outgoing_m->get($mail_id);
                if ($out->signer==$postdata['receiver'][count($postdata['receiver'])-1]){
                    $this->outgoing_m->save(array('status'=>MAIL_STATUS_SIGN,'last_position'=>$postdata['receiver'][count($postdata['receiver'])-1]), $mail_id);
                }else{
                    $this->outgoing_m->save(array('status'=>MAIL_STATUS_APPROVAL,'last_position'=>$postdata['receiver'][count($postdata['receiver'])-1]), $mail_id);
                }
            }
            
            //update history if ref id
            if ($history_ref_id){
                $this->disposition_m->save(array('status'=>MAIL_STATUS_POSTED), $history_ref_id);
            }
            
            if ($back_url){
                redirect(urldecode($back_url));
            }else{
                redirect('disposition/index?page='.$page);
            }
        }
        
        if (validation_errors()){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', validation_errors());
        }
        
        $gets = array('page'=>$page,'mail'=>$mail_id,'type'=>$mail_type,'ref'=>$history_ref_id,'url'=>$back_url);
        redirect('disposition/post?'. http_build_query($gets));
    }
    
    function edit(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        $item = $this->disposition_m->get($id);
        if (!$id || !$item){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. Data not found or ID is not a valid ID');
            redirect('disposition/index?page='.$page);
        }else if (!$this->users->has_access('DISPOSITION_CREATE')){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
            redirect('disposition/index?page='.$page);
        }
        
        //test if mail exists
        if ($item->mail_type == MAIL_TYPE_INCOMING){
            $mail = $this->incoming_m->get($item->mail_id);
        }else{
            $mail = $this->outgoing_m->get($item->mail_id);
        }
        
        $this->data['mail'] = $mail;
        $this->data['histories'] = $this->_history($mail->id, $item->mail_type);
        
        $this->data['item'] = $item;
        
        //suporting data
        $this->data['users'] = array();
        $users = $this->user_m->get_select_where('id,full_name,position',array('id !='=> $this->users->get_userid()),FALSE);
        foreach ($users as $user){
            $user->incoming = $this->incoming_m->get_count(array('receiver'=>$user->id,'status'=>MAIL_STATUS_NEW));
            $user->disposition = $this->disposition_m->get_count(array('receiver'=>$user->id,'status'=>MAIL_STATUS_NEW));
            $user->outgoing = $this->outgoing_m->get_count(array('sender'=>$user->id,'status'=>MAIL_STATUS_NEW));
            $this->data['users'] []= $user;
        }
        $this->data['priorities'] = mail_priority();
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], 'Disposisi', site_url('disposition'));
        breadcumb_add($this->data['breadcumb'], 'Update', NULL, TRUE);
        
        $this->data['submit_url'] = site_url('disposition/save_edit?id='.$id.'&page='.$page);
        $this->data['back_url'] = site_url('disposition/index?page='.$page);
        $this->data['subview'] = 'cms/mail/disposition/edit';
        $this->load->view('_layout_admin', $this->data);
    }
    
    function save_edit(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        $mail_id = $this->input->post('mail_id');
        $mail_type = $this->input->post('mail_type');
        
        if (!$this->users->has_access('DISPOSITION_CREATE')){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
            
            redirect('disposition/index?page='.$page);
        }
        
        
        $rules = $this->disposition_m->rules;
        $this->form_validation->set_rules($rules);
        //exit(print_r($rules));
        if ($this->form_validation->run() != FALSE) {
            $postdata = $this->disposition_m->array_from_post(array('priority','receiver','notes'));
            
            //print_r($post_batch);exit;
            $this->disposition_m->save($postdata, $id);
            if ($this->disposition_m->get_last_message()){
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', $this->disposition_m->get_last_message());
            }else{
                $this->session->set_flashdata('message_type','success');
                $this->session->set_flashdata('message', 'Data disposition update successfully');
            }
            
            //update parent mail if this is last post
            if ($this->disposition_m->get_count(array('mail_id'=>$mail_id,'mail_type'=>$mail_type,'history_ref_id'=>$id))==0){
                if ($mail_type==MAIL_TYPE_INCOMING){
                    $this->incoming_m->save(array('last_position'=>$postdata['receiver']), $mail_id);
                }else{
                    //is target is to sign ?
                    $out = $this->outgoing_m->get($mail_id);
                    if ($out->signer==$postdata['receiver']){
                        $this->outgoing_m->save(array('status'=>MAIL_STATUS_SIGN,'last_position'=>$postdata['receiver']), $mail_id);
                    }else{
                        $this->outgoing_m->save(array('status'=>MAIL_STATUS_APPROVAL,'last_position'=>$postdata['receiver']), $mail_id);
                    }
                }
            }
            
            redirect('disposition/index?page='.$page);
        }
        
        if (validation_errors()){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', validation_errors());
        }
        
        $gets = array('id'=>$id,'page'=>$page);
        redirect('disposition/edit?'. http_build_query($gets));
    }
    
    function post_delete(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        if (!$this->users->has_access('DISPOSITION_DELETE')){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
            redirect('disposition/index?page='.$page);
        }
        
        //check if found data item
        $item = $this->disposition_m->get($id);
        if (!$item){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Could not find data. Delete failed!');
        }else{
            $deletable = $this->disposition_m->get_count(array('mail_id'=>$item->mail_id,'mail_type'=>$item->mail_type,'history_ref_id'=>$item->id))==0;
            
            if ($deletable){
                if ($this->disposition_m->delete($id)){
                    $this->session->set_flashdata('message_type','success');
                    $this->session->set_flashdata('message', 'Data item deleted successfully');
                }else{
                    $this->session->set_flashdata('message_type','error');
                    $this->session->set_flashdata('message', $this->disposition_m->get_last_message());
                }
            }else{
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', 'Data can not be delete because it has been reponded by other user');
            }
        }
        
        redirect('disposition/index?page='.$page);
    }
    
    function sign(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        
        if (!$this->users->has_access('OUTGOING_SIGN')){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
            redirect('disposition/index?page='.$page);
        }
        
        //check if found data item
        $item = $this->disposition_m->get($id);
        
        if (!$item){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Could not find mail to signe. Process failed!');
        }else{
            //try to get mail
            $mail = $this->outgoing_m->get($item->mail_id);
            if (!$mail){
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', 'Could not find mail to signe. Process failed!');
            }else{
                $this->outgoing_m->save(array('status'=>MAIL_STATUS_SIGNED, 'last_position'=>$mail->signer), $mail->id);
                
                $this->session->set_flashdata('message_type','success');
                $this->session->set_flashdata('message', 'You have signed the outgoing mail successfully');
                
                //is any reference to incoming
                if ($mail->incoming_ref_id>0){
                    $this->incoming_m->save(array('status' => MAIL_STATUS_CLOSED), $mail->incoming_ref_id);
                }
                //update the disposition
                $this->disposition_m->save(array('status'=>MAIL_STATUS_SIGNED), $id);
            }
        }
        
        redirect('disposition/index?page='.$page);
    }
}

/*
 * file location: engine/application/controllers/usergroups.php
 */
