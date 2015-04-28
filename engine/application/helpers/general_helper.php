<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('breadcumb_add')){
    function breadcumb_add(&$breadcumb,$title,$link=NULL,$active=FALSE){
        if (is_array($breadcumb)){
            $item = array('title'=>$title, 'active'=>$active);
            if ($link){
                $item['link'] = $link;
            }
            $breadcumb [] = $item;
        }
    }
}

if (!function_exists('breadcumb')){
    function breadcrumb($pages, $showServerTime=FALSE){
        $str = '<ol class="breadcrumb">';
        
        if (is_array($pages)){
            if ($showServerTime){
                $new_bc = array (array('title'=> date('D, dMY H:i:s')));
                array_splice($pages, 0,0, $new_bc);
            }
            foreach ($pages as $page){
                $active = (isset($page['active'])&&$page['active']==TRUE);
                $str.= '<li';
                if ($active)
                    $str.= ' class="active"';
                        
                $str.= '>';
                if (isset($page['link']))
                    $str.= '<a href="'.$page['link'].'">'. $page['title'].'</a>';
                else
                    $str.= $page['title'];
                
                
                $str.= '</li>';
            }
        }
        else
        {
            $str.= '<li>'.$page.'</li>';
        }
        $str.= '</ol>';
        return $str;
    }
}

if (!function_exists('create_alert_box')){
    function create_alert_box($alert_text, $alert_type=NULL, $alert_title=NULL, $autohide=TRUE){
        $type_labels = array(
            'default' => 'Information', 'info'=>'Information', 'success'=>'Successfull', 
            'warning'=>'Warning', 'danger'=>'Danger', 'error'=>'Error'
        );
        $type_alerts = array(
            'default'=>'alert-info', 'info'=>'alert-info', 'success'=>'alert-success', 
            'warning'=>'alert-warning', 'danger'=>'alert-danger', 'error'=>'alert-danger'
        );
        $s = '<div class="alert '.(isset($type_alerts[$alert_type])?$type_alerts[$alert_type]:$type_alerts['default']).' alert-dismissible" role="alert">';
        //button dismiss
        $s.= '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>';
        //Label in bold
        $s.= '<strong>'. ($alert_title?$alert_title:(isset($type_labels[$alert_type])?$type_labels[$alert_type]:$type_labels['default']).'!').'</strong> ';
        //Alert text
        $s.= $alert_text;
        $s.= '</div>';
        
        //add js to hide automatically
        if ($autohide){
            $s.= PHP_EOL . '<script>setTimeout(function(){$(".alert-dismissible").fadeOut("slow");},2500);</script>';
        }
        
        return $s;
    }
}

if (!function_exists('indonesia_date_format')){
    /**
     * 
     * @param type $format
     * @param type $time
     */
    function indonesia_date_format($format='%d-%m-%Y', $time=NULL){
        
        //create date object
        if (!$time) { $time = time(); }
        $date_obj  =  getdate($time);
        
        //set Indonesian month name
        $bulan = array(
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'Nopember', 'Desember'
        );
        
        $bulan_short = array(
            'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nop', 'Des'
        );
        
        $hari = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
        
        $format_search = array('%d','%D','%m','%M','%S','%y','%Y','%H','%i','%s');
        $format_replace = array( 
            $date_obj['mday'], $hari[$date_obj['wday']],  $date_obj['mon'], $bulan[$date_obj['mon']-1],  
            $bulan_short[$date_obj['mon']-1], $date_obj['year'], $date_obj['year'], $date_obj['hours'], 
            $date_obj['minutes'], $date_obj['seconds']  
        );
        $str = str_replace($format_search, $format_replace, $format);
        
        return $str;
    }
}

if (!function_exists('get_indonesia_month')){
    function get_indonesia_month($month_index=NULL, $short=FALSE){
        //set Indonesian month name
        $bulan = array(
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'Nopember', 'Desember'
        );
        
        $bulan_short = array(
            'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nop', 'Des'
        );
        
        if (!$month_index || $month_index < 1 || $month_index > 12){
            $month_index = date('m');
        }
        
        if ($short){
            return $bulan_short[$month_index-1];
        }else{
            return $bulan[$month_index-1];
        }
    }
}

