<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/config.php');
	
	try {
	
		$_SESSION['forms']['add-category'] = array();
	
		$objCategory = new Category();

		$objCategory->setIsActive(true);
		$objCategory->setName($_POST['name']);
		$objCategory->setDateCreated(time());
		
		if (!$objCategory->insertIntoDatabase())
			throw new RecordCreationInsertion();
			
		$_SESSION['forms']['add-category']['success'] = true;
		
		header('Location: index.php');
		exit;
	
	} catch (Exception $objException) {

		foreach ($_POST as $strKey => $mxdValue)
			$_SESSION['forms']['add-category'][$strKey] = $mxdValue;
			
		$_SESSION['forms']['add-category']['error'] = true;
			
		header('Location: add.php');
		exit;
	
	}