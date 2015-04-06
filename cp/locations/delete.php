<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/config.php');
	
	try {
		
		$objLocation = new Location($_GET['location']);
		
		if (!$objLocation->delete())
			throw new RecordDeletionException($objLocation);
			
		$_SESSION['forms']['location']['success'] = '<strong>' . $objLocation->getName() . '</strong> was deleted successfully!';
			
		header('Location: index.php');
		exit;
	
	} catch (Exception $objException) {
	
		$_SESSION['forms']['locations']['error'] = $objException->getMessage() ? $objException->getMessage() : 'There was an error deleting the location. Please try again';
		
		header('Location: index.php');
		exit;
		
	}