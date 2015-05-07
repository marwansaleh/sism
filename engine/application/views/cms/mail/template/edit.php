<div class="row">
    <div class="col-sm-12">
        <?php if ($this->session->flashdata('message')): ?>
        <?php echo create_alert_box($this->session->flashdata('message'),$this->session->flashdata('message_type')); ?>
        <?php endif; ?>
        
        <form role="form" method="post" action="<?php echo $submit_url; ?>">
            <input type="hidden" id="id" name="id" value="<?php echo $item->id; ?>" />
            <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?php echo $item->id?'Update Data':'Create New'; ?></h3>
            </div><!-- /.box-header -->
            
            <div class="box-body">
                <div class="form-group">
                    <label>Template Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Template name ..." value="<?php echo $item->name; ?>">
                </div>
                <div class="form-group">
                    <label>Available to Users</label>
                    <select class="form-control" name="available">
                        <option value="1" <?php echo $item->available==1?'selected':''; ?>>Available</option>
                        <option value="0" <?php echo $item->available==0?'selected':''; ?>>Not Available</option>
                    </select>
                </div>
                <legend>Print Attributes</legend>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Page Size</label>
                            <select class="form-control" name="style_page">
                                <?php foreach ($styles['page'] as $key=>$value): ?>
                                <option value="<?php echo $key; ?>" <?php echo $key==$item->styles->page?'selected':''; ?>><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Page Orientation</label>
                            <select class="form-control" name="style_orientation">
                                <?php foreach ($styles['orientation'] as $key=>$value): ?>
                                <option value="<?php echo $key; ?>" <?php echo $key==$item->styles->orientation?'selected':''; ?>><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Margin Left</label>
                            <input type="number" class="form-control" name="style_margin[]" step="1" value="<?php echo $item->styles->margin[0]; ?>">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Margin Top</label>
                            <input type="number" class="form-control" name="style_margin[]" step="1" value="<?php echo $item->styles->margin[1]; ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Font Family</label>
                    <select class="form-control" name="style_font_name">
                        <?php foreach ($styles['font_name'] as $key=>$value): ?>
                        <option value="<?php echo $key; ?>" <?php echo $key==$item->styles->font_name?'selected':''; ?>><?php echo $value; ?></option>
                        <?php endforeach; ?>
                    </select>
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