<style type="text/css">
    .large-box {max-height: 320px; overflow: hidden; outline: none;}
    .medium-box {max-height: 200px; overflow: hidden; outline:none;}
    
    #user-profile {border: solid 1px #C7C7C7; padding: 20px 20px 0 20px; background-color: #3C8DBC;}
    #user-profile .profile-header {padding-bottom: 10px;  color: #fff;}
    #user-profile .profile-picture {border-right: solid 1px #C7C7C7;}
    #user-profile .profile-about h3 {padding-top:0; margin-top:0; line-height: 1;}
    
</style>
<section id="user-profile">
    <!-- display user photo -->
    <div class="profile-header">
        <div class="row">
            <div class="col-sm-3 profile-picture">
                <div class="media">
                    <div class="media-left">
                        <img class="media-object img-circle <?php echo $me->id==$user->id?'user-active-image':''; ?>" src="<?php echo $user->avatar_full_url; ?>" />
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">Welcome back</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-9 profile-about">
                <h3>About</h3>
                <p><?php echo nl2br($user->about); ?></p>
            </div>
        </div>
    </div>
    <!-- display user tabs info -->
    <div class="profile-detail">
        <div class="row">
            <div class="col-sm-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="active"><a href="#profile" role="tab" data-toggle="tab">Profile</a></li>
                        <li><a href="#articles" role="tab" data-toggle="tab">Latest Articles</a></li>
                        <?php if ($me->id==$user->id): ?>
                        <li><a href="#update" role="tab" data-toggle="tab">Update Info</a></li>
                        <?php endif; ?>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="profile">
                            <?php $this->load->view('cms/users/profile/profile'); ?>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="articles">
                            <?php $this->load->view('cms/users/profile/articles'); ?>
                        </div>
                        <?php if ($me->id==$user->id): ?>
                        <div role="tabpanel" class="tab-pane" id="update">
                            <?php $this->load->view('cms/users/profile/update'); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<script type="text/javascript">
</script>