if (!function_exists('smart_paging_description')){
    function smart_paging_description($total_recs=0, $curr_num_recs=0){
        $str = '<ul class="pagination pagination-sm no-margin">';
        if ($total_recs==0){
            return $str .= '<li><span class="text-grey">Data not found</span></li>';
        }else{
            $str .= '<li><span class="text-grey">Showing '.$curr_num_recs.' from '. $total_recs.' records</span></li>';
        }
        
        $str .= '</ul>';
        
        return $str;
    }
}

if (!function_exists('smart_paging')){
    function smart_paging($totalPages, $page=1, $adjacents=2, $url_format='%i', $min_page_adjacents=5, $recordInfo=NULL){  
	$prev = $page - 1;
	$next = $page + 1;
        $first_page = 1;
	$lastpage = $totalPages-1;		//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;			//last page minus 1

	/* 
         * Now we apply our rules and draw the pagination object. 
         * We're actually saving the code to a variable in case we want to draw it more than once.
	*/
        
        
	$pagination = '';
        $pagination .= '<ul class="pagination pagination-sm no-margin pull-right">';
        
	if($lastpage >=1)
	{   
            //previous button
            if ($page > 1) 
                $pagination.= '<li><a href="'.str_replace('%i',$prev,$url_format).'">&laquo;</a></li>';
            else
                $pagination.= '<li class="disabled"><a>&laquo;</a></li>';

            //pages	
            if ($lastpage < $min_page_adjacents + $adjacents)	//not enough pages to bother breaking it up
            {	
                for ($counter = 1; $counter <= $totalPages; $counter++)
		{
                    if ($counter == $page)
                        $pagination.= '<li class="active"><a class="current">'.$counter.'</a></li>';
                    else
                        $pagination.= '<li><a href="'.str_replace('%i',$counter,$url_format).'">'.$counter.'</a></li>';				
		}
            }
            
            elseif($lastpage > $min_page_adjacents + $adjacents)	//enough pages to hide some
            {
                //close to beginning; only hide later pages
		if($page < 1 + ($adjacents * 2))		
		{
                    for ($counter = 1; $counter < $min_page_adjacents + $adjacents; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= '<li class="active"><a class="current">'.$counter.'</a></li>';
                        else
                            $pagination.= '<li><a href="'.str_replace('%i',$counter,$url_format).'">'.$counter.'</a></li>';			
                    }
                    $pagination.='<li class="disabled"><a>...</a></li>';
                    for($i=0;$i<$adjacents;$i++){
                        $pagination.= '<li><a href="'.str_replace('%i',($lastpage-$i),$url_format).'">'.($lastpage-$i).'</a></li>';
                    }
                    
                }
                //in middle; hide some front and some back
                elseif($lastpage - $adjacents > $page && $page > $adjacents)
		{
                    for($i=0;$i<$adjacents;$i++){
                        $pagination.= '<li><a href="'.str_replace('%i',($first_page+$i),$url_format).'">'.($first_page+$i).'</a></li>';
                    }
                    $pagination.='<li class="disabled"><a>...</a></li>';
                    for ($counter = $page - $adjacents; $counter <= $page+$adjacents; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= '<li class="active"><a>'.$counter.'</a></li>';
			else
                            $pagination.= '<li><a href="'.str_replace('%i',$counter,$url_format).'">'.$counter.'</a></li>';
                    }
                    $pagination.='<li class="disabled"><a>...</a></li>';
                    for($i=0;$i<$adjacents;$i++){
                        $pagination.= '<li><a href="'.str_replace('%i',($lastpage-$i),$url_format).'">'.($lastpage-$i).'</a></li>';
                    }
                }
                //close to end; only hide early pages
		else
		{
                    for($i=0;$i<$adjacents;$i++){
                        $pagination.= '<li><a href="'.str_replace('%i',($first_page+$i),$url_format).'">'.($first_page+$i).'</a></li>';
                    }
                    $pagination.='<li class="disabled"><a>...</a></li>';
                    for ($counter = $lastpage - (2 + $adjacents); $counter <= $totalPages; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= '<li class="active"><a>'.$counter.'</a></li>';
                        else
                            $pagination.= '<li><a href="'.str_replace('%i',$counter,$url_format).'">'.$counter.'</a></li>';				
                    }
		}
            }
            
            //next button
            if ($page < $totalPages) 
                $pagination.= '<li><a href="'.str_replace('%i',$next,$url_format).'">&raquo;</a></li>';
            else
                $pagination.= '<li class="disabled"><a>&raquo;</a></li>';
	}
        $pagination.= '</ul>';

        
        return $pagination;
    }
}

