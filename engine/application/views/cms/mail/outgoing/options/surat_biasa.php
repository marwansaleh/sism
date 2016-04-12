<div class="row">
    <div class="col-sm-9">
        <div class="form-group">
            <label>Kepada Yth</label>
            <input type="text" class="form-control literally-receiver" name="sbs_kepada_yth" value="<?php echo isset($item->elements->kepada_yth)?$item->elements->kepada_yth:''; ?>">
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <label>Di</label>
            <input type="text" class="form-control" name="sbs_kepada_yth_di" value="<?php echo isset($item->elements->kepada_yth_di)?$item->elements->kepada_yth_di:'Tempat'; ?>">
        </div>
    </div>
</div>
<div id="lampiran-container">
    <div class="form-group">
        <label>Lampiran</label>
        <?php if (isset($item->elements->lampiran) && count($item->elements->lampiran)): ?>
        <?php foreach ($item->elements->lampiran as $lampiran): ?>
        <div class="input-group input-group-sm input-lampiran">
            <input type="text" class="form-control" name="sbs_lampiran[]" value="<?php echo $lampiran; ?>">
            <div class="input-group-btn">
                <button type="button" class="btn btn-default btn-tambah-lampiran"><span class="glyphicon glyphicon-plus"></span></button>
                <button type="button" class="btn btn-danger btn-hapus-lampiran"><span class="glyphicon glyphicon-minus"></span></button>
            </div>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
        <div class="input-group input-group-sm input-lampiran">
            <input type="text" class="form-control" name="sbs_lampiran[]" value="">
            <div class="input-group-btn">
                <button type="button" class="btn btn-default btn-tambah-lampiran"><span class="glyphicon glyphicon-plus"></span></button>
                <button type="button" class="btn btn-danger btn-hapus-lampiran"><span class="glyphicon glyphicon-minus"></span></button>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<div id="tembusan-container">
    <div class="form-group">
        <label>Tembusan</label>
        <?php if (isset($item->elements->tembusan) && count($item->elements->tembusan)): ?>
        <?php foreach ($item->elements->tembusan as $tembusan): ?>
        <div class="input-group input-group-sm input-tembusan">
            <input type="text" class="form-control" name="sbs_tembusan[]" value="<?php echo $tembusan; ?>">
            <div class="input-group-btn">
                <button type="button" class="btn btn-default btn-tambah-tembusan"><span class="glyphicon glyphicon-plus"></span></button>
                <button type="button" class="btn btn-danger btn-hapus-tembusan"><span class="glyphicon glyphicon-minus"></span></button>
            </div>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
        <div class="input-group input-group-sm input-tembusan">
            <input type="text" class="form-control" name="sbs_tembusan[]" value="">
            <div class="input-group-btn">
                <button type="button" class="btn btn-default btn-tambah-tembusan"><span class="glyphicon glyphicon-plus"></span></button>
                <button type="button" class="btn btn-danger btn-hapus-tembusan"><span class="glyphicon glyphicon-minus"></span></button>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<div class="form-group">
    <label>Isi Surat</label>
    <textarea class="form-control" name="content" rows="10"><?php echo $item->content; ?></textarea>
</div>

<script type="text/javascript">
    $(document).ready(function (){
        $('.literally-receiver').on('keyup', function (){
            $('#literally-receiver').val($(this).val());
        });
        $('#tembusan-container').on('click','.btn-tambah-tembusan', function(){
            $tembusan = $(this).parents('.input-tembusan');
            $clone = $tembusan.clone(true);
            
            //remove data
            $clone.find('input[type="text"]').val('');
            $clone.insertAfter($tembusan).find('input[type="text"]').focus();
        });
        
        $('#tembusan-container').on('click','.btn-hapus-tembusan', function(){
            if ($('#tembusan-container .input-tembusan').length > 1){
                $(this).parents('.input-tembusan').remove();
            }
        });
        
        $('#lampiran-container').on('click','.btn-tambah-lampiran', function(){
            $lampiran = $(this).parents('.input-lampiran');
            $clone = $lampiran.clone(true);
            
            //remove data
            $clone.find('input[type="text"]').val('');
            $clone.insertAfter($lampiran).find('input[type="text"]').focus();
        });
        
        $('#lampiran-container').on('click','.btn-hapus-lampiran', function(){
            if ($('#lampiran-container .input-lampiran').length > 1){
                $(this).parents('.input-lampiran').remove();
            }
        });
    });
</script>