        <script type="text/javascript">
            $(document).ready(function(){
                //iCheck for checkbox and radio inputs
                $('input[type="checkbox"].icheck, input[type="radio"].icheck').iCheck({
                  checkboxClass: 'icheckbox_minimal-blue',
                  radioClass: 'iradio_minimal-blue'
                });
                //pretty photo
                $("a[rel^='prettyPhoto']").prettyPhoto({
                    social_tools:'',
                    markup: '<div class="pp_pic_holder"> \
                        <div class="ppt">&nbsp;</div> \
                        <div class="pp_top"> \
                            <div class="pp_left"></div> \
                            <div class="pp_middle"></div> \
                            <div class="pp_right"></div> \
                        </div> \
                        <div class="pp_content_container"> \
                            <div class="pp_left"> \
                            <div class="pp_right"> \
                                <div class="pp_content"> \
                                    <div class="pp_loaderIcon"></div> \
                                    <div class="pp_fade"> \
                                        <a href="#" class="pp_expand" title="Expand the image">Expand</a> \
                                        <div class="pp_hoverContainer"> \
                                            <a class="pp_next" href="#">next</a> \
                                            <a class="pp_previous" href="#">previous</a> \
                                        </div> \
                                        <div id="pp_full_res"></div> \
                                        <div class="pp_details"> \
                                            <div class="pp_nav"> \
                                                <a href="#" class="pp_arrow_previous">Previous</a> \
                                                <p class="currentTextHolder">0/0</p> \
                                                <a href="#" class="pp_arrow_next">Next</a> \
                                            </div> \
                                        </div> \
                                    </div> \
                                </div> \
                                <a class="pp_close" href="#">Close</a> \
                            </div> \
                            </div> \
                        </div> \
                    </div> \
                    <div class="pp_overlay"></div>'
                });
                //nice scroll
                $('html').niceScroll({cursorcolor:"#00F"});
                $('.nicescroll').niceScroll({cursorcolor:"#00F"});
                //tooltip
                $('[data-toggle="tooltip"]').tooltip();
                //selectpicker
                $('select.selectpicker').selectpicker();
                //datepicker
                $('.datepicker').datepicker({
                    format: 'dd-mm-yyyy',
                });
                $('.btn-calender').on('click', function(){
                    $(this).parents('.input-group').find('input.datepicker').focus();
                });
                
                $('.confirmation').on('click',function(){
                    var confirm_text = 'Are you sure to delete this item?';
                    if ($(this).attr('data-confirmation')){
                        confirm_text = $(this).attr('data-confirmation');
                    }
                    return confirm(confirm_text);
                });
                $('input.nospace').on('keypress', function (e){
                    if(e.which !== 32){
                        return true;
                    }else{
                        e.preventDefault();
                        if ($(this).attr('data-alert-message')){
                            alert($(this).attr('data-alert-message'));
                        }
                        return false;
                    }
                });
                $('input.valid_user').on('keypress', function (e){
                    //var regex = /^[-\w\.\$@\*\!]$/;
                    //var regex = /^[a-zA-Z0-9_]$/;
                    var regex = /^[-\w\.\$*]$/;
                    
                    if (e.which !== 13){
                        if (regex.test(String.fromCharCode(e.which))){
                            return true;
                        }else{
                            e.preventDefault();
                            if ($(this).attr('data-alert-message')){
                                alert($(this).attr('data-alert-message'));
                            }
                            return false;
                        }
                    }
                    return true;
                });
                
                $('a.sidebar-toggle').on('click', function(){
                    var sidebar = $('body').hasClass('sidebar-collapse')?0:1;
                    
                    $.post('<?php echo site_url('ajax/layout/sidebar'); ?>',{display:sidebar});
                });
            });
        </script>
        <!-- bootstrap -->
        <script src="<?php echo site_url(config_item('path_lib').'bootstrap/js/bootstrap.min.js'); ?>"></script>
        <!-- nice scroll -->
        <script src="<?php echo site_url(config_item('path_lib').'scrollTo/jquery.scrollTo.min.js'); ?>"></script>
        <script src="<?php echo site_url(config_item('path_lib').'nicescroll/jquery.nicescroll.min.js'); ?>" type="text/javascript"></script>
        <!-- prettyPhoto -->
        <script src="<?php echo site_url(config_item('path_lib').'prettyPhoto/3.15/js/jquery.prettyPhoto.js'); ?>"></script>
        <!-- iCheck -->
        <script src="<?php echo site_url(config_item('path_lib').'iCheck/icheck.min.js'); ?>"></script>
        <!-- Bootstrap select -->
        <script src="<?php echo site_url(config_item('path_lib').'bootstrap-select/js/bootstrap-select.min.js'); ?>"></script>
        <!-- Bootstrap datepicker -->
        <script src="<?php echo site_url(config_item('path_lib').'datepicker/bootstrap-datepicker.js'); ?>"></script>
        <!-- Bootstrap Typeahead & TagsInput -->
        <script src="<?php echo site_url(config_item('path_lib').'bootstrap-typeahead/bootstrap3-typeahead.js'); ?>"></script>
        <script src="<?php echo site_url(config_item('path_lib').'angular/angular.min.js'); ?>"></script>
        <script src="<?php echo site_url(config_item('path_lib').'tagsinput/bootstrap-tagsinput.min.js'); ?>"></script>
        <script src="<?php echo site_url(config_item('path_lib').'tagsinput/bootstrap-tagsinput-angular.js'); ?>"></script>
        <!-- Image Row grid -->
        <script src="<?php echo site_url(config_item('path_lib').'rowgrid/jquery.row-grid.min.js'); ?>"></script>
        <!-- Bootstrap fileinput -->
        <script src="<?php echo site_url(config_item('path_lib').'bootstrap-fileinput/js/fileinput.min.js'); ?>"></script>
        <!-- wysiwyg -->
        <!--<script src="<?php echo site_url(config_item('path_lib').'wysihtml5/wysihtml5x.min.js'); ?>"></script>-->
        <script src="<?php echo site_url(config_item('path_lib').'bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'); ?>"></script>
        <!-- Hidetext -->
        <script src="<?php echo site_url(config_item('path_lib').'hidetext/hidetext.js'); ?>"></script>
    </body>
</html>