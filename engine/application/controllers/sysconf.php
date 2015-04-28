<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Sysconf
 *
 * @author marwansaleh
 */
class Sysconf extends MY_AdminController {
    function __construct() {
        parent::__construct();
        $this->data['active_menu'] = 'system';
        $this->data['page_title'] = '<i class="fa fa-cogs"></i> System Configurations';
        $this->data['page_description'] = 'List and update configurations';
        
        //Loading model
        $this->load->model(array('system/sys_variables_m'));
    }
    
    function index(){
        $page = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE):1;
        
        $this->data['page'] = $page;
        $offset = ($page-1) * $this->REC_PER_PAGE;
        $this->data['offset'] = $offset;
        
        $where = NULL;
        
        //count totalRecords
        $this->data['totalRecords'] = $this->sys_variables_m->get_count($where);
        //count totalPages
        $this->data['totalPages'] = ceil ($this->data['totalRecords']/$this->REC_PER_PAGE);
        $this->data['items'] = array();
        if ($this->data['totalRecords']>0){
            $items = $this->sys_variables_m->get_offset('*',$where,$offset,  $this->REC_PER_PAGE);
            if ($items){
                foreach($items as $item){
                    $this->data['items'][] = $item;
                }
                $url_format = site_url('sysconf/index?page=%i');
                $this->data['pagination'] = smart_paging($this->data['totalPages'], $page, $this->_pagination_adjacent, $url_format, $this->_pagination_pages, array('records'=>count($items),'total'=>$this->data['totalRecords']));
            }
        }
        $this->data['pagination_description'] = smart_paging_description($this->data['totalRecords'], count($this->data['items']));
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], 'System Configuration', site_url('sysconf'), TRUE);
        
        $this->data['subview'] = 'cms/system/config/index';
        $this->load->view('_layout_admin', $this->data);
    }
    
    function edit(){
        $this->load->helper('custom_value');
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        if (!$this->users->has_access('SYS_PARAMETERS')){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
            redirect('sysconf/index?page='.$page);
        }
        
        if ($id){
            $item = $this->sys_variables_m->get($id);
        }else{
            $item = $this->sys_variables_m->get_new();
        }
        
        $this->data['item'] = $item;
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], 'System Configuration', site_url('sysconf'));
        breadcumb_add($this->data['breadcumb'], 'Update', NULL, TRUE);
        
        $this->data['submit_url'] = site_url('sysconf/save?id='.$id.'&page='.$page);
        $this->data['back_url'] = site_url('sysconf/index?page='.$page);
        $this->data['subview'] = 'cms/system/config/edit';
        
        $this->load->view('_layout_admin', $this->data);
    }
    
    function save(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        if (!$this->users->has_access('SYS_PARAMETERS')){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
            redirect('sysconf/index?page='.$page);
        }
        
        $rules = $this->sys_variables_m->rules;
        $this->form_validation->set_rules($rules);
        //exit(print_r($rules));
        if ($this->form_validation->run() != FALSE) {
            $postdata = $this->sys_variables_m->array_from_post(array('var_name','var_type','var_value','is_list','func_custom_value'));
            
            if (($this->sys_variables_m->save($postdata, $id))){
                $this->session->set_flashdata('message_type','success');
                $this->session->set_flashdata('message', 'Data configuration saved successfully');
                
                redirect('sysconf/index?page='.$page);
            }else{
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', $this->sys_variables_m->get_last_message());
            }
        }
        
        if (validation_errors()){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', validation_errors());
        }
        
        redirect('sysconf/edit?id='.$id.'&page='.$page);
    }
    
    function delete(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        if (!$this->users->has_access('SYS_PARAMETERS')){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
            redirect('sysconf/index?page='.$page);
        }
        
        //check if found data item
        $item = $this->sys_variables_m->get($id);
        if (!$item){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Could not find data config item. Delete failed!');
        }else{
            if ($this->sys_variables_m->delete($id)){
                $this->session->set_flashdata('message_type','success');
                $this->session->set_flashdata('message', 'Data configuration item deleted successfully');
            }else{
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', $this->sys_variables_m->get_last_message());
            }
        }
        
        redirect('sysconf/index?page='.$page);
    }
    
    
}

/*
 * file location: engine/application/controllers/sysconf.php
 */
