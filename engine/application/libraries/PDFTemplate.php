<?php

/**
 * Description of PDFTemplate
 *
 * @author marwansaleh
 */
require APPPATH . 'libraries/fpdf/fpdf.php';

class PDFTemplate extends FPDF {
    private $_margin = array(20,20);
    private $_page_width = 0; 
    private $_page_height = 0;
    private $_page_content_width = 0;
    
    private $AUTOTXT = NULL;
    private $ci;
    
    const LN_SINGLE = 5;
    const LN_HALF = 10;
    const LN_DOUBLE = 15;
    const LN_XTRA_DOUBLE = 30;
    
    const TB_TEMPLATES = 'mail_templates';
    
    function __construct($orientation='P', $unit='mm', $size='Folio', $margin=array()) {
        if ($size && is_array($size)){
            list($this->_page_width, $this->_page_height) = $size;
        }else{
            list($this->_page_width, $this->_page_height) = $this->_get_paper_size($size);
        }
        
        $this->FPDF($orientation, $unit, $size=='Folio'?$this->_get_paper_size($size):$size);
        if ($margin && is_array($margin)){
            $this->_margin[0] = isset($margin[0])?$margin[0]:10;
            $this->_margin[1] = isset($margin[1])?$margin[1]:10;
        }
        $this->SetMargins($this->_margin[0], $this->_margin[1]);
        $this->B = 0;
        $this->I = 0;
        $this->U = 0;
        $this->HREF = '';
        
        $this->_page_content_width = $this->_page_width - (2*$this->_margin[0]);
        
        $this->SetAuthor('Marwan Saleh');
        $this->SetCreator('MAIL MONITORING APP');
        $this->SetFont('Arial', '', 12);
    }
    
    function get_template($key){
        if (!$this->ci){ $this->ci =& get_instance(); }
        
        //get from db
        $this->ci->db->select('*');
        if (intval($key)){
            $this->ci->db->where('id', intval($key));
        }else{
            $this->ci->db->where('name', $key);
        }
        $template = $this->ci->db->get(self::TB_TEMPLATES)->row();
        if (!$template){
            return NULL;
        }
        $default_attributes = new stdClass();
        $default_attributes->page = 'Folio';
        $default_attributes->unit = 'mm';
        $default_attributes->orientation = 'P';
        $default_attributes->margin = $this->_margin;
        $default_attributes->font_name = 'Arial';
        
        if ($template->attributes){
            $attributes = json_decode($template->attributes);
            foreach (get_object_vars($attributes) as $key => $value){
                if ($key == 'margin'){
                    $default_attributes->margin = explode(',', str_replace(' ', '', $value));
                }else{
                    $default_attributes->$key = $value;
                }
            }
        }
        
        $template->styles = $default_attributes;
        
        return $template;
    }
    
    function makepdf($key, $data=NULL, $file_name=NULL){
        $template = $this->get_template($key);
        if (!$template){
            exit('No template defined');
        }
        
        //echo 'dictionary:';
        /*if (!$this->AUTOTXT){
            $this->AUTOTXT = Autotexts::getInstance();
        }
        print_r($this->AUTOTXT->get_dictionary_cache());exit;*/
        $method_name = $template->name;
        if (method_exists($this, $method_name)){
            $this->$method_name($data, $template->styles, $file_name);
            exit;
        }
    }
    
    public function add_dictionary_obj($stdClass){
        if (!$this->AUTOTXT){
            $this->AUTOTXT = Autotexts::getInstance();
        }
        
        $this->AUTOTXT->add_dictionary_obj($stdClass);
    }
    
