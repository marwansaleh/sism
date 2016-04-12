<?php
/**
 * AutoText logo
 *
 * @author marwansaleh
 */
class Logo {
    //must at least has one public method getValue
    function getValue(&$autoText){
        $img_src = 'assets/img/logo-lubuklinggau.png';
        
        if (ini_get('allow_url_fopen') || ini_get('allow_url_fopen')!='0'){
            $img_src = site_url($img_src);
        }
        
        
        $autoText->content = $img_src ;
    }
}
