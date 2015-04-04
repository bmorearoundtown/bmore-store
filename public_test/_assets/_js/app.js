/*
 * 
 * Define application namespace and base application structure
 * 
 * This is default but will be set globally as javascript files are loaded
 * 
 */

// CUSTOM ERROR HANLDER
function BMORE_ERROR( type ) {
	
  this.name = "BMORE_ERROR";
  this.message = "BMORE_JS_ERROR:" + type || "BMORE_JS_GENERIC_ERROR";
  
}

BMORE_ERROR.prototype = new Error();
BMORE_ERROR.prototype.constructor = BMORE_ERROR

var BMORE = {
	
	"appSettings": (function(){
		
		var fonts = [ "Oswald:300","Oswald:400", "Roboto" ]; // default fonts

		function getBaseURL(){
			return window.location.protocol + "//" + window.location.host;			
		}
		
		function getApplicationFonts(){
			return fonts;			
		}

		function setApplicationFonts( newFontSettings ){
			
			try {
				
				if( !Array.isArray( newFontSettings ) ) throw new BMORE_ERROR( "Param to 'setApplicationFonts' is not of type Array." );
				
				fonts = newFontSettings;
			
			} catch( e ){
				
				console.error( e.message );
				
			}
			
			return;
		}
		
		return {			
			"getFonts": getApplicationFonts,
			"setFonts": setApplicationFonts,
			"getBaseURL": getBaseURL
		}
		
	}()),
	
	"ajax": {},
	"utils": {},
	"plugins": {},
	"systemMessenger": (function(){
		
		var $messenger = null;
		var isBound = false;
		var $messageArea = null;
		
		function bindMessenger(){
			
			$messenger = $( "#systemMessenger" );
			$messageArea = $messenger.find( ".messenger-message" );
			
			isBound = $messenger.length > 0 && $messageArea.length > 0;
			
			if( !isBound ) throw new BMORE_ERROR( "System messenger has not been bound properly." );
			
			return;			
		}
		
		function reset(){
			
			if( !isBound ) throw new BMORE_ERROR( "System messenger has not been bound properly." );
			
			$messageArea.html( "" );
			
			return;			
		}

		function show( message, autoclose ){
			
			if( !isBound ) throw new BMORE_ERROR( "System messenger has not been bound properly." );
			
			$messageArea.html( message );
			
			$messenger.fadeIn();
			
			if( typeof autoclose !== undefined && autoclose ){
				
				setTimeout( hide, 3750 );
				
			}
			
			return;
		}

		function hide(){
			
			if( !isBound ) throw new BMORE_ERROR( "System messenger has not been bound properly." );
			
			$messenger.fadeOut();
			
			reset(); // just always erase the content when its hidden
			
			return;
		}
		
		return {
			"set": bindMessenger,
			"reset": reset,
			"show": show,
			"hide": hide
		}
		
	}()),
	
	"header": {},
	"cart": {},
	"slider": {},
	"footer": {},
	"module": {
		"home": {},
		"events": {},
		"gallery": {},
		"blog": {},
		"contact": {},
		"social": {}
	}
	
};

BMORE.utils = (function( window, document ){
	
	var pub = {};
	
	pub.checkIsMobile = function() {
		
		var check = false;
		
		(function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)))check = true})(navigator.userAgent||navigator.vendor||window.opera);
		
		return check;
		
	}
	
	// ref underscore.js annotated sorce for ._debounce
	pub.debounce = function(func, wait, immediate) {
		
		var timeout, args, context, timestamp, result;

		var later = function() {
			var last = new Date().getTime() - timestamp;

			if (last < wait && last > 0) {
				timeout = setTimeout(later, wait - last);
			} else {
				timeout = null;
				if (!immediate) {
					result = func.apply(context, args);
					if (!timeout) context = args = null;
				}
			}
		};

		return function() {
			
			context = this;
			args = arguments;
			timestamp = new Date().getTime(); // adjusted from original source
			var callNow = immediate && !timeout;
			if (!timeout) timeout = setTimeout(later, wait);
			if (callNow) {
				result = func.apply(context, args);
				context = args = null;
			}

			return result;
		};
		    
	}
	
	pub.throttle = function(func, wait, options) {

		var context, args, result;
		var timeout = null;
		var previous = 0;
		if (!options) options = {};

		var later = function() {
			previous = options.leading === false ? 0 : new Date().getTime();
			timeout = null;
			result = func.apply(context, args);
			if (!timeout) context = args = null;
		};

		return function() {
			var now = new Date().getTime();
			if (!previous && options.leading === false) previous = now;
			var remaining = wait - (now - previous);
			context = this;
			args = arguments;
			if (remaining <= 0 || remaining > wait) {
				clearTimeout(timeout);
				timeout = null;
				previous = now;
				result = func.apply(context, args);
				if (!timeout) context = args = null;
			} else if (!timeout && options.trailing !== false) {
				timeout = setTimeout(later, remaining);
			}
			return result;
		}
	
	}
	
	return pub;
	
}( window, document ));

