define(function(require,exports,moudle){
 var $ = require('../lib/jquery-2.1.4.min');
         require('../plug/imagelightbox/imagelightbox');
         require('../plug/lazyload/lazyload');
         

    var imagelightbox = {
           init : function(){

			$('img.lazy').lazyload();
	
			var navigationOn = function( instance, selector )
				{
					var images = $( selector );
					if( images.length )
					{
						var nav = $( '<div id="imagelightbox-nav"></div>' );
						for( var i = 0; i < images.length; i++ )
							nav.append( '<button type="button"></button>' );

						nav.appendTo( 'body' );
						nav.on( 'click touchend', function(){ return false; });

						var navItems = nav.find( 'button' );
						navItems.on( 'click touchend', function()
						{
							var $this = $( this );
							if( images.eq( $this.index() ).attr( 'href' ) != $( '#imagelightbox' ).attr( 'src' ) )
								instance.switchImageLightbox( $this.index() );

							navItems.removeClass( 'active' );
							navItems.eq( $this.index() ).addClass( 'active' );

							return false;
						})
						.on( 'touchend', function(){ return false; });
					}
				},
				navigationOff = function()
					{
						$( '#imagelightbox-nav' ).remove();
					},
				activityIndicatorOff = function()
					{
						$( '#imagelightbox-loading' ).remove();
					},
				activityIndicatorOn = function()
					{
						$( '<div id="imagelightbox-loading"><div></div></div>' ).appendTo( 'body' );
					},
				activityIndicatorOff = function()
				{
					$( '#imagelightbox-loading' ).remove();
				},
				overlayOn = function()
				{
					$( '<div id="imagelightbox-overlay"></div>' ).appendTo( 'body' );
				},
				overlayOff = function()
				{
					$( '#imagelightbox-overlay' ).remove();
				},
				navigationUpdate = function( selector )
					{

						var items = $( '#imagelightbox-nav button' );
						var $selector = $( selector );
						var src = $( '#imagelightbox' ).attr( 'src' );
						var $filter = $selector.filter( '[href="' + src + '"]' );
						
						items.removeClass( 'active' );

						items.eq( $selector.index( $filter ) ).addClass( 'active' );
					};

			$('.imagelightbox').each(function(index){
				var $this = $(this);
				$this.find('a').attr('data-imagelightbox',index);
				var selectorE = $this.find('a[data-imagelightbox="'+ index +'"]');
				var instanceE = selectorE.imageLightbox(
					{
						onStart:	 function() { overlayOn(); navigationOn( instanceE, selectorE ); },
						onEnd:		 function() { overlayOff();navigationOff(); activityIndicatorOff(); },
						onLoadStart: function() { activityIndicatorOn(); },
						onLoadEnd:	 function() { navigationUpdate( selectorE ); activityIndicatorOff(); }
					});
			});
           }
    }
      
      moudle.exports = imagelightbox;   

});
