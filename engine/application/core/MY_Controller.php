<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_BaseController extends CI_Controller {
    private $_cookie_visitor = 'visitor';
    private $_log_file = 'mylogfile.log';
    
    protected $REC_PER_PAGE = 8;
    protected $_log_path;
    protected $users;
    
    function __construct() {
        parent::__construct();
        
        //Load User Library
        $this->users = Userlib::getInstance();
        
        //Load helper
        $this->load->helper('general');
        $this->load->helper('cookie');
        
        //Iniatiate process
        $this->__initialisation();        
        
        $this->data['mobile'] = $this->is_device('MOBILE');
    }
    
    private function __initialisation(){
        //Create unique id for unique visitor
        $this->_create_unique_visitor();
        
        $this->_log_path = rtrim(sys_get_temp_dir(), '/') .'/';
    }
    
    /**
     * Create unique visitor cookie
     */
    protected function _create_unique_visitor(){
        //check if cookie for this visitor exists, if not create one
        
        if (!get_cookie($this->_cookie_visitor)){
            $cookie = array(
                'name'   => $this->_cookie_visitor,
                'value'  => md5(time() . $this->input->ip_address()),
                'expire' => strtotime('December 31 2020')
            );
            set_cookie($cookie);
            
            //register new user
            $this->_visitor_register();
        }
    }
    
    protected function _visitor_register(){
        $this->db->insert('unique_visitors', array(
            'visitor_id'    => $this->_get_unique_visitor(),
            'date'          => time(),
            'ip_address'    => $this->input->ip_address()
        ));
    }
    
    /**
     * Get unique visitor ID from cookie created by function create_unique_visitor
     * @return string unique visitor id
     */
    protected function _get_unique_visitor(){
        return get_cookie($this->_cookie_visitor);
    }


    /**
     * Write into log file
     * @param string $event_name log description
     * @throws Exception if failed
     */
    public function _write_log($event_name=''){
        $class_name = get_class($this);
        $content = array(
            date('Y-m-d H:i:s'), 
            $this->_get_unique_visitor(), 
            $this->input->ip_address(),
            ($class_name ? '['. $class_name .'] ' : '') .$event_name
        );
        
        if ($fp = @fopen($this->_log_path . $this->_log_file, 'a')){
            fputcsv($fp, $content, "\t");
            fclose($fp);
        }
    }
    
    protected function file_extension($filename){
        $ext = pathinfo($filename, PATHINFO_EXTENSION); 
        return strtolower($ext);
    }
    
    protected function read_log($lines=5, $filepath=NULL, $adaptive = true){
        // Open file
        if (!$filepath){
            $filepath = $this->_log_path . $this->_log_file;
        }
        $f = @fopen($filepath, "rb");
        if ($f === false) return false;

        // Sets buffer size
        if (!$adaptive) $buffer = 4096;
        else $buffer = ($lines < 2 ? 64 : ($lines < 10 ? 512 : 4096));

        // Jump to last character
        fseek($f, -1, SEEK_END);

        // Read it and adjust line number if necessary
        // (Otherwise the result would be wrong if file doesn't end with a blank line)
        if (fread($f, 1) != "\n") $lines -= 1;
        // Start reading
        $output = '';
        $chunk = '';

        // While we would like more
        while (ftell($f) > 0 && $lines >= 0) {

        // Figure out how far back we should jump
        $seek = min(ftell($f), $buffer);

        // Do the jump (backwards, relative to where we are)
        fseek($f, -$seek, SEEK_CUR);

        // Read a chunk and prepend it to our output
        $output = ($chunk = fread($f, $seek)) . $output;

        // Jump back to where we started reading
        fseek($f, -mb_strlen($chunk, '8bit'), SEEK_CUR);

        // Decrease our line counter
        $lines -= substr_count($chunk, "\n");

        }

        // While we have too many lines
        // (Because of buffer size we might have read too many)
        while ($lines++ < 0) {

        // Find first newline and remove all text before that
        $output = substr($output, strpos($output, "\n") + 1);

        }

        // Close file and return
        fclose($f);
        return trim($output);
    }
    
    /**
     * Get sys parameters
     * @param string $pattern
     * @return associative array
     */
    protected function get_sys_parameters($pattern=NULL){
        $this->load->model('system/sys_variables_m','sysvar_m');
        
        $sysvars = array();
        $result = NULL;
        
        if (!$pattern){
            $result = $this->sysvar_m->get();
        }else{
            if (is_array($pattern)){
                foreach($pattern as $index => $p){
                    if ($index==0):
                        $this->db->like('var_name', $p);
                    else:
                        $this->db->or_like('var_name', $p);
                    endif;
                }
            }else{
                $this->db->like('var_name', $pattern);
            }
            $result = $this->sysvar_m->get();
        }
        if ($result){
            foreach ($result as $var){
                $sysvars[$var->var_name] = variable_type_cast($var->var_value,$var->var_type);
            }
        }
        
        return $sysvars;
    }
    
    protected function get_sysvar_value($var_name){
        $this->load->model('system/sys_variables_m','sysvar_m');
        
        $result = $this->sysvar_m->get_select_where('var_value,var_type,is_list',array('var_name'=>$var_name),TRUE);
        
        if ($result){
            $var_value = variable_type_cast($result->var_value, $result->var_type);
            if ($result->is_list==1){
                $delimiter = ',';
                if (strpos($var_value, '|')!==FALSE){
                    $delimiter = '|';
                }
                return explode($delimiter, $var_value);
            }else{
                return $var_value;
            }
        }
        
        return NULL;
    }
    
    protected function is_device($deviceToCheck='DESKTOP'){
        $IE = stripos($_SERVER['HTTP_USER_AGENT'],"MSIE");
        $iPod = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
        $iPhone = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
        $iPad = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
        $iMac = stripos($_SERVER['HTTP_USER_AGENT'],"Macintosh");
        $AndroidTablet = false;
        
        if(stripos($_SERVER['HTTP_USER_AGENT'],"Android") && stripos($_SERVER['HTTP_USER_AGENT'],"mobile")){
                $Android = true;
        }else if(stripos($_SERVER['HTTP_USER_AGENT'],"Android")){
                $Android = false;
                $AndroidTablet = true;
        }else{
                $Android = false;
                $AndroidTablet = false;
        }
        
        $symbianOS = stripos($_SERVER['HTTP_USER_AGENT'],"symbianOS");
        //$webOS = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");
        $BlackBerry = stripos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
        $RimTablet= stripos($_SERVER['HTTP_USER_AGENT'],"RIM Tablet");
        $winP= stripos($_SERVER['HTTP_USER_AGENT'],"Windows Phone");
        $winM= stripos($_SERVER['HTTP_USER_AGENT'],"Windows Mobile");
        $win= stripos($_SERVER['HTTP_USER_AGENT'],"Windows");

        
        switch($deviceToCheck){
            case 'IE': return $IE; break;
            case 'IPOD': return $iPod; break;
            case 'IPHONE': return $iPhone; break;
            case 'IPAD': return $iPad; break;
            case 'IMAC': return $iMac; break;
            case 'ANDROID': return ( $Android || $AndroidTablet ? true : false); break;
            case 'ANDROIDPHONE': return $Android; break;
            case 'ANDROIDTAB': return $AndroidTablet; break;
            case 'WINMO': return ( $winP || $winM ? true : false); break;
            case 'WIN': return $win; break;
            case 'SYMBIAN': return $symbianOS; break;
            case 'BLACKBERRY': return $BlackBerry; break;
            case 'RIMTABLET': return $RimTablet; break;
            case 'MOBILE': return ( $iPad || $iPod || $iPhone || $Android || $symbianOS  || $BlackBerry || $winP || $winM ? true : false); break;
            case 'TABLET': return ( $AndroidTablet || $RimTablet ? true : false); break;
            case 'DESKTOP': return ( ($iMac || $win) ? true : false); break;
        }
        
        return FALSE;
    }
    
    protected function user_activity($activity, $user_id=NULL){
        $this->load->model('users/user_activity_m');
        $data = array(
            'activity'      => $activity, 
            'ip_address'    => $this->input->ip_address(), 
            'date'          => time(),
            'visitor_id'    => $this->_get_unique_visitor()
        );
        if ($user_id){
            $data['user_id'] = $user_id;
        }
        $this->user_activity_m->save($data);
    }
}

