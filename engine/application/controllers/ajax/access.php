<?php

/**
 * Description of Article for Ajax Call
 *
 * @author marwan
 * @email amazzura.biz@gmail.com
 */
class Access extends MY_Ajax {
    
    function __construct() {
        parent::__construct();
    }
    
    function set_access(){
        $this->load->model('users/useraccess_g_m','access_g_m');
        $result = array('status'=>0,'message'=>'');
        
        if (!$this->users->has_access('USER_ACCESS_MANAGEMENT')){
            $result['message'] = 'Sorry..you dont have access to this feature';
        }else{
            $role_id = $this->input->post('role_id',TRUE);
            $group_id = $this->input->post('group_id',TRUE);
            $has_access = $this->input->post('has_access',TRUE);

            $data_access = array('role_id'=>$role_id,'group_id'=>$group_id);

            if ($this->users->is_admin($group_id)){
                $result['status'] = 0;
                $result['message'] = 'Admin group always has access';
            }else{
                $this->access_g_m->delete_where($data_access);

                $data_access['has_access'] = $has_access;
                if ($this->access_g_m->save($data_access)){
                    $result['status'] = 1;

                    //create user activity log
                    $has_access_label = $has_access?'enabled':'disabled';
                    $this->user_activity("Set privileges to {$has_access_label} groupID:{$group_id} for feature roleID:{$role_id}.");
                }else{
                    $result['message'] = $this->access_g_m->get_last_message();
                }
            }
        }
        
        
        $this->send_output($result);
    }
    
    function set_access_peruser(){
        $this->load->model('users/useraccess_u_m','access_u_m');
        $result = array('status'=>0,'message'=>'');
        
        if (!$this->users->has_access('USER_ACCESS_USER_MANAGEMENT')){
            $result['message'] = 'Sorry..you dont have access to this feature';
        }else {
            $role_id = $this->input->post('role_id',TRUE);
            $user_id = $this->input->post('user_id',TRUE);
            $has_access = $this->input->post('has_access',TRUE);

            //get user record
            $user = $this->user_m->get($user_id);

            $data_access = array('role_id'=>$role_id,'user_id'=>$user_id);

            if ($this->users->is_admin($user->group_id)){
                $result['status'] = 0;
                $result['message'] = 'User in group administrator always has access';
            }else{
                $this->access_u_m->delete_where($data_access);

                $data_access['has_access'] = $has_access;
                if ($this->access_u_m->save($data_access)){
                    $result['status'] = 1;

                    //create user activity log
                    $has_access_label = $has_access?'enabled':'disabled';
                    $this->user_activity("Set privileges to {$has_access_label} userID:{$user_id} for feature roleID:{$role_id}.");
                }else{
                    $result['message'] = $this->access_u_m->get_last_message();
                }
            }
        }
        
        
        $this->send_output($result);
    }
}

/*
 * file location: ./application/controllers/ajax/access.php
 */