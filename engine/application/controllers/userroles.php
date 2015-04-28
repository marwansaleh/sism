<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Userroles
 *
 * @author marwansaleh
 */
class Userroles extends MY_AdminController {
    function __construct() {
        parent::__construct();
        $this->data['active_menu'] = 'users';
        $this->data['page_title'] = '<i class="fa fa-key"></i> Access Roles Management';
        $this->data['page_description'] = 'List and update access roles';
        
        //Loading model
        $this->load->model(array('users/userrole_m'));
    }
    
    function index(){
        $page = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE):1;
        
        $this->data['page'] = $page;
        $offset = ($page-1) * $this->REC_PER_PAGE;
        $this->data['offset'] = $offset;
        
        $where = NULL;
        
        //count totalRecords
        $this->data['totalRecords'] = $this->userrole_m->get_count($where);
        //count totalPages
        $this->data['totalPages'] = ceil ($this->data['totalRecords']/$this->REC_PER_PAGE);
        $this->data['items'] = array();
        if ($this->data['totalRecords']>0){
            $items = $this->userrole_m->get_offset('*',$where,$offset,  $this->REC_PER_PAGE);
            if ($items){
                foreach($items as $item){
                    $this->data['items'][] = $item;
                }
                $url_format = site_url('userroles/index?page=%i');
                $this->data['pagination'] = smart_paging($this->data['totalPages'], $page, $this->_pagination_adjacent, $url_format, $this->_pagination_pages, array('records'=>count($items),'total'=>$this->data['totalRecords']));
            }
        }
        $this->data['pagination_description'] = smart_paging_description($this->data['totalRecords'], count($this->data['items']));
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], 'Users', site_url('users'));
        breadcumb_add($this->data['breadcumb'], 'Roles', site_url('userroles'), TRUE);
        
        $this->data['subview'] = 'cms/users/role/index';
        $this->load->view('_layout_admin', $this->data);
    }
    
    function edit(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        if (!$this->users->has_access('USER_ROLES_MANAGEMENT')){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
            redirect('userroles/index?page='.$page);
        }
        
        if ($id){
            $item = $this->userrole_m->get($id);
        }else{
            $item = $this->userrole_m->get_new();
        }
        
        $this->data['item'] = $item;
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], 'Users', site_url('users/index?page='.$page));
        breadcumb_add($this->data['breadcumb'], 'Roles', site_url('userroles'));
        breadcumb_add($this->data['breadcumb'], 'Update', NULL, TRUE);
        
        $this->data['submit_url'] = site_url('userroles/save?id='.$id.'&page='.$page);
        $this->data['back_url'] = site_url('userroles/index?page='.$page);
        $this->data['subview'] = 'cms/users/role/edit';
        $this->load->view('_layout_admin', $this->data);
    }
    
    function save(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        if (!$this->users->has_access('USER_ROLES_MANAGEMENT')){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
            redirect('userroles/index?page='.$page);
        }
        
        $rules = $this->userrole_m->rules;
        $this->form_validation->set_rules($rules);
        //exit(print_r($rules));
        if ($this->form_validation->run() != FALSE) {
            $postdata = $this->userrole_m->array_from_post(array('role_name','role_description'));
            
            if (($this->userrole_m->save($postdata, $id))){
                $this->session->set_flashdata('message_type','success');
                $this->session->set_flashdata('message', 'Data role user saved successfully');
                
                redirect('userroles/index?page='.$page);
            }else{
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', $this->userrole_m->get_last_message());
            }
        }
        
        if (validation_errors()){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', validation_errors());
        }
        
        redirect('userroles/edit?id='.$id.'&page='.$page);
    }
    
    function delete(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        if (!$this->users->has_access('USER_ROLES_MANAGEMENT')){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
            redirect('userroles/index?page='.$page);
        }
        
        //check if found data item
        $item = $this->userrole_m->get($id);
        if (!$item){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Could not find data user role. Delete failed!');
        }else{
            if ($this->userrole_m->delete($id)){
                $this->session->set_flashdata('message_type','success');
                $this->session->set_flashdata('message', 'Data user role item deleted successfully');
            }else{
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', $this->userrole_m->get_last_message());
            }
        }
        
        redirect('userroles/index?page='.$page);
    }
}

/*
 * file location: engine/application/controllers/userroles.php
 */
