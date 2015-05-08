<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Outgoing
 *
 * @author marwansaleh
 */
class Outgoing extends MY_AdminController {
    function __construct() {
        parent::__construct();
        $this->data['active_menu'] = 'outgoing';
        $this->data['page_title'] = '<i class="fa fa-mail-reply"></i> Surat Keluar';
        $this->data['page_description'] = 'List and update outgoing mail';
        
        //Loading model
        $this->load->model('mail/incoming_m', 'incoming_m');
        $this->load->model('mail/outgoing_m', 'outgoing_m');
        $this->load->model('mail/disposition_m', 'disposition_m');
        $this->load->model('mail/attachment_m', 'attachment_m');
        
    }
    
    function index(){
        $page = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE):1;
        
        $this->data['page'] = $page;
        $offset = ($page-1) * $this->REC_PER_PAGE;
        $this->data['offset'] = $offset;
        
        $where = array('sender' => $this->users->get_userid());
        
        if ($this->users->has_access('OUTGOING_VIEW_ALL')){
            $where = NULL;
        }
        
        //count totalRecords
        $this->data['totalRecords'] = $this->outgoing_m->get_count($where);
        //count totalPages
        $this->data['totalPages'] = ceil ($this->data['totalRecords']/$this->REC_PER_PAGE);
        $this->data['items'] = array();
        if ($this->data['totalRecords']>0){
            $items = $this->outgoing_m->get_offset('*',$where,$offset,  $this->REC_PER_PAGE);
            if ($items){
                foreach($items as $item){
                    $item->sender_name = $this->user_m->get_value('full_name', array('id'=>$item->sender));
                    $item->receiver_name = $this->user_m->get_value('full_name', array('id'=>$item->receiver));
                    if ($item->last_position==0){
                        $item->last_position_name = $this->user_m->get_value('full_name', array('id'=>$item->sender));
                    }else{
                        $item->last_position_name = $this->user_m->get_value('full_name', array('id'=>$item->last_position));
                    }
                    $item->status_name = mail_status($item->status, MAIL_TYPE_OUTGOING, SIDE_SENDER);
                    //get attachments
                    $item->attachments = $this->attachment_m->get_select_where('file_name',array('mail_type'=>MAIL_TYPE_OUTGOING, 'mail_id'=>$item->id));
                    
                    //Set allow action
                    //check if it already responded by the receiver, if yes, can not edit or delete
                    $responded = $this->disposition_m->get_count(array('mail_id'=>$item->id,'mail_type'=>MAIL_TYPE_OUTGOING));
                    $item->responded = $responded > 0;
                    $item->can_post = ($item->sender==$this->users->get_userid() && $this->users->has_access('DISPOSITION_CREATE'));
                    $item->can_edit = !$item->responded && $this->users->has_access('OUTGOING_CREATE') && $item->sender==$this->users->get_userid();
                    $item->can_delete = !$item->responded && $this->users->has_access('OUTGOING_DELETE')  && $item->sender==$this->users->get_userid();
                    $this->data['items'][] = $item;
                }
                $url_format = site_url('outgoing/index?page=%i');
                $this->data['pagination'] = smart_paging($this->data['totalPages'], $page, $this->_pagination_adjacent, $url_format, $this->_pagination_pages, array('records'=>count($items),'total'=>$this->data['totalRecords']));
            }
        }
        $this->data['pagination_description'] = smart_paging_description($this->data['totalRecords'], count($this->data['items']));
        
