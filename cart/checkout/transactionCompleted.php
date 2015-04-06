<?php

	require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/config.php');

	/*--- Build objects ---*/

	if( !isset( $_GET['ids']  ) ){

		header('Location: index.php');

		exit();
	}

	$registrationIds = explode( "," , base64_decode($_GET['ids']) );

	/* Update database to show that the registration was canceled */

	$allRegistrations = array();

	foreach ( $registrationIds as $registrationCode ) {

		$objRegistration = new Registration();
		$objRegistration->loadByRegistrationCode( $registrationCode );

		$objEvent = new Event($objRegistration->getEventId());

		$objPackage = new Package($objRegistration->getPackageId());

		if (!$objRegistration->isValid() || !$objEvent->isValid() || !$objPackage->isValid()) {

			header('Location: index.php');
			exit;

		}

		$allRegistrations[] = array( "package"=>$objPackage->getName(),
									 "name"=>$objRegistration->getRegistrationCode(),
									 "ticketId"=>$objRegistration->getTicketId(),
									 "numTickets"=>$objRegistration->getNumberOfTickets() );
	}

	// empty cart contents
	$_SESSION["packages"] = null;

	require_once( "head.php" ); // includes body tag

	require_once( "header.php" );


?>

<div class="container">

	<div class="row">

		<div id="completedTransactionWrapper" class="wrapper highlight-layer" style="background-color: #000; padding: 30px 30px; min-height: 40em;">

			<h1>Transaction Completed</h1>

			<div style="font-size: 1.25em;">

	            <p>Thank you for purchasing.  You successfully completed registration for the following events: </p>

	            <hr>

	            <ul>

	            <?php foreach ( $allRegistrations as $registrationItem ) { ?>

	            	<li>

	            		<h3 class="text-muted"><?= $registrationItem["package"] ?></h3>

	            		<p><strong>Registration & Qty:</strong> <?= $registrationItem["name"] ?> x <?= $registrationItem["numTickets"] ?></p>

	            		<p><strong>Ticket Reference:</strong> <?= $registrationItem["ticketId"] ?></p>

	            	</li>

	            <?php } ?>

	            </ul>

	            <hr>

	            <p>
	            	If you have any questions please feel free to contact us at <a href="mailto:staff@bmorearoundtown.com">staff@bmorearoundtown.com</a>.
	            </p>

	            <p>You will receive an email no later than 72 hours before the event to your PayPal email address with a complete itinerary of the event and exact location.  If your package includes event tickets, airfare, etc., you will receive that at registration the day of the event.  All you will need at time of registration will be a valid government ID and/or our paypal confirmation email (via print out/smartphone)</p>

	           	<p>Also, be sure to <a href="/events/">check out our other events</a>.</p>

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
