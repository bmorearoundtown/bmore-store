<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/cp/config.php');
	
	
	
	$objRegistration = new Registration();
	$objRegistration->loadUniqueEmails();
	
	
	
	$arrEmails = array();
	
	while ($objRegistration->loadNext())
		if (preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i', $objRegistration->getEmailAddress()))
			$arrEmails[] = $objRegistration->getEmailAddress();
?>

	<?= implode(', ', $arrEmails) ?>