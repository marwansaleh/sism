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
                            <?php foreach ($autotexts as $autotext): ?>
                            <a href="javascript:void();" class="list-group-item" data-toggle="tooltip" title="<?php echo $autotext->title; ?>" data-text="<?php echo $autotext->code; ?>"><?php echo $autotext->name; ?></a>
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
    var templateManager = {
        activeEditor: null,
        initEditor: function (){
            var _this = this;
            $('.editor').wysihtml5({
                "font-styles"   : true,
                "emphasis"      : true,
                "lists"         : true,
                "link"          : false,
                "image"         : false,
                "color"         : false,
                "events"        : {
                    "focus:composer"     : function (){
                        _this.activeEditor = this;
                    }
                }
            });
        },
        getActiveEditor: function (){
            if (this.activeEditor){
                return this.activeEditor;
            }else{
                return $('.editor').eq(0).data('wysihtml5').editor;
            }
        },
        insertAutoText: function (ref){
            var _this = this;
            //get autotext code from selected option
            var code = $(ref).attr('data-text');
            if (!code){ return; }
            
            //get active editor
            var editor = _this.getActiveEditor();
            
            //check against the object
            if (editor){
                editor.composer.commands.exec("insertHTML",code);
            }else{
                alert('Can not find an editor / composer');
            }
            
            return;
        }
    };
    
    $(document).ready(function(){
        templateManager.initEditor();
        
        $('.autotext-list').on('click', 'a',function(){
            templateManager.insertAutoText($(this));
            return false;
        });
    });
</script>