<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Usergroups
 *
 * @author marwansaleh
 */
class Usergroups extends MY_AdminController {
    function __construct() {
        parent::__construct();
        $this->data['active_menu'] = 'users';
        $this->data['page_title'] = '<i class="fa fa-users"></i> User Group Management';
        $this->data['page_description'] = 'List and update user groups';
        
        //Loading model
        $this->load->model(array('users/usergroup_m'));
    }
    
    function index(){
        $page = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE):1;
        
        $this->data['page'] = $page;
        $offset = ($page-1) * $this->REC_PER_PAGE;
        $this->data['offset'] = $offset;
        
        $where = NULL;
        
        //count totalRecords
        $this->data['totalRecords'] = $this->usergroup_m->get_count($where);
        //count totalPages
        $this->data['totalPages'] = ceil ($this->data['totalRecords']/$this->REC_PER_PAGE);
        $this->data['items'] = array();
        if ($this->data['totalRecords']>0){
            $items = $this->usergroup_m->get_offset('*',$where,$offset,  $this->REC_PER_PAGE);
            if ($items){
                foreach($items as $item){
                    $item->is_super = $this->users->is_admin($item->group_id);
                    $item->user_count = $this->user_m->get_count(array('group_id'=>$item->group_id));
                    $this->data['items'][] = $item;
                }
                $url_format = site_url('usergroups/index?page=%i');
                $this->data['pagination'] = smart_paging($this->data['totalPages'], $page, $this->_pagination_adjacent, $url_format, $this->_pagination_pages, array('records'=>count($items),'total'=>$this->data['totalRecords']));
            }
        }
        $this->data['pagination_description'] = smart_paging_description($this->data['totalRecords'], count($this->data['items']));
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], 'Users', site_url('users'));
        breadcumb_add($this->data['breadcumb'], 'Groups', site_url('usergroups'), TRUE);
        
        $this->data['subview'] = 'cms/users/group/index';
        $this->load->view('_layout_admin', $this->data);
    }
    
    function edit(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        if (!$this->users->has_access('USER_GROUP_MANAGEMENT')){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
            redirect('usergroups/index?page='.$page);
        }
        
        if ($id){
            $item = $this->usergroup_m->get($id);
        }else{
            $item = $this->usergroup_m->get_new();
        }
        
        $this->data['item'] = $item;
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], 'Users', site_url('users/index?page='.$page));
        breadcumb_add($this->data['breadcumb'], 'Groups', site_url('usergroups'));
        breadcumb_add($this->data['breadcumb'], 'Update', NULL, TRUE);
        
        $this->data['submit_url'] = site_url('usergroups/save?id='.$id.'&page='.$page);
        $this->data['back_url'] = site_url('usergroups/index?page='.$page);
        $this->data['subview'] = 'cms/users/group/edit';
        $this->load->view('_layout_admin', $this->data);
    }
    
    function save(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        if (!$this->users->has_access('USER_GROUP_MANAGEMENT')){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
            redirect('usergroups/index?page='.$page);
        }
        
        $rules = $this->usergroup_m->rules;
        $this->form_validation->set_rules($rules);
        //exit(print_r($rules));
        if ($this->form_validation->run() != FALSE) {
            $postdata = $this->usergroup_m->array_from_post(array('group_name','sort','is_removable'));
            
            if (($this->usergroup_m->save($postdata, $id))){
                $this->session->set_flashdata('message_type','success');
                $this->session->set_flashdata('message', 'Data group user saved successfully');
                
                redirect('usergroups/index?page='.$page);
            }else{
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', $this->usergroup_m->get_last_message());
            }
        }
        
        if (validation_errors()){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', validation_errors());
        }
        
        redirect('usergroups/edit?id='.$id.'&page='.$page);
    }
    
    function delete(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        if (!$this->users->has_access('USER_GROUP_MANAGEMENT')){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
            redirect('usergroups/index?page='.$page);
        }
        
        //check if found data item
        $item = $this->usergroup_m->get($id);
        if (!$item){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Could not find data user group. Delete failed!');
        }else{
            if ($item->is_removable==0){
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', 'This group is not removable. Delete failed!');
            }else {
                if ($this->usergroup_m->delete($id)){
                    $this->session->set_flashdata('message_type','success');
                    $this->session->set_flashdata('message', 'Data user group item deleted successfully');
                }else{
                    $this->session->set_flashdata('message_type','error');
                    $this->session->set_flashdata('message', $this->usergroup_m->get_last_message());
                }
            }
        }
        
        redirect('usergroups/index?page='.$page);
    }
}

/*
 * file location: engine/application/controllers/usergroups.php
 */
