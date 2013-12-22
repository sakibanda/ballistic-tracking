/* WooFader
--------
Author: Foxinni.com
Optimised by: Matty
Last Modified: 2011-08-22.
Version: 1.1.0
---------
*/

(function(jQuery) {
	jQuery.fn.woofader = function(input) {
	
		var defaults = {
			
			featured: '#featured',
			container: '#container',
			pagination: '#breadcrumb .pagination',
			nav_right: '#breadcrumb .right',
			nav_left: '#breadcrumb .left',
			slides: '.slide',
			right: 'right',
			speed: 200,
			timeout: 5000,
			animate: false,
			resize: false
	
		};
		
		
		return this.each(function() {
		
			//Crucial Inits
			var holder  = jQuery(this);
    		var options = jQuery.extend(defaults, input);  
    		
 			//Object Setup
 			var featured = jQuery(options.featured,holder);
  			var pagination = jQuery(options.pagination,holder);
			var nav = jQuery(options.nav,holder);
			var nav_right = jQuery(options.nav_right,holder);
			var nav_left = jQuery(options.nav_left,holder);
			var slides = jQuery(options.slides,holder);
			
   			//Animation Variables
   			var right = options.right;
   			var speed = options.speed; 
  			var timeout = options.timeout; 
			var animate = options.animate;
			var resize = options.resize; 
   			
    		//Working Variables
			var slideCount = slides.length;
			var count = 0;
			var nextItem = featured.children( '.slide:eq(0)' );
			var nextItemHeight = nextItem.height();
			timeout = parseInt(timeout);
			
			slides.addClass( 'hidden' );
			
			// Setup height of first slide.
			if ( featured.hasClass( 'dynamic-height' ) ) { featured.css( 'height', nextItem.height() ); }
			
			// holder.css( 'overflow', 'hidden' );
			nextItem.addClass( 'active' ).removeClass( 'hidden' );
			
			// Make sure each slide has a height value, if the slide has the "dynamic-height" CSS class.
			if ( featured.children( '.dynamic-height' ).length ) {
				featured.children( '.dynamic-height' ).each( function ( i ) {
					var slideHeight = jQuery( this ).height() + parseInt( featured.css( 'padding-top' ) ) + parseInt( featured.css( 'padding-bottom' ) );
					
					// If the slide has a featured image, find it and check it's height.
					if ( jQuery( this ).find( '.featured-image img' ).length ) {
						var imageHeight = jQuery( this ).find( '.featured-image img' ).height();
						var imageMarginBottom = parseInt( jQuery( this ).find( '.featured-image img' ).css( 'margin-bottom' ) );
						
						if ( imageMarginBottom != 0 ) {
							slideHeight = slideHeight + ( imageMarginBottom );
						}
					}
					
					jQuery( this ).css( 'height', slideHeight + 'px' ).removeClass( 'dynamic-height' );
				});
			}
			
			//Height Setup
			if( resize && featured.hasClass( 'dynamic-height' ) ) { featured.css( 'height', nextItemHeight ); }
				
			// Auto-animation Call
			if(timeout > 0) {
				
				doAutoAnimate = setInterval( autoAnimate, timeout );
				holder.click( function(){ clearInterval( doAutoAnimate ); holder.addClass( 'stopped' ) }); // Clear Timeout
			}
			
			// Auto-animation Function
			function autoAnimate(){
				count++;
				slides.hide();
				slides.addClass( 'hidden' ).removeClass( 'active' );
				
				if( count >= slideCount ) { count = 0; }
				
					//Slides
					nextItem = featured.children( '.slide:eq('+count+')' );
					nextItem.fadeIn( speed ); // Formerly .fadeIn( speed ) // .animate({ opacity: 1 }, speed, function () { jQuery( this ).show(); });
					nextItemHeight = nextItem.height();
					
					if( animate && featured.hasClass( 'dynamic-height' ) ) { featured.stop().animate({ height: nextItemHeight }, speed, function () {}); }
					else if( resize && featured.hasClass( 'dynamic-height' ) ) { featured.css( 'height', nextItemHeight ); }
					
					toggleActiveClasses ( slides, nextItem );
					
					//Pagination
					pagination.children( 'li' ).removeClass( 'active' ).end().children( 'li:eq('+count+')' ).addClass( 'active' );
				
			};
			
			// Left/Right Navigation
			nav_right.add( nav_left ).click( function(){
				
				slides.hide().addClass( 'hidden' );
				if( timeout > 0 ){ clearInterval( doAutoAnimate ); holder.addClass( 'stopped' ) };
				if( jQuery( this ).hasClass( 'right' ) ){
					count++;
					if(count >= slideCount) { count = 0; }
					var action = 'right';
				} else { 
					count--;
					if(count < 0) { count = (slideCount - 1); }
					var action = 'left'
				}
				
				//Slides
				nextItem = featured.children( '.slide:eq('+count+')' );
				nextItem.stop( true, true ).fadeIn( speed );
				nextItemHeight = nextItem.height();
				if( animate ) { featured.stop( true, true ).animate( {height:nextItemHeight}, speed, function () { toggleActiveClasses ( slides, nextItem ); } ); }
				else if( resize ) { featured.css('height',nextItemHeight); }
				
				//Pagination
				pagination.children( 'li' ).removeClass( 'active' ).end().find( 'li:eq(' + count + ')' ).addClass( 'active' );
				
				return false;
				
			});
					
			pagination.children( 'li' ).click(function(){
				//Slides
				slides.hide();
				var index = jQuery( this ).index();
				if(timeout > 0){ clearInterval( doAutoAnimate ); holder.addClass( 'stopped' ); } // Clear Timeout
				nextItem = featured.children( '.slide:eq('+index+')' );
				nextItem.fadeIn(speed);
				nextItemHeight = nextItem.height();
				
				if ( animate ) {featured.stop( true, true ).animate({ height: nextItemHeight });}
				else if ( resize ) { featured.css( 'height', nextItemHeight ); }
				//Pagination
				pagination.children( 'li' ).removeClass( 'active' ).end().children( 'li:eq('+index+')' ).addClass( 'active' );

				
				count = index;
				return false;
			});
			
			holder.hover(
				function(){
						clearInterval( doAutoAnimate );
					},
				function(){
						if( holder.hasClass( 'stopped' ) ) {
							//Do not re-activate slider
						} else {
							if ( timeout > 0 ) {
								doAutoAnimate = setInterval( autoAnimate, timeout );
							}
						}
					}
					
			);
			
			// Toggle hidden/active classes.
			function toggleActiveClasses ( slides, nextItem ) {
				slides.addClass( 'hidden' ).removeClass( 'active' );
				nextItem.addClass( 'active' ).removeClass( 'hidden' );
			} // End toggleActiveClasses()
			
			

		});
	};
    
})(jQuery);