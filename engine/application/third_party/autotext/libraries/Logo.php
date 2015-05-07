<?php
/**
 * AutoText logo
 *
 * @author marwansaleh
 */
class Logo {
    //must at least has one public method getValue
    function getValue(&$autoText){
        $ci =& get_instance();
        //$ci->load->helper('url');
        //$img_src = 'http://google.co.id/logos/doodles/2015/ki-hajar-dewantaras-126th-birthday-4695498065707008.3-res.png';
        $img_src = site_url('assets/img/logo-surat.png');
        $autoText->content = $img_src ;//$ci->load->view('logo', array('logo_data'=>$img_src),TRUE);
    }
}
