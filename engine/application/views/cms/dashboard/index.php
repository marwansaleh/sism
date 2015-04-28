<style type="text/css">
    .large-box {height: 300px; overflow: hidden; outline: none;}
    .medium-box {height: 200px; overflow: hidden; outline:none;}
</style>
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="ion ion-ios-people"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Users</span>
                <span class="info-box-number"><?php echo number_format($user_count); ?><small> persons</small></span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-red"><i class="ion ion-email"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Surat Masuk</span>
                <span class="info-box-number">
                    { <span data-toggle="tooltip" title="Total"><?php echo number_format($incoming_total_count); ?></span> | 
                    <span data-toggle="tooltip" title="Milik anda"><?php echo number_format($incoming_count); ?></span> }
                </span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="ion ion-reply"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Surat Keluar</span>
                <span class="info-box-number">
                    { <span data-toggle="tooltip" title="Total"><?php echo number_format($outgoing_total_count); ?></span> |
                    <span data-toggle="tooltip" title="Milik anda"><?php echo number_format($outgoing_count); ?></span> }
                </span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="ion ion-forward"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Disposisi</span>
                <span class="info-box-number">
                    { <span data-toggle="tooltip" title="Mengirim disposisi"><?php echo number_format($disposition_send_count); ?></span> |
                    <span data-toggle="tooltip" title="Menerima disposisi"><?php echo number_format($disposition_receive_count); ?></span> }
                </span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div>
    
    <div class="col-sm-8">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-file"></i> Latest Articles</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="medium-box">
                    <ul class="media-list">
                        <?php foreach ($last_articles as $la): ?>
                        <li class="media">
                            <div class="media-left">
                                <a href="<?php echo site_url('cms/article/edit?id='.$la->id); ?>">
                                    <img class="media-object" src="<?php echo get_image_thumb($la->image_url, IMAGE_THUMB_SMALLER) ?>" />
                                </a>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading"><a href="<?php echo site_url('cms/article/edit?id='.$la->id); ?>"><?php echo $la->title; ?></a></h4>
                                <p><?php echo $la->synopsis; ?>  <i class="fa fa-user"></i> <span class="text-maroon"><?php echo $la->author;?></span> <i class="fa fa-clock-o"></i> <span class="text-maroon"><?php echo date('d M Y H:i', $la->modified); ?></span></p>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="box box-danger">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-users"></i> User Online</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="medium-box">
                    <table class="table table-striped">
                        <tbody>
                            <?php foreach ($user_onlines as $ol): ?>
                            <tr>
                                <td><a data-toggle="tooltip" data-placement="left" title="Look profile" href="<?php echo site_url('cms/profile/index?id='.$ol->id) ;?>"><?php echo $ol->full_name; ?></a></td>
                                <td>
                                    <td class="text-right">
                                        <?php if ($ol->is_online): ?>
                                        <i class="ion ion-ios-person" data-toggle="tooltip" title="Online"></i>
                                        <?php else:?>
                                        <i class="ion ion-ios-person-outline" data-toggle="tooltip" title="Offline"></i>
                                        <?php endif; ?>
                                    </td>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('.medium-box').niceScroll({cursorcolor:"#cecece"});
    });
</script>