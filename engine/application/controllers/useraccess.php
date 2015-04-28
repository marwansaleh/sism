<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Usergroups
 *
 * @author marwansaleh
 */
class Useraccess extends MY_AdminController {
    function __construct() {
        parent::__construct();
        $this->data['active_menu'] = 'users';
        $this->data['page_title'] = '<i class="fa fa-key"></i> Group Access Management';
        $this->data['page_description'] = 'List and update group privileges';
        
        //Loading model
        $this->load->model('users/userrole_m','role_m');
        $this->load->model('users/usergroup_m','group_m');
        $this->load->model('users/useraccess_g_m','useraccess_m');
    }
    
    function index(){
        $page = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE):1;
        
        $this->data['page'] = $page;
        $offset = ($page-1) * $this->REC_PER_PAGE;
        $this->data['offset'] = $offset;
        
        $where = NULL;
        
        //get all groups
        $usergroups = $this->_get_all_groups();
        $this->data['usergroups'] = $usergroups;
        
        //count totalRecords
        $this->data['totalRecords'] = $this->role_m->get_count($where);
        //count totalPages
        $this->data['totalPages'] = ceil ($this->data['totalRecords']/$this->REC_PER_PAGE);
        $this->data['items'] = array();
        if ($this->data['totalRecords']>0){
            $items = $this->role_m->get_offset('*',$where,$offset,  $this->REC_PER_PAGE);
            if ($items){
                foreach($items as $item){
                    $access = $this->_get_access($item->role_id);
                    $item->groups = array();
                    foreach ($usergroups as $key=>$g){
                        if ($g['is_admin']){
                            $item->groups[$key] = TRUE;
                        }else{
                            $item->groups[$key] = isset($access[$key]) ? $access[$key] : FALSE;
                        }
                    }
                    $this->data['items'][] = $item;
                }
                $url_format = site_url('useraccess/index?page=%i');
                $this->data['pagination'] = smart_paging($this->data['totalPages'], $page, $this->_pagination_adjacent, $url_format, $this->_pagination_pages, array('records'=>count($items),'total'=>$this->data['totalRecords']));
            }
        }
        $this->data['pagination_description'] = smart_paging_description($this->data['totalRecords'], count($this->data['items']));
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], 'Users', site_url('users'));
        breadcumb_add($this->data['breadcumb'], 'Roles', site_url('userroles'), TRUE);
        
        $this->data['subview'] = 'cms/users/g_access/index';
        $this->load->view('_layout_admin', $this->data);
    }
    
    private function _get_access($role_id){
        $all_access = $this->useraccess_m->get_by(array('role_id'=>$role_id));
        
        $privileges = array();
        foreach($all_access as $acc){
            $privileges[$acc->group_id] = $acc->has_access==1;
        }
        
        return $privileges;
    }
    
    private function _get_all_groups(){
        $admin_skip = $this->users->get_admin_groupID();
        
        $all = $this->group_m->get_by(array('group_id !=' => $admin_skip));
        
        $result = array();
        foreach($all as $g){
            $result[$g->group_id] = array(
                'group_id' => $g->group_id,
                'is_admin'=>  $this->users->is_admin($g->group_id),
                'name'=>$g->group_name
            );
        }
        
        return $result;
    }
}

/*
 * file location: engine/application/controllers/useraccess.php
 */
