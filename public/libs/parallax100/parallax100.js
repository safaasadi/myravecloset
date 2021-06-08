(function($){
	"use strict";

	$.fn.extend({ 
         
        parallax100: function(options) {
            var defaults = {
            	speedScroll: 2
            }
 
            var options =  $.extend(defaults, options);
 
            return this.each(function() {
            	var obj = $(this);
				var bgParallax = $(obj).children('.inner-parallax');
			    var speed = options.speedScroll;
			    var centerObj = 0;
			    var centerWindow = 0;
			    var posBg = 0;
			    var pos50Percent = 0;

			    var setPosParallax = function() {
			    	pos50Percent = bgParallax.outerHeight() / 2 *(-1);
			    	centerObj = $(obj).offset().top + $(obj).outerHeight() / 2;
			    	centerWindow = $(window).scrollTop() + $(window).outerHeight() / 2;
			    	posBg = pos50Percent + (centerWindow - centerObj)/speed;

			    	$(bgParallax).css('transform','translateY(' + posBg + 'px)');
			    }

			    setPosParallax();

			    if(!$(obj).hasClass('parallax100-inited')) {
			    	$(obj).addClass('parallax100-inited')

			    	$(window).on('resize', function(){
			    		setPosParallax();
			    	});

			    	$(window).on('scroll',function(){
			    		setPosParallax();
			    	});
			    }
            });
        }
    });
     
})(jQuery);