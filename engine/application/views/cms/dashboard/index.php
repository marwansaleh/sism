<style type="text/css">
    .large-box {height: 300px; overflow: hidden; outline: none;}
    .medium-box {height: 110px; overflow: hidden; outline:none;}
    .userlist-box {height: 500px; overflow: hidden; outline:none;}
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
        <!-- Latest Incoming -->
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-file"></i> Latest Incomings</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="medium-box">
                    <?php if (count($last_incomings)): ?>
                    <table class="table table-striped table-condensed" role="table">
                        <tr>
                            <th>Sender</th>
                            <th>Recipient</th>
                            <th>Date</th>
                            <th>Subject</th>
                        </tr>
                        <?php foreach ($last_incomings as $incoming): ?>
                        <tr>
                            <td><?php echo $incoming->sender_name; ?></td>
                            <td><?php echo $incoming->receiver_name ?></td>
                            <td><?php echo date("d-m-Y", strtotime($incoming->receive_date)); ?></td>
                            <td><?php echo $incoming->subject; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                    <?php else : ?>
                    <p>You don't have any incoming mail yet</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- Latest disposition -->
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-file"></i> Latest Dispositions</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="medium-box">
                    <?php if (count($last_dispositions)): ?>
                    <table class="table table-striped table-condensed" role="table">
                        <tr>
                            <th>Sender</th>
                            <th>Recipient</th>
                            <th>Date</th>
                            <th>Note</th>
                        </tr>
                        <?php foreach ($last_dispositions as $disposition): ?>
                        <tr>
                            <td><?php echo $disposition->sender_name; ?></td>
                            <td><?php echo $disposition->receiver_name ?></td>
                            <td><?php echo date("d-m-Y", $disposition->created); ?></td>
                            <td><?php echo $disposition->notes; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                    <?php else: ?>
                    <p>You don't have any disposition mail yet</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- Latest outgoing -->
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-file"></i> Latest Outgoings</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="medium-box">
                    <?php if (count($last_outgoings)): ?>
                    <table class="table table-striped table-condensed" role="table">
                        <tr>
                            <th>Sender</th>
                            <th>Recipient</th>
                            <th>Date</th>
                            <th>Subject</th>
                        </tr>
                        <?php foreach ($last_outgoings as $outgoing): ?>
                        <tr>
                            <td><?php echo $outgoing->sender_name; ?></td>
                            <td><?php echo $outgoing->receiver_name ?></td>
                            <td><?php echo date("d-m-Y", $outgoing->created); ?></td>
                            <td><?php echo $outgoing->subject; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                    <?php else: ?>
                    <p>You don't have any outgoing mail yet</p>
                    <?php endif; ?>
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
                <div class="userlist-box">
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
        $('.userlist-box').niceScroll({cursorcolor:"#cecece"});
    });
</script>