<?php

/**
 * Description of GoogleShortener
 *
 * @author marwansaleh
 */
class GoogleShortener extends Library {
    private $_api_base_url = 'https://www.googleapis.com/urlshortener/v1/url';
    private $_api_key;
    private $_debug_file;
    
    function __construct($api_key=NULL) {
        parent::__construct();
        
        if (!$api_key){
            $config = $this->_load_app_config('GOOGLE_');
            $this->_api_key = $config['GOOGLE_API_KEY'];
        }else{
            $this->_api_key = $api_key;
        }
        $this->_debug_file = rtrim(sys_get_temp_dir(),'/') .'/googlest.log';
    }
    
    public function shortener($long_url){
        //open debug file
        $fp = fopen($this->_debug_file, 'w');
        
        $options = array(
            'longUrl'   => $long_url
        );
        $params = array(
            'fields'    => 'id',
            'key'       => $this->_api_key
        );
        //create curl
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->_api_base_url .'?'.  http_build_query($params));
        curl_setopt($curl, CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($options));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($curl, CURLOPT_VERBOSE, TRUE);
        curl_setopt($curl, CURLOPT_STDERR, $fp);
            
        $output = curl_exec($curl);
        curl_close($curl);
        
        if (($decoded = json_decode($output))){
            return $decoded;
        }
        return $output;
    }
    
    public function expand($short_url){
        //open debug file
        $fp = fopen($this->_debug_file, 'w');
        
        $params = array(
            'fields'    => 'longUrl,status',
            'shortUrl'  => $short_url,
            'key'       => $this->_api_key
        );
        
        //create curl
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->_api_base_url .'?'.  http_build_query($params));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        //curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($curl, CURLOPT_VERBOSE, TRUE);
        curl_setopt($curl, CURLOPT_STDERR, $fp);
            
        $output = curl_exec($curl);
        curl_close($curl);
        
        if (($decoded = json_decode($output))){
            return $decoded;
        }
        return $output;
    }
    
    public function analytic($short_url){
        //open debug file
        $fp = fopen($this->_debug_file, 'w');
        
        $params = array(
            'shortUrl'  => $short_url,
            'projection'=> 'FULL',
            'fields'    => 'analytics,longUrl,status',
            'key'       => $this->_api_key
        );
        //create curl
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->_api_base_url .'?'.  http_build_query($params));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        curl_setopt($curl, CURLOPT_VERBOSE, TRUE);
        curl_setopt($curl, CURLOPT_STDERR, $fp);
            
        $output = curl_exec($curl);
        curl_close($curl);
        
        if (($decoded = json_decode($output))){
            return $decoded;
        }
        return $output;
    }
    
    public function parseAnalytics($analytics_obj){
        $outputs = array();
        $parent_elements = array('allTime','month','week','day','twoHours');
        
        
        foreach ($parent_elements as $pe){
            
            foreach(get_object_vars($analytics_obj->$pe) as $key=>$value){
                if (!is_array($value)){
                    $outputs[strtoupper($pe)][$key] = $value;
                }else{
                    foreach($value as $item){
                        //check if item has properties
                        $outputs[strtoupper($pe)][$key .'::'.$item->id] = $item->count;
                    }
                }
            }
        }
        
        return $outputs;
    }
}
