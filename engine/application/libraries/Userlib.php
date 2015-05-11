<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Users
 *
 * @author marwansaleh
 */
class Userlib extends Library {
    private static $objInstance;
    
    private $_prefix_session_access = '_SISM_';
    
    private $_table_roles = 'user_roles';
    private $_table_access_group = 'user_groupaccess';
    private $_table_access_user = 'user_access';
    private $_table_groups = 'user_groups';
    
    private $_ADMIN_GROUP_ID = 1;
    
    const USER_TYPE_INT = 0;
    const USER_TYPE_EXT = 1;
    
    function __construct() {
        parent::__construct();
        
        //load user model
        $this->ci->load->model('users/user_m');
        
        //$this->_update_session();
    }
    
    public static function getInstance(  ) { 
            
        if(!self::$objInstance){ 
            self::$objInstance = new Userlib();
        } 
        
        return self::$objInstance; 
    }
    
    public function get_name(){
        return $this->ci->session->userdata('full_name');
    }
    
    public function get_userid(){
        return $this->ci->session->userdata('userid');
    }
    /**
     * Check if user is loggedin
     * @return boolean
     */
    public function isLoggedin(){
        if ($this->ci->session->userdata('isloggedin')){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    /**
     * Try to guess wheather user is online
     * @param string $session_id
     * @param string &$add_info if not null and exists, will store info attribute
     * @return boolean
     */
    public function is_online($session_id, &$add_info=NULL){
        $session_table = $this->ci->config->item('sess_table_name');
        $this->ci->db->select('*')->from($session_table)->where('session_id',$session_id);
        $row = $this->ci->db->get()->row();
        
        if ($row){
            if ($add_info){
                $add_info = isset($row->$add_info) ? $row->$add_info : '';
            }
            return TRUE;
        }
        
        return FALSE;
    }
    
    /**
     * Generate an unique token
     * @return string hash token
     */
    public function generate_token($user_id=NULL){
        if ($user_id){
            return md5($user_id . time());
        }else{
            return md5(time());
        }
    }
    
    /**
     * Try login using username and password
     * @param string $user_name
     * @param string $password
     * @return boolean FALSE if failed, or return user object if success
     */
    public function login($user_name, $password){
        //get user specific
        $where = array('is_active'=>1);
        if (strpos($user_name, '@')!==FALSE){
            $where['email'] = $user_name;
        }else{
            $where['username'] = $user_name;
        }
        $user = $this->ci->user_m->get_by($where, TRUE);
        
        if ($user){
            if ($user->password == $this->hash($password)){
                //check if already loggedin using different location ?
                $user_info = 'ip_address';
                if ($this->is_online($user->session_id, $user_info)){
                    //check if last ip same
                    if ($user_info != $user->last_ip){
                        $this->_error_message = 'Sorry...You or some one else is logged in using this account in another machine. Please log out from the machine and continue.';
                        return FALSE;
                    }
                }
                
                //generate token
                $token = $this->generate_token($user->id);
                $user->token = $token;
                //create user loggedin session
                $this->create_login_session($user);
                //create user privileges session
                $this->_set_user_access_session($user->id, $user->group_id);
                
                return $user;
            }else{
                $this->_error_message = 'Username dan password tidak sesuai';
            }
        }else{
            $this->_error_message = 'Username dan password tidak sesuai';
        }
        
        return FALSE;
    }
    
    /**
     * Try login using user id
     * @param type $userid
     * @return boolean
     */
    public function login_by_userid($userid){
        //get user from database
        $user = $this->ci->user_m->get($userid);
        if (!$user){
            $this->_error_message = 'User not found by ID:'.$userid;
            return FALSE;
        }
         
        //generate token
        $token = $this->generate_token($userid);
        $user->token = $token;
        //create user loggedin session
        $this->create_login_session($user);
        //create user privileges session
        $this->_set_user_access_session($user->user_id, $user->group_id);

        return $user;
    }
    
    /**
     * User logout / end session
     * @param type $user_id USER ID(optional) if omitted, userID will be taken from session
     */
    public function logout($user_id=NULL){
        if (!$user_id){
            if ($this->isLoggedin()){
                $user_id = $this->ci->session->userdata('userid');
            }
        }
        
        //Update database
        if ($user_id){
            $this->ci->user_m->save(array(
                'session_id'=> '',
                'token'     => ''
            ),$user_id);
        }
        
        $this->ci->session->sess_destroy();
        if (isset($_SESSION)){
            session_destroy();
        }
    }
    
    /**
     * Save / Update user data
     * @param array $data associate array data to update
     * @param int $id userID, if omitted, will do update instead of create
     * @return boolean FALSE if failed, or return userID if success
     */
    public function save($data, $id = NULL) {
        if (isset($data['username'])){
            $check = array('username'=>$data['username']);
        }else{
            $check = array();
        }
        if (!$id){
            if (!isset($data['full_name']) || !$data['full_name']){
                $data['full_name'] = 'User_'.time();
            }
            if (!isset($data['created_on'])){
                $data['created_on'] = time();
            }
        }else{
            $check['id !='] = $id;
        }
        
        if (isset($data['password'])){
            $data['password'] = $this->hash($data['password']);
        }
        
        if (isset($data['username']) && $this->ci->user_m->get_count($check)){
            $this->_error_message = 'Duplicate entry for username '.$data['username'];

            return FALSE;
        }
        
        $result = $this->ci->user_m->save($data, $id);
        return $result;
    }
    
    /**
     * Check for access privileges for a specific role
     * @param string $role_name
     * @return boolean
     */
    public function has_access($role_name){
        return $this->ci->session->userdata($this->_prefix_session_access . $role_name);
    }
    
    /**
     * Check group is admin group
     * @param int $group_id
     * @return boolean
     */
    public function is_admin($group_id=NULL){
        if (!$group_id){
            return $this->ci->session->userdata('is_administrator');
        }else{
            return ($group_id == $this->_ADMIN_GROUP_ID);
        }
    }
    
    /**
     * Get admin group ID
     * @return int
     */
    public function get_admin_groupID(){
        return $this->_ADMIN_GROUP_ID;
    }
    
    /**
     * Generate password
     * @return string
     */
    public function generate_password($length=8){
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
    
    /**
     * Get internal user type
     * @return int
     */
    public function internal_type(){
        return self::USER_TYPE_INT;
    }
    
    /**
     * Get full url avatar
     * @param string $avatar
     * @return string
     */
    public function get_avatar_url($avatar=NULL){
        if (!$avatar){
            $avatar = $this->ci->session->userdata('avatar');
        }
        if (strpos($avatar, 'http')!==FALSE){
            return $avatar;
        }else{
            $base_avatar_path = '';
            if (strpos($avatar, $this->ci->config->item('avatar'))===FALSE){
                $base_avatar_path = $this->ci->config->item('avatar');
            }
            
            return $this->ci->config->site_url($base_avatar_path . $avatar);
        }
    }
    
    /**
     * Get user avatar by userID
     * @param int $user_id
     * @return string avatar url if exists or NULL if not exists
     */
    public function get_avatar_by_id($user_id){
        $user = $this->ci->user_m->get($user_id);
        if ($user && !empty($user->avatar)){
            return $this->get_avatar_url($user->avatar);
        }
        
        return NULL;
    }
    
    public function get_default_avatars(){
        $list = array();
        $avatar_def_path = $this->ci->config->item('avatar') .'default/';
        foreach (glob($avatar_def_path . '*.*') as $avatar){
            $list [] = $avatar;
        }
        
        return $list;
    }
    
    public function get_my_avatars(){
        $list = array();
        
        //get user's avatar path
        $user_avatar_path = $this->get_userid();
        if ($user_avatar_path){
            $user_avatar_path = $this->ci->config->item('avatar') .$user_avatar_path .'/';
            foreach (glob($user_avatar_path . '*.*') as $avatar){
                $list [] = $avatar;
            }
        }
        
        return $list;
    }
    
    public function me($session=TRUE){
        if ($session){
            $user = new stdClass();
            $user->id = $this->ci->session->userdata('userid');
            $user->full_name = $this->ci->session->userdata('full_name');
            $user->group_id = $this->ci->session->userdata('group_id');
            $user->group_name = $this->ci->session->userdata('group_name');
            $user->is_administrator = $this->ci->session->userdata('is_administrator');
            $user->username = $this->ci->session->userdata('username');
            $user->avatar = $this->ci->session->userdata('avatar');
            $user->created_on = $this->ci->session->userdata('created_on');
            
        }else {
            $user = $this->ci->user_m->get($this->get_userid());
            $user->group_name = $this->_get_group_name($user->group_id);
            $user->is_administrator = $this->is_admin($user->group_id);
        }
        
        return $user;
    }
    
    
    
    public function update_session_me($full_name=NULL, $user_name=NULL, $group_id=NULL, $avatar=NULL){
        if ($full_name){
            $this->ci->session->set_userdata('full_name',$full_name);
        }
        if ($user_name){
            $this->ci->session->set_userdata('username',$user_name);
        }
        if ($group_id){
            $this->ci->session->set_userdata('group_id',$group_id);
            $this->ci->session->set_userdata('group_name',$this->_get_group_name($group_id));
            $this->ci->session->set_userdata('is_administrator',  $this->is_admin($group_id));
        }
        if ($avatar){
            $this->ci->session->set_userdata('avatar',$avatar);
        }
    }
    
    /**
     * Create session for a user
     * @param mixed $user user record object
     */
    public function create_login_session($user=NULL, $update_login_info=TRUE){
        if (!$user){
            $user = $this->ci->user_m->get($this->ci->session->userdata('userid'));
        }
        if ($update_login_info){
            //Update database
            $this->ci->user_m->save(array(
                'last_login'    => time(),
                'last_ip'       => $this->ci->input->ip_address(),
                'session_id'    => $this->ci->session->userdata('session_id'),
                'token'         => $user->token

            ), $user->id);
        }
        
        //create session for detail user
        $user_session = array(
            'isloggedin'        => TRUE,
            'userid'            => $user->id,
            'username'          => $user->username,
            'full_name'         => $user->full_name,
            'group_id'          => $user->group_id,
            'group_name'        => $this->_get_group_name($user->group_id),
            'is_administrator'  => $this->is_admin($user->group_id),
            'last_login'        => $user->last_login>0 ? $user->last_login : time(),
            'avatar'            => $user->avatar ? $user->avatar : $this->ci->config->item('avatar') .'default/default.jpg',
            'created_on'        => $user->created_on,
            'token'             => $user->token
        );
        
        $this->ci->session->set_userdata($user_session);
    }
    
    public function get_user_privileges($user_id){
        $group_id = $this->ci->user_m->get_value('group_id',array('id'=>$user_id));
        
        if (!$group_id){
            return FALSE;
        }
        
        $user_privileges = array();
        
        //get all roles
        $all_roles = $this->_get_all_roles();
        $group_access = array();
        $user_access = array();
        if (!$this->is_admin($group_id)){
            //get access for its group
            $group_access_db = $this->_get_group_access($group_id);
            foreach ($group_access_db as $g_role){
                $group_access [$g_role->role_id] = $g_role->has_access == 1?TRUE:FALSE;
            }
            
            //get access for this specific user if any
            $user_access_db = $this->_get_users_access($user_id);
            foreach ($user_access_db as $u_role){
                $user_access [$u_role->role_id] = $u_role->has_access == 1?TRUE:FALSE;
            }
        }
        
        //start looping for making user access
        //set the roles
        foreach($all_roles as $role){
            if ($this->is_admin($group_id)){
                $role->has_access = TRUE;
            }else{
                $role->has_access = isset($user_access[$role->role_id]) ? $user_access[$role->role_id] : (isset($group_access[$role->role_id]) ? $group_access[$role->role_id] : FALSE);
            }
            
            $user_privileges [] = $role;
        }
        
        return $user_privileges;
    }
    
    public function update_session(){
        $this->_update_session();
    }
    
    public function get_user_record($userid){
        return $this->ci->user_m->get($userid);
    }
    
    /**
     * Update loggedin user session in database
     */
    private function _update_session(){
        $session_id = $this->ci->session->userdata('session_id');
        if ($this->isLoggedin()){
            $user_id = $this->ci->session->userdata('userid');
            $this->ci->user_m->save(array('session_id'=>  $session_id),  $user_id);
        }else{
            $session_table = $this->ci->config->item('sess_table_name');
            $this->ci->db->simple_query('DELETE FROM '.$this->ci->db->dbprefix($session_table).' WHERE session_id='.$session_id);
        }
    }
    
    /**
     * Set user privileges
     * @param int $user_id
     * @param int $group_id
     */
    private function _set_user_access_session($user_id, $group_id){
        
        //get roles defined for this group
        if (!$this->is_admin($group_id)){
            $group_roles = array();
            foreach ($this->_get_group_access($group_id) as $g_role){
                $group_roles [$g_role->role_id] = $g_role->has_access == 1?TRUE:FALSE;
            }

            //get per user roles
            $user_roles = array();
            foreach ($this->_get_users_access($user_id) as $u_role){
                $user_roles [$u_role->role_id] = $u_role->has_access == 1?TRUE:FALSE;
            }
        }
        
        //get all roles
        $access_roles = array();
        foreach ($this->_get_all_roles() as $role){
            if ($this->is_admin($group_id)){
                $access_roles[$this->_prefix_session_access . $role->role_name] = TRUE;
            }else{
                $access_roles[$this->_prefix_session_access . $role->role_name] = isset($user_roles[$role->role_id]) ? $user_roles[$role->role_id] : (isset($group_roles[$role->role_id]) ? $group_roles[$role->role_id] : FALSE);
            }
        }
        
        $this->ci->session->set_userdata($access_roles);
    }
    
    /**
     * Get all roles from database
     * @return mixed
     */
    private function _get_all_roles(){
        return $this->ci->db->select('*')->from($this->_table_roles)->get()->result();
    }
    
    /**
     * Get group privileges
     * @param int $group_id
     * @return mixed
     */
    private function _get_group_access($group_id){
        return $this->ci->db->select('*')->from($this->_table_access_group)->where('group_id', $group_id)->get()->result();
    }
    
    /**
     * Get user privileges
     * @param int $user_id
     * @return mixed
     */
    private function _get_users_access($user_id){
        return $this->ci->db->select('*')->from($this->_table_access_user)->where('user_id', $user_id)->get()->result();
    }
    
    private function _get_group_name($group_id){
        $this->ci->load->model('users/usergroup_m');
        
        return $this->ci->usergroup_m->get_value('group_name', array('group_id'=>$group_id));
    }
}
