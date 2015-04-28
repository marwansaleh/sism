<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Disposition
 *
 * @author marwansaleh
 */
class File extends MY_AdminController {
    function __construct() {
        parent::__construct();
        
        $this->load->model('mail/attachment_m', 'attachment_m');
    }
    
    function download(){
        $file = $this->input->get('f', TRUE);
        
        if (!$file){
            exit;
        }else{
            $file = base64_decode($file);
        }
        
        if (!file_exists($file)){
            exit('File attachment is not found');
        }
        
        if(ini_get('zlib.output_compression')) { ini_set('zlib.output_compression', 'Off');	}
        
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        // get the file mime type using the file extension
	$mime = finfo_file($finfo, $file);
        
	header('Pragma: public'); 	// required
	header('Expires: 0');		// no cache
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($file)).' GMT');
	header('Cache-Control: private',false);
	header('Content-Type: '.$mime);
	header('Content-Disposition: attachment; filename="'.basename($file).'"');
	header('Content-Transfer-Encoding: binary');
	header('Content-Length: '.filesize($file));	// provide file size
	header('Connection: close');
	readfile($file);		// push it out
	exit();
    }
}

/*
 * file location: engine/application/controllers/file.php
 */
