<div class="row">
    <div class="col-sm-12">
        <?php if ($this->session->flashdata('message')): ?>
        <?php echo create_alert_box($this->session->flashdata('message'),$this->session->flashdata('message_type')); ?>
        <?php endif; ?>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Surat Masuk</h3>
                <div class="box-tools">
                    <div class="input-group">
                        <input type="text" name="table_search" class="form-control input-sm pull-right" style="width: 250px;" placeholder="Search">
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                            <a class="btn btn-sm btn-primary" data-toggle="tooltip" title="Create" href="<?php echo site_url('incoming/edit'); ?>"><i class="fa fa-plus-square"></i></a>
                        </div>
                    </div>
                </div>
            </div><!-- /.box-header -->
            
            <div class="box-body no-padding">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 15px">#</th>
                            <th>Subjek</th>
                            <th class="text-center" style="width: 100px;">Tgl.Surat</th>
                            <th style="width: 120px">Pengirim</th>
                            <th style="width: 120px">Penerima</th>
                            <th class="text-center">Status</th>
                            <th>Posisi Surat</th>
                            <th class="text-center"><i class="fa fa-link"></i></th>
                            <th class="text-center" style="width: 120px">Action</th>
                        </tr>
                    </thead>
                    <?php if (count($items)) :?>
                    <tbody>
                        <?php foreach($items as $index => $item): ?>
                        <tr class="<?php echo mail_priority_bg($item->priority); ?>">
                            <td><?php echo ($offset+ ($index+1)); ?>.</td>
                            <td><?php echo $item->subject; ?> <span class="text-info help" data-toggle="tooltip" title="<?php echo text_cutter($item->content); ?>">(i)</span></td>
                            <td class="text-center"><?php echo date('d/M/Y', strtotime($item->mail_date)); ?></td>
                            <td><?php echo $item->sender_name; ?></td>
                            <td><?php echo $item->receiver_name; ?></td>
                            <td class="text-center"><?php echo $item->status_name; ?></td>
                            <td><?php echo $item->last_position_name; ?></td>
                            <td class="text-center">
                                <?php if ($item->attachments): ?>
                                <div class="btn-group">
                                    <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"><?php echo count($item->attachments) ?> files <span class="caret"></span></button>
                                    <ul class="dropdown-menu" role="menu">
                                        <?php foreach ($item->attachments as $attach): ?>
                                        <li><a href="<?php echo site_url('file/download?f='.base64_encode(config_item('attachments').$attach->file_name)); ?>"><?php echo $attach->file_name; ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <?php else: ?>
                                -
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($item->can_edit):?>
                                <a class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit" href="<?php echo site_url('incoming/edit?id='.$item->id.'&page='.$page); ?>"><i class="fa fa-pencil-square"></i></a>
                                <?php endif; ?>
                                <?php if ($item->can_post):?>
                                <a class="btn btn-xs btn-primary" data-toggle="tooltip" title="Disposisi" href="<?php echo site_url('disposition/post?page='.$page.'&mail='.$item->id.'&type='.MAIL_TYPE_INCOMING.'&url='. current_url_full()); ?>"><i class="fa fa-mail-forward"></i></a>
                                <?php endif; ?>
                                <?php if ($item->can_delete):?>
                                <a class="btn btn-xs btn-danger" data-toggle="tooltip" title="Delete" href="<?php echo site_url('incoming/delete?id='.$item->id.'&page='.$page); ?>"><i class="fa fa-minus-circle"></i></a>
                                <?php endif; ?>
                                <a class="btn btn-xs btn-info"  rel="prettyPhoto" data-toggle="tooltip" title="View history" href="<?php echo site_url('history/index/'.$item->id.'?width=80%&height=95%&iframe=true'); ?>"><i class="fa fa-eye"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <?php else: ?>
                    <tbody><tr><td colspan="9">Tidak ada data</td></tr></tbody>
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