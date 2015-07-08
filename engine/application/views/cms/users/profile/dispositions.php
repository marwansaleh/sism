<div class="large-box nicescroll">
    <table class="table table-striped table-condensed" role="table">
        <tr>
            <th>Sender</th>
            <th>Recipient</th>
            <th>Date</th>
            <th>Note</th>
        </tr>
        <?php foreach ($last_dispositions as $disposition): ?>
            <tr>
                <td><?php echo $disposition->sender_name; ?></td>
                <td><?php echo $disposition->receiver_name ?></td>
                <td><?php echo date("d-m-Y", $disposition->created); ?></td>
                <td><?php echo $disposition->notes; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>