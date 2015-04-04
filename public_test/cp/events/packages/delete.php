<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/config.php');
	
	try {
		
		$objPackage = new Package($_GET['package']);
		$referenceEventId = $objPackage->getEventId();
		
		if (!$objPackage->delete())
			throw new RecordDeletionException($objPackage);
			
		$_SESSION['forms']['package']['success'] = true;
		$_SESSION['forms']['package']['successMessage'] = '<strong>' . $objPackage->getName() . '</strong> was deleted successfully!';
		
		header('Location: ../view.php?event=' . $referenceEventId );
		exit;
	
	} catch (Exception $objException) {
	
		$_SESSION['forms']['package']['error'] = true;
		$_SESSION['forms']['package']['errorMessage'] = $objException->getMessage() ? $objException->getMessage() : 'There was an error deleting the package. Please try again';
		
		header('Location: ../view.php?event=' . $referenceEventId );
		exit;
		
	}