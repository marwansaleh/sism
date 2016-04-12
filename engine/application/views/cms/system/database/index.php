<div class="row">
    <div class="col-sm-12">
        <?php if ($this->session->flashdata('message')): ?>
        <?php echo create_alert_box($this->session->flashdata('message'),$this->session->flashdata('message_type')); ?>
        <?php endif; ?>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">List of Backups</h3>
                <div class="box-tools">
                    <div class="input-group">
                        <input type="text" name="table_search" class="form-control input-sm pull-right" style="width: 250px;" placeholder="Search">
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                            <a class="btn btn-sm btn-primary" data-toggle="tooltip" title="Create" href="<?php echo site_url('database/backup'); ?>"><i class="fa fa-plus-square"></i></a>
                        </div>
                    </div>
                </div>
            </div><!-- /.box-header -->
            
            <div class="box-body table-responsive no-padding">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Backup Name</th>
                            <th style="width:140px;" class="text-center">Time Created</th>
                            <th>Filesize</th>
                            <th style="width: 90px" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($items as $index => $item): ?>
                        <tr>
                            <td><?php echo ($index+1); ?>.</td>
                            <td><?php echo $item->name; ?></td>
                            <td><?php echo date('d-m-Y H:i:s',$item->time); ?></td>
                            <td><?php echo number_format($item->size); ?> bytes</td>
                            <td class="text-center">
                                <a class="btn btn-xs btn-success" data-toggle="tooltip" title="Download" target="blank" href="<?php echo site_url('database/download?path='.base64_encode($item->path)); ?>"><i class="fa fa-download"></i></a>
                                <a class="btn btn-xs btn-danger confirmation" data-toggle="tooltip" title="Delete" data-confirmation="Are your sure to delete this record ?" href="<?php echo site_url('database/delete?path='.base64_encode($item->path)); ?>"><i class="fa fa-minus-square"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
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