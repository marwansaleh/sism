<?php
/**
 * AutoText logo
 *
 * @author marwansaleh
 */
class Logo {
    //must at least has one public method getValue
    function getValue(&$autoText){
        if (ini_get('allow_url_fopen')!='1'){
            return;
        }
        $ci =& get_instance();
        $img_src = site_url('assets/img/lubuklinggau.png');
        $autoText->content = $img_src ;//$ci->load->view('logo', array('logo_data'=>$img_src),TRUE);
    }
}
