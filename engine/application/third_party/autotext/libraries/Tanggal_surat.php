<?php
/**
 * AutoText logo
 *
 * @author marwansaleh
 */
class Tanggal_surat {
    
    //must at least has one public method getValue
    function getValue(&$autoText){
        $date = date('Y-m-d');
        $autoText->content = $this->_indonesia_date($date, TRUE);
    }
    
    private function _indonesia_date($date, $long=TRUE){
        if (!is_int($date)){
            $date = strtotime($date);
        }
        $days = array('Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu');
        $months_long = array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','Nopember','Desember');
        $months_short = array('Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nop','Des');
        
        $date_obj = getdate($date);
        
        $formated_date = '';
        if ($long){
            $formated_date = $days[$date_obj['wday']].', ';
        }
        $formated_date .= $date_obj['mday'] .' '. ($long ? $months_long[$date_obj['mon']]:$months_short[$date['mon']]) .' '. $date_obj['year'];
        
        return $formated_date;
    }
}
