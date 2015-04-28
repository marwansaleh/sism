<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Users
 *
 * @author marwansaleh
 */
class Users extends MY_AdminController {
    function __construct() {
        parent::__construct();
        $this->data['active_menu'] = 'users';
        $this->data['page_title'] = '<i class="fa fa-users"></i> User Management';
        $this->data['page_description'] = 'List and update users';
        
        //Loading model
        $this->load->model(array('users/usergroup_m'));
    }
    
    function index(){
        $page = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE):1;
        
        $this->data['page'] = $page;
        $offset = ($page-1) * $this->REC_PER_PAGE;
        $this->data['offset'] = $offset;
        
        //set access
        $this->data['set_access_privilege'] = $this->users->has_access('USER_ACCESS_USER_MANAGEMENT');
        
        $where = NULL;
        
        //count totalRecords
        $this->data['totalRecords'] = $this->user_m->get_count($where);
        //count totalPages
        $this->data['totalPages'] = ceil ($this->data['totalRecords']/$this->REC_PER_PAGE);
        $this->data['items'] = array();
        if ($this->data['totalRecords']>0){
            $items = $this->user_m->get_offset('*',$where,$offset,  $this->REC_PER_PAGE);
            if ($items){
                foreach($items as $item){
                    $item->group_name = $this->usergroup_m->get_value('group_name',array('group_id'=>$item->group_id));
                    $item->is_online = $this->users->is_online($item->session_id);
                    $this->data['items'][] = $item;
                }
                $url_format = site_url('users/index?page=%i');
                $this->data['pagination'] = smart_paging($this->data['totalPages'], $page, $this->_pagination_adjacent, $url_format, $this->_pagination_pages, array('records'=>count($items),'total'=>$this->data['totalRecords']));
            }
        }
        $this->data['pagination_description'] = smart_paging_description($this->data['totalRecords'], count($this->data['items']));
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], 'Users', site_url('cms/users'), TRUE);
        
        $this->data['subview'] = 'cms/users/user/index';
        $this->load->view('_layout_admin', $this->data);
    }
    
    function edit(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        if (!$this->users->has_access('USER_MANAGEMENT')){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
            redirect('users/index?page='.$page);
        }
        
        $this->data['author'] = $this->users->get_userid();
        
        if ($id){
            $item = $this->user_m->get($id);
            $item->is_online = $this->users->is_online($item->session_id);
        }else{
            $item = $this->user_m->get_new();
            $item->is_online = FALSE;
            $item->created_on = time();
        }
        
        $this->data['item'] = $item;
        
        //data support
        $this->data['groups'] = $this->usergroup_m->get();
        
        $avatars = $this->users->get_default_avatars();
        if ($id && $id==$this->users->get_userid()){
            $this->data['avatars'] = array_merge($avatars, $this->users->get_my_avatars());
        }else{
            $this->data['avatars'] = $avatars;
        }
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], 'Users', site_url('cms/users/index?page='.$page));
        breadcumb_add($this->data['breadcumb'], 'Update User', NULL, TRUE);
        
        $this->data['submit_url'] = site_url('users/save?id='.$id.'&page='.$page);
        $this->data['back_url'] = site_url('users/index?page='.$page);
        $this->data['subview'] = 'cms/users/user/edit';
        $this->load->view('_layout_admin', $this->data);
    }
    
    function save(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        if (!$this->users->has_access('USER_MANAGEMENT')){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
            redirect('users/index?page='.$page);
        }
        
        $rules = $this->user_m->rules_update;
        $this->form_validation->set_rules($rules);
        //exit(print_r($rules));
        if ($this->form_validation->run() != FALSE) {
            $postdata = $this->user_m->array_from_post(array('full_name','group_id','type','position','username','password','change_password','email','mobile','phone','avatar','about'));
            if ((!$id && !$postdata['password']) || ($id && $postdata['change_password'] && !$postdata['password'])){
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', 'Password can not blank');
                
                redirect('users/edit?id='.$id.'&page='.$page);
            }else if ($id && !$postdata['change_password']){
                unset($postdata['password']);
            }
            //unset not user data model attribute
            unset($postdata['change_password']);
            
            if (isset($postdata['password'])){
                $postdata['password'] = $this->users->hash($postdata['password']);
            }
            if (!$id){
                $postdata['created_on'] = time();
            }
            
            if (($this->user_m->save($postdata, $id))){
                $this->session->set_flashdata('message_type','success');
                $this->session->set_flashdata('message', 'Data user saved successfully');
                
                if ($id && $id==$this->users->get_userid()){
                    //update me profile
                    $this->users->update_session_me($postdata['full_name'], $postdata['username'], $postdata['group_id'], $postdata['avatar']);
                }
                
                redirect('users/index?page='.$page);
            }else{
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', $this->user_m->get_last_message());
            }
        }
        
        if (validation_errors()){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', validation_errors());
        }
        
        redirect('users/edit?id='.$id.'&page='.$page);
    }
    
    function delete(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        if (!$this->users->has_access('USER_MANAGEMENT')){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
            redirect('users/index?page='.$page);
        }
        
        //check if found data item
        $item = $this->user_m->get($id);
        if (!$item){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Could not find data user. Delete user failed!');
        }else{
            if ($this->user_m->delete($id)){
                $this->session->set_flashdata('message_type','success');
                $this->session->set_flashdata('message', 'Data user item deleted successfully');
            }else{
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', $this->user_m->get_last_message());
            }
        }
        
        redirect('users/index?page='.$page);
    }
    
    function access(){
        $id = $this->input->get('id');
        
        $user = $this->user_m->get($id);
        $access_roles = $this->users->get_user_privileges($id);
        
        $this->data['user'] = $user;
        $this->data['access'] = $access_roles;
        
        $this->data['subview'] = 'cms/users/u_access/index';
        $this->load->view('_layout_admin_simple', $this->data);
    }
}

/*
 * file location: engine/application/controllers/users.php
 */
