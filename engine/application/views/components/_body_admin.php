<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="#" class="logo">
            <!-- <img src="<?php echo site_url(config_item('path_assets') . 'img/logo.png'); ?>"> -->
            <?php echo $APPINFO["APP_NAME"]; ?> <small><?php echo 'v.' . $APPINFO["APP_VERSION"]; ?></small>
        </a>

        <!-- Header Navbar -->
        <?php $this->load->view("components/_header_navbar") ?>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            

            <!-- Sidebar user panel (optional) -->
            <div class="user-panel">
                <div class="pull-left image">
                    <!--<img src="<?php echo $avatar_url_me; ?>" class="img-circle user-active-image" alt="User Image"> -->
                    <img src="<?php echo site_url(config_item('path_assets') . 'img/logo-lubuklinggau.png'); ?>" class="user-active-image" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p><?php echo $me->group_name; ?></p>
                    <!-- Status -->
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>

            <!-- search form (Optional) -->
            <form action="#" method="get" class="sidebar-form">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Search...">
                    <span class="input-group-btn">
                        <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                    </span>
                </div>
            </form>
            <!-- /.search form -->

            <!-- Sidebar Menu -->
            <?php $this->load->view('components/_admin_menu') ?>
            <!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="min-height: 294px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <?php echo isset($page_title) ? $page_title : 'Page Title'; ?>
                <?php echo isset($page_description) ? '<small>' . $page_description . '</small>' : ''; ?>
            </h1>
            <!-- Breadcumb -->
            <?php echo breadcrumb($breadcumb); ?>
            <!-- End Breadcumb -->
        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Your Page Content Here -->
            <?php if (isset($subview)) {
                $this->load->view($subview);
            } ?>

        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs">
            <?php echo $APPINFO["APP_NAME_LONG"]; ?>
        </div>
        <!-- Default to the left --> 
        <strong>Copyright &copy; 2015 Kab. Lubuklinggau <a href="#"><?php echo $APPINFO["APP_NAME"] . ' v.' . $APPINFO["APP_VERSION"]; ?></a>.</strong> All rights reserved.
    </footer>

</div>