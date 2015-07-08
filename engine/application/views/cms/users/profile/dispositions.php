<div class="large-box nicescroll">
    <table class="table table-striped table-condensed" role="table">
        <tr>
            <th>Sender</th>
            <th>Recipient</th>
            <th>Date</th>
            <th>Note</th>
            <th class="text-center">Status</th>
        </tr>
        <?php foreach ($last_dispositions as $disposition): ?>
            <tr>
                <td><?php echo $disposition->sender_name; ?></td>
                <td><?php echo $disposition->receiver_name ?></td>
                <td><?php echo date("d-m-Y H:i", $disposition->created); ?></td>
                <td><?php echo $disposition->notes; ?></td>
                <td class="text-center"><?php echo $disposition->status_name; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>