/*
 * Application plugin wrapper to control plugin initialization
 * 
 */
BMORE.plugins = (function( window, document, $ ){
	
	var plugins = {

		webfonts: { // non-jquery
				
			"load": function( module, fontFamilyArray, type ){ 
				
				var settings = {};
				
				settings[ module ] = {
						
					"families": fontFamilyArray
					
				}
				
				if( typeof type !== undefined && module === "google" ){ // type is explicitly for google api
					
					settings[ "type" ] = type;
					
				}
				
				WebFont.load( settings );
				
				return;
				
			}
			
		},
	
		niceScroll: { // jquery
			
			"enable": function( $el, settings ){
				
				// default behavior to html if no $el is defined
				var $scrollEl = $el || $( "html" );
				var defaults = settings || {};
				
				$scrollEl.niceScroll( defaults );
				
				return;
				
			}
			
		},

		owlCarousel: { // jquery
			
			"enable": function( $el, settings ){
				
				// default behavior to html if no $el is defined
				var $carouselEl = $el;
				var defaults = settings || {};
				
				if( $carouselEl.length === 0 ) throw new BMORE_ERROR( "Set a valid element to use for the owlCarousel" );
					
				$carouselEl.owlCarousel( defaults );
				
				var $customNav = $carouselEl.closest( ".wrapper" ).find( ".custom-carousel-nav" );
				
				if( $customNav.length > 0 ){
					
					// Custom Navigation Events
					$customNav.find( ".next" ).click(function(){
						
						$carouselEl.trigger( 'owl.next' );
						
					});
					
					$customNav.find( ".prev" ).click(function(){
						
						$carouselEl.trigger( 'owl.prev' );
						
					});					
					
				}
				
				return;
				
			}
			
		}
		
	};
	
	return plugins;
	
}( window, document, $ ));

/*
 * ----------------------------------------------------------------------------------------------------------------
 * 
 * BMORE.UTILS, BMORE.AJAX, BMORE.PLUGINS needs to be defined before any other definitions
 * 
 * ----------------------------------------------------------------------------------------------------------------
 */

/*
 * 
 * BMORE.header
 * 
 * Wrapper for header functionality inside of bmorearoundtown.com
 * 
 * Depends on
 * 
 * - jquery
 * - niceScroll plugin
 * - webfonts plugin
 * 
 */
BMORE.header = (function( window, document, $, UTILS, settings, niceScroll, webfonts ){
	
	// Constructor
	var module = function(){
		
		this.$el = $( "#header" );
		this.$html = $( "html" );
		
		return this;
	}
	
	// _PROTO_
	module.prototype = {
		
		constructor: module,
		
		enablePlugins: function( plugins ){
			
			if( typeof plugins.niceScroll !== 'undefined' && !UTILS.checkIsMobile() ) niceScroll.enable( this.$html );

			if( typeof plugins.webfonts !== 'undefined' ) webfonts.load( "google",  settings.getFonts() );
			
			return;
			
		}
		
	}
	
	return module;
	
}( window, document, $, BMORE.utils, BMORE.appSettings, BMORE.plugins.niceScroll, BMORE.plugins.webfonts ));

/*
 * 
 * BMORE.cart
 * 
 * Wrapper for cart functionality inside of bmorearoundtown.com
 * 
 * Depends on
 * 
 * - jquery -> selectors and dom load functionality
 * - bootstrap -> modal window
 * - BMORE.systemMessenger -> showing items added to cart and update information
 * - BMORE.utils -> utility functions needed like debouncing the hover view
 */
