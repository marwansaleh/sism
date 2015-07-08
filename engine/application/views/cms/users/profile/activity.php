<div class="large-box nicescroll">
    <table class="table table-striped table-condensed" role="table">
        <tr>
            <th class="text-center" style="width: 140px;">Date</th>
            <th class="text-center" style="width: 140px;">IP Address</th>
            <th>Activity</th>
        </tr>
        <?php foreach ($user_activities as $activity): ?>
            <tr>
                <td class="text-center"><?php echo date('d-M-Y H:i', $activity->date); ?></td>
                <td class="text-center"><?php echo $activity->ip_address; ?></td>
                <td><?php echo $activity->activity ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>