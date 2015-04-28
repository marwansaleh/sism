<?php
if (!function_exists('custom_select_bg')){
    function custom_select_bg($def_value=NULL){
        $array = array(
            'bg-primary'        => 'Primary',
            'bg-success'        => 'Success',
            'bg-default'        => 'Default',
            'bg-lime'           => 'Lime',
            'bg-important'      => 'Important',
            'bg-danger'         => 'Danger',
            'bg-warning'        => 'Warning',
            'bg-aqua'           => 'Aqua',
            'bg-black'          => 'Black',
            'bg-fuchsia'        => 'Fuchsia',
            'bg-blue'           => 'Blue',
            'bg-gray'           => 'Gray',
            'bg-green'          => 'Green',
            'bg-maroon'         => 'Maroon',
            'bg-navy'           => 'Navy',
            'bg-olive'          => 'Olive',
            'bg-orange'         => 'Orange',
            'bg-purple'         => 'Purple',
            'bg-red'            => 'Red',
            'bg-teal'           => 'Teal',
            'bg-yellow'         => 'Yellow'
        );
        
        $str = '<select name="var_value" class="form-control">';
        foreach ($array as $key => $value){
            $str .= '<option value="'.$key.'"' .($key==$def_value?' selected':'').'>'.$value.'</option>';
        }
        $str.= '</select>';
        
        return $str;
    }
}

if (!function_exists('custom_select_extensions')){
    function custom_select_extensions($string_value=''){
        $arr_val = array();
        if ($string_value){
            if (strpos($string_value, '|')!==FALSE){
                $arr_val = explode('|', $string_value);
            }else{
                $arr_val = explode(',', $string_value);
            }
        }
        
        $exts = '"jpg","png","gif","pdf","doc","xls","ppt","docx","xlsx","pptx","css","zip"';
        
        $str = '<br><input type="text" id="custom_select_extension" name="var_value" autocomplete="off" class="form-control tagsinput" placeholder="Extension" value="'.  implode(',', $arr_val).'">';
        $str.= '<script type="text/javascript">';
        $str.= '$(document).ready(function(){ '
                .   '$("input#custom_select_extension").tagsinput({ '
                .       'typeahead: {'
                .           'source: ['.$exts.']'
                .       '}'
                .   '});'
                . '});';
        $str.= '</script>';
        
        return $str;
    }
}