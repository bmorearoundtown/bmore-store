<?php
	
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/_includes/config.php' );
	
	if( !isset( $_GET['ids']  ) ){
		
		header('Location: index.php');
		
		exit();
	}
	
	$registrationIds = explode( "," , base64_decode($_GET['ids']) );
	
	/* Update database to show that the registration was canceled */
	
	foreach ( $registrationIds as $registrationCode ) {
			
		$objRegistration = new Registration();
		$objRegistration->loadByRegistrationCode( $registrationCode );
		
		$objEvent = new Event($objRegistration->getEventId());
		
		$objPackage = new Package($objRegistration->getPackageId());
		
		if (!$objRegistration->isValid() || !$objEvent->isValid() || !$objPackage->isValid()) {

			header('Location: index.php');
			exit;
			
		}

		$objRegistration->setIsCanceled(true);
		$objRegistration->setDateCanceled(time());
		$objRegistration->setIpnResponse( 'Cancelled' );
		$objRegistration->setIpnError( 'None' );
		 
		$objRegistration->updateDatabase();
		
	}
	
	require_once( "head.php" ); // includes body tag
	
	require_once( "header.php" );
	
?>

<div class="container">
	
	<div class="row">
		
		<div id="canceledTransactionWrapper" class="wrapper highlight-layer" style="background-color: #000; padding: 30px 30px; min-height: 40em;">
			
			<h1>Transaction Canceled</h1>
			
			<div style="font-size: 1.25em;">
			
				<p>Your transaction has been <span class="text-danger">canceled</span>. The following registrations were canceled:</p>
				
				<ul>
					<?php foreach ( $registrationIds as $registrationCode ) { ?>
						<li><?= $registrationCode ?></li>
					<?php } ?>
				</ul>
				
				<p>If you wish to check out another event please <a href="/events/">view our upcoming events</a>.</p>
				
			</div>
			
		</div>
	
	</div>

</div>

<div id="newsletterWrapper" class="wrapper">
	
	<div class="container-fluid">
		
		<?php include("newsletter.php"); ?>
		
	</div>
	
</div>
	
<?php require_once("footer.php"); ?>

</body>

</html>
