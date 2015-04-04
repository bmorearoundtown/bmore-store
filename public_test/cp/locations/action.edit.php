<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/config.php');
	
	try {
	
		$_SESSION['forms']['edit-location'] = array();

		$objLocation = new Location($_POST['locationId']);

		$objLocation->setIsActive($_POST['isActive']);
		$objLocation->setName($_POST['name']);
		$objLocation->setDescription($_POST['description']);
		$objLocation->setAddress1($_POST['address1']);
		$objLocation->setAddress2($_POST['address2'] ? $_POST['address2'] : '');
		$objLocation->setCity($_POST['city']);
		$objLocation->setState($_POST['state']);
		$objLocation->setZipCode($_POST['zipCode']);
		$objLocation->setDateCreated(time());
		
		if (!$objLocation->updateDatabase())
			throw new RecordUpdateInsertion();
			
		$_SESSION['forms']['location']['success'] = '<strong>' . $objLocation->getName() . '</strong> was saved successfully!';
		
		header('Location: index.php');
		exit;
	
	} catch (Exception $objException) {
	
		foreach ($_POST as $strKey => $mxdValue)
			$_SESSION['forms']['edit-location'][$strKey] = $mxdValue;
			
		$_SESSION['forms']['edit-location']['error'] = true;
			
		header('Location: edit.php');
		exit;
	
	}