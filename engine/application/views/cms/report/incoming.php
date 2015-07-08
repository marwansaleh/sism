<table class="table table-striped table-condensed" role="table">
    <thead>
        <tr>
            <th>Date</th>
            <th>Sender</th>
            <th>Recipient</th>
            <th>Subject</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($incomings as $incoming): ?>
        <tr>
            <td><?php echo date("d-m-Y", strtotime($incoming->receive_date)); ?></td>
            <td><?php echo $incoming->sender_name; ?></td>
            <td><?php echo $incoming->receiver_name; ?></td>
            <td><?php echo $incoming->subject; ?></td>
            <td><?php echo $incoming->status_name; ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
