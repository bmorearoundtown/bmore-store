<?
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/_includes/config.php' );
	
	try {
		
		$_SESSION['register'] = array();
		$_SESSION['forms']['register'] = array();
		
		/*--- Create a json object for returning the data ---*/
		
		$JSON = new Services_JSON();
		
		/*--- Server side form validation ---*/
		
		if ( !trim($_POST['firstName']) ) $_SESSION['forms']['register']['errors'][] = 'Enter your first name';
		
		if ( !trim($_POST['lastName']) ) $_SESSION['forms']['register']['errors'][] = 'Enter your last name';
		
		if ( !trim($_POST['address1']) ) $_SESSION['forms']['register']['errors'][] = 'Enter your address';
		
		if ( !trim($_POST['city']) ) $_SESSION['forms']['register']['errors'][] = 'Enter your city';
		
		if ( !trim($_POST['state']) ) $_SESSION['forms']['register']['errors'][] = 'Enter your state';
		
		if ( !trim($_POST['zipCode']) ) $_SESSION['forms']['register']['errors'][] = 'Enter your zip code';
		
		if ( !trim($_POST['emailAddress']) ) $_SESSION['forms']['register']['errors'][] = 'Enter your email address';
		
		if ( !isset( $_POST['paymentMethod'] ) ) $_SESSION['forms']['register']['errors'][] = 'Please select a payment method to use';
		
		
		if( isset( $_SESSION["packages"] ) && count( $_SESSION["packages"] ) ){
		
			foreach ( $_SESSION["packages"] as $cartItem ) {
					
				$packageId = $cartItem["id"];
					
				$currentPackage = new Package( $packageId );
				$packageEventId = $currentPackage->getEventId();
				
				$packageEvent = new Event( $currentPackage->getEventId() );
					
				$packageName = $cartItem["name"];
				$packagePrice = $cartItem["price"];
				$packageQty = $cartItem["qty"];
				$packageSubtotal = $packagePrice * $packageQty;
				
				$objField = new RegistrationField();
				$objField->loadByEventId( $packageEventId );
				
				while( $objField->loadNext() ) {
				
					if ($objField->getIsRequired() && !trim($_POST['fields'][$objField->getId()]))
						$_SESSION['forms']['register']['errors'][] = '\'' . $objField->getName() . '\' is a required field';
						
				}
								
			}
													
		} else {
			
			$_SESSION['forms']['register']['errors'][] = "You do not have any items in your shopping cart!";
			
		}
																
		if ( count( $_SESSION['forms']['register']['errors'] ) ) {
			header( 'Location: register.php' );
			exit;
		}
		
		$registrationsCreated = 0;
		$packageInformation = array();
		
		/*--- Basic registration information ---*/
		/* --- Create a registration object for each package in the cart --- */
		
		$count = 1;
		$registrationCodeArray = array(); // store all the registration codes in an array
		
		foreach ( $_SESSION["packages"] as $cartItem ) {

			if( $cartItem["qty"] ){
					
				$packageId = $cartItem["id"];
					
				$currentPackage = new Package( $packageId );
				$packageEventId = $currentPackage->getEventId();
			
				$packageEvent = new Event( $currentPackage->getEventId() );
					
				$packageName = $cartItem["name"];
				$packagePrice = $cartItem["price"];
				$packageQty = $cartItem["qty"];
				$packageSubtotal = $packagePrice * $packageQty;
	
				$packageInformation[] = array( ("item_name_" . $count)=>$packageName, ("quantity_" . $count)=>$packageQty, ("amount_" . $count)=>$packagePrice );
				
				$objRegistration = new Registration();
				
				$objRegistration->setEventId( $packageEventId );
				$objRegistration->setPackageId( $packageId );
				$objRegistration->setDateCreated(time());
				$objRegistration->setDateLastUpdated(time());
				$objRegistration->setDatePaid(null);
				$objRegistration->setDateCanceled(null);
				$objRegistration->setIsActive(true);
				$objRegistration->setFirstName($_POST['firstName']);
				$objRegistration->setLastName($_POST['lastName']);
				$objRegistration->setAddress1($_POST['address1']);
				$objRegistration->setAddress2($_POST['address2'] ? $_POST['address2'] : '');
				$objRegistration->setCity($_POST['city']);
				$objRegistration->setState($_POST['state']);
				$objRegistration->setZipCode($_POST['zipCode']);
				$objRegistration->setEmailAddress($_POST['emailAddress']);
				$objRegistration->setPhoneNumber($_POST['phoneNumber'] ? $_POST['phoneNumber'] : '');
				$objRegistration->setNumberOfTickets($packageQty);
				$objRegistration->createRegistrationCode();
				$objRegistration->createTicketId();
				$objRegistration->setAmountPaid(0); // This updates on the response from paypal if its a completed transaction
				
				/*--- Package price by ticket quantity ---*/
	
	/* 			if ($_POST['upgradeQuantity']) {
				
					// Upgrade prices
					foreach ($_POST['upgradeQuantity'] as $intUpgradeId => $intQuantity) {
							
						$objUpgrade = new Upgrade($intUpgradeId);
				
						$dblPrice += ($objUpgrade->getPrice() * $intQuantity);
							
					}
						
				}
				
				if ($_POST['discountCode']) {
						
					// Factor in any discounts
					if ($_POST['discountCode'] && $_POST['discountAmount']) {
							
						$objDiscount = new DiscountCode();
						$objDiscount->loadByDiscountCode($_POST['discountCode'], $_POST['eventId']);
							
						$dblDiscountedPrice = $objDiscount->applyDiscount($dblPrice, $_POST['eventId']);
				
						$dblDiscountAmount = $dblPrice - $dblDiscountedPrice;
				
						$dblPrice = $dblDiscountedPrice;
				
						$intDiscountId = $objDiscount->getId();
							
					}
						
				}
	 */			
				
				if (!$objRegistration->insertIntoDatabase())
					throw new RecordCreationException($objRegistration);
				
				$registrationCodeArray[] = array("registrationCode"=>$objRegistration->getRegistrationCode());
				
				$registrationsCreated += 1;
				
				/*--- Save Discount ---*/
				
		/* 		if ($objDiscount) {
				
					$objRegistrationDiscount = new RegistrationDiscount();
						
					$objRegistrationDiscount->setEventId($objRegistration->getEventId());
					$objRegistrationDiscount->setRegistrationId($objRegistration->getId());
					$objRegistrationDiscount->setDiscountId($objDiscount->getId());
					$objRegistrationDiscount->setDiscountAmount($dblDiscountAmount);
						
					if (!$objRegistrationDiscount->insertIntoDatabase())
						throw new RecordCreationException($objRegistrationDiscount);
				
					$objDiscount->updateCounts();
				
				}
		 */			
				
				
				/*--- Save Upgrades ---*/
				
		/* 		if ($_POST['upgradeQuantity']) {
						
					foreach ($_POST['upgradeQuantity'] as $intUpgradeId => $intQuantity) {
							
						$objRegistrationUpgrade = new RegistrationUpgrade();
				
						$objRegistrationUpgrade->setEventId($objRegistration->getEventId());
						$objRegistrationUpgrade->setRegistrationId($objRegistration->getId());
						$objRegistrationUpgrade->setUpgradeId($intUpgradeId);
						$objRegistrationUpgrade->setQuantity($intQuantity);
				
						if (!$objRegistrationUpgrade->insertIntoDatabase())
							throw new RecordCreationException($objRegistrationUpgrade);
							
					}
				
				}
		 */		
			
				$count += 1;
			
			}
			
		}
		
		$billingInformation = array('first_name'=>$_POST['firstName'],'last_name'=>$_POST['lastName'],'address1'=>$_POST['address1']
				,'city'=>$_POST['city'],'state'=>$_POST['state'],'zip'=>$_POST['zipCode'],'email'=>$_POST['emailAddress']);
		
		$jsonReturnObject = array( 'success'=>true, 'ispp'=>($_POST['paymentMethod'] == 0 ), 
								'registrationsSaved'=>$registrationsCreated, 
								'billing'=>$billingInformation,
								'registrationCodes'=>$registrationCodeArray,
								'packages'=>$packageInformation );
		
		echo $JSON->encode( $jsonReturnObject );
			
		exit;

		
	} catch (Exception $objException) {
		
		if ($objRegistration && $objRegistration->getId())
			$objRegistration->delete();
	
		foreach ($_POST as $strKey => $mxdValue)
			$_SESSION['forms']['register'][$strKey] = $mxdValue;
			
		$_SESSION['forms']['register']['errors'][] = $objException->getMessage();
	
		$jsonReturnObject = array( 'success'=>false, 'registrationsSaved'=>0, 'message'=>$objException->getMessage() );
		
		echo $JSON->encode( $jsonReturnObject );
	
		exit;
				
	}