<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Sysconf
 *
 * @author marwansaleh
 */
class Template extends MY_AdminController {
    const TMPL_PATH = 'views/cms/mail/outgoing/options/';
    const TMPL_SRC = 'views/cms/mail/outgoing/options/surat_biasa.php';
    
    private $_tmpl_err_message = array (
        0   => 'Unknown error',
        1   => 'Success, no error',
        -1  => 'Template folder is not writable',
        -2  => 'Failed copy from source template'
    );
    
    function __construct() {
        parent::__construct();
        $this->data['active_menu'] = 'template';
        $this->data['page_title'] = '<i class="fa fa-paragraph"></i> Mail Template';
        $this->data['page_description'] = 'List and update templates';
        
        //Loading model
        $this->load->model(array('mail/template_m'));
        
        
    }
    
    function index(){
        $page = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE):1;
        
        $this->data['page'] = $page;
        $offset = ($page-1) * $this->REC_PER_PAGE;
        $this->data['offset'] = $offset;
        
        $where = NULL;
        
        //count totalRecords
        $this->data['totalRecords'] = $this->template_m->get_count($where);
        //count totalPages
        $this->data['totalPages'] = ceil ($this->data['totalRecords']/$this->REC_PER_PAGE);
        $this->data['items'] = array();
        if ($this->data['totalRecords']>0){
            $items = $this->template_m->get_offset('*',$where,$offset,  $this->REC_PER_PAGE);
            if ($items){
                foreach($items as $item){
                    $this->data['items'][] = $item;
                }
                $url_format = site_url('template/index?page=%i');
                $this->data['pagination'] = smart_paging($this->data['totalPages'], $page, $this->_pagination_adjacent, $url_format, $this->_pagination_pages, array('records'=>count($items),'total'=>$this->data['totalRecords']));
            }
        }
        $this->data['pagination_description'] = smart_paging_description($this->data['totalRecords'], count($this->data['items']));
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], 'Templates', site_url('template'), TRUE);
        
        $this->data['subview'] = 'cms/mail/template/index';
        $this->load->view('_layout_admin', $this->data);
    }
    
    function edit(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        if (!$this->users->has_access('TEMPLATE_MANAGEMENT')){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
            redirect('template/index?page='.$page);
        }
        
        if ($id){
            $item = $this->template_m->get($id);
            $item->styles = json_decode($item->attributes);
            $item->styles->margin = explode(',', $item->styles->margin);
        }else{
            $item = $this->template_m->get_new();
            $item->available = 0;
            
            $styles = new stdClass();
            $styles->page = 'Folio';
            $styles->margin = array(20,20);
            $styles->orientation = 'P';
            $styles->font_name = 'Arial';
            
            $item->styles = $styles;
        }
        
        $this->data['item'] = $item;
        
        //get supported data
        $this->data['styles']['page'] = array('A4'=>'A4','Letter'=>'Letter','Legal'=>'Legal','Folio'=>'Folio');
        $this->data['styles']['unit'] = array('cm'=>'Centimeters','mm'=>'Milimeters');
        $this->data['styles']['orientation'] = array('P'=>'Portrait', 'L'=>'Landscape');
        $this->data['styles']['font_name'] = array('Arial'=>'Arial', 'Times'=>'Times Roman','Tahoma'=>'Tahoma');
        
        //set breadcumb
        breadcumb_add($this->data['breadcumb'], 'Templates', site_url('template'), TRUE);
        breadcumb_add($this->data['breadcumb'], 'Update', NULL, TRUE);
        
        $this->data['submit_url'] = site_url('template/save?id='.$id.'&page='.$page);
        $this->data['back_url'] = site_url('template/index?page='.$page);
        $this->data['subview'] = 'cms/mail/template/edit';
        
        $this->load->view('_layout_admin', $this->data);
    }
    
    function save(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        if (!$this->users->has_access('TEMPLATE_MANAGEMENT')){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
            redirect('template/index?page='.$page);
        }
        
        $rules = $this->template_m->rules;
        $this->form_validation->set_rules($rules);
        //exit(print_r($rules));
        if ($this->form_validation->run() != FALSE) {
            $postdata = $this->template_m->array_from_post(array('name','available'));
            $postdata['name'] = url_title($postdata['name'], '_', TRUE);
            
            $attributes = new stdClass();
            foreach ($this->input->post() as $key => $value){
                if (strpos($key, 'style')===FALSE){ continue; }
                else if (strpos($key, 'margin')!==FALSE){
                    $attributes->margin = implode(',',$value);
                }else{
                    $key_attribute = str_replace('style_', '', $key);
                    $attributes->$key_attribute = $value;
                }
            }
            
            $postdata['attributes'] = json_encode($attributes);
            //print_r($attributes);exit;
            
            if (($this->template_m->save($postdata, $id))){
                $this->session->set_flashdata('message_type','success');
                $this->session->set_flashdata('message', 'Data template saved successfully');
                
                //check if template file needs to be created
                $tmpl_name = $postdata['name'] .'.php';
                if (!file_exists(APPPATH . self::TMPL_PATH . $tmpl_name)){
                    if (($tmpl_gen_err=$this->_generate_tmpl_file($tmpl_name))<1){
                        //thereis an error
                        $this->session->set_flashdata('message_type','success');
                        $this->session->set_flashdata('message', 'Data template saved successfully but some error while creating template: '. $this->_tmpl_err_message[$tmpl_gen_err]);
                    }
                }
                
                redirect('template/index?page='.$page);
            }else{
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', $this->template_m->get_last_message());
            }
        }
        
        if (validation_errors()){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', validation_errors());
        }
        
        redirect('template/edit?id='.$id.'&page='.$page);
    }
    
    function _unique_template_name(){
        $id = $this->input->get('id', TRUE);
        $name = url_title($this->input->post('name'), '_', TRUE);
        
        $where = array('name'=>$name);
        if ($id){
            $where['id !='] = $id;
        }
        if ($this->template_m->get_count($where)>0){
            $this->form_validation->set_message('_unique_template_name', 'Duplicate template name "'.$name.'"');
            return FALSE;
        }else{
            return TRUE;
        }
        
    }
    
    function _generate_tmpl_file($tmpl_name){
        //check if target path is writable
        if (!is_writable(APPPATH . self::TMPL_PATH)){
            return -1;
        }
        if (!copy(APPPATH . self::TMPL_SRC, APPPATH . self::TMPL_PATH . $tmpl_name)){
            return -2;
        }else{
            chmod(APPPATH . self::TMPL_PATH . $tmpl_name, 0777);
            return 1;
        }
        
        return 0;
    }
    
    function preview($key='biasa'){
        $pdf = new PDFTemplate();
        
        //get template
        $pdf->makepdf($key);
    }
    function printpdf($name='biasa', $mail_id=NULL){
        $pdf = new PDFTemplate();
        $key ='surat_'.$name;
        //$dictionary = array('nama_pengirim'=>'Marwan Saleh','pangkat_pengirim'=>'Ka Badan','nip_pengirim'=>'21000234500099');
        $data = array(
            //'autotext' => $mt->get_autotexts(),
            //'dictionary' => $dictionary,
            'content'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum vehicula malesuada turpis. Integer aliquet justo in nibh vulputate rutrum sed nec nulla. In at risus sollicitudin, sollicitudin risus eu, placerat massa. Praesent congue rutrum ligula. Duis ac vehicula velit. Maecenas lobortis posuere eleifend. Donec eu tincidunt nulla.<br>In elementum ante urna, vel malesuada lorem blandit in. Phasellus laoreet viverra leo, id tincidunt leo faucibus non. Curabitur ac aliquam sapien. Nunc congue consequat diam, quis facilisis sem interdum sit amet. Suspendisse efficitur tellus eget nunc vestibulum accumsan.<br>Sed tempus nulla arcu, eu auctor dolor imperdiet quis. Integer vel placerat tortor. Curabitur vitae risus eu dolor mollis faucibus. Sed et tortor diam.',
            'tembusan' => array('Seketaris Daerah', 'Ketua Ikatan Penyandang Cacat')
        );
        $pdf->makepdf($key, $data);
    }
    
    function register_auto($name,$title=NULL,$content=NULL,$callback=NULL,$editable=1){
        $mt = Autotexts::getInstance();
        if (!$title){
            $title = ucfirst($name);
        }
        var_dump($mt->autotext_register($name,$title,$content,$callback,$editable,$callback?FALSE:TRUE));
    }
    
    function unregister_auto($name){
        $mt = Autotexts::getInstance();
        var_dump($mt->autotext_unreg($name));
    }
    
    function delete(){
        $id = $this->input->get('id', TRUE);
        $page = $this->input->get('page', TRUE);
        
        if (!$this->users->has_access('TEMPLATE_MANAGEMENT')){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Sorry. You dont have access for this feature');
            redirect('template/index?page='.$page);
        }
        
        //check if found data item
        $item = $this->template_m->get($id);
        if (!$item){
            $this->session->set_flashdata('message_type','error');
            $this->session->set_flashdata('message', 'Could not find data template item. Delete failed!');
        }else{
            if ($this->template_m->delete($id)){
                $this->session->set_flashdata('message_type','success');
                $this->session->set_flashdata('message', 'Data template item deleted successfully');
            }else{
                $this->session->set_flashdata('message_type','error');
                $this->session->set_flashdata('message', $this->template_m->get_last_message());
            }
        }
        
        redirect('template/index?page='.$page);
    }
}

/*
 * file location: engine/application/controllers/template.php
 */
