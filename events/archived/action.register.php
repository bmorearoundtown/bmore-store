<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/config.php');
	
	try {
		
		$_SESSION['register'] = array();
		$_SESSION['forms']['register'] = array();
		
		
		
		
		
		/*--- Validate ---*/
		
		if (!trim($_POST['firstName']))
			$_SESSION['forms']['register']['errors'][] = 'Enter your first name';
		
		if (!trim($_POST['lastName']))
			$_SESSION['forms']['register']['errors'][] = 'Enter your last name';
		
		if (!trim($_POST['address1']))
			$_SESSION['forms']['register']['errors'][] = 'Enter your address';
		
		if (!trim($_POST['city']))
			$_SESSION['forms']['register']['errors'][] = 'Enter your city';
		
		if (!trim($_POST['state']))
			$_SESSION['forms']['register']['errors'][] = 'Enter your state';
		
		if (!trim($_POST['zipCode']))
			$_SESSION['forms']['register']['errors'][] = 'Enter your zip code';
		
		if (!trim($_POST['emailAddress']))
			$_SESSION['forms']['register']['errors'][] = 'Enter your email address';
			
		$objField = new RegistrationField();
		$objField->loadByEventId($_POST['eventId']);
		
		while ($objField->loadNext()) {
		
			if ($objField->getIsRequired() && !trim($_POST['fields'][$objField->getId()]))
				$_SESSION['forms']['register']['errors'][] = '\'' . $objField->getName() . '\' is a required field';
			
		}
			
		if (count($_SESSION['forms']['register']['errors'])) {
			header('Location: register.php?event=' . $_POST['eventCode'] . '#register');
			exit;
		}
			
		
		
		
		
		/*--- Basic registration information ---*/
		
		$objRegistration = new Registration();
		
		$objRegistration->setEventId($_POST['eventId']);
		$objRegistration->setPackageId($_POST['packageId']);
		
		$objRegistration->setDateCreated(time());
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
		
		$objRegistration->setNumberOfTickets($_POST['packageQuantity']);
		
		$objRegistration->createRegistrationCode();
		$objRegistration->createTicketId();
		
		
		/*--- Calculate price ---*/
		
		$dblPrice = 0.0;
		
		// Package price
		$objPackage = new Package($_POST['packageId']);
		
		$dblPrice = $objPackage->getPrice() * $_POST['packageQuantity'];
		
		if ($_POST['upgradeQuantity']) {
		
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
		
		$objRegistration->setAmountPaid($dblPrice);
		
		if (!$objRegistration->insertIntoDatabase())
			throw new RecordCreationException($objRegistration);
			
		
		/*--- Save Discount ---*/
		
		if ($objDiscount) {
		
			$objRegistrationDiscount = new RegistrationDiscount();
			
			$objRegistrationDiscount->setEventId($objRegistration->getEventId());
			$objRegistrationDiscount->setRegistrationId($objRegistration->getId());
			$objRegistrationDiscount->setDiscountId($objDiscount->getId());
			$objRegistrationDiscount->setDiscountAmount($dblDiscountAmount);
			
			if (!$objRegistrationDiscount->insertIntoDatabase())
				throw new RecordCreationException($objRegistrationDiscount);
				
			$objDiscount->updateCounts();
		
		}
			
		
		
		/*--- Save Upgrades ---*/
		
		if ($_POST['upgradeQuantity']) {
			
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
		
		
		
		/*--- Custom Fields ---*/
		
		if ($_POST['fields']) {
			
			foreach ($_POST['fields'] as $intFieldId => $strValue) {
			
				$objField = new RegistrationFieldValue();
				
				$objField->setEventId($objRegistration->getEventId());
				$objField->setRegistrationId($objRegistration->getId());
				$objField->setFieldId($intFieldId);
				$objField->setValue($strValue);
				
				if (!$objField->insertIntoDatabase())
					throw new RecordCreationException($objField);
			
			}
			
		}
		
		$_SESSION['register']['id'] = $objRegistration->getId();
		
		header('Location: register.php?step=2');
		exit;
	
	} catch (Exception $objException) {
	
		if ($objRegistration && $objRegistration->getId())
			$objRegistration->delete();

		foreach ($_POST as $strKey => $mxdValue)
			$_SESSION['forms']['register'][$strKey] = $mxdValue;
			
		$_SESSION['forms']['register']['errors'][] = $objException->getMessage();	
		
		header('Location: register.php?event=' . $_POST['eventCode'] . '#register');
		exit;
		
	}