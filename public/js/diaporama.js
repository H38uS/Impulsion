(function($){
	$.fn.diaporama = function(options) {
	
		var defaults = {
			delay: 6,
			deplacement: 1,
		};	 
		var options = $.extend(defaults, options);
		
		function moveleft(current, index)
		{
			var position = current.offset();
			if (typeof(offset[index])=='undefined') {
				offset[index] = position['left'];
				// don't know why : first image need one less pixel in the offset...
				if (index == 0) {
					offset[index]--;
				}	
				// init width
				picture_width[index] = current.width();
				
				var nbLeft = current.css('left').split("px");
				pos_current[index] = nbLeft[0];
			} 
			var dec = pos_current[index];
			pos_current[index] -= options.deplacement;
			if (position['left'] < - picture_width[index]) {
				// la photo vient de disparaitre totalement
				pos_current[index] = $(window).width() - offset[index] - picture_width[index];
			}
			current.css('left', pos_current[index]+"px");
		}
		
		offset = {};
		picture_width = {};
		pos_current = {};
	
		this.each(function(index){
			// init
			var my_photo = $(this);
			inter = setInterval(moveleft, 30, my_photo, index);
		});
		return this;
	};
})(jQuery);