if (!function_exists('smart_paging_js')){
    function smart_paging_js($totalPages, $page=1, $jsClick='', $adjacents=2, $offsetTag='$'){  
	$prev = $page - 1;
	$next = $page + 1;
	$lastpage = $totalPages-1;		//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;			//last page minus 1
	
	/* 
         * Now we apply our rules and draw the pagination object. 
         * We're actually saving the code to a variable in case we want to draw it more than once.
	*/
	$pagination = '';
	if($lastpage >=1)
	{	
            $pagination .= '<div class="pagination"><ul>';
            //previous button
            if ($page > 1) 
                $pagination.= '<li><a href='.  parseJs($jsClick, $prev, $offsetTag).'>Prev</a></li>';
            else
                $pagination.= '<li class="disabled"><a>Prev</a></li>';
		
            //pages	
            if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
            {	
                for ($counter = 1; $counter <= $totalPages; $counter++)
		{
                    if ($counter == $page)
                        $pagination.= '<li class="active"><a>'.$counter.'</a></li>';
                    else
                        $pagination.= '<li><a href='.  parseJs($jsClick, $counter).'>'.$counter.'</a></li>';				
		}
            }
            
            elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
            {
                //close to beginning; only hide later pages
		if($page < 1 + ($adjacents * 2))		
		{
                    for ($counter = 1; $counter < 5 + ($adjacents * 2); $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= '<li class="active"><a>'.$counter.'</a></li>';
                        else
                            $pagination.= '<li><a href='.  parseJs($jsClick, $counter).'>'.$counter.'</a></li>';			
                    }
                    $pagination.='<li><a>...</a></li>';
                    $pagination.= '<li><a href='.  parseJs($jsClick, $lpm1).'>'.$lpm1.'</a></li>';
                    $pagination.= '<li><a href='.  parseJs($jsClick, $lastpage).'>'.$lastpage.'</a></li>';
                    
                }
                //in middle; hide some front and some back
                elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
		{
                    $pagination.= '<li><a href='.  parseJs($jsClick, 1).'>1</a></li>';
                    $pagination.= '<li><a href='.  parseJs($jsClick, 2).'>2</a></li>';
                    $pagination.='<li><a>...</a></li>';
                    for ($counter = $page - $adjacents; $counter <= $page+1 + $adjacents; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= '<li class="active"><a>'.$counter.'</a></li>';
			else
                            $pagination.= '<li><a href='.  parseJs($jsClick, $counter).'>'.$counter.'</a></li>';
                    }
                    $pagination.='<li><a>...</a></li>';
                    $pagination.= '<li><a href='.  parseJs($jsClick, $lpm1).'>'.$lpm1.'</a></li>';
                    $pagination.= '<li><a href='.  parseJs($jsClick, $lastpage).'>'.$lastpage.'</a></li>';
                }
                //close to end; only hide early pages
		else
		{
                    $pagination.= '<li><a href='.  parseJs($jsClick, 1).'>1</a></li>';
                    $pagination.= '<li><a href='.  parseJs($jsClick, 2).'>2</a></li>';
                    $pagination.='<li><a>...</a></li>';
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $totalPages; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= '<li class="active"><a>'.$counter.'</a></li>';
                        else
                            $pagination.= '<li><a href='.parseJs($jsClick, $counter).'>'.$counter.'</a></li>';				
                    }
		}
            }
            
            //next button
            if ($page < $totalPages) 
                $pagination.= '<li><a href='. parseJs($jsClick, $next).'>Next</a></li>';
            else
                $pagination.= '<li class="disabled"><a>Next</a></li>';

            $pagination.= '</ul></div>';
	}
		
	
        
        return $pagination;
    }
    
    function parseJs($js, $var, $tag='$'){
        return str_replace($tag, $var, $js);
    }
}
    
