<?php

/**
 * Description of Editor for Ajax Call
 *
 * @author marwan
 * @email amazzura.biz@gmail.com
 */
class Editor extends MY_Ajax {
    
    function save(){
        $this->load->model('mail/outgoing_m');
        $result = array('status'=>0,'message'=>'');
        
        $mail_id = $this->input->post('id');
        $content = $this->input->post('content');
        
        if (!$mail_id){
            $result['message'] = 'Undefined mail id';
        }else{
            $this->outgoing_m->save(array('content'=>$content), $mail_id);
            $result['status'] = 1;
        }
        
        $this->send_output($result);
    }
}
    

/*
 * file location: ./application/controllers/ajax/editor.php
 */