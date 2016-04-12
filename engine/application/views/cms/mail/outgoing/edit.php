<div class="row">
    <div class="col-sm-12">
        <?php if ($this->session->flashdata('message')): ?>
        <?php echo create_alert_box($this->session->flashdata('message'),$this->session->flashdata('message_type')); ?>
        <?php endif; ?>
        
        <form role="form" method="post" action="<?php echo $submit_url; ?>">
            <input type="hidden" id="id" name="id" value="<?php echo $item->id; ?>" />
            <input type="hidden" name="sender" value="<?php echo $item->sender; ?>">
            <input type="hidden" name="template_id" value="<?php echo $template->id; ?>">
            <input type="hidden" name="template_name" value="<?php echo $template->name; ?>">
            <input type="hidden" id="literally-receiver" name="literally_receiver" value="<?php echo $item->literally_receiver; ?>">
            <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?php echo $item->id?'Update Data':'Create New'; ?></h3>
            </div><!-- /.box-header -->
            
            <div class="box-body">
                <div class="form-group">
                    <label>Referensi Surat</label>
                    <select id="incoming_ref_id" name="incoming_ref_id" class="form-control selectpicker" data-live-search="true" data-size="5"data-header="Pilih referensi surat">
                        <option value="0"></option>
                        <?php foreach ($incomings as $incoming): ?>
                        <option value="<?php echo $incoming->id; ?>" <?php echo $incoming->id==$item->incoming_ref_id?'selected':''; ?>><?php echo $incoming->subject; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div id="incoming_ref_preview" class="well well-sm">
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
                <div class="form-group">
                    <label>Subjek / Maksud Surat</label>
                    <input type="text" name="subject" class="form-control" placeholder="Subjek surat ..." value="<?php echo $item->subject; ?>">
                </div>
                <legend>Atribut Surat</legend>
                <?php $this->load->view($form_element); ?>
                <div class="form-group">
                    <label>Signer</label>
                    <select name="signer" class="form-control selectpicker" data-live-search="true" data-size="5">
                        <?php foreach ($signers as $signer): ?>
                        <option value="<?php echo $signer->id; ?>" <?php echo $signer->id==$item->signer?'selected':''; ?>><?php echo $signer->jabatan . ' ' . $signer->full_name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Penandatangan Surat</label>
                    <input type="checkbox" id="literally_signer" name="literally_signer" value="1" <?php echo $item->literally_signer==1?'checked':''; ?>>Sama dengan "Signer"
                </div>
                <div id="literal-signer-container" class="well well-sm <?php echo $item->literally_signer==1?'hidden':''; ?>">
                    <div class="row">
                        <div class="col-sm-4">
                            <label>Nama Pejabat</label>
                            <input type="text" class="form-control" name="nama_pengirim" value="<?php echo isset($item->elements->nama_pengirim)?$item->elements->nama_pengirim:''; ?>">
                        </div>
                        <div class="col-sm-4">
                            <label>Pangkat Pejabat</label>
                            <input type="text" class="form-control" name="pangkat_pengirim" value="<?php echo isset($item->elements->pangkat_pengirim)?$item->elements->pangkat_pengirim:''; ?>">
                        </div>
                        <div class="col-sm-4">
                            <label>NIP Pejabat</label>
                            <input type="text" class="form-control" name="nip_pengirim" value="<?php echo isset($item->elements->nip_pengirim)?$item->elements->nip_pengirim:''; ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer clearfix">
                <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Save &amp; Close</button>
                <button id="btn-preview" class="btn btn-success" type="button"><i class="fa fa-eye"></i> Make PDF</button>
                <button class="btn btn-warning" type="reset"><i class="fa fa-refresh"></i> Reset</button>
                <a class="btn btn-default" href="<?php echo $back_url; ?>"><i class="fa fa-backward"></i> Cancel</a>
            </div>
        </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    var OutGoingManager = {
        selectedRefId : 0,
        init: function (){
            this.prepareEvent();
            this.loadReference(this.selectedRefId);
        },
        loadReference : function (incoming_id){
            if (incoming_id==0){
                $('#incoming_ref_preview').addClass('hidden');
            }else{
                $('#incoming_ref_preview').removeClass('hidden').html('<p class="text-center">Loading references...</p>');
                
                //loading html data
                $('#incoming_ref_preview').load('<?php echo site_url('history/index'); ?>/'+incoming_id+' #mail-history', function (){
                    $('#mail-history').addClass('small');
                });
            }
        },
        prepareEvent : function () {
            var _this = this;
            $('#incoming_ref_id').on('change', function(){
                _this.loadReference($(this).val());
            });
            
            $('#btn-preview').on('click', function(){
                if (!$('input#id').val()){
                    alert('Surat harus disimpan terlebih dahulu untuk melihat preview');
                    return false;
                }

                var wnd = window.open("<?php echo site_url('outgoing/preview'); ?>/"+$('#id').val());
                wnd.focus();
            });
            $('#literally_signer').on('click', function(){
                $('#literal-signer-container').toggleClass('hidden');
            });
            $('#btn-show-attachment').on('click', function(){
                $(this).find('i').toggleClass('fa-eye-slash');
                $('#attachment-container').toggle('display');
            });
        }
    };
    
    $(document).ready(function(){
        OutGoingManager.selectedRefId = $('#incoming_ref_id').val();
        OutGoingManager.init();
    });
</script>
