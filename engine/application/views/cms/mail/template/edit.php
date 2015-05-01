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
                <div class="row">
                    <div class="col-sm-10" style="border-right: solid 3px grey">
                        <div class="form-group">
                            <label>Template Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Template name ..." value="<?php echo $item->name; ?>">
                        </div>
                        <div class="form-group">
                            <label>Template Header</label>
                            <textarea name="header" class="form-control editor" placeholder="Template header ..." ><?php echo isset($item->header)?$item->header:''; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Template Body</label>
                            <textarea name="body" class="form-control editor" placeholder="Template body ..." ><?php echo isset($item->body)?$item->body:''; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Template Header</label>
                            <textarea name="footer" class="form-control editor" placeholder="Template footer ..." ><?php echo isset($item->footer)?$item->footer:''; ?></textarea>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <label>Select Autotext</label>
                        <div class="list-group autotext-list">
                            <?php foreach ($autotext as $at_key => $at_label): ?>
                            <a href="#" class="list-group-item" data-toggle="tooltip" title="<?php echo $at_key; ?>" data-text="<?php echo $at_key; ?>"><?php echo $at_label; ?></a>
                            <?php endforeach; ?>
                        </div>
                    </div>
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

<script type="text/javascript">
    $(document).ready(function(){
        $('.autotext-list').on('click', 'a',function(){
            //var $focused = $('textarea :focus');
            //var current_content = $focus.val();
            //$focus.val(current_content + $(this).attr('data-text'));
            var auto_text = $(this).attr('data-text');
            
            return false;
        });
        
        $('.editor').wysihtml5({
            "font-styles"   : true,
            "emphasis"      : true,
            "lists"         : true,
            "link"          : false,
            "image"         : false,
            "color"         : false
        });
    });
</script>