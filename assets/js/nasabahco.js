var Nasabah = {
    slideCounter : 0,
    init: function (){
        $('.ticker').ticker();
        $('#slider').flexslider({
            controlNav: false,
            directionNav : false,
            touch: true,
            animation: "fade",
            animationLoop: true,
            slideshow : false
        });
        
        $('.slider-navigation .navigation-item').on('click', function() {
            var link = $(this).attr('rel');
            link = parseInt(link);
            $('.slider-navigation .navigation-item.active').removeClass('active');
            $(this).addClass('active');
            $('#slider').flexslider((link-1));
            clearInterval(intervalID);
            intervalID = setInterval( Nasabah.moveSliders, 5000 );
        });

        var intervalID = setInterval( Nasabah.moveSliders, 5000 );
        $('.slider-navigation .navigation-item:first-child').click();
        
        jQuery("a[rel^='prettyPhoto']").prettyPhoto({social_tools:''});
        
        Nasabah.articleShowcase();
    },
    moveSliders : function() {
        var max = jQuery('.slider-navigation .navigation-item').length;
        Nasabah.slideCounter++;
        if (Nasabah.slideCounter < max) {
            $('.slider-navigation .navigation-item.active').next().click();
        } else {
            Nasabah.slideCounter = 0;
            $('.slider-navigation .navigation-item:first-child').click();
        }
    },
    articleShowcase : function() {
        jQuery('.article-showcase article').on('click', function() {
            jQuery('.article-showcase article.active').removeClass('active');
            jQuery(this).addClass('active');
            var link = jQuery(this).attr('rel');
            jQuery('.article-showcase .big-article.active').removeClass('active').fadeOut('slow', function() {
                jQuery('.article-showcase .big-article[rel="'+link+'"]').fadeIn('slow');
                jQuery('.article-showcase .big-article[rel="'+link+'"]').addClass('active');
            });
        });
    }
};

$(document).ready(function(){
    Nasabah.init();
});