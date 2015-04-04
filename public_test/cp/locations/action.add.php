<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/config.php');
	
	try {
	
		$_SESSION['forms']['add-location'] = array();
	
		$objLocation = new Location();

		$objLocation->setIsActive(true);
		$objLocation->setName($_POST['name']);
		$objLocation->setDescription($_POST['description']);
		$objLocation->setAddress1($_POST['address1']);
		$objLocation->setAddress2($_POST['address2'] ? $_POST['address2'] : '');
		$objLocation->setCity($_POST['city']);
		$objLocation->setState($_POST['state']);
		$objLocation->setZipCode($_POST['zipCode']);
		$objLocation->setDateCreated(time());
		
		if (!$objLocation->insertIntoDatabase())
			throw new RecordCreationInsertion();
			
		$_SESSION['forms']['location']['success'] = '<strong>' . $objLocation->getName() . '</strong> was added successfully!';
		
		header('Location: index.php');
		exit;
	
	} catch (Exception $objException) {
	
		foreach ($_POST as $strKey => $mxdValue)
			$_SESSION['forms']['add-location'][$strKey] = $mxdValue;
		
		$_SESSION['forms']['add-location']['error'] = true;
		
		header('Location: add.php');
		exit;
	
	}