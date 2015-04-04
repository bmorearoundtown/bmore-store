<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/config.php');
	
	try {
	
		$_SESSION['forms']['add-hotel'] = array();
	
		$objHotel = new Hotel();

		$objHotel->setIsActive(true);
		$objHotel->setName($_POST['name']);
		$objHotel->setAddress1($_POST['address1']);
		$objHotel->setAddress2($_POST['address2'] ? $_POST['address2'] : '');
		$objHotel->setCity($_POST['city']);
		$objHotel->setState($_POST['state']);
		$objHotel->setZipCode($_POST['zipCode']);
		$objHotel->setPhone1($_POST['phone1']);
		$objHotel->setPhone2($_POST['phone2'] ? $_POST['phone2'] : '');
		$objHotel->setUrl($_POST['url'] ? $_POST['url'] : '');
		$objHotel->setDateCreated(time());
		
		if (!$objHotel->insertIntoDatabase())
			throw new RecordCreationInsertion();
			
		$_SESSION['forms']['hotel']['success'] = '<strong>' . $objHotel->getName() . '</strong> was added successfully!';
		
		header('Location: index.php');
		exit;
	
	} catch (Exception $objException) {
	
		foreach ($_POST as $strKey => $mxdValue)
			$_SESSION['forms']['add-hotel'][$strKey] = $mxdValue;
			
		header('Location: add.php');
		exit;
	
	}