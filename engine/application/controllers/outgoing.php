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
        $this->load->model('mail/template_m', 'template_m');
        
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
                    $item->receiver_name = $item->receiver>0 ? $this->user_m->get_value('full_name', array('id'=>$item->receiver)):$item->literally_receiver;
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
                    $item->can_edit = $this->users->has_access('OUTGOING_CREATE') && $item->sender==$this->users->get_userid();
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
    
    function edit($template_id=0){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        
        
        if (!$this->users->has_access('OUTGOING_CREATE')){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
            redirect('outgoing/index?page='.$page);
        }
        
        $me = $this->users->me();
        
        if ($id){
            $item = $this->outgoing_m->get($id);
            $template_id = $item->template_id;
            $item->mail_date = strtotime($item->mail_date);
            if ($item->elements){
                $item->elements = json_decode($item->elements);
            }
        }else{
            $item = $this->outgoing_m->get_new();
            $item->mail_date = $item->receive_date = time();
            
            $item->sender = $me->id;
            $item->sender_name = $me->full_name;
            
            $item->literally_signer = 1;
        }
        
        //get template
        $template = $this->template_m->get($template_id);
        $this->data['template'] = $template;
        
        $this->data['item'] = $item;
        
        //suporting data
        $this->data['signers'] = $this->user_m->get_select_where('id,full_name,jabatan,position',array('id !='=> $me->id),FALSE);
        $this->data['priorities'] = mail_priority();
        $this->data['incomings'] = $this->incoming_m->get_select_where('id,subject',NULL);
        
        $this->data['form_element'] = 'cms/mail/outgoing/options/'.$template->name;
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], 'Outgoing', site_url('outgoing'));
        breadcumb_add($this->data['breadcumb'], 'Update', NULL, TRUE);
        
        $this->data['submit_url'] = site_url('outgoing/save/'.$template_id.'?id='.$id.'&page='.$page);
        $this->data['back_url'] = site_url('outgoing/index?page='.$page);
        $this->data['subview'] = 'cms/mail/outgoing/edit';
        $this->load->view('_layout_admin', $this->data);
    }
    
    function save($template_id=0){
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
            $postdata = $this->outgoing_m->array_from_post(array('sender','incoming_ref_id','template_id','mail_date','priority','subject','content','signer','literally_signer','literally_receiver'));
            
            //clean array
            if (function_exists('clean_array')){
                $postdata = clean_array($postdata);
            }
            if (isset($postdata['mail_date'])){
                $postdata['mail_date'] = date('Y-m-d', strtotime($postdata['mail_date']));
            }
            
            if (($id = $this->outgoing_m->save($postdata, $id))){
                $this->session->set_flashdata('message_type','success');
                $this->session->set_flashdata('message', 'Data outgoing saved successfully');
                
                $this->user_activity("Update outgoing with subject '".$postdata["subject"]."'", $this->users->get_userid());
                //check if any reference in incoming
                if (isset($postdata['incoming_ref_id'])){
                    $this->incoming_m->save(array('status'=>MAIL_STATUS_RESPONDED), $postdata['incoming_ref_id']);
                }
                
                $template_name = $this->input->post('template_name');
                //save base on template
                $method_save_template = '_save_'.$template_name;
                if (method_exists($this, $method_save_template)){
                    $this->$method_save_template($id);
                }else{
                    exit('Method '.$method_save_template.' is not defined');
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
        
        redirect('outgoing/edit/'.$template_id.'?id='.$id.'&page='.$page);
    }
    
    public function preview($id){
        //get PDFTemplate
        $pdf = new PDFTemplate();
        
        //get item
        $item = $this->outgoing_m->get($id);
        if ($item->elements){
            $item_elements = json_decode($item->elements);
            $pdf->add_dictionary_obj($item_elements);
        }
        
        //additional dictionary
        $dict = new stdClass();
        $dict->subjek_surat = $item->subject;
        $dict->sifat_surat = mail_priority($item->priority);
        if ($item->literally_signer==1){
            $pengirim = $this->users->get_user_record($item->signer);
            $dict->nama_pengirim = $pengirim->full_name;
            $dict->pangkat_pengirim = $pengirim->position;
        }
        $pdf->add_dictionary_obj($dict);
        
        $pdf->makepdf($item->template_id, array('content'=>$item->content));
    }
    
    private function _save_surat_biasa($id){
        
        $stdClass = new stdClass();
        $key_search = 'sbs_';
        foreach ($this->input->post() as $key=>$value){
            if (strpos($key, $key_search)!==FALSE){
                $key = str_replace($key_search, '', $key);
                $stdClass->$key = $value;
            }
        }
        
        if (!$this->input->post('literally_signer')){
            $stdClass->nama_pengirim = $this->input->post('nama_pengirim');
            $stdClass->pangkat_pengirim = $this->input->post('pangkat_pengirim');
            $stdClass->nip_pengirim = $this->input->post('nip_pengirim');
        }
        
        $this->outgoing_m->save(array('elements'=>  json_encode($stdClass)),$id);
    }
    
    private function _save_surat_keterangan($id){
        
        $stdClass = new stdClass();
        $key_search = 'sk_';
        foreach ($this->input->post() as $key=>$value){
            if (strpos($key, $key_search)!==FALSE){
                $key = str_replace($key_search, '', $key);
                $stdClass->$key = $value;
            }
        }
        
        if (!$this->input->post('literally_signer')){
            $stdClass->nama_pengirim = $this->input->post('nama_pengirim');
            $stdClass->pangkat_pengirim = $this->input->post('pangkat_pengirim');
            $stdClass->nip_pengirim = $this->input->post('nip_pengirim');
        }
        
        $this->outgoing_m->save(array('elements'=>  json_encode($stdClass)),$id);
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
