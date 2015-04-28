<div class="row">
    <div class="col-sm-12">
        <?php if ($this->session->flashdata('message')): ?>
        <?php echo create_alert_box($this->session->flashdata('message'),$this->session->flashdata('message_type')); ?>
        <?php endif; ?>
        
        <form role="form" method="post" action="<?php echo $submit_url; ?>">
            <input type="hidden" id="id" name="id" value="<?php echo $item->group_id; ?>" />
            <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?php echo $item->group_id?'Update Data':'Create New'; ?></h3>
            </div><!-- /.box-header -->
            
            <div class="box-body">
                <div class="form-group">
                    <label>Group Name</label>
                    <input type="text" name="group_name" class="form-control" placeholder="Group name ..." value="<?php echo $item->group_name; ?>">
                </div>
                <div class="form-group">
                    <label>Group Order</label>
                    <input type="number" name="sort" step="1" min="0" class="form-control" placeholder="Group order ..." value="<?php echo $item->sort?$item->sort:0; ?>">
                </div>
                <div class="form-group">
                    <label>
                        Is this group removable ?
                        <input type="radio" name="is_removable" class="form-control icheck" value="0" <?php echo $item->is_removable==0?'checked':''; ?>> No
                        <input type="radio" name="is_removable" class="form-control icheck" value="1" <?php echo $item->is_removable==1?'checked':''; ?>> Yes
                    </label>
                </div>
            </div>
            <div class="box-footer clearfix">
                <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Submit</button>
                <button class="btn btn-warning" type="reset"><i class="fa fa-refresh"></i> Reset</button>
                <a class="btn btn-default" href="<?php echo $back_url; ?>"><i class="fa fa-backward"></i> Cancel</a>
            </div>
        </div>
        </form>
    </div>
</div>

