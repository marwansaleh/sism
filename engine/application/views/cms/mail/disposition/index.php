<div class="row">
    <div class="col-sm-12">
        <?php if ($this->session->flashdata('message')): ?>
        <?php echo create_alert_box($this->session->flashdata('message'),$this->session->flashdata('message_type')); ?>
        <?php endif; ?>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Surat Disposisi</h3>
                <div class="box-tools">
                    <div class="input-group">
                        <input type="search" name="table_search" class="form-control input-sm pull-right" style="width: 250px;" placeholder="Search">
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div>
            </div><!-- /.box-header -->
            
            <div class="box-body table-responsive no-padding">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 30px">#</th>
                            <th>Subjek</th>
                            <th style="width: 90px">Pengirim</th>
                            <th style="width: 90px">Penerima</th>
                            <th style="width: 150px" class="text-center">Tgl.Terima</th>
                            <th style="width: 90px">Status</th>
                            <th style="width: 120px" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <?php if (count($items)): ?>
                    <tbody>
                        <?php foreach($items as $index => $item): ?>
                        <tr class="<?php echo mail_priority_bg($item->priority); ?>">
                            <td><?php echo ($offset+ ($index+1)); ?>.</td>
                            <td>
                                <small>
                                    <?php if ($item->sender==$me->id): ?>
                                    <i class="fa fa-mail-forward"></i>
                                    <?php else: ?>
                                    <i class="fa fa-mail-reply"></i>
                                    <?php endif;?>
                                </small>
                                <?php echo $item->notes; ?> <span class="text-info help" data-toggle="tooltip" title="<?php echo $item->subject; ?>">(i)</span>
                            </td>
                            <td style="width: 120px"><?php echo $item->sender_name; ?></td>
                            <td style="width: 120px"><?php echo $item->receiver_name; ?></td>
                            <td class="text-center"><?php echo date('d/M/Y H:i', $item->created); ?></td>
                            <td><?php echo $item->status_name; ?></td>
                            <td class="text-center">
                                <!-- editable / deletable ? -->
                                <?php if ($item->can_post): ?>
                                <a class="btn btn-xs btn-primary" data-toggle="tooltip" title="Disposisi" href="<?php echo site_url('disposition/post?mail='.$item->mail_id.'&type='.$item->mail_type.'&ref='.$item->id.'&page='.$page); ?>"><i class="fa fa-mail-forward"></i></a>
                                <?php endif; ?>
                                <?php if ($item->can_edit): ?>
                                <a class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit" href="<?php echo site_url('disposition/edit?id='.$item->id.'&page='.$page); ?>"><i class="fa fa-pencil"></i></a>
                                <?php endif; ?>
                                <?php if ($item->can_delete): ?>
                                <a class="btn btn-xs btn-danger" data-toggle="tooltip" title="Delete" href="<?php echo site_url('disposition/post_delete?id='.$item->id.'&page='.$page); ?>"><i class="fa fa-minus-circle"></i></a>
                                <?php endif; ?>
                                <?php if (isset($item->can_sign)&&$item->can_sign): ?>
                                <a class="btn btn-xs btn-success" data-toggle="tooltip" title="Sign" href="<?php echo site_url('disposition/sign?id='.$item->id.'&page='.$page); ?>"><i class="fa fa-sign-in"></i></a>
                                <?php endif; ?>
                                <a class="btn btn-xs btn-info"  rel="prettyPhoto" data-toggle="tooltip" title="View history" href="<?php echo site_url('history/index/'.$item->mail_id.'/'.$item->mail_type.'?width=80%&height=95%&iframe=true'); ?>"><i class="fa fa-eye"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <?php else: ?>
                    <tbody><tr><td colspan="8">Tidak ada data</td></tr></tbody>
                    <?php endif; ?>
                </table>
            </div><!-- /.box-body -->
            <div class="box-footer clearfix">
                <!-- paging description -->
                <?php echo isset($pagination_description)? $pagination_description:''; ?>
                <!-- paging list bullets -->
                <?php echo isset($pagination)? $pagination:''; ?>
            </div>
        </div>
    </div>
</div>