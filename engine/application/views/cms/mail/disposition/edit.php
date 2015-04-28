<div class="row">
    <div class="col-sm-12">
        <?php if ($this->session->flashdata('message')): ?>
        <?php echo create_alert_box($this->session->flashdata('message'),$this->session->flashdata('message_type')); ?>
        <?php endif; ?>
        
        <form role="form" method="post" action="<?php echo $submit_url; ?>">
            <input type="hidden" name="mail_id" value="<?php echo $item->mail_id; ?>" />
            <input type="hidden" name="mail_type" value="<?php echo $item->mail_type; ?>" />
            
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Update Data</h3>
                </div><!-- /.box-header -->
                
                <div class="box-body">
                    <!-- display history -->
                    <?php $this->load->view('cms/mail/disposition/history'); ?>
                    <!--end history -->
                    <div class="form-group">
                        <label>Pilih Prioritas</label>
                        <select name="priority" class="form-control selectpicker" data-live-search="true" data-size="5" data-header="Pilih prioritas">
                            <?php foreach ($priorities as $key=>$priority): ?>
                            <option value="<?php echo $key; ?>" <?php echo $key==$item->priority?'selected':''; ?>><?php echo $priority; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Pilih Penerima</label>
                        <select name="receiver" class="form-control selectpicker" data-live-search="true" data-size="5" data-header="Pilih penerima disposisi">
                            <?php foreach ($users as $user): ?>
                            <option value="<?php echo $user->id; ?>" <?php echo $item->receiver==$user->id?'selected':''; ?>>
                                <?php echo $user->full_name; ?> [ <?php echo $user->position; ?> ]
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Catatan / Instruksi</label>
                        <textarea class="form-control" name="notes" placeholder="Tulis catatan..."><?php echo $item->notes; ?></textarea>
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
