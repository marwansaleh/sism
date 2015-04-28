<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" type="image/gif" href="<?php echo site_url('favicon.png'); ?>"/>
        <!-- Meta tags -->
        <?php if (isset($meta)&&  is_array($meta)): ?>
        <?php foreach($meta as $key=>$val): ?>
        <meta name="<?php echo $key; ?>" content="<?php echo $val; ?>">
        <?php endforeach; ?>
        <?php endif; ?>
        <!-- Meta Og properties -->
        <?php if (isset($og_props)&&  is_array($og_props)): ?>
        <?php foreach($og_props as $prop=>$val): ?>
        <?php if (strpos($prop,'fb')!==FALSE): ?>
        <meta property="<?php echo $prop; ?>" content="<?php echo $val; ?>" />
        <?php else: ?>
        <meta property="og:<?php echo $prop; ?>" content="<?php echo $val; ?>" />
        <?php endif; ?>
        <?php endforeach; ?>
        <?php endif; ?>

        <title><?php echo $meta_title; ?></title>

        <!-- Bootstrap CSS -->    
        <link href="<?php echo site_url(config_item('path_lib').'bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
        <!-- bootstrap theme -->
        <link href="<?php echo site_url(config_item('path_lib').'bootstrap/css/bootstrap-theme.min.css'); ?>" rel="stylesheet">
        <!--external css-->
        <link href="<?php echo site_url(config_item('path_lib').'font-awesome-4.1.0/css/font-awesome.min.css'); ?>" rel="stylesheet" />    
        <!-- Custom styles -->
        <link href="<?php echo site_url(config_item('path_assets').'css/style.css'); ?>" rel="stylesheet">
        
        <!-- Custom styles -->
        <link href="<?php echo site_url(config_item('path_assets').'css/mobile-style.css'); ?>" rel="stylesheet">
        
        <script src="<?php echo site_url(config_item('path_lib').'jquery/jquery-1.11.2.min.js'); ?>"></script>
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 -->
        <!--[if lt IE 9]>
          <script src="<?php echo site_url(config_item('path_lib').'html5shiv/html5shiv.min.js'); ?>"></script>
          <script src="<?php echo site_url(config_item('path_lib').'respondjs/respond.min.js'); ?>"></script>
          <script src="<?php echo site_url(config_item('path_lib').'lte-ie7/lte-ie7.js'); ?>"></script>
        <![endif]-->
    </head>
    <body>
    