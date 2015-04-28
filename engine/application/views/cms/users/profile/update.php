<style type="text/css">
    /* avatar */
    .avatar-item {
      float: left;
      margin-bottom: 15px; 
      cursor:pointer;
      border: solid 1px #fff;
    }
    .avatar-container .active {border: solid 1px #3071a9;}
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
<?php if ($this->session->flashdata('message')): ?>
<?php echo create_alert_box($this->session->flashdata('message'),$this->session->flashdata('message_type'),NULL,FALSE); ?>
<?php endif; ?>
<form role="form" method="post" action="<?php echo site_url('profile/save?id='.$user->id); ?>">
    <input type="hidden" id="avatar" name="avatar" value="<?php echo $user->avatar; ?>">
    <div class="row">
        <div class="col-sm-8">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="full_name" class="form-control" placeholder="Full name ..." value="<?php echo $user->full_name; ?>">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" data-alert-message="Only allow alfa numeric and limited special chars" class="form-control valid_user" placeholder="Username ..." value="<?php echo $user->username; ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
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
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control nospace" value="<?php echo $user->email; ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="tel" name="phone" class="form-control nospace" value="<?php echo $user->phone; ?>">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Mobile Phone</label>
                        <input type="tel" name="mobile" class="form-control nospace" value="<?php echo $user->mobile; ?>">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>About Me</label>
                <textarea name="about" rows="8" class="form-control" placeholder="About ..."><?php echo $user->about; ?></textarea>
            </div>
        </div>
        <div class="col-sm-4">
            <legend>Select Avatar</legend>
            <div class="avatar-container">
                <?php $i=0; foreach ($avatars as $avatar): ?>
                <a class="avatar-item <?php echo $avatar==$user->avatar?'active':''; ?>">
                    <img src="<?php echo $avatar; ?>" <?php echo $i==0?'class="first-item"':''; $i++; ?> />
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Submit</button>
    <button class="btn btn-warning" type="reset"><i class="fa fa-refresh"></i> Reset</button>
</form>
<script>
    $(document).ready(function(){
        /* Start rowGrid.js */
        $(".avatar-container").rowGrid({itemSelector: ".item", minMargin: 10, maxMargin: 25, firstItemClass: "first-item"});
        $('.avatar-container').on('click','.avatar-item',function(){
            var image_avatar = $(this).find('img').attr('src');
            $('#avatar').val(image_avatar);
            $('img.user-active-image').attr('src', image_avatar);
            
            //change class
            $('.avatar-item').each(function(){
                $(this).removeClass('active');
            });
            
            $(this).addClass('active');
        });
    });
</script>