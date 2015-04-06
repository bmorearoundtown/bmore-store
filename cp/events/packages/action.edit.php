<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/config.php');
	
	try {
	
		$_SESSION['forms']['edit-package'] = array();

		$objPackage = new Package( $_POST['packageId'] );
		
		$objPackage->setName($_POST['name']);
		$objPackage->setEventId($_POST['eventId']);
		$objPackage->setAtDoor($_POST['atDoor']);
		$objPackage->setDescription($_POST['description']);
		$objPackage->setPrice( $_POST['price'] );
		$objPackage->setIsHidden( $_POST['isHidden'] == 1 );
		$objPackage->setMaxParticipants($_POST['maxParticipants']);
		$objPackage->setRegistrationStartDate(strtotime($_POST['registrationStartDate'] . ' ' . $_POST['registrationStartTime']));
		$objPackage->setRegistrationDeadlineDate(strtotime($_POST['registrationDeadlineDate'] . ' ' . $_POST['registrationDeadlineTime']));
		$objPackage->setTicketsPerPackage($_POST['ticketsPerPackage']);
		$objPackage->setDateLastUpdated(time());

		if (!$objPackage->updateDatabase())
			throw new RecordUpdateException($objPackage);
			
		$_SESSION['forms']['edit-package']['success'] = true;
		
		header('Location: ../view.php?event=' . $objPackage->getEventId());
		exit;
		
	} catch (Exception $objException) {

		foreach ($_POST as $strKey => $mxdValue)
			$_SESSION['forms']['edit-package'][$strKey] = $mxdValue;
			
		$_SESSION['forms']['edit-package']['error'] = true;
		$_SESSION['forms']['edit-package']['exception'] = print_r( $objException, true );
		
		header('Location: add.php');
		exit;
		
	}