<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of History
 *
 * @author marwansaleh
 */
class History extends MY_AdminController {
    function __construct() {
        parent::__construct();
        //Loading model
        $this->load->model('mail/incoming_m', 'incoming_m');
        $this->load->model('mail/outgoing_m', 'outgoing_m');
        $this->load->model('mail/disposition_m', 'disposition_m');
        $this->load->model('mail/attachment_m', 'attachment_m');
    }
    
    
    function index($mail_id, $mail_type=MAIL_TYPE_INCOMING){
        //get mail record
        $all_histories = $this->_get_all_histories($mail_id, $mail_type);
        $this->data['histories'] = $all_histories['histories'];
        $this->data['attachments'] = $all_histories['attachments'];
        
        //$this->data['body_class'] = 'bg-blue';
        $this->data['subview'] = 'cms/mail/history/index';
        $this->load->view('_layout_admin_simple', $this->data);
    }
    
    private function _get_all_histories($mail_id, $mail_type=MAIL_TYPE_INCOMING){
        $histories = array();
        $attachments = NULL;
        
        if ($mail_type==MAIL_TYPE_INCOMING){
            $mail = $this->incoming_m->get($mail_id);
        }else{
            $mail = $this->outgoing_m->get($mail_id);
        }
        $this->data['mail'] = $mail;
        
        //get reference histories if any
        if ($mail_type == MAIL_TYPE_OUTGOING && $mail->incoming_ref_id>0){
            $histories [] = $this->_get_parent_history($mail->incoming_ref_id, MAIL_TYPE_INCOMING);
            $ref_childs = $this->_get_histories($mail->incoming_ref_id, MAIL_TYPE_INCOMING);
            if ($ref_childs && count($ref_childs)){
                $histories = array_merge($histories, $ref_childs);
            }
        }
        
        //get the mail histories
        $histories [] = $this->_get_parent_history($mail_id, $mail_type);
        $childs = $this->_get_histories($mail_id, $mail_type);
        if ($childs && count($childs)){
            $histories = array_merge($histories, $childs);
        }
        
        //is there outgoing respond to incoming ?
        if ($mail_type == MAIL_TYPE_INCOMING){
            if (($out=$this->outgoing_m->get_by(array('incoming_ref_id'=>$mail_id),TRUE))){
                $histories [] = $this->_get_parent_history($out->id, MAIL_TYPE_OUTGOING);
                $out_childs = $this->_get_histories($out->id, MAIL_TYPE_OUTGOING);
                if ($out_childs && count($out_childs)){
                    $histories = array_merge($histories, $out_childs);
                }
            }
            
            //get attachment if any
            $attachments = $this->attachment_m->get_by(array('mail_id'=>$mail_id, 'mail_type'=>$mail_type));
            
        }
        
        $result = array('histories'=>$histories, 'attachments'=>$attachments);
        
        return $result;
    }
    
    private function _get_parent_history($mail_id, $mail_type=MAIL_TYPE_INCOMING){
        if ($mail_type==MAIL_TYPE_INCOMING){
            $mail = $this->incoming_m->get($mail_id);
        }else{
            $mail = $this->outgoing_m->get($mail_id);
        }
        
        //insert the parent mail
        $parent = new stdClass();
        $parent->id = $mail_id;
        $parent->mail_id = $mail->id;
        $parent->mail_type = $mail_type;
        $parent->priority = $mail->priority;
        $parent->sender = isset($mail->sender)?$mail->sender:0;
        $parent->sender_name = $parent->sender ? $this->user_m->get_value('full_name', array('id'=>$parent->sender)) : $mail->sender_name;
        $parent->receiver = $mail->receiver;
        $parent->receiver_name = $mail->receiver >0? $this->user_m->get_value('full_name', array('id'=>$mail->receiver)):(isset($mail->literally_receiver)?$mail->literally_receiver:'');
        $parent->status = $mail->status;
        $parent->status_name = mail_status($mail->status, $mail_type, $mail_type==MAIL_TYPE_INCOMING?SIDE_RECEIVER:SIDE_SENDER);
        $parent->notes = $mail->content ? strip_tags($mail->content,"<p>"):$mail->subject;
        $parent->created = $mail->created;
        
        return $parent;
    }
    
    private function _get_histories($mail_id, $mail_type=MAIL_TYPE_INCOMING){
        
        $histories = array();
        
        //get history
        $this->db->order_by('created asc');
        $history_result = $this->disposition_m->get_by(array('mail_id'=>$mail_id, 'mail_type'=>$mail_type));
        
        foreach ($history_result as $item){
            $item->sender_name = $this->user_m->get_value('full_name', array('id'=>$item->sender));
            $item->receiver_name = $this->user_m->get_value('full_name', array('id'=>$item->receiver));
            $item->status_name = mail_status($item->status, $item->mail_type, $item->mail_type==MAIL_TYPE_INCOMING?SIDE_RECEIVER:SIDE_SENDER);
            $histories [] = $item;
        }
        
        return $histories;
    }
}

/*
 * file location: engine/application/controllers/history.php
 */
