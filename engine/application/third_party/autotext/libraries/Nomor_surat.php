<?php
/**
 * AutoText Nomor_surat
 *
 * @author marwansaleh
 */
class Nomor_surat {
    
    //must at least has one public method getValue
    function getValue(&$autoText, $id=NULL){
        if ($id){
            $autoText->content = '00/xx/www/wwww';
        }
    }
}
