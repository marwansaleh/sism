<div class="form-group">
    <label>Nama Penerima Rekomendasi</label>
    <input type="text" class="form-control" name="sk_nama_rekomendasi" value="<?php echo isset($item->elements->nama_rekomendasi)?$item->elements->nama_rekomendasi:''; ?>">
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label>Jabatan Penerima Rekomendasi</label>
            <input type="text" class="form-control" name="sk_jabatan_rekomendasi" value="<?php echo isset($item->elements->jabatan_rekomendasi)?$item->elements->jabatan_rekomendasi:''; ?>">
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <label>Pangkat / Golongan</label>
            <input type="text" class="form-control" name="sk_pangkat_rekomendasi" value="<?php echo isset($item->elements->pangkat_rekomendasi)?$item->elements->pangkat_rekomendasi:''; ?>">
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <label>NIP</label>
            <input type="text" class="form-control" name="sk_nip_rekomendasi" value="<?php echo isset($item->elements->nip_rekomendasi)?$item->elements->nip_rekomendasi:''; ?>">
        </div>
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
    });
</script>