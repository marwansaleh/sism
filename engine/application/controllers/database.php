<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Database
 *
 * @author marwansaleh
 */
class Database extends MY_AdminController {
    protected $backup_path = 'dbbackup/';
    function __construct() {
        parent::__construct();
        $this->data['active_menu'] = 'system';
        $this->data['page_title'] = '<i class="fa fa-cogs"></i> Database Backup';
        $this->data['page_description'] = 'List and create backup';
    }
    
    function index(){
        //Load all db backup_files
        $this->data['items'] = $this->_get_all_backups();
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], 'System Configuration', site_url('database'), TRUE);
        
        $this->data['subview'] = 'cms/system/database/index';
        $this->load->view('_layout_admin', $this->data);
    }
    
    function backup(){
        if (!$this->users->has_access('DATABASE_BACKUP_CREATE')){
            $this->session->set_flashdata('message_type','warning');
            $this->session->set_flashdata('message', 'Maaf. Anda tidak memiliki akses membuat file backup database');
            redirect('database');
        }
        //Load db utility library
        $this->load->dbutil();
        
        $db_name  = $this->db->database ? $this->db->database :'mydb';
        
        $backup_filename = $db_name .'_' . date('YmdHi') . '.sql';
        $prefs = array(
            //'tables'      => array('table1', 'table2'),  // Array of tables to backup.
            //'ignore'      => array(),           // List of tables to omit from the backup
            'format'      => 'zip',               // gzip, zip, txt
            'filename'    => $backup_filename,    // File name - NEEDED ONLY WITH ZIP FILES
            'add_drop'    => TRUE,              // Whether to add DROP TABLE statements to backup file
            'add_insert'  => TRUE,              // Whether to add INSERT data to backup file
            'newline'     => "\n"               // Newline character used in backup file
        );

        $backup =& $this->dbutil->backup($prefs);
        
        // Load the file helper and write the file to your server
        $this->load->helper('file');
        write_file($this->backup_path . $backup_filename .'.zip', $backup); 
        
        $this->session->set_flashdata('message_type','success');
        $this->session->set_flashdata('message', 'Berhasil membuat file backup database dengan nama "'.$backup_filename.'.zip"');
            
        redirect('database');
    }
    
    function delete(){
        if (!$this->users->has_access('DATABASE_BACKUP_DELETE')){
            $this->session->set_flashdata('message_type','warning');
            $this->session->set_flashdata('message', 'Maaf. Anda tidak memiliki akses menghapus file backup database');
            redirect('database');
        }
        $filepath = $this->input->get('path');
        if ($filepath){
            $filepath = base64_decode($filepath);
            
            if (file_exists($filepath)){
                if (unlink($filepath)){
                    $this->session->set_flashdata('message_type','success');
                    $this->session->set_flashdata('message', 'Berhasil menghapus file backup database dengan nama "'.$filepath.'"');
                    
                    redirect('database');
                }else{
                    $this->session->set_flashdata('message_type','error');
                    $this->session->set_flashdata('message', 'Gagal menghapus file backup database');
                }
            }else{
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', 'File "'.$filepath.' tidak ditemukan." Gagal menghapus file backup database');
            }
        }else{
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Parameter filepath tidak terdefinisi. Gagal menghapus file backup');
        }
                    
        redirect('database');
    }
    
    function download(){
        $filepath = $this->input->get('path');
        if ($filepath){
            $filepath = base64_decode($filepath);
            
            if (file_exists($filepath)){
                $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
                $mime_type = finfo_file($finfo, $filepath);
                finfo_close($finfo);
                
                header('Content-Description: File Transfer');
                header('Content-Type: '.$mime_type);
                header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($filepath));
                readfile($file);
            }else{
                echo "<p>File not found</p>";
            }
        }else{
            echo "<p>Parameter not complete</p>";
        }
        exit;
    }
    
    private function _get_all_backups(){
        $db_name = $this->db->database ? $this->db->database :'mydb';
        
        $backups = array();
        
        foreach (glob($this->backup_path . $db_name.'*.*') as $db){
            $file = new stdClass();
            $file->name = basename($db);
            $file->path = $db;
            $file->size = filesize($db);
            $file->time = filemtime($db);
            $backups [] = $file;
        }
        
        return $backups;
    }
}

/*
 * file location: engine/application/controllers/database.php
 */
