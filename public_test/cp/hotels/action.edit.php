<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/config.php');
	
	try {
	
		$_SESSION['forms']['edit-hotel'] = array();

		$objHotel = new Hotel($_POST['hotelId']);

		$objHotel->setIsActive($_POST['isActive']);
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
		
		if (!$objHotel->updateDatabase())
			throw new RecordCreationInsertion();
			
		$_SESSION['forms']['hotel']['success'] = '<strong>' . $objHotel->getName() . '</strong> was saved successfully!';
		
		header('Location: index.php');
		exit;
	
	} catch (Exception $objException) {
	
		foreach ($_POST as $strKey => $mxdValue)
			$_SESSION['forms']['edit-hotel'][$strKey] = $mxdValue;
			
		$_SESSION['forms']['edit-hotel']['error'] = true;
			
		header('Location: edit.php');
		exit;
	
	}