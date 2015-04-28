<?php if (isset($histories)):?>
<div class="box box-info">
    <div class="box-header">
        <h4 class="box-title">Histori Surat</h4>
        <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
    </div>
    <div class="box-body">
        <?php if (count($histories)):?>
        <table role="table" class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th style="width: 150px;">Tanggal</th>
                    <th class="hidden-xs" style="width: 120px;">Pengirim</th>
                    <th>Isi / Catatan</th>
                    <th style="width: 120px;">Penerima</th>
                    <th class="hidden-xs text-center" style="width: 90px;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($histories as $history): ?>
                <tr class="<?php echo mail_priority_bg($history->priority); ?>">
                    <td><?php echo date('d/M/Y H:i', $history->created); ?></td>
                    <td class="hidden-xs"><?php echo $history->sender_name; ?></td>
                    <td class="hidden-xs hidetext"><?php echo $history->notes; ?></td>
                    <td><?php echo $history->receiver_name; ?></td>
                    <td class="hidden-xs text-center"><?php echo $history->status_name; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>Surat ini belum pernah di-disposisikan</p>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<script type="text/javascript">
    $(document).ready(function(){
        $('.hidetext').hidetext({suffix:"...",maxlength: 80, buttonStyle:"info"});
    });
</script>