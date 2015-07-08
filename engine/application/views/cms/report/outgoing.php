<table class="table table-striped table-condensed" role="table">
    <tr>
        <th>Date</th>
        <th>Sender</th>
        <th>Recipient</th>
        <th>Subject</th>
        <th class="text-center">Status</th>
    </tr>
    <?php foreach ($outgoings as $outgoing): ?>
        <tr>
            <td><?php echo date("d-m-Y", $outgoing->created); ?></td>
            <td><?php echo $outgoing->sender_name; ?></td>
            <td><?php echo $outgoing->receiver_name ?></td>
            <td><?php echo $outgoing->subject; ?></td>
            <td class="text-center"><?php echo $outgoing->status_name; ?></td>
        </tr>
    <?php endforeach; ?>
</table>