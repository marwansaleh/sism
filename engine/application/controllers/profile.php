<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Profile
 *
 * @author marwansaleh
 */
class Profile extends MY_AdminController {
    function __construct() {
        parent::__construct();
        $this->data['active_menu'] = 'users';
        $this->data['page_title'] = '<i class="fa fa-users"></i> User Profile';
        $this->data['page_description'] = 'Profile selected user';
        
        //Loading model
        $this->load->model(array('users/usergroup_m'));
    }
    
    function index(){
        $id = $this->input->get('id', TRUE);
        
        $user = $this->user_m->get($id);
        $user->group_name = $this->usergroup_m->get_value('group_name',array('group_id'=>$user->group_id));
        $user->is_online = $this->users->is_online($user->session_id);
        $user->avatar_full_url = $this->users->get_avatar_url($user->avatar);
        
        //get last articles
        $this->load->model('article/article_m');
        $articles = $this->article_m->get_offset('*',array('created_by'=>$id),0,10);
        $user->last_articles = array();
        foreach ($articles as $article){
            $article->author = $user->full_name;
            $user->last_articles [] = $article;
        }
        
        $this->data['user'] = $user;
        
        //get supported data
        if ($this->users->get_userid()==$id){
            $avatars = $this->users->get_default_avatars();
            $this->data['avatars'] = array_merge($avatars, $this->users->get_my_avatars());
        }
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], 'Users', site_url('cms/users'));
        breadcumb_add($this->data['breadcumb'], 'Profile', site_url('cms/profile'), TRUE);
        
        $this->data['subview'] = 'cms/users/profile/index';
        $this->load->view('_layout_admin', $this->data);
    }
    
    function save(){
        $id = $this->input->get('id', TRUE);
        
        if ($this->users->get_userid()!=$id){
            redirect('cms/profile/index?id='.$id);
        }
        
        $rules = $this->user_m->rules_profile;
        $this->form_validation->set_rules($rules);
        //exit(print_r($rules));
        if ($this->form_validation->run() != FALSE) {
            $postdata = $this->user_m->array_from_post(array('full_name','username','password','change_password','email','mobile','phone','avatar','about'));
            if ($postdata['change_password'] && !$postdata['password']){
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', 'Password can not blank');
                
                redirect('cms/profile/index?id='.$id);
            }else if (!$postdata['change_password']){
                unset($postdata['password']);
            }
            //unset not user data model attribute
            unset($postdata['change_password']);
            
            if (isset($postdata['password'])){
                $postdata['password'] = $this->users->hash($postdata['password']);
            }
            
            if (($this->user_m->save($postdata, $id))){
                $this->session->set_flashdata('message_type','success');
                $this->session->set_flashdata('message', 'Data user saved successfully');
                
                $this->users->update_session_me($postdata['full_name'], $postdata['username'], NULL, $postdata['avatar']);
                redirect('cms/profile/index?page='.$page);
            }else{
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', $this->user_m->get_last_message());
            }
        }
        
        if (validation_errors()){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', validation_errors());
        }
        
        redirect('cms/profile/index?id='.$id);
    }
    
}

/*
 * file location: engine/application/controllers/profile.php
 */
