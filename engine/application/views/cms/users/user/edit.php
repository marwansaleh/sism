<style type="text/css">
    .avatar-item {
      float: left;
      margin-bottom: 15px; 
      cursor:pointer;
    }
    .avatar-item img {
      max-width: 100%;
      max-height: 100%;
      vertical-align: bottom;
      height:80px;
    }
    .first-item {
      clear: both;
    }
    /* remove margin bottom on last row */
    .last-row, .last-row ~ .item {
      margin-bottom: 0;
    }
</style>
<div class="row">
    <div class="col-sm-12">
        <?php if ($this->session->flashdata('message')): ?>
        <?php echo create_alert_box($this->session->flashdata('message'),$this->session->flashdata('message_type')); ?>
        <?php endif; ?>
        
        <form role="form" method="post" action="<?php echo $submit_url; ?>">
            <input type="hidden" id="author" value="<?php echo $author; ?>" />
            <input type="hidden" id="id" value="<?php echo $item->id; ?>" />
            <!-- main user data -->
            <div class="col-sm-8">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"><?php echo $item->id?'Update Data':'Create New'; ?></h3>
                    </div><!-- /.box-header -->

                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input type="text" name="full_name" class="form-control" placeholder="Full name ..." value="<?php echo $item->full_name; ?>">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" name="username" data-alert-message="Only allow alfa numeric and limited special chars" class="form-control valid_user" placeholder="Username ..." value="<?php echo $item->username; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Divisi / Bidang</label>
                                    <select name="division" class="form-control">
                                        <?php foreach ($divisions as $div): ?>
                                        <option value="<?php echo $div->id; ?>" <?php echo $item->division_id==$div->id?'selected':''; ?>><?php echo $div->division; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Jabatan</label>
                                    <input type="text" name="jabatan" class="form-control" value="<?php echo $item->jabatan; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Pangkat</label>
                                    <select name="pangkat" class="form-control">
                                        <?php foreach ($pangkat_array as $pangkat): ?>
                                        <option value="<?php echo $pangkat; ?>" <?php echo $item->pangkat==$pangkat?'selected':''; ?>><?php echo $pangkat; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Golongan</label>
                                    <select name="golongan" class="form-control">
                                        <?php foreach ($golongan_array as $golongan): ?>
                                        <option value="<?php echo $golongan; ?>" <?php echo $item->golongan==$golongan?'selected':''; ?>><?php echo $golongan; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>NIP</label>
                                    <input type="text" name="nip" class="form-control" value="<?php echo $item->nip; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Jenis Kelamin</label>
                                    <select name="sex" class="form-control">
                                        <option value="P" <?php echo $item->sex=='P'?'selected':''; ?>>Perempuan</option>
                                        <option value="L" <?php echo $item->sex=='L'?'selected':''; ?>>Laki-laki</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Group User</label>
                                    <select name="group_id" class="form-control selectpicker" data-live-search="true" data-size="5">
                                        <?php foreach ($groups as $group): ?>
                                        <option value="<?php echo $group->group_id; ?>" <?php echo $group->group_id==$item->group_id?'selected':''; ?>><?php echo $group->group_name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Type User</label>
                                    <select name="type" class="form-control">
                                        <option value="0" <?php echo $item->type==0?'selected':''; ?>>Internal</option>
                                        <option value="1" <?php echo $item->type==1?'selected':''; ?>>Eksternal</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input type="email" name="email" class="form-control nospace" value="<?php echo $item->email; ?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="tel" name="phone" class="form-control nospace" value="<?php echo $item->phone; ?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Mobile Phone</label>
                                    <input type="tel" name="mobile" class="form-control nospace" value="<?php echo $item->mobile; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Password</label>
                                    <div class="input-group">
                                        <input type="text" name="password" class="form-control" placeholder="Password ...">
                                        <div class="input-group-addon">
                                            <input type="checkbox" class="icheck" name="change_password" value="1"> Change
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Set Active User</label>
                                    <select name="is_active" class="form-control">
                                        <option value="0" <?php echo $item->is_active==0?'selected':''; ?>>Not Active</option>
                                        <option value="1" <?php echo $item->is_active==1?'selected':''; ?>>Active</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Sort Order</label>
                                    <input type="number" step="1" min="0" name="sort" class="form-control nospace" value="<?php echo $item->sort; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>About Me</label>
                            <textarea name="about" rows="5" class="form-control" placeholder="About ..."><?php echo $item->about; ?></textarea>
                        </div>
                    </div>
                    <div class="box-footer clearfix">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Submit</button>
                        <button class="btn btn-warning" type="reset"><i class="fa fa-refresh"></i> Reset</button>
                        <a class="btn btn-default" href="<?php echo $back_url; ?>"><i class="fa fa-backward"></i> Cancel</a>
                    </div>
                </div>
            </div> <!-- end main user data -->
            <div class="col-sm-4"> <!-- user info -->
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">User Info</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td>Created on</td>
                                    <td><?php echo time_difference($item->created_on, TRUE); ?></td>
                                </tr>
                                <tr>
                                    <td>Last login</td>
                                    <td><?php echo $item->last_login ? time_difference($item->last_login, TRUE):'never'; ?></td>
                                </tr>
                                <tr>
                                    <td>Last IP</td>
                                    <td><?php echo $item->last_ip; ?></td>
                                </tr>
                                <tr>
                                    <td>Last page</td>
                                    <td><?php echo $item->last_page; ?></td>
                                </tr>
                                <tr>
                                    <td>Online</td>
                                    <td><?php if ($item->is_online):?><i class="fa fa-check-circle"></i><?php else: ?><i class="fa fa-minus-circle"></i><?php endif;?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-4"> <!-- user avatar -->
                <input type="hidden" name="avatar" id="avatar" value="<?php echo $item->avatar; ?>" />
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">User Avatar</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div id="avatar-container" class="box-sizing" style="float:left; height: 240px; overflow: auto; width:98%">
                                <div class="col-sm-12">
                                    <?php foreach ($avatars as $avatar): ?>
                                    <a class="avatar-item">
                                        <img src="<?php echo $avatar; ?>" />
                                    </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function(){
        /* Start rowGrid.js */
        $(".avatar-container").rowGrid({itemSelector: ".item", minMargin: 10, maxMargin: 25, firstItemClass: "first-item"});
        
        $('#avatar-container').on('click','.avatar-item',function(){
            var image_avatar = $(this).find('img').attr('src');
            $('#avatar').val(image_avatar);
            if ($('#author').val()===$('#id').val()){
                $('img.user-active-image').attr('src', image_avatar);
            }
        });
    });
</script>