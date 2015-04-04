<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/config.php');
	
	try {
		
		$_SESSION['forms']['add-event'] = array();

		$objEvent = new Event();
		
		$objEvent->setName($_POST['name']);
		$objEvent->setDescription($_POST['description']);
		$objEvent->setCategoryId($_POST['categoryId']);
		$objEvent->setIsActive( $_POST['isActive'] == 1 );
		$objEvent->setIsPublished( $_POST['isPublished'] == 1 );
		$objEvent->setMaxParticipants($_POST['maxParticipants']);
		$objEvent->setStartDate(strtotime($_POST['startDate'] . ' ' . $_POST['startTime']));
		$objEvent->setEndDate(strtotime($_POST['endDate'] . ' ' . $_POST['endTime']));
		$objEvent->setIsMultiDay($_POST['startDate'] != $_POST['endDate']);
		$objEvent->setMaxTicketsPerRegistration($_POST['maxTicketsPerRegistration']);
		$objEvent->setRegistrationDeadlineDate(strtotime($_POST['registrationDeadlineDate'] . ' ' . $_POST['registrationDeadlineTime']));
		$objEvent->setDateCreated(time());
		$objEvent->generateRegistrationCode();
		$objEvent->setHasLogo( $_FILES['eventLogoFile']['size'] > 0 );
		$objEvent->setDateLastUpdated(time());
		
		if( $_POST['isPublished'] == 1 ){
			$objEvent->setDatePublished(time());
		}
		
		if (!$objEvent->insertIntoDatabase())
			throw new RecordInsertException($objEvent);
		
		
		// This can be a string of multiple ids
		foreach ( $_POST['locationId'] as $selectedLocationOption ){
			
			$objLocation = new EventLocation();
			$objLocation->setEventId($objEvent->getId());
			$objLocation->setLocationId( $selectedLocationOption );

			if (!$objLocation->insertIntoDatabase())
				throw new RecordInsertException($objLocation);
			
		}
		
		if ($_FILES['eventLogoFile']['size']) {
		
			$objUpload = new Upload($_FILES['eventLogoFile']);
			
			$strFilename = $objEvent->getRegistrationCode() . '.png';
			$strTempFilename = time() . '.upload';
			
			$objUpload->moveTo($_SERVER['DOCUMENT_ROOT'] . '/_tmp/' . $strTempFilename);
			
			$objImage = new Image($_SERVER['DOCUMENT_ROOT'] . '/_tmp/' . $strTempFilename);
			
			$objImage->convertTo('PNG', $strFilename);
			
			$objImage = new Image($_SERVER['DOCUMENT_ROOT'] . '/_tmp/' . $strFilename);
			
			$objImage->resizeToWidth(360);
			
			$objImage->export($_SERVER['DOCUMENT_ROOT'] . '/_assets/_images/logos/events/' . $strFilename, IMAGETYPE_PNG);
			
			$objImage->destroy();
			
			@unlink($_SERVER['DOCUMENT_ROOT'] . '/_tmp/' . $strFilename);
			@unlink($_SERVER['DOCUMENT_ROOT'] . '/_tmp/' . $strTempFilename);
			
		}
		
			
		$_SESSION['forms']['add-event']['success'] = true;
		
		header('Location: view.php?event=' . $objEvent->getId());
		exit;
		
	} catch (Exception $objException) {
		
		var_dump( $objException ); exit();
		
		foreach ($_POST as $strKey => $mxdValue)
			$_SESSION['forms']['add-event'][$strKey] = $mxdValue;
			
		$_SESSION['forms']['add-event']['error'] = true;
			
		header('Location: add.php');
		exit;
		
	}