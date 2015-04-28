<?php

/**
 * Description of Library
 *
 * @author marwansaleh
 */
class Library {
    
    private $_log_file = 'mylogfile.log';
    private $_log_path;
    
    protected $ip_address;
    protected $ci;
    protected $_error_message;
    
    function __construct() {
        $this->ci =& get_instance();
        
        $log_file = $this->ci->config->item('log_file');
        if ($log_file){
            $this->_log_file = $log_file;
        }
        
        $this->_log_path = rtrim(sys_get_temp_dir(), '/') .'/';
        $this->ip_address = $this->ci->input->ip_address();
    }
    
    public function hash($subject){
        return hash('sha256', $this->ci->config->item('encryption_key') . $subject);
    }
    
    /**
     * Get error message
     * @return string
     */
    public function get_message(){
        return $this->_error_message;
    }
    
    /**
     * Get IP address
     * @return string
     */
    public function get_ip_address(){
        return $this->ip_address;
    }
    
    /**
     * Write into log file
     * @param string $event_name log description
     * @throws Exception if failed
     */
    protected function write_log($event_name=''){
        $content = array(
            date('Y-m-d H:i:s'),  
            $this->ip_address,
            $event_name
        );
        
        if ($fp = @fopen($this->_log_path . $this->_log_file, 'a')){
            fputcsv($fp, $content, "\t");
            fclose($fp);
        }
    }
    
    protected function _load_app_config($APP_PATTERN){
        $this->ci->load->model('system/sys_variables_m');
        $like = array('field'=>'var_name', 'value'=>$APP_PATTERN);
        $result = $this->ci->sys_variables_m->get_like($like);
        
        $config = array();
        foreach($result as $r){
            $config[$r->var_name] = $r->var_value;
        }
        
        return $config;
    }
}
