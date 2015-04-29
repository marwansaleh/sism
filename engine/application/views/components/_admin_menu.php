<ul class="sidebar-menu">
    <li class="header">MAINMENU</li>
    <!-- Optionally, you can add icons to the links -->
    <li <?php echo $active_menu=='dashboard'?'class="active"':''; ?>><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>
    <li <?php echo $active_menu=='incoming'?'class="active"':''; ?>><a href="<?php echo site_url('incoming'); ?>"><i class="fa fa-envelope"></i> <span>Surat Masuk</span></a></li>
    <li <?php echo $active_menu=='disposition'?'class="active"':''; ?>><a href="<?php echo site_url('disposition'); ?>"><i class="fa fa-mail-forward"></i> <span>Surat Disposisi</span></a></li>
    <li <?php echo $active_menu=='outgoing'?'class="active"':''; ?>><a href="<?php echo site_url('outgoing'); ?>"><i class="fa fa-mail-reply"></i> <span>Surat Keluar</span></a></li>
    <li <?php echo $active_menu=='report'?'class="active"':''; ?>><a href="<?php echo site_url('report'); ?>"><i class="fa fa-calendar-o"></i> <span>Laporan</span></a></li>
    <li <?php echo $active_menu=='template'?'class="active"':''; ?>><a href="<?php echo site_url('template'); ?>"><i class="fa fa-paragraph"></i> <span>Template</span></a></li>
    <li class="treeview">
        <a href="#"><i class="fa fa-envelope"></i> <span>Log Email</span>  <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
            <li><a href="#">Email list</a></li>
            <li><a href="#">Email configuration</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#"><i class="fa fa-users"></i> <span>User Managements</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
            <li><a href="<?php echo site_url('users'); ?>">User list</a></li>
            <li><a href="<?php echo site_url('usergroups'); ?>">User groups</a></li>
            <li><a href="<?php echo site_url('useraccess'); ?>">Group Access</a></li>
            <li><a href="<?php echo site_url('userroles'); ?>">Access roles</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#"><i class="fa fa-cogs"></i> <span>Configuration</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
            <li><a href="<?php echo site_url('sysconf'); ?>">System configuration</a></li>
            <li><a href="#">System log</a></li>
            <li><a href="<?php echo site_url('database'); ?>">Database Backup</a></li>
        </ul>
    </li>
    
    <li><a href="<?php echo site_url('auth/logout'); ?>"><i class="fa fa-lock"></i> <span>Sign Out</span></a></li>
</ul>