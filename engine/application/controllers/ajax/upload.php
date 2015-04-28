<?php

/**
 * Description of Upload for Ajax Call
 *
 * @author marwan
 * @email amazzura.biz@gmail.com
 */
class Upload extends MY_Ajax {
    
    function __construct() {
        parent::__construct();
    }
    
    function index(){
        $result = array('initialPreview'=>array(),'initialPreviewConfig'=>array(),'append'=>TRUE,'upload'=>array());
        $base_path = config_item('attachments');
        
        $allowed_types = $this->get_sysvar_value('ATTACHMENT_ALLOWED_TYPES');
        $config = array(
            'upload_path'   => $base_path,
            'allowed_types' => $allowed_types ? implode('|', $allowed_types):'jpg|pdf|doc',
            'remove_spaces' => TRUE
        );
        
        $this->load->library('upload');
        $field_name = 'upload';
        
        $files = $_FILES;
        $cpt = count($_FILES[$field_name]['name']);
        for($i=0; $i<$cpt; $i++)
        {
            $_FILES[$field_name]['name']= $files[$field_name]['name'][$i];
            $_FILES[$field_name]['type']= $files[$field_name]['type'][$i];
            $_FILES[$field_name]['tmp_name']= $files[$field_name]['tmp_name'][$i];
            $_FILES[$field_name]['error']= $files[$field_name]['error'][$i];
            $_FILES[$field_name]['size']= $files[$field_name]['size'][$i];  

            $this->upload->initialize($config);
            if ($this->upload->do_upload($field_name)){
                $upload_data = $this->upload->data();
                $result['upload'][] = $upload_data['file_name'];
                $result['initialPreview'] [] = '<img src="'.  site_url(attachment_thumbnail($upload_data['file_name'])).'" class="file-preview-image" >';
                $result['initialPreviewConfig'] [] = ['caption' => basename($upload_data['raw_name']), 'width' => '80px', 'url' => site_url('ajax/upload/delete'), 'key' => base64_encode($upload_data['file_name'])];
                
            }else{
                $result['error'] = $this->upload->display_errors() . ' Ensure your files in format of '.$config['allowed_types'];
                $this->send_output($result);
                break;
            }
        }
        
        echo json_encode($result);
    }
    
    function delete(){
        $result = array();
        $base_path = config_item('attachments');
        $file_name = base64_decode($this->input->delete('key'));
        if (!$file_name){
            $result['error'] = 'Error! Parameter key is empty';
        }else if (!file_exists($base_path . $file_name)){
            $result['error'] = 'Unable to find the file '. $file_name;
        }else if (!@unlink($base_path . $file_name)){
            $error = error_get_last();
            $result['error'] =  $error ? $error['message']: 'Unable to delete the file '. $file_name;
        }else{
            $result['status'] = 'OK';
        }
        
        $this->send_output($result);
    }
}

/*
 * file location: ./application/controllers/ajax/upload.php
 */