/**
 * Description of MY_Controller
 *
 * @author Marwan Saleh <marwan.saleh@ymail.com>
 */
class MY_Controller extends MY_BaseController {
    public $data = array();
    
    protected $_pagination_adjacent = 3;
    protected $_pagination_pages = 5;
    
    function __construct() {
        parent::__construct();    
        
        $this->users->update_session();
        
        $this->data['meta_title'] = 'Sistem Informasi Surat Menyurat';
        $this->data['active_menu'] = 'dashboard';
    }
    
}

class MY_AdminController extends MY_Controller {
    protected $REC_PER_PAGE = 10;
    
    function __construct() {
        parent::__construct();
        if (!$this->users->isLoggedin()){
            $this->_write_log('Trying to access page required loggedin but not authorized. Redirect to login page');
            redirect('auth');
            exit;
        }
        $this->data['active_menu'] = 'dashboard';
        if ($this->session->userdata('sidebar_collapse')!=TRUE){
            $this->data['body_class'] = 'skin-blue';
        }else{
            $this->data['body_class'] = 'skin-blue sidebar-collapse';
        }
        
        $this->load->helper(array('form','url'));
        $this->load->library('form_validation');
        
        $this->data['breadcumb'] = array();
        //set default breadcumb
        breadcumb_add($this->data['breadcumb'], '<i class="fa fa-home"></i> Dashboard', site_url('dashboard'));
        
        $this->data['page_title'] = 'Dashboard';
        
        //set user loggedin info
        $this->data['avatar_url_me'] = $this->users->get_avatar_url();
        $this->data['me'] = $this->users->me();
    }
    
    
}

/**
 * Description of MY_Ajax
 *
 * @author Marwan Saleh <marwan.saleh@ymail.com>
 */
class MY_Ajax extends MY_BaseController {
    
    function __construct() {
        parent::__construct();
        
        //check if ajax request
        $this->_exit_not_ajax_request();
    }
    
    function send_output($data=NULL){
        $this->output->set_content_type('application/json');
        $this->output->set_header('Cache-Control: no-cache, must-revalidate');
        $this->output->set_header('Expires: '.date('r', time()+(86400*365)));

        $output = json_encode($data);

        $this->output->set_output($output);
    }
    
    private function _exit_not_ajax_request(){
        if (!$this->input->is_ajax_request()){
            show_error('The requested page is not allowed to access', 401);
            exit;
        }
    }
}

/*
 * file location: engine/application/core/MY_Controller.php
 */