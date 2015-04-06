<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/cp/config.php');
	
	try {
		
		$objHotel = new Hotel($_GET['hotel']);
		
		if (!$objHotel->delete())
			throw new RecordDeletionException($objHotel);
			
		$_SESSION['forms']['hotel']['success'] = '<strong>' . $objHotel->getName() . '</strong> was deleted successfully!';
			
		header('Location: index.php');
		exit;
	
	} catch (Exception $objException) {
	
		$_SESSION['forms']['hotel']['error'] = $objException->getMessage() ? $objException->getMessage() : 'There was an error deleting the hotel. Please try again';
		
		header('Location: index.php');
		exit;
		
	}