BMORE.cart = (function( window, document, $, MESSENGER, UTILS, APPSETTINGS ){

	var Base64 = {

		// private property
		_keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",

		// public method for encoding
		encode : function (input) {
		    var output = "";
		    var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
		    var i = 0;

		    input = Base64._utf8_encode(input);

		    while (i < input.length) {

		        chr1 = input.charCodeAt(i++);
		        chr2 = input.charCodeAt(i++);
		        chr3 = input.charCodeAt(i++);

		        enc1 = chr1 >> 2;
		        enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
		        enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
		        enc4 = chr3 & 63;

		        if (isNaN(chr2)) {
		            enc3 = enc4 = 64;
		        } else if (isNaN(chr3)) {
		            enc4 = 64;
		        }

		        output = output +
		        this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
		        this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

		    }

		    return output;
		},

		// public method for decoding
		decode : function (input) {
		    var output = "";
		    var chr1, chr2, chr3;
		    var enc1, enc2, enc3, enc4;
		    var i = 0;

		    input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

		    while (i < input.length) {

		        enc1 = this._keyStr.indexOf(input.charAt(i++));
		        enc2 = this._keyStr.indexOf(input.charAt(i++));
		        enc3 = this._keyStr.indexOf(input.charAt(i++));
		        enc4 = this._keyStr.indexOf(input.charAt(i++));

		        chr1 = (enc1 << 2) | (enc2 >> 4);
		        chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
		        chr3 = ((enc3 & 3) << 6) | enc4;

		        output = output + String.fromCharCode(chr1);

		        if (enc3 != 64) {
		            output = output + String.fromCharCode(chr2);
		        }
		        if (enc4 != 64) {
		            output = output + String.fromCharCode(chr3);
		        }

		    }

		    output = Base64._utf8_decode(output);

		    return output;

		},

		// private method for UTF-8 encoding
		_utf8_encode : function (string) {
		    string = string.replace(/\r\n/g,"\n");
		    var utftext = "";

		    for (var n = 0; n < string.length; n++) {

		        var c = string.charCodeAt(n);

		        if (c < 128) {
		            utftext += String.fromCharCode(c);
		        }
		        else if((c > 127) && (c < 2048)) {
		            utftext += String.fromCharCode((c >> 6) | 192);
		            utftext += String.fromCharCode((c & 63) | 128);
		        }
		        else {
		            utftext += String.fromCharCode((c >> 12) | 224);
		            utftext += String.fromCharCode(((c >> 6) & 63) | 128);
		            utftext += String.fromCharCode((c & 63) | 128);
		        }

		    }

		    return utftext;
		},

		// private method for UTF-8 decoding
		_utf8_decode : function (utftext) {
		    var string = "";
		    var i = 0;
		    var c = c1 = c2 = 0;

		    while ( i < utftext.length ) {

		        c = utftext.charCodeAt(i);

		        if (c < 128) {
		            string += String.fromCharCode(c);
		            i++;
		        }
		        else if((c > 191) && (c < 224)) {
		            c2 = utftext.charCodeAt(i+1);
		            string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
		            i += 2;
		        }
		        else {
		            c2 = utftext.charCodeAt(i+1);
		            c3 = utftext.charCodeAt(i+2);
		            string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
		            i += 3;
		        }

		    }

		    return string;
		}

	}
		
	function hideValidationErrors(){		
		$( ".validation-error" ).fadeOut();
		$( ".has-error" ).removeClass( "has-error" );		
		return;
	}
	
	function showValidationError(){
		
		var $errorContainer = $( this ).closest( ".form-group" );
		var $errorText = $errorContainer.find( ".validation-error" );

		$errorText.fadeIn();
		$errorContainer.addClass( "has-error" );

		return;
	}
	
	function isEmpty(){
		return $( this ).val() === "";
	}
	
	function validateRequiredFields( $inputs ){

		var emptyInputs = $inputs.filter( isEmpty );

		hideValidationErrors();
		
		if( emptyInputs.length === 0 ) return true;

		emptyInputs.each( showValidationError );
		
		return false;
	}

	function backStep( e ){

		var $activeEl = $( ".nav-wizard" ).find( "li.active" );
		var prev = $activeEl.data().prev;
		var $prevStep = $( ".nav-wizard li:nth-child(" + prev + ") a" );

		$prevStep.tab( "show" );
		
		$( document ).scrollTop(0);
		
		return false;
	}
	
	function processStep( e ){
		
		e.preventDefault()
		
		var $clickedEl = $( e.currentTarget );		
		var $activeEl = $( ".nav-wizard" ).find( "li.active" );
		var data = $activeEl.data();
		var step = data.step;
		var next = data.next;
		var prev = data.prev;
		var contextId = $activeEl.find( "a" ).attr( "href" );
		var $contextDiv = $( contextId );

		var isTopLink = $clickedEl.hasClass( "tab-toggler" );
		
		if( isTopLink ) { // clicked a top link
			
			var clickedData = $clickedEl.closest( "li" ).data();

			clickedStep = clickedData.step;

			if( clickedStep > step ){
				
				if( ( clickedStep - step ) == 2 ){
					
					next = clickedStep - 1;
					
				} else {
					
					next = clickedStep;
					
				}
				
				
			} else {
				
				$( document ).scrollTop(0);
				
				$( ".nav-wizard li:nth-child(" + clickedStep + ") a" ).tab( "show" );
				
				return false;
				
			}
			
		}
		
		switch( next ){

			case 1:
			case 2:

				var hasAllRequiredInformation = validateRequiredFields.call( this, $( ":input[required]", $contextDiv ) );
				
			case 3:

				if( next === 3 ){

					if( $( "input[name=paymentMethod]:checked" ).val() == 1 ){
						
						var $creditCardPaymentFields = $( "input, select", $( "#authorizeNetPayment" ) );

						hasAllRequiredInformation = validateRequiredFields.call( this, $creditCardPaymentFields );

						if( !hasAllRequiredInformation ) return false;
						
					}				
					
					// Clone the previous form areas and clone for the summary but disable form fields to prevent being sent into the post params
					var $clonedBillingForm = $( "#billingInformationContainer .form-group" ).clone();
					var $clonedInputFields = $clonedBillingForm.find( ":input" );
					
					// disable all fields from being editable
					$clonedInputFields.prop( "disabled", true );
					
					// change all classes to be col-xs-12
					$clonedInputFields.parent().removeClass().addClass( "col-xs-9" );
					
					$( "#billingInfoSummaryLoader" ).html( $clonedBillingForm );

					// clone payment information
					var $paymentLoader = $( "#paymentMethodSummaryLoader" );
					var paymentHTML = "<h4>Paying for packages through Paypal processor</h4>";

					//console.log( $( "input[name=paymentMethod]:checked" ).val() );
					
					if( $( "input[name=paymentMethod]:checked" ).val() == 1 ){

						paymentHTML = $( "#authorizeNetPayment .form-group" ).clone();
						paymentHTML.find( ":input" ).prop( "disabled", true );
						
					}

					$paymentLoader.html( paymentHTML );

					$( ".nav-wizard li:nth-child(" + next + ") a" ).tab( "show" );
					
					$( document ).scrollTop(0);
					
					return true;
										
				}

				if( hasAllRequiredInformation ) {

					$( ".nav-wizard li:nth-child(" + next + ") a" ).tab( "show" );
					
					$( document ).scrollTop(0);
					
					return true;
					
				}
				
				break;
				
		}
		
		return false;
	}

	function togglePaymentMethod(){

		var $checkedPaymentMethod = $( "input[name='paymentMethod']:checked" );
		var $authorizeNETPayment = $( "#authorizeNetPayment" );

		if( $checkedPaymentMethod.val() == 1 ){

			$authorizeNETPayment.fadeIn();
			
		} else {

			$authorizeNETPayment.fadeOut();
			
		}
		
		return;
	}

	function checkRegistrationData(){

		return $.ajax({

					url: 'action.register.php',
					data: $( "#registrationForm" ).serializeArray(),
					type: 'POST',
					dataType: 'json'
						
				});
	}

	function addHidden(theForm, key, value) {
	    // Create a hidden input element, and append it to the form:
	    var input = document.createElement('input');
	    input.type = 'hidden';
	    input.name = key;;
	    input.value = value;
	    theForm.appendChild(input);

	    return;
	}
		
	function populatePaypalFormWithReturnData( billing, items, registrationCodes ){

		var form = document.forms.paypalForm;

		for( var billingFieldName in billing ){

			addHidden( form, billingFieldName, billing[ billingFieldName ] );
			
		}

		items.forEach( function( cartObject, index ){

			for( var itemFieldName in cartObject ){
	
				addHidden( form, itemFieldName, cartObject[ itemFieldName ] );
				
			}

		});

		var registrationCodeString = registrationCodes.map( function( packageItem ){ return packageItem.registrationCode; }).join(",");
		var base64EncodedRegistrationString = Base64.encode( registrationCodeString );
		
		// add in custom field for using later in completed/notification steps
		addHidden( form, "custom", base64EncodedRegistrationString );

		// add in return_url and cancel_url with registrationCodes attached for processing on page later
		var baseURL = APPSETTINGS.getBaseURL() + "/cart/checkout/";
			
		addHidden( form, "return", baseURL + "transactionCompleted.php?ids=" + base64EncodedRegistrationString );

		addHidden( form, "cancel_return", baseURL + "transactionCanceled.php?ids=" + base64EncodedRegistrationString );

		return true;
	}
	
	function sendPaymentForm( data ){

		if( data.success ) {

			if( data.ispp ){ // if payment method was paypal

				var processedToPaypal = populatePaypalFormWithReturnData( data.billing, data.packages, data.registrationCodes );
				
				if( processedToPaypal ){

					$( "#transaction-overlay" ).fadeOut();
					
					MESSENGER.show( "<p>Completed processing. You will now be redirected to paypal to finish your payment processing.</p>" +
							"<p>This could take a few moments please be patient.</p>", false );
					
					document.forms.paypalForm.submit();
	
				}
				
			} else {

				console.log( "Process as authorize.net" );
				
			}
			
		} else {

			$( "#transaction-overlay" ).fadeOut();
			
			MESSENGER.show( "PROCESSING ERROR! " + data.message, false );
			
		}
		
		return;
	}

	function throwPaymentErrorNotice(){

		alert( "There was an error while processing your payment information" );
		
		return;
	}

	function processBuyNow(){

		$( "#transaction-overlay" ).fadeIn();

		$( ".buy-now" ).prop( "disabled", true );
		
		// Enable processing screen and overlay
		checkRegistrationData().then( sendPaymentForm, throwPaymentErrorNotice )
		
		return;
	}
		
	function validate( $form ){
		
		var invalidElements = [];
		var $requiredFormElements = $( ':input' ).filter(' [required]:visible' );
		
		$requiredFormElements.each( function(){
			
			var $el = $( this );
			var elType = $el.prop( "type" );
			var value = $el.val();
			
			switch( elType ){
				
				case "select-one":
					
					if( typeof value === undefined || value === "" ) invalidElements.push( $el ); 
					
					break;
				
				default:
					
					if( typeof value === undefined || value === 0 ) invalidElements.push( $el ); 
				
					break;
			}
			
		});
		
		return [ invalidElements.length === 0, invalidElements ];
	}
	
	function afterQuickAddLoad( htmlPage, status ){
		
		if( status !== "success" ){
			
			alert( "Error occurred while trying to load packages. Please contact an administrator to report the error!" );
			
			this.$ajaxQuickAddModal.modal( "hide" );
			
		}
		
		return;
	};
		
	function changeQuanity( $input, amount ){
		
		try {
			
			var currentInputValue = parseInt( $input.val() );
			var $selectableEl = $input.closest( "tr" ).find( "input.selectable-package" );
			var isQuickAddNotSelectAdd = $selectableEl.length > 0;
			
			if( currentInputValue <= 1 && amount < 0 ){
				
				// deselect element as selected if no quanity is present
				if( isQuickAddNotSelectAdd ) $selectableEl.prop( "checked", false );

				$input.val( 0 );
				
			} else {

				$input.val( currentInputValue + amount );
				
				// make the checkbox element checked if it is not already
				if( isQuickAddNotSelectAdd ) $selectableEl.prop( "checked", true );
				
			}
			
			if( isQuickAddNotSelectAdd ){
				
				$( document ).trigger( "update.quickadd" );
				
			} else {
				
				$( document ).trigger( "update.eventadd" );
				
			}
			
			
		} catch ( exception ){
			
			console.log( exception );
			
			// something went wrong just change input back to 0 and calculate
			$input.val( 0 );
			
		}
		
	}
	
	function increasePackageQuantity( e ){
		
		e.preventDefault();
		e.stopPropagation();
		
		var $el = $( e.currentTarget );
		var $closestInput= $el.closest( ".quantity-wrapper" ).find( "input" );
		
		changeQuanity( $closestInput, 1 );
		
		return;
	}

	function decreasePackageQuantity( e ){
		
		e.preventDefault();
		
		var $el = $( e.currentTarget );
		var $closestInput= $el.closest( ".quantity-wrapper" ).find( "input" );
		
		changeQuanity( $closestInput, -1 );
		
		e.stopPropagation();
		
		return;
	}
	
	function generateTablePackageUpdateMapping( arrayParams, allowZeroQty ){
		
		var packageMappings = { "packageIds": {} }, formIndex = 0, paramsLength = arrayParams.length;
		
		for( ; formIndex < paramsLength; formIndex++ ){
			
			var paramName = arrayParams[ formIndex ].name;
			var paramValue = arrayParams[ formIndex ].value;
			var intRegex = /[0-9 -()+]+$/; 
			var idArray = intRegex.exec( paramName );
			
			if( idArray && idArray.length > 0 ){
				
				var currentId = idArray[0];
				
				if( paramName.indexOf( "quantity_" ) !== -1 ){
					
					var quantityAsInt = parseInt( paramValue );
					
					if( ( allowZeroQty && quantityAsInt === 0 ) || quantityAsInt > 0  ){
						
						packageMappings.packageIds[ currentId ] = quantityAsInt;
						
					}
					
				}
				
			} else {
				
				packageMappings[ paramName ] = paramValue; // continue passing on the meta params like eventId, operation type
			}
			
		}
		
		return packageMappings;
	}
	
	function processAjaxError(){
		
		alert( "Error occurred while trying to load items into the cart. Please try again. If there continues to be an issue please contact us for support." )
		
		return;
	}
	
	function cartUpdate( params ){
		
		return $.ajax({
			
			url: '/cart/action.cartUpdate.php',
			data: params,
			dataType: 'json',
			type: 'POST'
			
		});
		
	}

	function completeCartUpdate( data ){
		
		var self = this;
		
		if( data && data.success ){
			
			// reload top bar area thru ajax
			$( "#topbarAjaxLoader" ).empty().load( "/_includes/topbar.php" , function(){
				
				MESSENGER.show( "Successfully added " + data.numAdded + " to your cart. Updated " 
						+ data.numUpdated + " records that were in your cart.", true )
				
				if( self.$ajaxQuickAddModal.length > 0 && self.$ajaxQuickAddModal.is( ":visible" ) ){
					
					self.$ajaxQuickAddModal.modal( "hide" );
				
				}
				
			});
			
		} else {
			
			alert( "error while trying to update the cart" );
			
		}
		
		return;
	}

	function completeCartCheckoutUpdate( data ){
		
		if( data && data.success ){
			
			location.reload();
			
		} else {
			
			alert( "error while trying to update the cart" );
			
		}
		
		return;
	}
	
	function generateSelectPackageUpdateMapping( id, quantity, formParams ){
		
		var formIndex = 0;
		var packageMappings = { "packageIds": {} };
		
		packageMappings.packageIds[ id ] = quantity;
	
		for( ; formIndex < formParams.length; formIndex++ ){
			
			var paramName = formParams[ formIndex ].name;
			var paramValue = formParams[ formIndex ].value;
			
			if( paramName != 'quantity' || paramName != 'id' ){

				packageMappings[ paramName ] = paramValue;
				
			}
			
		}
		
		return packageMappings;
	}
	
	function processSelectAddSubmit( e ){
		
		e.preventDefault();
		
		var $selectAddForm = $( "#selectAddToCartForm" );
		var validationObject = validate( $selectAddForm );
		var isValid = validationObject[0];
		
		if( isValid ){
			
			try {
			
				var packageId = parseInt( $( "#packageSelectList" ).val() );
				var quantity = parseInt( $( "#packageQuantityInput" ).val() );
				
				if( packageId === "" || quantity < 1 ){
					
					MESSENGER.show( "At least one package and quantity must be set up to add to your cart.", true ); 
					
				} else {
					
					var formMappings = generateSelectPackageUpdateMapping( packageId, quantity, $selectAddForm.serializeArray() );
					
					if( Object.keys( formMappings.packageIds ).length > 0 ){
						
						cartUpdate( formMappings ).then( completeCartUpdate.bind( this ), processAjaxError );
						
					} else {
						
						MESSENGER.show( "At least one package and quantity must be set up to add to your cart.", true )
								
					}				
					
				}
	
			} catch ( exception ){
				
				console.log( exception );
				
				MESSENGER.show( "At least one package and quantity must be set up to add to your cart.", true )
				
			}
		
		} else {
			
			var invalidElements = validationObject[1];
			
			invalidElements.forEach( function(){
				
				$( this ).addClass( "has-error")
				
			});
			
		}
		
		return;
	}
	
	function processQuickAddSubmit( e ){
		
		e.preventDefault();
		
		var $quickAddForm = $( "#quickAddToCartForm" );
		var formMappings = generateTablePackageUpdateMapping( $quickAddForm.serializeArray(), false );
		
		if( Object.keys( formMappings.packageIds ).length > 0 ){
			
			cartUpdate( formMappings ).then( completeCartUpdate.bind( this ), processAjaxError );
			
		} else {
			
			MESSENGER.show( "No packages were selected to be added to the cart!", true )
					
		}
		
		return;
	}
	
	// Constructor
	var module = function(){
		
		// elements that are static on the page and not dynamically derived can go here
		
		// top level dom els
		this.$html = $( "html" );
		this.$body = $( "body" );
		
		// cart display
		this.$el = $( "#topbar" );
		this.$topCart = $( '#topbarCartDisplay' );
		this.$topCartContents = $( '#topbarCartContents' );
		
		// quick add modal window
		this.$ajaxQuickAddModal = $( "#ajaxQuickAddModal" );
		
		return this;
	}
	
	// _PROTO_
	module.prototype = {
		
		constructor: module,
		
		init: function(){
			
			this._bindEvents();
			
			return;
			
		},
		
		_toggleCartContentsDisplay: function( e ){
			
			e.preventDefault();
			
			// can change dynamically so need to just use the current dom reference
			$( '#topbarCartContents' ).fadeToggle( "fast" );

			return;
		},
		
		_bindEvents: function(){
			
			// cart hover display
			if( !UTILS.checkIsMobile() && $( window ).width() > 768 ){ // mobile devices and tablets cant really hover...
				
				$( document ).on( "mouseenter.cartContents mouseleave.cartContents", ".topbar-hoverer", this._toggleCartContentsDisplay.bind( this ) );
			
			} else {
				
				$( document ).on( "touchstart click", "#topbarCartDisplay", this._toggleCartContentsDisplay.bind( this ) );
				
			}
			
			// quick add feaures
			$( document ).on( "click.quickadd", ".quick-add", this.loadQuickAddPackageModal.bind( this ) );
			
			$( document ).on( "click.quickadd", "button.quantity-up", increasePackageQuantity );
			
			$( document ).on( "click.quickadd", "button.quantity-down", decreasePackageQuantity );
			
			$( document ).on( "update.quickadd", this.updateTableAddConfiguration.bind( this ) ); // custom event handler unrelated to dom
			
			$( document ).on( "update.eventadd", this.updateSelectAddConfiguration ); // custom event handler unrelated to dom
			
			$( document ).on( "click.quickadd", "button#quickAddToCartButton", processQuickAddSubmit.bind( this ) );
			
			$( document ).on( "click.eventadd", "button#packageAddToCartEventViewButton", processSelectAddSubmit.bind( this ) );
			
			$( document ).on( "change.eventadd", "select#packageSelectList, input#packageQuantityInput", this.updateSelectAddConfiguration );
			
			$( document ).on( "click.checkoutupdate", "#checkoutCartUpdate", this.updateCheckoutTableWithNewItems );
			
			// CHECKOUT SPECIFIC
			
			$( document ).on( "click.checkoutNextStep", ".next-step, .tab-toggler", processStep );

			$( document ).on( "click.checkoutBackStep", ".back-step", backStep );
			
			$( document ).on( "change", "input[name='paymentMethod']", togglePaymentMethod );

			$( document ).on( "click", ".buy-now", processBuyNow );
			
			return;
		},
		
		checkoutToggler: function(){
			
			// hide validation errors
			hideValidationErrors();

			// toggle correct payment method
			togglePaymentMethod();			
			
			return;
		},
		
		loadQuickAddPackageModal: function( e ){
			
			e.preventDefault();
			
			// load content dynamically using jquery load based on elements href attribute
			var $currentTarget = $( e.currentTarget );
			var $content = this.$ajaxQuickAddModal.find( ".modal-content" );
			var navigationHREF = $currentTarget.attr( "href" );
			
			$content.empty().load( navigationHREF, afterQuickAddLoad.bind( this ) );
			
			this.$ajaxQuickAddModal.modal( "show" );
			
			return;
		},
		
		updateCheckoutTableWithNewItems: function( e ){
			
			e.preventDefault();
			
			var $checkoutTableForm = $( "#checkoutTableForm" );
			var formMappings = generateTablePackageUpdateMapping( $checkoutTableForm.serializeArray(), true );
			
			if( Object.keys( formMappings.packageIds ).length > 0 ){
				
				cartUpdate( formMappings ).then( completeCartCheckoutUpdate, processAjaxError );
				
			} else {
				
				MESSENGER.show( "No packages have changed!", true )
						
			}
				
			return;
		},
		
		updateSelectAddConfiguration: function( e ){
			
			var $form = $( "#selectAddToCartForm" );
			var $configDollarTotal = $( "#configDollarTotal" );
			var $selectEl = $( "#packageSelectList" );
			var $selectedOption = $selectEl.find( "option:selected" );
			var $quantityEl = $( "#packageQuantityInput" );
			var packageId = $selectEl.val();
			var accumulatedTotal = 0;

			try {
				
				if( packageId > 0 ){
					
					var packagePrice = $selectedOption.attr( "data-price" );
					var quantityValue = parseInt( $quantityEl.val() );
					
					if( quantityValue > 0 && packagePrice >= 0 ){
						accumulatedTotal += quantityValue * packagePrice;
					}
				
				}
				
			} catch ( exception ){
				
				throw new BMORE_ERROR( exception );
				
				accumulatedTotal += 0;
				
			}
			
			// display updated totals
			$configDollarTotal.text( "$ " + accumulatedTotal.toFixed( 2 ) ); // not an input field
			
			return;
		},
		
		updateTableAddConfiguration: function(){
			
			var self = this;
			var $tableContext = this.$ajaxQuickAddModal.find( "table" );
			var $configDollarTotal = $( "#configDollarTotal" );
			var accumulatedTotal = 0;
			var $rows = $tableContext.find( "tbody tr" );
			
			$rows.each( function(){
				
				var $rowEl = $( this );
				var $selectEl = $rowEl.find( "input.selectable-package" );
				var $priceEl = $rowEl.find( ".quickadd-package-price" );
				var $quantityEl = $rowEl.find( ".package-quantity" );
				var isSelected = $selectEl.is( ":checked" );
				
				try {
					
					var priceValue = parseFloat( $priceEl.text() );
					var quantityValue = parseInt( $quantityEl.val() );

					if( !isSelected ) return;
						
					accumulatedTotal += quantityValue * priceValue;
					
				} catch ( exception ){
					
					throw new BMORE_ERROR( exception );
					
					accumulatedTotal += 0;
					
				}
				
				return;
			});
			
			// display updated totals
			$configDollarTotal.val( accumulatedTotal.toFixed( 2 ) );
			
		}
		
		
	}
	
	return module;
	
}( window, document, $, BMORE.systemMessenger , BMORE.utils, BMORE.appSettings ));

