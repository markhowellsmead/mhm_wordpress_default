(function($, undefined){

	var anchorAnimateOffset;

	$.extend($.fn,{

		anchorAnimate: function() {
			return this.each(function(){
				$(this).bind('click', function(e){
					var targetAnchor = $(this).attr('href').split('#');
					var destination = $('#'+targetAnchor[1]);
					if(destination && destination.length){
						e.preventDefault();
						if( $(destination).hasClass('is-folded') ){
							$(destination).toggleClass('unfolded');
						}
						$(destination).scrollToMe();
					}
				});
			});
		},

		scrollToMe : function(){
			var destination = $(this);
			if(destination && destination.length){
				var destinationTop = destination.offset().top;
				if(anchorAnimateOffset){
					destinationTop = destinationTop - anchorAnimateOffset;
				}
				$("html:not(:animated),body:not(:animated)").animate({ scrollTop: Math.floor(destinationTop)}, 600);
			}
			return this;
		}

	});

	//////////////////////////////////////////////////

	$(document).on('ready', function(){
		$('a').each(function(){
			if( $(this).attr('href').indexOf('#') === 0 ){
				$(this).anchorAnimate();
			}
		});
	});

})(jQuery);