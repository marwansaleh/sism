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

if (!function_exists('browse_image')){
    function browse_image($activeSelected=NULL,$type=NULL){
        $fileman_path = config_item('path_lib') .'filemanager/dialog.php';
        if ($activeSelected=='none' || $activeSelected=='NULL'){
            $activeSelected = NULL;
        }
        $str = '<br>';
        $str.= '<div class="form-group">';
            $str.= '<input type="hidden" id="browse_image" name="var_value" value="'.$activeSelected.'">';
            $str.= '<div class="input-group">';
                $str.= '<input type="text" readonly="true" class="form-control disabled" id="selected_image" name="selected_image" value="'.$activeSelected.'" placeholder="Browse image..">';
                $str.= '<div class="input-group-btn">';
                    $str.= '<a href="'.$fileman_path.'?type=1&&fldr='.$type.'&field_id=selected_image&relative_url=1&iframe=true&width=80%&height=80%"  rel="prettyPhoto" class="btn btn-default"><i class="fa fa-upload"></i> Browse Image</a>';
                    $str.= '<button type="button" class="btn btn-warning" onclick="removeBrowseImage()"><i class="fa fa-remove"></i> Remove Image</button>';
                $str.= '</div>';
            $str.= '</div>';
        $str.= '</div>';
        $str.= '<div class="form-group" id="browse-image-container">
                <img class="img-responsive" '.($activeSelected?'src="'.$activeSelected.'"':'').'
            </div>';
        $str.= '<script type="text/javascript">';
        $str.= 'var baseImage="'.  config_item('images').'";';
        $str.= 'function responsive_filemanager_callback(field_id){
                    var image_name = baseImage + document.getElementById(field_id).value;
                    //set display image with base image url
                    document.getElementById(field_id).value = image_name;
                    //set image field value
                    $("#browse_image").val(image_name);
                    $("#browse-image-container").find("img").attr("src",image_name); 
                    alert(image_name);
                }';
        $str.= 'function removeBrowseImage(){
                    $("#browse_image").val("NULL");
                    $("#selected_image").val("");
                    $("#browse-image-container").find("img").attr("src",""); 
                }';
        $str.= '</script>';
        return $str;
    }
}