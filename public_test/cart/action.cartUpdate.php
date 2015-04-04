<?php

require_once( $_SERVER['DOCUMENT_ROOT'] . '/_includes/config.php' );

// Empty cart -> just destory session variable
if( isset( $_GET[ "emptycart" ] ) && $_GET[ "emptycart" ] == 1 ){
	
    session_destroy();
    
    $return_url = base64_decode( $_GET["return_url"] );
    
    header( 'Location: ' . $return_url );
    
    exit;
}

if( isset( $_POST[ "ajax" ] ) && $_POST[ "ajax" ] ){
	
	$JSON = new Services_JSON();

	$totalPackagesAdded = 0;
	$totalPackagesUpdated = 0;
	
}

// Add to cart
if( isset( $_POST[ "oper" ] ) && ( $_POST[ "oper" ] == 'add' || $_POST[ "oper" ] == 'changeQty' ) ){
	
	foreach ( $_POST[ "packageIds" ] as $packageId=>$value ) {
		
		$id	= filter_var( $packageId, FILTER_SANITIZE_NUMBER_INT );
		$qty = filter_var( $value, FILTER_SANITIZE_NUMBER_INT );
		$return_url = base64_decode( $_POST["return_url"] );		
		
		$objPackage = new PackageX( $id );
		$packageEventId = $objPackage->getEventId();
		$packageEvent = new Event( $packageEventId );
				
		if( $objPackage ) {
			
			$packageToAdd = array( array( 'name'=>$packageEvent->getName() . " - " . $objPackage->getName(), 'id'=>$id, 'qty'=>$qty, 'price'=>$objPackage->getPrice() * $objPackage->getTicketsPerPackage() ) );
			$package = array();
			$addedable = true;
			
			if( isset( $_SESSION[ "packages" ] ) ) {

				$found = false; //set found item to false
				
				foreach ( $_SESSION[ "packages" ] as $cart_itm ) {
					 
					if( $cart_itm["id"] == $id ){
						
						if( $_POST[ "oper" ] == 'changeQty' ){
							
							$newQuantity = $qty;
						
						} else {
							
							$newQuantity = $qty + $cart_itm["qty"];
							
						}
						
						if( $newQuantity > 0 ){
							
							$package[] = array('name'=>$cart_itm["name"], 'id'=>$cart_itm["id"], 'qty'=>$newQuantity, 'price'=>$cart_itm["price"] );
							$found = true;
	
						}
						
					} else {
						
						$package[] = array('name'=>$cart_itm["name"], 'id'=>$cart_itm["id"], 'qty'=>$cart_itm["qty"], 'price'=>$cart_itm["price"] );
						
					}
				
				}
				
				if( !$found ) {
					
					if( is_array( $package ) ){
						$_SESSION[ "packages" ] = array_merge( $package, $packageToAdd );
						$totalPackagesAdded += 1;
					} else {
						$totalPackagesUpdated += 1;
					}
					
				} else {

					$_SESSION["packages"] = $package;
					$totalPackagesUpdated += 1;
											
				}
								
			} else {
				
				$_SESSION["packages"] = $packageToAdd;
				$totalPackagesAdded = 1;
				
			}
			
		}
		
	}
   
	if( isset( $_POST[ "ajax" ] ) && $_POST[ "ajax" ] ){
		
		$jsonReturnObject = array( 'success'=>true, 'numAdded'=>$totalPackagesAdded, 'numUpdated'=>$totalPackagesUpdated );
		
		echo $JSON->encode( $jsonReturnObject );
		
		exit;
		
	} else {
		
		// redirect
		header( 'Location:' . $return_url );
		
		exit;
		
	}
    
}

//remove item from shopping cart
if( isset( $_GET["removep"] ) && isset( $_SESSION["packages"] ) ){
    
	$id = $_GET["removep"];
	$return_url = base64_decode( $_GET["return_url"] );
	$package = array();
	
	if( is_array($_SESSION[ "packages" ] ) ) {
		
	    foreach ( $_SESSION["packages"] as $cart_itm ) {
	    	
	        if( $cart_itm["id"] != $id ){
	            $package[] = array('name'=>$cart_itm["name"], 'id'=>$cart_itm["id"], 'qty'=>$cart_itm["qty"], 'price'=>$cart_itm["price"] );
	        }
	       	        
	    }
		
		// create a new package list for cart
		$_SESSION["packages"] = $package;
			
	}
	
    //redirect back to original page
    
    header('Location:' . $return_url );
    
    exit;
}