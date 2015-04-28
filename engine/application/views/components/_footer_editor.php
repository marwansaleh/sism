        <script type="text/javascript">
            var adv_editor;
            $(document).ready(function(){
                //nice scroll
                //$('html').niceScroll({cursorcolor:"#00F"});
                //$('.nicescroll').niceScroll({cursorcolor:"#00F"});
                //tooltip
                $('[data-toggle="tooltip"]').tooltip();
                
                
                adv_editor = new wysihtml5.Editor('editor', {
                    toolbar             : 'toolbar',
                    style               : true,
                    autoLink            : true,
                    parserRules         : wysihtml5ParserRules,
                    pasteParserRulesets : wysihtml5ParserPasteRulesets,
                    stylesheets         : [
                        "<?php echo site_url(config_item('path_lib').'bootstrap/css/bootstrap.min.css'); ?>"
                    ],
                    useLineBreaks       : false,
                    cleanUp             : true
                });

                adv_editor.on('showSource', function(){
                    alert(adv_editor.getValue(true));
                });
            });
        </script>
        <!-- bootstrap -->
        <script src="<?php echo site_url(config_item('path_lib').'bootstrap/js/bootstrap.min.js'); ?>"></script>
        <!-- nice scroll -->
        <script src="<?php echo site_url(config_item('path_lib').'scrollTo/jquery.scrollTo.min.js'); ?>"></script>
        <script src="<?php echo site_url(config_item('path_lib').'nicescroll/jquery.nicescroll.min.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo site_url(config_item('path_lib').'wysihtml5/wysihtml-toolbar.js'); ?>"></script>
        <script src="<?php echo site_url(config_item('path_lib').'wysihtml5/parser/advanced_and_extended.js'); ?>"></script>
        
        
    </body>
</html>