<div class="row">
    <div class="col-sm-12">
        <?php if ($this->session->flashdata('message')): ?>
        <?php echo create_alert_box($this->session->flashdata('message'),$this->session->flashdata('message_type')); ?>
        <?php endif; ?>
        
        <form role="form" method="post" action="<?php echo $submit_url; ?>">
            <input type="hidden" id="id" name="id" value="<?php echo $item->role_id; ?>" />
            <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?php echo $item->role_id?'Update Data':'Create New'; ?></h3>
            </div><!-- /.box-header -->
            
            <div class="box-body">
                <div class="form-group">
                    <label>Role Name</label>
                    <input type="text" name="role_name" class="form-control" placeholder="Role name ..." value="<?php echo $item->role_name; ?>">
                </div>
                <div class="form-group">
                    <label>Role Description</label>
                    <textarea  name="role_description" class="form-control" placeholder="Role description ..."><?php echo $item->role_description; ?></textarea>
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

