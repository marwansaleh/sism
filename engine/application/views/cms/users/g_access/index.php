<div class="row">
    <div class="col-sm-12">
        <?php if ($this->session->flashdata('message')): ?>
        <?php echo create_alert_box($this->session->flashdata('message'),$this->session->flashdata('message_type')); ?>
        <?php endif; ?>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">List of Privileges</h3>
                <div class="box-tools">
                    <div class="input-group">
                        <input type="text" name="table_search" class="form-control input-sm pull-right" style="width: 250px;" placeholder="Search">
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
                            <th style="width: 10px">#</th>
                            <th>Role Name</th>
                            <th>Description</th>
                            <?php foreach ($usergroups as $g): ?>
                            <th class="text-center"><?php echo $g['name']; ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; foreach($items as $item): ?>
                        <tr>
                            <td><?php echo ($offset+$i++); ?>.</td>
                            <td><?php echo $item->role_name; ?></td>
                            <td><?php echo $item->role_description; ?></td>
                            <?php foreach ($item->groups as $group_id=>$has_access): ?>
                            <td class="text-center">
                                <input class="role_check" type="checkbox" id="<?php echo $item->role_id; ?>" name="<?php echo $item->role_id; ?>" value="<?php echo $group_id; ?>" <?php echo $has_access ? 'checked="checked"':''; ?> />
                            </td>
                            <?php endforeach; ?>
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

<script type="text/javascript">
    $(document).ready(function(){
        $('.role_check').click(function(){
            var $check = $(this);
            var has_access;
            if ($(this).prop('checked')){
                has_access = 1;
            }else{
                has_access = 0;
            }
            var group_id = $(this).val();
            var role_id = $(this).attr('id');
            
            $.post('<?php echo site_url('ajax/access/set_access'); ?>',{group_id:group_id,role_id:role_id,has_access:has_access},function(result){
                if (result.message){
                    alert(result.message);
                }
                
                if (result.status==0){
                    $($check).prop('checked', !$($check).prop('checked'));
                }
            },'json');
        });
    });
</script>