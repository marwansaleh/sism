<?php

/**
 * Description of Dashboard for Ajax Call
 *
 * @author marwan
 * @email amazzura.biz@gmail.com
 */
class Dashboard extends MY_Ajax {
    function __construct() {
        parent::__construct();
        
        $this->load->model(array('mail/incoming_m','mail/disposition_m','mail/outgoing_m'));
    }
    
    
    function loadMailMonthly(){
        $result = array("labels"=>array(), "dataset"=>array());
        $months = array (
            "Jan", "Feb", "Mar", "Apr", "Mei", 
            "Jun", "Jul", "Agu", "Sep",
            "Okt", "Nop", "Des"
        );
        $result['labels'] = $months;
        
        $year = date('Y');
        
        //Initialize dataset value foreach month
        for ($i=1; $i<=count($months); $i++){
            $result['dataset']['incoming'][$i] = 0;
            $result['dataset']['disposition'][$i] = 0;
            $result['dataset']['outgoing'][$i] = 0;
        }
        
        //get incomings
        $this->db->select('MONTH(receive_date) AS receive_month, COUNT(*) AS mail_count', FALSE);
        $this->db->where('YEAR(receive_date)', $year, FALSE);
        $this->db->group_by('MONTH(receive_date)');
        $incomings = $this->incoming_m->get();
        foreach ($incomings as $in){
            $result['dataset']['incoming'][$in->receive_month - 1] = $in->mail_count;
        }
        
        //get disposition
        $this->db->select('MONTH(FROM_UNIXTIME(created)) AS month_created, COUNT(*) AS mail_count', FALSE);
        $this->db->where('YEAR(FROM_UNIXTIME(created))', $year, FALSE);
        $this->db->group_by('MONTH(FROM_UNIXTIME(created))');
        $dispositions = $this->outgoing_m->get();
        foreach ($dispositions as $dp){
            $result['dataset']['disposition'][$dp->month_created - 1] = $dp->mail_count;
        }
        
        //get outgoing
        $this->db->select('MONTH(mail_date) AS mail_month, COUNT(*) AS mail_count', FALSE);
        $this->db->where('YEAR(mail_date)', $year, FALSE);
        $this->db->group_by('MONTH(mail_date)');
        $outgoings = $this->incoming_m->get();
        foreach ($outgoings as $out){
            $result['dataset']['outgoing'][$out->mail_month - 1] = $out->mail_count;
        }
        
        $this->send_output($result);
    }
}

/*
 * file location: ./application/controllers/ajax/dashboard.php
 */