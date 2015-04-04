<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/config.php');
	
	/*--- Build objects ---*/
	
	$objRegistration = new Registration();
	$objRegistration->loadByRegistrationCode($_GET['code']);
	
	$objEvent = new Event($objRegistration->getEventId());
	
	$objPackage = new Package($objRegistration->getPackageId());
	
	if (!$objRegistration->isValid() || !$objEvent->isValid() || !$objPackage->isValid()) {
	
		$_SESSION['register'] = array();
		
		header('Location: index.php');
		exit;
	
	}
	
	$objRegistration->setIsCanceled(true);
	$objRegistration->setDateCanceled(time());
	$objRegistration->setIpnResponse( 'Cancelled' );
	$objRegistration->setIpnError( 'None' );
					
	$objRegistration->updateDatabase();
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<title>BMORE Around Town | Dedicated to Bringing you the Best social Event Activities in Baltimore | Calendar</title>
	
	<link rel="stylesheet" type="text/css" href="/_assets/_css/events.css" />
	
<?
	 include('../_includes/functions.php');
?>

</head>

<body>

	<div class="min-width">
		
	    <div id="container">
    	
<?
	include('../_includes/navigation.php');
?>
        
	        <div id="contentcontainer">
        	
<?
	include('../_includes/sidebar.php');
?>
            
	            <div id="content">
                
                	<h1>Registration Canceled</h1>
                	
                	<br />
                	
                	<p>Your transaction has been canceled. If you wish to check out another event please <a href="/events/">view our upcoming events</a>.</p>
                	
	            </div>
	            
	            <div class="clear"></div>
	            
	        </div>
        
<?
	include('../_includes/footer.php');
?>
        
	    </div>
	
	</div>

</body>
</html>