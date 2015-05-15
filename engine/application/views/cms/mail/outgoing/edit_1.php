<div class="row">
    <div class="col-sm-12">
        <?php if ($this->session->flashdata('message')): ?>
        <?php echo create_alert_box($this->session->flashdata('message'),$this->session->flashdata('message_type')); ?>
        <?php endif; ?>
        
        <form role="form" method="post" action="<?php echo $submit_url; ?>">
            <input type="hidden" id="id" name="id" value="<?php echo $item->id; ?>" />
            <input type="hidden" name="sender" value="<?php echo $item->sender; ?>">
            <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?php echo $item->id?'Update Data':'Create New'; ?></h3>
            </div><!-- /.box-header -->
            
            <div class="box-body">
                <div class="form-group">
                    <label>Referensi Surat</label>
                    <select name="incoming_ref_id" class="form-control selectpicker" data-live-search="true" data-size="5"data-header="Pilih referensi surat">
                        <option value="0"></option>
                        <?php foreach ($incomings as $incoming): ?>
                        <option value="<?php echo $incoming->id; ?>" <?php echo $incoming->id==$item->incoming_ref_id?'selected':''; ?>><?php echo $incoming->subject; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Tanggal Surat <em>(dd-mm-yyy)</em></label>
                            <div class="input-group">
                                <input type="text" name="mail_date" class="form-control datepicker"value="<?php echo date('d-m-Y', $item->mail_date); ?>">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default btn-calender"><span class="glyphicon glyphicon-calendar"></span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Prioritas</label>
                            <select name="priority" class="form-control selectpicker" data-live-search="true" data-size="5">
                                <?php foreach ($priorities as $key=>$priority): ?>
                                <option value="<?php echo $key; ?>" <?php echo $key==$item->priority?'selected':''; ?>><?php echo $priority; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>No Surat</label>
                            <div class="input-group">
                                <input type="text" name="mail_no" class="form-control" placeholder="Nomor surat ..." value="<?php echo $item->mail_no; ?>">
                                <div class="input-group-btn">
                                    <button id="btn-generate" type="button" class="btn btn-default">Generate</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Penerima</label>
                            <select name="receiver" class="form-control selectpicker" data-live-search="true" data-size="5">
                                <option value="0"></option>
                                <?php foreach ($users as $user): ?>
                                <option value="<?php echo $user->id; ?>" <?php echo $user->id==$item->receiver?'selected':''; ?>><?php echo $user->full_name; ?> [ <?php echo $user->position; ?> ]</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Subjek / Maksud Surat</label>
                    <input type="text" name="subject" class="form-control" placeholder="Subjek surat ..." value="<?php echo $item->subject; ?>">
                </div>
                <div class="form-group">
                    <label>Isi Surat</label>
                    <textarea name="content" id="content" rows="10" class="form-control" placeholder="Isi surat ..."><?php echo $item->content; ?></textarea>
                </div>
                <div class="form-group">
                    <input type="hidden" id="attachments" name="attachments" value="<?php echo implode('|', $item->attachments); ?>">
                    <label>Attachments <a id="btn-show-attachment" class="btn btn-sm btn-link" data-toggle="tooltip" title="Toggle attachment container"><i class="fa fa-eye"></i></a></label>
                    <div id="attachment-container" style="display: none;">
                        <p class="help-block" id="upload-error"></p>
                        <input id="upload" name="upload[]" multiple="true" type="file" class="file-loading" data-show-preview="true" data-show-upload="true" data-show-caption="true">
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
        
        $('#btn-show-attachment').on('click', function(){
            $(this).find('i').toggleClass('fa-eye-slash');
            $('#attachment-container').toggle('display');
        });
        
        $('.attachment-container').on('click','.btn-delete-attachment',function(){
            var message = $(this).attr('data-confirmation')?$(this).attr('data-confirmation'):'Hapus file attachment?';
            if (confirm(message)){
                $(this).parents('.attachment-row').remove();
            }
        });
        
        $("#upload").fileinput({
            uploadUrl: "<?php echo site_url('ajax/upload'); ?>", // server upload action
            uploadAsync: false,
            elErrorContainer: "#upload-error",
            initialPreview: [
                <?php foreach ($attachments as $preview): ?>
                "<img src='<?php echo site_url(attachment_thumbnail($preview->file_name)); ?>' class='file-preview-image'>",
                <?php endforeach; ?>
            ],
            initialPreviewConfig: [
                <?php foreach ($attachments as $config): ?>
                    {caption: "<?php echo $config->file_name ?>", width: "120px", url: "<?php echo site_url('ajax/upload/delete'); ?>", key: "<?php echo base64_encode($config->file_name); ?>"},
                <?php endforeach; ?>
            ]
            
        }).on('filebatchuploadsuccess', function(event, data, previewId, index) {
            var response = data.response;
            console.log('File batch upload success');
            
            var attachment_list = $('input#attachments').val().trim();
            if (attachment_list){
                attachment_list = attachment_list.split('|');
            }else{
                attachment_list = [];
            }
            
            for (var i in response.upload){
                attachment_list.push(response.upload[i]);
            }
            
            $('input#attachments').val(attachment_list.join('|'));
            console.log($('input#attachments').val());
        }).on('filedeleted', function(event, key) {
            var attachment_list = $('input#attachments').val().trim();
            if (attachment_list){
                attachment_list = attachment_list.split('|');
                
                attachment_list.splice(attachment_list.indexOf(key),1);
                
                $('input#attachments').val(attachment_list.join('|'));
            }
            console.log($('input#attachments').val());
        });

        //$('.tinywysiwyg').wysiwyg();
        $('#content').wysihtml5({
            "font-styles"   : true,
            "emphasis"      : true,
            "lists"         : true,
            "link"          : false,
            "image"         : false,
            "color"         : false
        });
    });
</script>
