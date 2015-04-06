<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/config.php');
///*
	// Validate the IPN	
	$strResponse = 'cmd=_notify-validate';
	
	foreach ($_POST as $strKey => $mxdValue) {
		$mxdValue = urlencode(stripslashes($mxdValue));
		$strResponse .= '&' . $strKey . '=' . $mxdValue;
	}
	
	$strHeader .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
	$strHeader .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$strHeader .= "Content-Length: " . strlen($strResponse) . "\r\n\r\n";
	
	$resFp = fsockopen ('ssl://www.paypal.com', 443, $intError, $strError, 30);
	
	if (!$resFp) {
		// HTTP ERROR
	} else {
	
		fputs ($resFp, $strHeader . $strResponse);
		
		while (!feof($resFp)) {
		
			$strReply = fgets($resFp, 1024);
			
			try {
//*/
				$objRegistration = new Registration();
				$objRegistration->loadByRegistrationCode($_POST['custom']);
//				$objRegistration->loadByRegistrationCode($_GET['custom']);
///*				
				if (strcmp($strReply, "VERIFIED") == 0) {
					
					if (!$objRegistration->isValid())
						throw new UnexpectedValueException('registrationCode');
					
					$objRegistration2 = new Registration();
					
					if ($objRegistration2->loadByConfirmationNumber($_POST['txn_id']))
						throw new UnexpectedValueException('confirmationNumber');
					
					$objRegistration->setConfirmationNumber($_POST['txn_id']);
						
					if ($objRegistration->getAmountPaid() != $_POST['mc_gross'])
						throw new UnexpectedValueException('amountPaid-' . $_POST['mc_gross']);

					$payment_status = $_POST['payment_status'];//read the payment details and the account holder

					$objRegistration->setDatePaid(time());
					$objRegistration->setPaypalEmailAddress($_POST['payer_email']);
					$objRegistration->setIpnResponse( $payment_status );
					$objRegistration->setIpnError( 'None' );

					if ( $_POST['payment_status'] === 'Completed' ){
					
						$objRegistration->sendConfirmationEmail();
					
					}
					
					if (!$objRegistration->updateDatabase())
						throw new RecordUpdateException();
											
				} elseif (strcmp ($strReply, "INVALID") == 0)
					throw new UnexpectedValueException('invalidIPN');
					
			} catch (UnexpectedValueException $objException) {
			
				if ($objRegistration->isValid()) {
					$objRegistration->setIpnError($objException->getMessage());
					$objRegistration->setIpnResponse(print_r($_POST, true));
					$objRegistration->updateDatabase();
				}
			
			} catch (Exception $objException) {}
			
		}
		
		fclose ($resFp);
		
	}	
//*/