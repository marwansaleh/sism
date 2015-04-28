<div class="row">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Setup User [<?php echo $user->full_name; ?>] Privileges</h3>
        </div><!-- /.box-header -->

        <div class="box-body table-responsive no-padding">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Role Name</th>
                        <th>Description</th>
                        <th style="width: 40px;" class="text-center">Access</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; foreach ($access as $acc): ?>
                    <tr>
                        <td><?php echo $i++; ?>.</td>
                        <td><?php echo $acc->role_name; ?></td>
                        <td><?php echo $acc->role_description; ?></td>
                        <td class="text-center">
                            <input class="role_check" type="checkbox" id="<?php echo $acc->role_id; ?>" name="<?php echo $acc->role_name; ?>" value="<?php echo $user->id; ?>" <?php echo $acc->has_access ? 'checked="checked"':''; ?> />
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div><!-- /.box-body -->
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
            var user_id = $(this).val();
            var role_id = $(this).attr('id');
            
            $.post('<?php echo site_url('ajax/access/set_access_peruser'); ?>',{user_id:user_id,role_id:role_id,has_access:has_access},function(result){
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