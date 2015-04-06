$( function(){
	
	// enable system messenger
	BMORE.systemMessenger.set();
	
	// enable cart actions
	var bmoreCart= new BMORE.cart();
	
	bmoreCart.init();
    
	return;
	
});