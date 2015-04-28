(function ( $ ) {
 
    $.fn.hidetext = function(options) {
        // This is the easiest way to have default options.
        var settings = $.extend({
            // These are the defaults.
            suffix: "...",
            buttonStyle:"default",
            maxlength: 120,
            adjacent: 3
        }, options );
        
        this.each(function(){
            var container = $(this);
            if (container.text().length > settings.maxlength){
                original = container.text();
                new_text = original.substring(0, settings.maxlength);
                
                rest_string = original.substring(settings.maxlength);
                //only cut if rest less then 2
                if (rest_string.length<=settings.adjacent){
                    return true;
                }
                container.text(new_text);
                container.append('<span class="rest-string hidetext-rest-hide">'+rest_string+'</span>');
                container.append(' <button type="button" class="btn btn-'+settings.buttonStyle+' btn-xs btn-hidetext" title="Full text">'+settings.suffix+'</button>');
                
            }
        }).on('click', '.btn-hidetext',function(){
            $(this).prev('.rest-string').toggleClass('hidetext-rest-show');
        });
        return this;
    };
 
}( jQuery ));