    protected function surat_biasa($data, $style=NULL, $file_name=NULL){
        $this->SetTitle('Surat Keluar Biasa');
        $this->SetSubject('Surat Keterangan');
        if ($style){
            $this->SetFont($style->font_name);
            $this->SetMargins($style->margin[0], $style->margin[1]);
            $this->AddPage($style->orientation, $this->_get_paper_size($style->page));
            
            //set local margin and content width
            $this->_margin = $style->margin;
            $this->_page_content_width = $this->_page_width - (2*$this->_margin[0]);
        }
        
        /**** mail header ****/
        $this->_set_common_header($data);
        
        /***** Content *****/
        $this->SetFontSize(12);
        $this->Ln(self::LN_SINGLE);
        $this->Cell($this->_page_content_width,self::LN_SINGLE, 'Lubuklinggau, '.$this->_parse_autotext('{tanggal_surat}'),0,1,'R');
        $this->Cell($this->_page_content_width, self::LN_DOUBLE, 'Kepada',0,1,'C');
        
        //get width for each two column
        $half_page = ceil($this->_page_content_width/2);
        $this->Cell($half_page);
        $this->Cell($half_page,self::LN_SINGLE, 'Yth, '.$this->_parse_autotext('{kepada_yth}'),0,1);
        $this->Cell($half_page);
        $this->Cell($half_page,self::LN_SINGLE, 'di '.$this->_parse_autotext('{kepada_yth_di}'),0,1);
        
        $header_array = array();
        
        //add nomor surat
        $header_label = 20;
        $header_colon = 5;
        $header_value = $this->_page_content_width-$header_label-$header_colon;
        $header_array [] = array(
                array('width'=>$header_label,'value'=>'Nomor'),
                array('width'=>$header_colon,'value'=>':'),
                array('width'=>$header_value,'value'=>$this->_parse_autotext('{nomor_surat}')),
        );
        //add sifat surat
        $header_array [] = array(
                array('width'=>$header_label,'value'=>'Sifat'),
                array('width'=>$header_colon,'value'=>':'),
                array('width'=>$header_value,'value'=>$this->_parse_autotext('{sifat_surat}')),
        );
        
        $lampiran = $this->_parse_autotext('{lampiran}');
        if ($lampiran && is_array($lampiran)){
            foreach ($lampiran as $index => $value){
                $header_array [] = array(
                    array('width'=>$header_label,'value'=>($index==0 ? 'Lampiran':'')),
                    array('width'=>$header_colon,'value'=>($index==0 ? ':':'')),
                    array('width'=>$header_value,'value'=>$value),
                );
            }
        }
            
        $header_array [] = array(
                array('width'=>$header_label,'value'=>'Hal'),
                array('width'=>$header_colon,'value'=>':'),
                array('width'=>$header_value,'value' => $this->_parse_autotext('{subjek_surat}'),'multi'=>1),
        );
        
        
        
        $this->_insert_table($header_array);
        
        $this->Ln(self::LN_HALF);
        if (isset($data['content'])){
            $content = preg_split('/\n|\r/', $data['content'], -1, PREG_SPLIT_NO_EMPTY);
            foreach ($content as $line_text){
                $this->Write(self::LN_SINGLE, $line_text);
                $this->Ln(self::LN_HALF);
            }
        }
        
        //draw footer
        $this->_set_common_footer($data);
        
        $this->Output($file_name);
    }
    
