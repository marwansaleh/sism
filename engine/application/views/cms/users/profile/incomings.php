<div class="large-box nicescroll">
    <table class="table table-striped table-condensed" role="table">
        <tr>
            <th>Sender</th>
            <th>Recipient</th>
            <th>Date</th>
            <th>Subject</th>
            <th class="text-center">Status</th>
        </tr>
        <?php foreach ($last_incomings as $incoming): ?>
            <tr>
                <td><?php echo $incoming->sender_name; ?></td>
                <td><?php echo $incoming->receiver_name ?></td>
                <td><?php echo date("d-m-Y", strtotime($incoming->receive_date)); ?></td>
                <td><?php echo $incoming->subject; ?></td>
                <td class="text-center"><?php echo $incoming->status_name; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>