if (!function_exists('time_difference')){
    function time_difference($date,$unix_input=FALSE)
    {
        if(empty($date)) {
            return "Please provide date.";
        }

        $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
        $lengths = array("60","60","24","7","4.35","12","10");

        $now = time();
        if ($unix_input){
            $unix_date = $date;
        }else{
            $unix_date = strtotime($date);
        }

        // check validity of date
        if(empty($unix_date)) {
            return "Invalid date";
        }

        //Check to see if it is past date or future date
        if($now > $unix_date) {
            $difference = $now - $unix_date;
            $tense = "ago";

        } else {
            $difference = $unix_date - $now;
            $tense = "from now";
        }

        for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
            $difference /= $lengths[$j];
        }

        $difference = round($difference);

        if($difference != 1) {
            $periods[$j].= "s";
        }

        return "$difference $periods[$j] {$tense}";
    }
}

if (!function_exists('file_extension')){
    function file_extension($path){
        $ext = pathinfo($path, PATHINFO_EXTENSION); 
        return strtolower($ext);
    }
}

if (!function_exists('variable_type_cast')){
    function variable_type_cast($value, $type='string'){
        switch ($type){
            case 'integer': return intval($value);
            case 'float': return floatval($value);
            case 'boolean': return boolval($value);
            default : return strval($value);
        }
    }
}

if (!function_exists('mail_status')){
    function mail_status($status, $mail_type=MAIL_TYPE_INCOMING, $side=SIDE_RECEIVER){
        $status_receiver_array = array(
            MAIL_STATUS_NEW         => 'New',
            MAIL_STATUS_READ        => 'Read',
            MAIL_STATUS_POSTED      => 'Posted',
            MAIL_STATUS_REPOSTED    => 'Re-posted',
            MAIL_STATUS_RESPONDED   => 'Responded',
            MAIL_STATUS_APPROVAL    => 'Approval',
            MAIL_STATUS_APPROVED    => 'Approved',
            MAIL_STATUS_SIGN        => 'Sign',
            MAIL_STATUS_SIGNED      => 'Signed',
            MAIL_STATUS_CLOSED      => 'Closed'
        );
        $status_sender_array = array(
            MAIL_STATUS_NEW         => 'New',
            MAIL_STATUS_READ        => 'Read',
            MAIL_STATUS_POSTED      => 'Posted',
            MAIL_STATUS_REPOSTED    => 'Re-posted',
            MAIL_STATUS_RESPONDED   => 'Responded',
            MAIL_STATUS_APPROVAL    => 'Approval',
            MAIL_STATUS_APPROVED    => 'Approved',
            MAIL_STATUS_SIGN        => 'Signing',
            MAIL_STATUS_SIGNED      => 'Signed',
            MAIL_STATUS_CLOSED      => 'Closed'
        );
        
        $status_array = $side==SIDE_RECEIVER ? $status_receiver_array : $status_sender_array;
        if (isset($status_array[$status])){
            return $status_array[$status];
        }
        
        return NULL;
    }
}

if (!function_exists('mail_priority')){
    function mail_priority($priority=NULL){
        $priority_array = array(
            MAIL_PRIORITY_NORMAL        => 'Normal',
            MAIL_PRIORITY_IMPORTANT     => 'Important',
            MAIL_PRIORITY_VIMPORTANT    => 'Very Important',
            MAIL_PRIORITY_CONFIDENTIAL  => 'Confidential'
        );
        
        if (is_null($priority)){
            return $priority_array;
        }
        if (isset($priority_array[$priority])){
            return $priority_array[$priority];
        }
        
        return NULL;
    }
}

