<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Report
 *
 * @author marwansaleh
 */
class Report extends MY_AdminController {
    function __construct() {
        parent::__construct();
        $this->data['active_menu'] = 'report';
        $this->data['page_title'] = '<i class="fa fa-calendar"></i> Laporan';
        $this->data['page_description'] = 'Mail report';
        
        //Loading model
        $this->load->model('mail/incoming_m', 'incoming_m');
        $this->load->model('mail/outgoing_m', 'outgoing_m');
        $this->load->model('mail/disposition_m', 'disposition_m');
        
        //$this->output->enable_profiler(TRUE);
    }
    
    function index(){
        //check if any post data
        $selected_year = $this->input->post("year");
        $selected_month = $this->input->post("month");
        
        $this->data['incomings'] = array();
        $this->data['dispositions'] = array();
        $this->data['outgoings'] = array();
        
        if ($this->input->post('submitted')){
            $this->user_activity('Generate report using month:'.$selected_month.' and year: '.$selected_year, $this->users->get_userid());
            //build filter
            $where = array();
            if ($selected_year){
                $where['YEAR(receive_date)'] = $selected_year;
            }
            if ($selected_month){
                $where['MONTH(receive_date)'] = $selected_month;
            }
            if (!count($where)){$where = NULL;}
            
            //get incomings
            if (!isset($this->incoming_m)){
                $this->load->model('mail/incoming_m');
            }
            $incomings = $this->incoming_m->get_by($where);
            
            foreach ($incomings as $incoming){
                $incoming->receiver_name = $this->user_m->get_value('full_name', array('id'=>$incoming->receiver));
                $incoming->status_name = mail_status($incoming->status, MAIL_TYPE_INCOMING, SIDE_RECEIVER);

                $this->data['incomings'] [] = $incoming;
            }
            
            //get dispositions
            if (!isset($this->disposition_m)){
                $this->load->model('mail/disposition_m');
            }
            //build filter
            $where = array();
            if ($selected_year){
                $where['YEAR(FROM_UNIXTIME(created))'] = $selected_year;
            }
            if ($selected_month){
                $where['MONTH(FROM_UNIXTIME(created))'] = $selected_month;
            }
            if (!count($where)){$where = NULL;}
            
            $dispositions = $this->disposition_m->get_by($where);
            
            foreach ($dispositions as $disposition){
                $disposition->status_name = mail_status($disposition->status, $disposition->mail_type, $disposition->sender==$this->users->get_userid()?SIDE_SENDER:SIDE_RECEIVER);
                if ($disposition->mail_type==MAIL_TYPE_INCOMING){
                    $disposition->subject = $this->incoming_m->get_value('subject', array('id' => $disposition->mail_id));
                } else {
                    $disposition->subject = $this->outgoing_m->get_value('subject', array('id' => $disposition->mail_id));
                }
                if ($disposition->sender == 0) {
                    if ($disposition->mail_type == MAIL_TYPE_INCOMING) {
                        $disposition->sender_name = $this->incoming_m->get_value('sender_name', array('id' => $disposition->mail_id));
                    } else {
                        $disposition->sender_name = '';
                    }
                } else {
                    $disposition->sender_name = $this->user_m->get_value('full_name', array('id' => $disposition->sender));
                }
                $disposition->receiver_name = $this->user_m->get_value('full_name', array('id'=>$disposition->receiver));

                $this->data['dispositions'] [] = $disposition;
            }
            
            //get outgoings
            if (!isset($this->outgoing_m)){
                $this->load->model('mail/outgoing_m');
            }
            $where = array();
            if ($selected_year){
                $where['YEAR(FROM_UNIXTIME(created))'] = $selected_year;
            }
            if ($selected_month){
                $where['MONTH(FROM_UNIXTIME(created))'] = $selected_month;
            }
            if (!count($where)){$where = NULL;}
            $outgoings = $this->outgoing_m->get_by($where);
            
            foreach ($outgoings as $outgoing){
                $outgoing->sender_name = $this->user_m->get_value('full_name', array('id'=>$outgoing->sender));
                $outgoing->receiver_name = $outgoing->receiver>0 ? $this->user_m->get_value('full_name', array('id'=>$outgoing->receiver)):$outgoing->literally_receiver;
                $outgoing->status_name = mail_status($outgoing->status, MAIL_TYPE_OUTGOING, SIDE_SENDER);

                $this->data['outgoings'] [] = $outgoing;
            }
        }else{
            if (!$selected_month){
                $selected_month = date('m');
            }
            if (!$selected_year){
                $selected_year = date('Y');
            }
        }
        
        $this->data["selected_year"] = $selected_year;
        $this->data["selected_month"] = $selected_month;
        
        //get supporting data
        $this->data['years'] = array(2015);
        $this->data['months'] = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus","September", "Oktober","Nopember","Desember");
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], 'Report', site_url('report'), TRUE);
        
        $this->data['subview'] = 'cms/report/index';
        $this->load->view('_layout_admin', $this->data);
    }
}

/*
 * file location: engine/application/controllers/report.php
 */
