<?php
/**
 * Description of Email
 *
 * @author Marwan Saleh <amazzura.biz@gmail.com>
 * @license public
 */

class Mailroot extends MY_Library {
    
    const EMAIL_TO_SEND = 0;
    const EMAIL_PROCCESSING = 2;
    const EMAIL_SENT = 1;
    const EMAIL_ERROR = 9;
    const EMAIL_CANCEL = 8;
    
    const REQ_STATUS_SENT = 1;
    const REQ_STATUS_PENDING = 2;
    const REQ_STATUS_FAILED = 0;
    
    private $_email_m = NULL;
    private $_attachment_m = NULL;
    private $_cid_m = NULL;
    private $_pending_m = NULL;
    private $_req_log = NULL;
    
    function __construct(){
        parent::__construct();
        
        $this->_CI->load->model(array('email/email_m', 'email/email_attachment_m','email/email_cid_m'));
        $this->_email_m = $this->_CI->email_m;
        $this->_attachment_m = $this->_CI->email_attachment_m;
        $this->_cid_m = $this->_CI->email_cid_m;
    }
    
    /**
     * Get email job info by its job_id
     * @param int $job_id email job id
     * @return boolean FALSE if no email job with that id or return email job object array
     */
    public function get_email_job($job_id){
        
        $email_job = $this->_email_m->get($job_id);
        
        if (!$email_job || !count($email_job)){
            
            return FALSE;
        }
        $email_job->status_desc = $this->get_email_status($email_job->status); // add status description to return value
        $email_job->category_desc = $this->get_email_category($email_job->category); //add category desc to return value
        
        //select attachments if any
        $attachments = $this->_attachment_m->getSelect('id,file_url', array('email_job_id'=>$job_id));
        if ($attachments && count($attachments)){
            $email_job->attachments = $attachments;
        }else{
            $email_job->attachments = NULL;
        }
        
        //select cid image if any
        $cids = $this->_cid_m->getSelect('id,image_url,cid_name', array('email_job_id'=>$job_id));
        if ($cids && count($cids)){
            $email_job->inlineAttachments = $cids;
        }else{
            $email_job->inlineAttachments = NULL;
        }
        
        return $email_job;
    }
    
    /**
     * Get email status description
     * @param int $status status code
     * @return string status description
     */
    public function get_email_status($status, $short=TRUE){
        $email_status = $this->status_list($short);
        
        if (isset($email_status[$status])){
            return $email_status[$status];
        }else{
            return '';
        }
    }
    
    public function status_list($short=FALSE){
        if ($short){ 
            return array(
                self::EMAIL_TO_SEND     => 'New',
                self::EMAIL_SENT        => 'Sent',
                self::EMAIL_PROCCESSING => 'Processing',
                self::EMAIL_CANCEL      => 'Aborted',
                self::EMAIL_ERROR       => 'Error'
            );
        }else{
            return array(
                self::EMAIL_TO_SEND     => 'Email about to send',
                self::EMAIL_SENT        => 'Email successfully sent',
                self::EMAIL_PROCCESSING => 'Email in in proccess to send',
                self::EMAIL_CANCEL      => 'Email is cancel to send',
                self::EMAIL_ERROR       => 'Email sent error'
            );
        }
    }
    
    /**
     * Create new email job
     * @param type $email_data associative array of recipient_email,subject,message,option,[recipient_name,sender,inserted_time,status,category]
     * @param type $attachment array of attachment file
     * @param type $cid_images array of cid associative arrays
     * @param type $message reference to any error message
     * @return boolean FALSE if failed or return INT job_id if success for later use
     */
    public function create_email_jobs($email_data, $attachment=NULL, $cid_images=NULL, &$message=NULL){
        
        if (!isset($email_data['recipient_name'])){
            $message = 'Parameter is not complete. Recipient name is not defined';
            
            //return FALSE; optional
        }
        if (!isset($email_data['recipient_email'])){
            $message = 'Parameter is not complete. Recipient email is not defined';
            
            return FALSE;
        }
        if (!isset($email_data['subject'])){
            $message = 'Parameter is not complete. Email subject is not defined';
            
            return FALSE;
        }
        if (!isset($email_data['content'])){
            $message = 'Parameter is not complete. Email body is not defined';
            
            return FALSE;
        }
        if (!isset($email_data['inserted'])){
            $email_data['inserted'] = time();
        }
        if (!isset($email_data['status'])){
            $email_data['status'] = self::EMAIL_TO_SEND; //need to proccess
        }
        
        //insert new record to email jobs
        $email_job_id = $this->_email_m->save($email_data);
        if (!$email_job_id){
            $message = 'Failed insert email job because '. $this->_email_m->get_last_message(); 
            
            return FALSE;
        }
        
        if ($attachment || $cid_images){
            $this->_add_embedded_images($email_job_id, 0, $attachment, $cid_images, $message);
        }
        
        return $email_job_id;
    }
    
    
    protected function _add_embedded_images($email_job_id,$attachment=NULL,$cid_images=NULL, &$message=NULL){
        //Insert attachments if any
        if ($attachment && is_array($attachment)){
            
            $valid_attachment = 0;
            foreach($attachment as $file){                
                if (file_exists($file)){
                    //insert new attachment for new created email job
                    $attach_arr = array(
                        'email_job_id'      =>  $email_job_id,
                        'file_url'          =>  $file
                    );
                    if (!$this->_attachment_m->save($attach_arr)){
                        $message = 'Failed insert new attachment record with message '.$this->_attachment_m->get_last_message(); 
                    }else{
                        $valid_attachment++;
                    }
                    
                }else{
                    $message = 'Attachment file '. $file. ' is not exists';
                }
            }
        }
        
        //Insert image cid if exists
        if ($cid_images && is_array($cid_images)){
            
            $valid_cids = 0;
            foreach ($cid_images as $cid){
                if (!file_exists($cid['image_url'])){ 
                    $message = 'Inline image '.$cid['image_url'].' is not exists';
                    
                    continue;
                }
                $cid_arr = array(
                    'email_job_id'      =>  $email_job_id,
                    'image_url'         =>  $cid['image_url'],
                    'cid_name'          =>  $cid['cid_name']
                );

                if (!$this->_cid_m->save($cid_arr)){                    
                    $message = 'Failed insert new cid image record with message '.$this->_cid_m->getLastError(); 
                }else{
                    $valid_cids++;
                }
            }
        }
    }
}