    protected function surat_keterangan($data, $style=NULL, $file_name=NULL){
        $this->SetTitle('Surat Keterangan');
        $this->SetSubject('Surat Keterangan');
        
        if ($style){
            $this->SetFont($style->font_name);
            $this->SetMargins($style->margin[0], $style->margin[1]);
            $this->AddPage($style->orientation, $this->_get_paper_size($style->page));
            
            //set local margin and content width
            $this->_margin = $style->margin;
            $this->_page_content_width = $this->_page_width - (2*$this->_margin[0]);
        }
        
        /**** mail header ****/
        $this->_set_common_header($data);
        
        /***** Content *****/
        $this->Ln(self::LN_SINGLE);
        $this->SetFontSize(13);
        $this->Cell($this->_page_content_width, self::LN_SINGLE, 'SURAT KETERANGAN', 0, 1, 'C');
        $this->Cell($this->_page_content_width, self::LN_SINGLE, 'NOMOR '.$this->_parse_autotext('{nomor_surat}'), 0, 1, 'C');
        
        $this->Ln(self::LN_DOUBLE);
        $this->SetFontSize(12);
        
        //set content width smaller then used to be
        $content_indent = 20;
        $content_width = $this->_page_content_width - $content_indent;
        
        //Move indent
        $this->Cell($content_indent);
        $this->Cell($content_width, self::LN_HALF, 'Yang bertandatangan di bawah ini :', 0, 1);
        
        //Insert first table
        $table_indent = $content_indent + 10;
        
        $this->_insert_table(array(
            array(
                array('width'=>40,'value'=>'a. Nama'),
                array('width'=>$this->_page_content_width-40-$table_indent,'value'=>': '.$this->_parse_autotext('{nama_pengirim}')),
            ),
            array(
                array('width'=>40,'value'=>'b. Jabatan'),
                array('width'=>$this->_page_content_width-40-$table_indent,'value'=>': '.$this->_parse_autotext('{jabatan_pengirim}')),
            )
        ),0,$table_indent);
        
        $this->Ln(self::LN_DOUBLE);
        $this->Cell($content_indent);
        $this->Cell($content_width, self::LN_HALF, 'Dengan ini menerangkan bahwa :', 0, 1);
        
        $this->_insert_table(array(
            array(
                array('width'=>40,'value'=>'a. Nama/NIP'),
                array('width'=>$this->_page_content_width-40-$table_indent,'value'=>': '.$this->_parse_autotext('{nama_rekomendasi}') . ' / ' .$this->_parse_autotext('{nip_rekomendasi}')),
            ),
            array(
                array('width'=>40,'value'=>'b. Pangkat/Golongan'),
                array('width'=>$this->_page_content_width-40-$table_indent,'value'=>': '.$this->_parse_autotext('{pangkat_rekomendasi}') .'/'.$this->_parse_autotext('{golongan_rekomendasi}')),
            ),
            array(
                array('width'=>40,'value'=>'c. Jabatan'),
                array('width'=>$this->_page_content_width-40-$table_indent,'value'=>': '.$this->_parse_autotext('{jabatan_rekomendasi}')),
            )
        ),0,$table_indent);
        
        $this->Ln(self::LN_XTRA_DOUBLE);
        $this->Cell($this->_page_content_width,self::LN_SINGLE, 'Lubuklinggau, '.$this->_parse_autotext('{tanggal_surat}'),0,1,'R');
        
        //draw footer
        $this->_set_common_footer($data);
        
        //show output
        $this->Output($file_name);
    }
    
    private function _set_common_header($data){
        //create image logo
        $logo_img_wdth = 30;
        //try to get logo
        $logo_url = $this->_parse_autotext('{logo}');
        if ($logo_url != '{logo}'){
            $this->Image($logo_url,  $this->_margin[0],  $this->_margin[1],$logo_img_wdth);
        }else{
            $this->Cell($logo_img_wdth, 30, '{logo}',1,0,'C');
        }
        //create mail header detail
        $mail_header_width = $this->_page_content_width-$logo_img_wdth;
        //Move to the right of image logo if logo exists
        if ($logo_url != '{logo}'){ $this->Cell($logo_img_wdth); }
        $this->Cell($mail_header_width, self::LN_SINGLE,'PEMERINTAH KOTA LUBUKLINGGAU',0,1,'C');
        //Move to the right of image logo
        $this->Cell($logo_img_wdth);
        $this->SetFontSize(14);
        $this->Cell($mail_header_width, self::LN_SINGLE,'BAPPEDA',0,1,'C');
        $this->SetFontSize(10);
        //Move to the right of image logo
        $this->Cell($logo_img_wdth);
        $this->Cell($mail_header_width, self::LN_SINGLE,'Jalan '. $this->_parse_autotext('{nama_jalan}').' Lubuklinggau',0,1,'C');
        //Move to the right of image logo
        $this->Cell($logo_img_wdth);
        $this->Cell($mail_header_width, self::LN_SINGLE,'Telepon '. $this->_parse_autotext('{nomor_telepon}').' Faksimile '. $this->_parse_autotext('{nomor_faksimile}').' Kode Pos '. $this->_parse_autotext('{kode_pos}').'',0,1,'C');
        //Move to the right of image logo
        $this->Cell($logo_img_wdth);
        $this->Cell($mail_header_width, self::LN_SINGLE,'Email '.$this->_parse_autotext('{alamat_email}').' Website '.$this->_parse_autotext('{website}'),0,1,'C');
        //draw line
        $this->Ln(self::LN_HALF);
        $ori_line_width = $this->LineWidth;
        $this->SetLineWidth(1);
        $this->Line($this->x, $this->y, $this->x+$this->_page_content_width, $this->y);
        $this->SetLineWidth($ori_line_width);
        $this->Line($this->x, $this->y+1, $this->x + $this->_page_content_width, $this->y+1);
    }
    
