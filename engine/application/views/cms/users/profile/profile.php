<div class="large-box nicescroll">
    <table class="table table-striped" role="table">
        <tbody>
            <tr>
                <td style="width: 200px;" class="text-bold">Full name</td>
                <td><?php echo $user->full_name; ?></td>
            </tr>
            <tr>
                <td class="text-bold">Group name</td>
                <td><?php echo $user->group_name; ?></td>
            </tr>
            <tr>
                <td class="text-bold">Divisi</td>
                <td><?php echo $user->division_name; ?></td>
            </tr>
            <tr>
                <td class="text-bold">Jabatan</td>
                <td><?php echo $user->jabatan; ?></td>
            </tr>
            <tr>
                <td class="text-bold">Golongan</td>
                <td><?php echo $user->golongan; ?></td>
            </tr>
            <tr>
                <td class="text-bold">Pangkat</td>
                <td><?php echo $user->pangkat; ?></td>
            </tr>
            <tr>
                <td class="text-bold">Jumlah Surat Masuk</td>
                <td><span class="badge badge-info"><?php echo $user->mail_count["incoming"]; ?></span></td>
            </tr>
            <tr>
                <td class="text-bold">Jumlah Disposisi</td>
                <td><span class="badge badge-info"><?php echo $user->mail_count["disposition"]; ?></span></td>
            </tr>
            <tr>
                <td class="text-bold">Jumlah Surat Keluar</td>
                <td><span class="badge badge-info"><?php echo $user->mail_count["outgoing"]; ?></span></td>
            </tr>
            <tr>
                <td class="text-bold">Email address</td>
                <td><?php echo $user->email; ?></td>
            </tr>
            <tr>
                <td class="text-bold">Phone Number</td>
                <td><?php echo $user->phone; ?></td>
            </tr>
            <tr>
                <td class="text-bold">Mobile Number</td>
                <td><?php echo $user->mobile; ?></td>
            </tr>
        </tbody>
    </table>
</div>