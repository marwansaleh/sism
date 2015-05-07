<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" type="image/png" href="<?php echo site_url(config_item('path_assets').'img/logo.png'); ?>"/>
        <base href="<?php echo site_url(); ?>">
        <title><?php echo $meta_title; ?></title>

        <!-- Bootstrap CSS -->    
        <link href="<?php echo site_url(config_item('path_lib').'bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
        <!-- bootstrap theme -->
        <link href="<?php echo site_url(config_item('path_lib').'bootstrap/css/bootstrap-theme.min.css'); ?>" rel="stylesheet">
        <!--external css-->
        <link href="<?php echo site_url(config_item('path_lib').'font-awesome-4.1.0/css/font-awesome.min.css'); ?>" rel="stylesheet" />    
        <link href="<?php echo site_url(config_item('path_lib').'prettyPhoto/3.15/css/prettyPhoto.css'); ?>" rel="stylesheet" /> 
        <link href="<?php echo site_url(config_item('path_assets').'css/skins/skin-blue.min.css'); ?>" rel="stylesheet">
        <link href="<?php echo site_url(config_item('path_lib').'iCheck/minimal/blue.css'); ?>" rel="stylesheet">
        <link href="<?php echo site_url(config_item('path_lib').'bootstrap-select/css/bootstrap-select.min.css'); ?>" rel="stylesheet">
        <link href="<?php echo site_url(config_item('path_lib').'datepicker/datepicker3.css'); ?>" rel="stylesheet">
        <link href="<?php echo site_url(config_item('path_lib').'tagsinput/bootstrap-tagsinput.css'); ?>" rel="stylesheet">
        
        <link href="<?php echo site_url(config_item('path_lib').'ionicons/css/ionicons.min.css'); ?>" rel="stylesheet">
        <link href="<?php echo site_url(config_item('path_lib').'bootstrap-fileinput/css/fileinput.min.css'); ?>" rel="stylesheet">
        <link href="<?php echo site_url(config_item('path_lib').'bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css'); ?>" rel="stylesheet">
        <link href="<?php echo site_url(config_item('path_lib').'bootstrap-wysihtml5/bootstrap3-wysihtml5-editor.min.css'); ?>" rel="stylesheet">
        <link href="<?php echo site_url(config_item('path_lib').'hidetext/hidetext.css'); ?>" rel="stylesheet">
        <!-- Custom styles -->
        <link href="<?php echo site_url(config_item('path_assets').'css/AdminLTE.min.css'); ?>" rel="stylesheet">
        <link href="<?php echo site_url(config_item('path_assets').'css/adminstyle.css'); ?>" rel="stylesheet">
        
        <script src="<?php echo site_url(config_item('path_lib').'jquery/jquery-1.11.2.min.js'); ?>"></script>
        <script src="<?php echo site_url(config_item('path_assets').'js/admin-lte.js'); ?>"></script>
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 -->
        <!--[if lt IE 9]>
          <script src="<?php echo site_url(config_item('path_lib').'html5shiv/html5shiv.min.js'); ?>"></script>
          <script src="<?php echo site_url(config_item('path_lib').'respondjs/respond.min.js'); ?>"></script>
          <script src="<?php echo site_url(config_item('path_lib').'lte-ie7/lte-ie7.js'); ?>"></script>
        <![endif]-->
    </head>
    <body <?php echo isset($body_class) && $body_class?'class="'.$body_class.'"':''; ?>>
    