    private function _set_common_footer($data){
        
        //get width for each two column
        $half_page = ceil($this->_page_content_width/2);
        
        $this->Ln(self::LN_DOUBLE);
        $this->Cell($half_page);
        $this->Cell($half_page, self::LN_SINGLE,'KEPALA BAPPEDA',0,1,'C');
        $this->Ln(self::LN_XTRA_DOUBLE);
        $this->Cell($half_page);
        $this->Cell($half_page, self::LN_SINGLE,$this->_parse_autotext('{nama_pengirim}'),0,1,'C');
        $this->Cell($half_page);
        $this->Cell($half_page, self::LN_SINGLE,$this->_parse_autotext('{pangkat_pengirim}'),0,1,'C');
        $this->Cell($half_page);
        $this->Cell($half_page, self::LN_SINGLE,$this->_parse_autotext('{nip_pengirim}'),0,1,'C');
        
        $this->Ln(self::LN_HALF);
        $this->Cell($this->_page_content_width, self::LN_SINGLE, 'Tembusan:',0,1);
        
        //get tembusan must in array
        $tembusan = $this->_parse_autotext('{tembusan}');
        if ($tembusan && is_array($tembusan)){
            foreach ($tembusan as $index => $tembusan){
                $this->Cell($this->_page_content_width, self::LN_SINGLE, ($index+1) . '. ' . $tembusan,0,1);
            }
        }else{
            for ($i=1; $i<=3; $i++){
                $this->Cell($this->_page_content_width, self::LN_SINGLE, $i . '......',0,1);
            }
        }
    }
    
    private function _parse_autotext($autotext){
        //get autotext class object
        if (!$this->AUTOTXT){
            $this->AUTOTXT = Autotexts::getInstance();
        }
        
        return $this->AUTOTXT->parse_autotext($autotext);
    }
    
    private function _insert_table($data, $border=0, $indent=0){
        for ($i=0; $i<count($data); $i++){
            if ($indent){
                $this->Cell($indent);
            }
            foreach ($data[$i] as $col){
                if (isset ($col['multi']) && $col['multi']==1){
                    $this->MultiCell(isset($col['width'])?$col['width']:60, isset($col['height'])?$col['height']:self::LN_SINGLE, $col['value'], isset($col['border'])&&$col['border']==1?1:$border, isset($col['align'])?$col['align']:'L');
                }else{
                    $this->Cell(isset($col['width'])?$col['width']:60, isset($col['height'])?$col['height']:self::LN_SINGLE, $col['value'], isset($col['border'])&&$col['border']==1?1:$border, 0, isset($col['align'])?$col['align']:'L');
                }
            }
            $this->Ln();
        }
    }
    
    private function _get_paper_size($size='A4'){
        switch (strtolower($size)){
            case 'folio': return array(216,330);
            case 'letter': return array(216,279);
            case 'legal': return array(216,356);
            case 'a4': 
            default:
                return array(210,297);
        }
    }
}