/*
 * 
 * BMORE.slider
 * 
 * Wrapper for slider functionality
 * 
 * Depends on
 * 
 * - jquery
 * - bootstrap carousel
 * 
 */
BMORE.slider = (function( window, document, $, owlCarousel, bmoreCart ){
	
	function toggleDescriptionOverlay( e ){
		
		var opacityValue = $( e.currentTarget ).find( '.description' ).is( ':visible' ) ? 1 : .1;
		var $image = $( e.currentTarget ).find( 'img' );
		var $description = $( e.currentTarget ).find( '.description' );
		
		$image.animate({ opacity: opacityValue }, 1 );
		 
		$description.toggle();
		
		return;
	};

	// Constructor
	var module = function(){
		
		this.$el = null;
		this.settings = {
			interval: 6000
		};
		
		return this;
	}
	
	// _PROTO_
	module.prototype = {
		
		constructor: module,
		
		setType: function( sliderType ){
			
			switch( sliderType ){
				
				case 'featuredEvents':
					
					this.$el = $( '#featuredEventsCarousel' );
					
				break;
				
				default:
					
					break;
			}
			
			return this;
			
		},

		enableDescriptionOverlay: function(){
			
			var $descriptionOverlays = $( ".img-text-overlay", this.$el );
			
			$descriptionOverlays.on( "mouseenter mouseleave", toggleDescriptionOverlay );
			
			return this;
			
		},
		
		init: function(){
			
			if( typeof this.$el === undefined ) throw new BMORE_ERROR( "Please set a valid type for the slider." );
			
			var self = this;
			
			owlCarousel.enable( this.$el, {
				
				stopOnHover: true,
				autoPlay: 5000, // Set autoplay to 5 seconds
				items : 3,
				itemsDesktop : [ 1199, 3 ],
				itemsDesktopSmall : [ 979, 2 ],
				lazyLoad : true,
				afterInit: function(){
					
					self.$el.fadeIn();
					
					return;
					
				}
			
			});
			
			return this;
		}
		
	}
	
	return module;
	
}( window, document, $, BMORE.plugins.owlCarousel, BMORE.cart ));
