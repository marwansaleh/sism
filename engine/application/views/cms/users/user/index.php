<div class="row">
    <div class="col-sm-12">
        <?php if ($this->session->flashdata('message')): ?>
        <?php echo create_alert_box($this->session->flashdata('message'),$this->session->flashdata('message_type')); ?>
        <?php endif; ?>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">List of Users</h3>
                <div class="box-tools">
                    <div class="input-group">
                        <input type="text" name="table_search" class="form-control input-sm pull-right" style="width: 250px;" placeholder="Search">
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                            <a class="btn btn-sm btn-primary" data-toggle="tooltip" title="Create" href="<?php echo site_url('users/edit'); ?>"><i class="fa fa-plus-square"></i></a>
                        </div>
                    </div>
                </div>
            </div><!-- /.box-header -->
            
            <div class="box-body table-responsive no-padding">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Full Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Group</th>
                            <th>Mobile</th>
                            <th>Phone</th>
                            <th>Online</th>
                            <th>Lastlogin</th>
                            <th style="width: 30px">Active</th>
                            <th style="width: 120px" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; foreach($items as $item): ?>
                        <tr>
                            <td><?php echo ($offset+$i++); ?>.</td>
                            <td><?php echo $item->full_name; ?></td>
                            <td><?php echo $item->username; ?></td>
                            <td><?php echo $item->email; ?></td>
                            <td><?php echo $item->group_name; ?></td>
                            <td><?php echo $item->mobile; ?></td>
                            <td><?php echo $item->phone; ?></td>
                            <td class="text-center">
                                <?php if ($item->is_online): ?>
                                <i class="fa fa-check-circle"></i>
                                <?php else:?>
                                <i class="fa fa-minus-circle"></i>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $item->last_login ? time_difference($item->last_login, TRUE):'never'; ?></td>
                            <td class="text-center">
                                <?php if ($item->is_active==1): ?>
                                <i class="fa fa-check-circle"></i>
                                <?php else:?>
                                <i class="fa fa-minus-circle"></i>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit" href="<?php echo site_url('users/edit?id='.$item->id.'&page='.$page); ?>"><i class="fa fa-pencil-square"></i></a>
                                <a class="btn btn-xs btn-danger confirmation" data-toggle="tooltip" title="Delete" data-confirmation="Are your sure to delete this record ?" href="<?php echo site_url('users/delete?id='.$item->id.'&page='.$page); ?>"><i class="fa fa-minus-square"></i></a>
                                <?php if ($set_access_privilege):?>
                                <a class="btn btn-xs btn-primary" data-toggle="tooltip" rel="prettyPhoto" title="Set user privileges" href="<?php echo site_url('users/access?id='.$item->id.'&width=80%&height=95%&iframe=true'); ?>"><i class="fa fa-key"></i></a>
                                <?php endif; ?>
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