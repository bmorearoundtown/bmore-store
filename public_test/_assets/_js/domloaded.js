


$( function(){
	
	// add nice scroller
	new BMORE.header().enablePlugins({ "niceScroll": true });
	
	var $window = $( window );
	var windowWidth = $window.width();
	var windowHeight = $window.height();
	var carouselEl = document.getElementById( "featuredEventsCarousel" );
	var featuredEvents = new BMORE.slider();
	
	if( carouselEl ){ 

		featuredEvents.setType( "featuredEvents" ).init()

		if( !BMORE.utils.checkIsMobile() && windowWidth >= 992 ){

			featuredEvents.enableDescriptionOverlay();

		}

	}

	// enable system messenger
	BMORE.systemMessenger.set();

	// enable cart actions
	var bmoreCart = new BMORE.cart();
	
	bmoreCart.init();
	
	// scroll to top arrow
	$( '#scrollToTop button' ).on( "click touchend", function(){

		$( 'html' ).animate( { scrollTop: 0 }, 600 );

		return false;

	});
	
	// set anything with a max-height container to the window height
	$( ".max-height-container" ).css( "max-height", windowHeight - 200 );
		
	var registrationElement = document.getElementById( "registrationWrapper" );
	
	if( registrationElement ){
		
		bmoreCart.checkoutToggler();
		
	}
	
	var hasFBToggling = document.getElementById( "informationalDividerWrapper" );
	
	if( hasFBToggling ){

		window.fbAsyncInit = function() {
			FB.init({
				appId      : '550094458427750',
				xfbml      : true,
				version    : 'v2.1'
			});
		};
		
		// LAZY LOADING for the purposes of minimizing inital load times 
		// Enable fb after scrolling past a certain point in the page
		(function(){
	
			$window.on( "scroll", BMORE.utils.debounce( function() {
				
				if( $window.scrollTop() > 250 ){
	
					(function(d, s, id){
						   var js, fjs = d.getElementsByTagName(s)[0];
						   if (d.getElementById(id)) {return;}
						   js = d.createElement(s); js.id = id;
						   js.src = "//connect.facebook.net/en_US/sdk.js";
						   fjs.parentNode.insertBefore(js, fjs);
						 }(document, 'script', 'facebook-jssdk'));				
	
					$( window ).off( "scroll" ); // remove the listener after we have loaded the script
	
				}
	
				return;
	
			}, 500 ) );
	
		}());

	}
	
	return;

});
