<input type="hidden" name="content" id="content" value="Pemberian izin" />

<div id="lampiran-container">
    <div class="form-group">
        <label>Dasar Pemberian Ijin</label>
        <?php if (isset($item->elements->dasarijin) && count($item->elements->dasarijin)): ?>
        <?php foreach ($item->elements->dasarijin as $dasarijin): ?>
        <div class="input-group input-group-sm input-lampiran">
            <input type="text" class="form-control" name="si_dasarijin[]" value="<?php echo $dasarijin; ?>">
            <div class="input-group-btn">
                <button type="button" class="btn btn-default btn-tambah-lampiran"><span class="glyphicon glyphicon-plus"></span></button>
                <button type="button" class="btn btn-danger btn-hapus-lampiran"><span class="glyphicon glyphicon-minus"></span></button>
            </div>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
        <div class="input-group input-group-sm input-lampiran">
            <input type="text" class="form-control" name="si_dasarijin[]" value="">
            <div class="input-group-btn">
                <button type="button" class="btn btn-default btn-tambah-lampiran"><span class="glyphicon glyphicon-plus"></span></button>
                <button type="button" class="btn btn-danger btn-hapus-lampiran"><span class="glyphicon glyphicon-minus"></span></button>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label>Memberi izin kepada</label>
            <input type="text" class="form-control" name="si_namaijin" id="si_namaijin" placeholder="Nama" value="<?php echo isset($item->elements->namaijin)?$item->elements->namaijin : ''; ?>">
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label>Jabatan</label>
            <input type="text" class="form-control" name="si_jabatanijin" placeholder="Jabatan" value="<?php echo isset($item->elements->jabatanijin)?$item->elements->jabatanijin : ''; ?>">
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label>Alamat</label>
            <input type="text" class="form-control" name="si_alamatijin" placeholder="Alamat" value="<?php echo isset($item->elements->alamatijin)?$item->elements->alamatijin : ''; ?>">
        </div>
    </div>
</div>

<div class="form-group">
    <label>Memberi izin untuk</label>
    <input type="text" class="form-control" id="si_untukijin" name="si_untukijin" value="<?php echo isset($item->elements->untukijin)?$item->elements->untukijin : ''; ?>">
</div>

<div id="tembusan-container">
    <div class="form-group">
        <label>Tembusan</label>
        <?php if (isset($item->elements->tembusan) && count($item->elements->tembusan)): ?>
        <?php foreach ($item->elements->tembusan as $tembusan): ?>
        <div class="input-group input-group-sm input-tembusan">
            <input type="text" class="form-control" name="si_tembusan[]" value="<?php echo $tembusan; ?>">
            <div class="input-group-btn">
                <button type="button" class="btn btn-default btn-tambah-tembusan"><span class="glyphicon glyphicon-plus"></span></button>
                <button type="button" class="btn btn-danger btn-hapus-tembusan"><span class="glyphicon glyphicon-minus"></span></button>
            </div>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
        <div class="input-group input-group-sm input-tembusan">
            <input type="text" class="form-control" name="si_tembusan[]" value="">
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
        $('#si_untukijin').on('change', function (){
            $('#content').val('Pemberian izin kepada ' + $('#si_namaijin').val() +' untuk: ' + $(this).val());
        });
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