if (!function_exists('mail_priority_bg')){
    function mail_priority_bg($priority=MAIL_PRIORITY_NORMAL){
        $array = array(
            MAIL_PRIORITY_NORMAL        => 'bg-default',
            MAIL_PRIORITY_IMPORTANT     => 'bg-lime',
            MAIL_PRIORITY_VIMPORTANT    => 'bg-important',
            MAIL_PRIORITY_CONFIDENTIAL  => 'bg-danger'
        );
        
        if (isset($array[$priority])){
            return $array[$priority];
        }else{
            return $array[MAIL_PRIORITY_NORMAL];
        }
    }
}

if (!function_exists('mail_bg')){
    function mail_bg($type=MAIL_TYPE_INCOMING){
        if ($type==MAIL_TYPE_INCOMING){
            return 'bg-primary';
        }else{
            return 'bg-success';
        }
    }
}

if (!function_exists('attachment_thumbnail')){
    function attachment_thumbnail($file_name){
        $thumb_types = array(
            'image'     => config_item('attachments') . $file_name,
            'pdf'       => config_item('attachments_thumb') . 'pdf.png',
            'zip'       => config_item('attachments_thumb') . 'zip.png',
            'doc'       => config_item('attachments_thumb') . 'doc.png',
            'xls'       => config_item('attachments_thumb') . 'xls.png',
            'ppt'       => config_item('attachments_thumb') . 'ppt.png',
            'file'      => config_item('attachments_thumb') . 'file.png'
        );
        
        //get extension from file
        $extension = file_extension($file_name);
        
        $thumb_url = '';
        
        switch ($extension){
            case 'jpg':
            case 'png':
            case 'gif': $thumb_url = $thumb_types['image']; break;
            case 'pdf': $thumb_url = $thumb_types['pdf']; break;
            case 'zip': $thumb_url = $thumb_types['zip']; break;
            case 'doc': 
            case 'docx': $thumb_url = $thumb_types['doc']; break;
            case 'xls': 
            case 'xlsx': $thumb_url = $thumb_types['xls']; break;
            case 'ppt': 
            case 'pptx': $thumb_url = $thumb_types['ppt']; break;
            default: $thumb_url = $thumb_types['file']; break;
        }
        
        return $thumb_url;
    }
}

if (!function_exists('masking_chars')){
    function masking_chars($subject, $mask_length=0, $mask_char='*', $display_length=0){
        $masked_chars = array();
        
        $total_length = strlen($subject);
        if ($mask_length > $total_length){
            $mask_length = $total_length;
        }
        
        if ($mask_length <= 0){
            $mask_length = $total_length;
        }
        
        for($i=0; $i<$total_length; $i++){
            if ($i < $mask_length){
                $masked_chars [] = $mask_char;
            }else{
                $masked_chars [] = $subject[$i];
            }
        }
        
        if ($display_length>0 && count($masked_chars)>$display_length){
            array_splice($masked_chars, $display_length);
        }
        
        return implode('', $masked_chars);
    }
}

if (!function_exists('current_url_full')){
    function current_url_full(){
        //get current url from ci
        $current = current_url();
        
        $gets = array();
        foreach ($_GET as $key => $val){
            $gets [$key] = $val;
        }
        if (count($gets)){
            return $current .'?' . http_build_query($gets);
        }else{
            return $current;
        }
    }
}

if (!function_exists('clean_array')){
    function clean_array($array){
        $cleaned_array = array();
        if (is_array($array)){
            foreach ($array as $key => $value){
                if ($value!='' || $value != NULL){
                    $cleaned_array[$key] = $value;
                }
            }
        }
        
        return $cleaned_array;
    }
}

if (!function_exists('text_cutter')){
    function text_cutter($str, $max_length=120, $suffix='...', $strip_tags=TRUE){
        $text = $strip_tags ? strip_tags($str) : $str;
        
        if (strlen($text)>$max_length){
            $text = substr($text, 0, $max_length);
            if ($suffix){
                $text .= $suffix;
            }
        }
        
        return $text;
    }
}

/*
 * file location: /application/helpers/general_helper.php
 */
