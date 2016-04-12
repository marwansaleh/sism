<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label>Yang memberikan perintah</label>
            <input type="text" class="form-control literally-receiver" name="sp_namapemberiperintah" placeholder="Nama" value="<?php echo isset($item->elements->namapemberiperintah)?$item->elements->namapemberiperintah:''; ?>">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label>Jabatan</label>
            <input type="text" class="form-control" name="sp_jabatanpemberiperintah" placeholder="Jabatan" value="<?php echo isset($item->elements->jabatanpemberiperintah)?$item->elements->jabatanpemberiperintah:''; ?>">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label>Memberikan perintah kepada</label>
            <input type="text" placeholder="Nama" class="form-control literally-receiver" name="sp_namapenerimaperintah" value="<?php echo isset($item->elements->namapenerimaperintah)?$item->elements->namapenerimaperintah:''; ?>">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label>Jabatan</label>
            <input type="text" placeholder="Jabatan" class="form-control" name="sp_jabatanpenerimaperintah" value="<?php echo isset($item->elements->jabatanpenerimaperintah)?$item->elements->jabatanpenerimaperintah:''; ?>">
        </div>
    </div>
</div>

<div class="form-group">
    <label>Isi Perintah</label>
    <textarea class="form-control" name="content" rows="10"><?php echo $item->content; ?></textarea>
</div>

<div id="tembusan-container">
    <div class="form-group">
        <label>Tembusan</label>
        <?php if (isset($item->elements->tembusan) && count($item->elements->tembusan)): ?>
        <?php foreach ($item->elements->tembusan as $tembusan): ?>
        <div class="input-group input-group-sm input-tembusan">
            <input type="text" class="form-control" name="sp_tembusan[]" value="<?php echo $tembusan; ?>">
            <div class="input-group-btn">
                <button type="button" class="btn btn-default btn-tambah-tembusan"><span class="glyphicon glyphicon-plus"></span></button>
                <button type="button" class="btn btn-danger btn-hapus-tembusan"><span class="glyphicon glyphicon-minus"></span></button>
            </div>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
        <div class="input-group input-group-sm input-tembusan">
            <input type="text" class="form-control" name="sp_tembusan[]" value="">
            <div class="input-group-btn">
                <button type="button" class="btn btn-default btn-tambah-tembusan"><span class="glyphicon glyphicon-plus"></span></button>
                <button type="button" class="btn btn-danger btn-hapus-tembusan"><span class="glyphicon glyphicon-minus"></span></button>
            </div>
        </div>
        <?php endif; ?>
    </div>
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