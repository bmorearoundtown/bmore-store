<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/config.php');
	
	try {

		$_SESSION['forms']['edit-category'] = array();
	
		$objCategory = new Category($_POST['categoryId']);

		$objCategory->setIsActive($_POST['isActive']);
		$objCategory->setName($_POST['name']);
		
		if (!$objCategory->updateDatabase())
			throw new RecordCreationInsertion();
			
		$_SESSION['forms']['category']['success'] = '<strong>' . $objCategory->getName() . '</strong> was saved successfully!';
		
		header('Location: index.php');
		exit;
	
	} catch (Exception $objException) {

		foreach ($_POST as $strKey => $mxdValue)
			$_SESSION['forms']['edit-category'][$strKey] = $mxdValue;
			
		$_SESSION['forms']['edit-category']['error'] = true;
			
		header('Location: edit.php');
		exit;
	
	}