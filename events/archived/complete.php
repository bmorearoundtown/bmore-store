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
                
                	<h1>Registration Complete</h1>
                	
                	<br />
                	
                	<p>Thank you for purchasing a<?= preg_match('/[aeiou]/', substr($objPackage->getName(), 0, 1)) ? 'n' : '' ?> <strong><?= $objPackage->getName() ?></strong> for the <strong><?= $objEvent->getName() ?></strong>.  Your ticket number is <strong><?= $objRegistration->getRegistrationCode() ?></strong>.  If you have any questions please feel free to contact us at <a href="mailto:staff@bmorearoundtown.com">staff@bmorearoundtown.com</a>.</p>
                	
                	<p>You will receive an email shortly containing a printable ticket for the event. Please remember to print out your ticket and bring it with you the day of the event.</p>
                	
                	<p>Also, be sure to <a href="/events/">check out our other events</a>.</p>

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