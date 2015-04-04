<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/config.php');
	
	try {
		
		$objRegistration = new Registration( $_GET['registration']);
		
		if( $GLOBALS['env'] && $GLOBALS['env'] === 'production' ){
			
			$objRegistration->sendConfirmationEmail();
		
		} else {
			
			$objRegistration->sendDevelopmentConfirmationEmail();
			
		}
		
		header('Location: /cp/events/view.php?event=' . $objRegistration->getEventId());
		
		exit;
		
	} catch (Exception $objException) {

		header('Location: index.php');
		exit;
		
	}