<style type="text/css">
    .wysiwyg-color-revision {color: white; font-weight: bolder; background-color: fuchsia}
</style>
<input type="hidden" id="mail_id" value="<?php echo $mail->id; ?>" />
<div id="eWrapper" class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div id="toolbar" class="btn-toolbar" role="toolbar">
                        <?php $this->load->view('cms/mail/editor/toolbar'); ?>
                        <div class="btn-group btn-group-xs">
                            <a title="CTRL+B" class="btn btn-default bold"><i class="fa fa-bold"></i></a>
                        </div>
                    </div><!-- toolbar -->
                </div>
                <div class="panel-body">
                    <div id="editor" class="article editable"><?php echo $mail->content; ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    $(document).ready(function(){
        $('#btn-clear-editor').on('click', function(){
            if (adv_editor.getValue(true) && confirm('Content will be clear, are your sure ?')){
                adv_editor.clear();
            }
        });
        $('#btn-revision').on('click', function(){
            alert(adv_editor.getTextContent());
        });
        $('#btn-save-editor').on('click', function(){
            $.post('<?php echo site_url('ajax/editor/save'); ?>',{content:adv_editor.getValue(true),id:$('#mail_id').val()},function(data){
                if (data.status!=1){
                    alert(data.message);
                }
            },'json');
        });
    });
</script>