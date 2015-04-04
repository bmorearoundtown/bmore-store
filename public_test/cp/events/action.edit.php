<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/config.php');

	try {
	
		$_SESSION['forms']['edit-event'] = array();
	
		$objEvent = new Event($_POST['eventId']);
		
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
		
		$imageHasChanged = $_POST['imageChanged'] == 1;
		
		if( $imageHasChanged ){
			$objEvent->setHasLogo( $_FILES['eventLogoFile']['size'] > 0 );
		}
		
		if( $_POST['isPublished'] == 1 ){
			$objEvent->setDatePublished(time());
		}
		
		if (!$objEvent->updateDatabase())
			throw new RecordUpdateException($objEvent);
			
		$objLocation = new EventLocation();
		$objLocation->loadByEventId($objEvent->getId());
		
		while ($objLocation->loadNext())
			$objLocation->delete();
		
		// This can be a string of multiple ids
		foreach ( $_POST['locationId'] as $selectedLocationOption ){
			
			$objLocation = new EventLocation();
			$objLocation->setEventId($objEvent->getId());
			$objLocation->setLocationId( $selectedLocationOption );

			if (!$objLocation->insertIntoDatabase())
				throw new RecordInsertException($objLocation);
			
		}
		
		$strFilename = $objEvent->getRegistrationCode() . '.png';
		
		// Change out image if it has been toggled to be changed otherwise leave the image as it is
		if ( $imageHasChanged ) {
			
			// Remove old image
			$target = $_SERVER['DOCUMENT_ROOT'] . '/_assets/_images/logos/events/' . $strFilename;
			
			if ( file_exists($target) ) {
			
				unlink( $target ); // Delete now
				
			}
			
			if( $_FILES['eventLogoFile']['size'] ){
				
				// Upload new image
				$objUpload = new Upload($_FILES['eventLogoFile']);
				
				$strTempFilename = time() . '.upload';
				
				$objUpload->moveTo($_SERVER['DOCUMENT_ROOT'] . '/_tmp/' . $strTempFilename);
				
				$objImage = new Image($_SERVER['DOCUMENT_ROOT'] . '/_tmp/' . $strTempFilename);
				
				$objImage->convertTo('PNG', $strFilename);
				
				$objImage = new Image($_SERVER['DOCUMENT_ROOT'] . '/_tmp/' . $strFilename);
				
				$objImage->resizeToWidth(360);
				
				$objImage->export($_SERVER['DOCUMENT_ROOT'] . '/_assets/_images/logos/events/' . $strFilename, IMAGETYPE_PNG);
				
				$objImage->destroy();
				
				@unlink( $_SERVER['DOCUMENT_ROOT'] . '/_tmp/' . $strFilename );
				@unlink( $_SERVER['DOCUMENT_ROOT'] . '/_tmp/' . $strTempFilename );
			
			}
			
		}
		
		$_SESSION['forms']['edit-event']['success'] = true;
		
		header('Location: view.php?event=' . $objEvent->getId());
		exit;
		
	} catch (Exception $objException) {

		foreach ($_POST as $strKey => $mxdValue)
			$_SESSION['forms']['edit-event'][$strKey] = $mxdValue;
			
		$_SESSION['forms']['edit-event']['error'] = true;
			
		header('Location: edit.php');
		exit;
		
	}