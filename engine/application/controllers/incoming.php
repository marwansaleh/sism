<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Incoming
 *
 * @author marwansaleh
 */
class Incoming extends MY_AdminController {
    function __construct() {
        parent::__construct();
        $this->data['active_menu'] = 'incoming';
        $this->data['page_title'] = '<i class="fa fa-envelope"></i> Surat Masuk';
        $this->data['page_description'] = 'List and update incoming mail';
        
        //Loading model
        $this->load->model('mail/incoming_m', 'incoming_m');
        $this->load->model('mail/outgoing_m', 'outgoing_m');
        $this->load->model('mail/disposition_m', 'disposition_m');
        $this->load->model('mail/attachment_m', 'attachment_m');
        $this->load->model('mail/sender_m', 'sender_m');
    }
    
    function index(){
        $page = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE):1;
        
        $this->data['page'] = $page;
        $offset = ($page-1) * $this->REC_PER_PAGE;
        $this->data['offset'] = $offset;
        
        $where = array('receiver' => $this->users->get_userid());
        if ($this->users->has_access('INCOMING_VIEW_ALL')){
            $where = NULL;
        }
        
        //count totalRecords
        $this->data['totalRecords'] = $this->incoming_m->get_count($where);
        //count totalPages
        $this->data['totalPages'] = ceil ($this->data['totalRecords']/$this->REC_PER_PAGE);
        $this->data['items'] = array();
        if ($this->data['totalRecords']>0){
            $items = $this->incoming_m->get_offset('*',$where,$offset,  $this->REC_PER_PAGE);
            if ($items){
                foreach($items as $item){
                    $item->status_name = mail_status($item->status, MAIL_TYPE_INCOMING, SIDE_RECEIVER);
                    $item->receiver_name = $this->user_m->get_value('full_name', array('id'=>$item->receiver));
                    if ($item->last_position==0){
                        $item->last_position_name = $this->user_m->get_value('full_name', array('id'=>$item->receiver));
                    }else{
                        $item->last_position_name = $this->user_m->get_value('full_name', array('id'=>$item->last_position));
                    }
                    //get attachments
                    $item->attachments = $this->attachment_m->get_select_where('file_name',array('mail_type'=>MAIL_TYPE_INCOMING, 'mail_id'=>$item->id));
                    
                    //Set allow action
                    //check if it already responded by the receiver, if yes, can not edit or delete
                    $responded = $this->disposition_m->get_count(array('mail_id'=>$item->id,'mail_type'=>MAIL_TYPE_INCOMING));
                    $item->responded = $responded > 0;
                    $item->can_post = ($item->receiver==$this->users->get_userid() && $this->users->has_access('DISPOSITION_CREATE'));
                    $item->can_edit = !$item->responded && $this->users->has_access('INCOMING_CREATE') && $item->receiver==$this->users->get_userid();
                    $item->can_delete = !$item->responded && $this->users->has_access('INCOMING_DELETE')  && $item->receiver==$this->users->get_userid();
                    
                    $this->data['items'][] = $item;
                }
                $url_format = site_url('incoming/index?page=%i');
                $this->data['pagination'] = smart_paging($this->data['totalPages'], $page, $this->_pagination_adjacent, $url_format, $this->_pagination_pages, array('records'=>count($items),'total'=>$this->data['totalRecords']));
            }
        }
        $this->data['pagination_description'] = smart_paging_description($this->data['totalRecords'], count($this->data['items']));
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], 'Incoming', site_url('incoming'), TRUE);
        
        $this->data['subview'] = 'cms/mail/incoming/index';
        $this->load->view('_layout_admin', $this->data);
    }
    
    function edit(){
        
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        if (!$this->users->has_access('INCOMING_CREATE')){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
            redirect('incoming/index?page='.$page);
        }
        
        $this->data['attachments'] = array();
        
        if ($id){
            $item = $this->incoming_m->get($id);
            $item->attachments = array();
            $item->mail_date = strtotime($item->mail_date);
            $item->receive_date = strtotime($item->receive_date);
            
            $attachments = $this->attachment_m->get_by(array('mail_type'=>MAIL_TYPE_INCOMING, 'mail_id'=>$id));
            if ($attachments){
                foreach ($attachments as $attach){
                    $item->attachments [] = $attach->file_name;
                    $this->data['attachments'] [] = $attach;
                }
            }
        }else{
            $item = $this->incoming_m->get_new();
            $item->mail_date = $item->receive_date = time();
            $item->attachments = array();
        }
        
        
        $this->data['item'] = $item;
        
        //suporting data
        $this->data['users'] = $this->user_m->get_select_where('id,full_name,position',NULL,FALSE);
        $this->data['priorities'] = mail_priority();
        $this->data['sender_names'] = $this->sender_m->get_array();
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], 'Incoming', site_url('incoming/index?page='.$page));
        breadcumb_add($this->data['breadcumb'], 'Update', NULL, TRUE);
        
        $this->data['submit_url'] = site_url('incoming/save?id='.$id.'&page='.$page);
        $this->data['back_url'] = site_url('incoming/index?page='.$page);
        $this->data['subview'] = 'cms/mail/incoming/edit';
        $this->load->view('_layout_admin', $this->data);
    }
    
    function save(){
        
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        if (!$this->users->has_access('INCOMING_CREATE')){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
            redirect('incoming/index?page='.$page);
        }
        
        $rules = $this->incoming_m->rules;
        $this->form_validation->set_rules($rules);
        //exit(print_r($rules));
        if ($this->form_validation->run() != FALSE) {
            $postdata = $this->incoming_m->array_from_post(array('mail_date','receive_date','mail_no','sender_name','receiver','priority','subject','content','attachments'));
            
            //delete old attachments in database
            if ($id){
                $this->attachment_m->delete_where(array('mail_type'=>MAIL_TYPE_INCOMING,'mail_id'=>$id));
            }else{
                $postdata['last_position'] = $postdata['receiver'];
                $postdata['created'] = time();
                $postdata['created_by'] = $this->users->get_userid();
            }
            //store to variable
            $attachments = $postdata['attachments'];
            unset($postdata['attachments']);
            
            if ($postdata['mail_date']){
                $postdata['mail_date'] = date('Y-m-d', strtotime($postdata['mail_date']));
            }
            if ($postdata['receive_date']){
                $postdata['receive_date'] = date('Y-m-d', strtotime($postdata['receive_date']));
            }
            if (($id = $this->incoming_m->save($postdata, $id))){
                $this->session->set_flashdata('message_type','success');
                $this->session->set_flashdata('message', 'Data group user saved successfully');
                
                //save with new data
                if ($attachments){
                    $attachments = explode('|', $attachments);
                    
                    $att_post = array();
                    foreach ($attachments as $attach){
                        $att_post [] = array(
                            'mail_type'     => MAIL_TYPE_INCOMING,
                            'mail_id'       => $id,
                            'file_name'     => $attach,
                            'inserted_by'   => $this->users->get_userid(),
                            'created'       => time()
                        );
                    }
                    
                    $this->attachment_m->save_batch($att_post);
                }
                
                redirect('incoming/index?page='.$page);
            }else{
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', $this->incoming_m->get_last_message());
            }
        }
        
        if (validation_errors()){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', validation_errors());
        }
        
        redirect('incoming/edit?id='.$id.'&page='.$page);
    }
    
    function delete(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        if (!$this->users->has_access('INCOMING_DELETE')){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
            redirect('incoming/index?page='.$page);
        }
        
        //count is any disposition reding this mail
        if ($this->disposition_m->get_count(array('mail_id'=>$id, 'mail_type'=>MAIL_TYPE_INCOMING))){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'This incoming mail can not be deleted since it has been posted!');
            redirect('incoming/index?page='.$page);
        }
        
        //check if found data item
        $item = $this->incoming_m->get($id);
        if (!$item){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Could not find data to delete. Delete failed!');
        }else{
            if ($this->incoming_m->delete($id)){
                $this->session->set_flashdata('message_type','success');
                $this->session->set_flashdata('message', 'Data item deleted successfully');
            }else{
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', $this->incoming_m->get_last_message());
            }
        }
        
        redirect('incoming/index?page='.$page);
    }
}

/*
 * file location: engine/application/controllers/incoming.php
 */