        //get templates
        $this->load->model('mail/template_m');
        $this->data['templates'] = array();
        foreach ($this->template_m->get() as $tmp){
            $tmp->label = ucfirst(str_replace('_', ' ', $tmp->name));
            $this->data['templates'] [] = $tmp;
        }
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], 'Outgoing', site_url('outgoing'), TRUE);
        
        $this->data['subview'] = 'cms/mail/outgoing/index';
        $this->load->view('_layout_admin', $this->data);
    }
    
    function edit(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        if (!$this->users->has_access('OUTGOING_CREATE')){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
            redirect('outgoing/index?page='.$page);
        }
        
        $me = $this->users->me();
        
        $this->data['attachments'] = array();
        if ($id){
            $item = $this->outgoing_m->get($id);
            $item->mail_date = strtotime($item->mail_date);
            
            $item->attachments = array();
            $attachments = $this->attachment_m->get_by(array('mail_type'=>MAIL_TYPE_OUTGOING, 'mail_id'=>$id));
            if ($attachments){
                foreach ($attachments as $attach){
                    $item->attachments [] = $attach->file_name;
                    $this->data['attachments'] [] = $attach;
                }
            }
        }else{
            $item = $this->outgoing_m->get_new();
            $item->attachments = array();
            $item->mail_date = $item->receive_date = time();
            
            $item->sender = $me->id;
            $item->sender_name = $me->full_name;
        }
        
        $this->data['item'] = $item;
        
        //suporting data
        $this->data['users'] = $this->user_m->get_select_where('id,full_name,position',array('id !='=> $me->id),FALSE);
        $this->data['priorities'] = mail_priority();
        $this->data['incomings'] = $this->incoming_m->get_select_where('id,subject',NULL);
        
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], 'Outgoing', site_url('outgoing'));
        breadcumb_add($this->data['breadcumb'], 'Update', NULL, TRUE);
        
        $this->data['submit_url'] = site_url('outgoing/save?id='.$id.'&page='.$page);
        $this->data['back_url'] = site_url('outgoing/index?page='.$page);
        $this->data['subview'] = 'cms/mail/outgoing/edit';
        $this->load->view('_layout_admin', $this->data);
    }
    
    function save(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        if (!$this->users->has_access('OUTGOING_CREATE')){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
            redirect('outgoing/index?page='.$page);
        }
        
        $rules = $this->outgoing_m->rules;
        $this->form_validation->set_rules($rules);
        //exit(print_r($rules));
        if ($this->form_validation->run() != FALSE) {
            $postdata = $this->outgoing_m->array_from_post(array('sender','incoming_ref_id','mail_date','priority','mail_no','receiver','subject','content'));
            
            //delete old attachments in database
            if ($id){
                $this->attachment_m->delete_where(array('mail_type'=>MAIL_TYPE_OUTGOING,'mail_id'=>$id));
            }else{
                $postdata['last_position'] = $postdata['sender'];
                $postdata['created'] = time();
                $postdata['created_by'] = $this->users->get_userid();
            }
            //clean array
            if (function_exists('clean_array')){
                $postdata = clean_array($postdata);
            }
            if (isset($postdata['mail_date'])){
                $postdata['mail_date'] = date('Y-m-d', strtotime($postdata['mail_date']));
            }
            
            $attachments = $this->input->post('attachments');
            
            if (($id = $this->outgoing_m->save($postdata, $id))){
                $this->session->set_flashdata('message_type','success');
                $this->session->set_flashdata('message', 'Data outgoing saved successfully');
                
                //save with new data
                if ($attachments){
                    $attachments = explode('|', $attachments);
                    
                    $att_post = array();
                    foreach ($attachments as $attach){
                        $att_post [] = array(
                            'mail_type'     => MAIL_TYPE_OUTGOING,
                            'mail_id'       => $id,
                            'file_name'     => $attach,
                            'inserted_by'   => $this->users->get_userid(),
                            'created'       => time()
                        );
                    }
                    
                    $this->attachment_m->save_batch($att_post);
                }
                
                //check if any reference in incoming
                if (isset($postdata['incoming_ref_id'])){
                    $this->incoming_m->save(array('status'=>MAIL_STATUS_RESPONDED), $postdata['incoming_ref_id']);
                }
                
                redirect('outgoing/index?page='.$page);
            }else{
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', $this->outgoing_m->get_last_message());
            }
        }
        
        if (validation_errors()){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', validation_errors());
        }
        
        redirect('outgoing/edit?id='.$id.'&page='.$page);
    }
    
    function delete(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        if (!$this->users->has_access('OUTGOING_DELETE')){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
            redirect('outgoing/index?page='.$page);
        }
        
        //count is any disposition reding this mail
        if ($this->disposition_m->get_count(array('mail_id'=>$id, 'mail_type'=>MAIL_TYPE_OUTGOING))){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'This outgoing mail can not be deleted since it has been posted!');
            redirect('outgoing/index?page='.$page);
        }
        
        //check if found data item
        $item = $this->outgoing_m->get($id);
        if (!$item){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Could not find data. Delete failed!');
        }else{
            if ($this->outgoing_m->delete($id)){
                $this->session->set_flashdata('message_type','success');
                $this->session->set_flashdata('message', 'Data item deleted successfully');
            }else{
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', $this->outgoing_m->get_last_message());
            }
        }
        
        redirect('outgoing/index?page='.$page);
    }
    
    function editor($id=NULL){
        if (!$id || !($mail = $this->outgoing_m->get($id))){
            show_404();
            exit;
        }
        
        $this->data['mail'] = $mail;
        
        //try to get revision if exists
        $this->load->model('mail/revision_m');
        
        $this->data['submit_url'] = site_url('outgoing/editor/'. $id);
        $this->data['subview'] = 'cms/mail/editor/index';
        $this->load->view('_layout_editor', $this->data);
    }
}

/*
 * file location: engine/application/controllers/outgoing.php
 */
