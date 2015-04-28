<h3 style="margin-top: 0; padding-top: 0;">Subjek: <?php echo $mail->subject; ?></h3>
<table role="table" class="table table-condensed table-hover">
    <thead>
        <tr>
            <th style="width: 150px;" class="text-center">Tanggal</th>
            <th style="width: 120px;" class="hidden-xs">Pengirim</th>
            <th>Catatan</th>
            <th style="width: 120px;">Penerima</th>
            <th style="width: 100px;" class="hidden-xs">Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($histories as $history): ?>
        <tr class="<?php echo $history->id==$history->mail_id?mail_bg($history->mail_type):mail_priority_bg($history->priority); ?>">
            <td class="text-center"><?php echo date('d/M/Y H:i', $history->created); ?></td>
            <td class="hidden-xs"><?php echo $history->sender_name; ?></td>
            <td class="hidden-xs hidetext"><?php echo $history->notes; ?></td>
            <td><?php echo $history->receiver_name; ?></td>
            <td class="hidden-xs"><?php echo $history->status_name; ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script type="text/javascript">
    $(document).ready(function(){
        $('.hidetext').hidetext({suffix:"...",maxlength: 70, buttonStyle:"warning"});
    });
</script>