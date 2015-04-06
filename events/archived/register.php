<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/config.php');
	
//	print_r($_SESSION['forms']['register']);die;



	/*--- Build objects ---*/
	
	if ($_GET['step'] == 2 && $_SESSION['register']['id']) {
	
		$objRegistration = new Registration($_SESSION['register']['id']);
		
		$objEvent = new Event($objRegistration->getEventId());
		
		$objPackage = new Package($objRegistration->getPackageId());
		
		if (!$objRegistration->isValid() || !$objEvent->isValid() || !$objPackage->isValid()) {
		
			$_SESSION['register'] = array();
			
			header('Location: index.php');
			exit;
		
		}
	
	} else {
		
		$objPackage = new Package();
		$objPackage->loadByRegistrationCode($_GET['event']);
		
		$objEvent = new EventX($objPackage->getEventId());
		
		$objLocation = new Location();
		$objLocation->loadByEventId($objEvent->getId());
		
		if (!$objPackage->isValid() || !$objEvent->isValid() || !$objEvent->getIsActive() || !$objEvent->getIsActive()) {
			header('Location: index.php');
			exit;
		}
		
	}
	
?>
<!DOCTYPE html>
<html>
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<title>BMORE Around Town | Dedicated to Bringing you the Best social Event Activities in Baltimore | Calendar</title>
	
	<link rel="stylesheet" type="text/css" href="/_assets/_css/events.css" />
	
<?
	 include('../_includes/functions.php');
?>
	<script type="text/javascript" src="/_assets/_js/events/registration.js"></script>

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

<?
	if ($_GET['step'] == 2 && $_SESSION['register']['id']) {
	?>
					<h1>Complete Your Registration</h1>
					
					<br />
					
					<p>Your registration information is displayed below. Please verify that the information is correctly displayed, then continue on to payment via PayPal.</p>
					
					<br />
					
					<dl>
					
						<dt><strong>Event:</strong></dt>
						<dd>
							<strong><?= $objEvent->getName() ?></strong><br />
							<?= $objEvent->getDatesDisplay() ?>
						</dd>
	
						<dt><strong>Price:</strong></dt>
						<dd><strong>$<?= number_format($objRegistration->getAmountPaid(), 2) ?></strong></dd>
						
						<dt>Package:</dt>
						<dd>
							(<?= $objRegistration->getNumberOfTickets() ?>) <?= $objPackage->getName() ?> - $<?= number_format($objPackage->getPrice(), 2) ?> / ticket
						</dd>
						
	<?
		$objUpgrade = new RegistrationUpgradeX();
		$objUpgrade->loadByRegistrationId($objRegistration->getId());
		
		if (count($objUpgrade)) {
		?>
						<dt>Upgrades:</dt>
						<dd>
		<?
			while ($objUpgrade->loadNext()) {
			?>
							(<?= $objUpgrade->getQuantity() ?>) <?= $objUpgrade->getUpgradeName() ?> - $<?= number_format($objUpgrade->getUpgradePrice(), 2) ?> each
			<?
			}
		?>
						</dd>
		<?
		}
	?>
						
						<dt>Ticket Number:</dt>
						<dd><?= $objRegistration->getRegistrationCode() ?></dd>
						
					</dl>
					
					<br />
					
					<dl>
						
						<dt>First Name:</dt>
						<dd><?= $objRegistration->getFirstName() ?></dd>
						
						<dt>Last Name:</dt>
						<dd><?= $objRegistration->getLastName() ?></dd>
						
						<dt>Address:</dt>
						<dd>
							<?= $objRegistration->getAddressDisplay() ?>
						</dd>
						
						<dt>Email Address:</dt>
						<dd><?= $objRegistration->getEmailAddress() ?></dd>
	<?
		if ($objRegistration->getPhoneNumber()) {
		?>
						<dt>Phone Number:</dt>
						<dd>
							<?= $objRegistration->getPhoneNumberDisplay() ?> (Home)<br />
						</dd>
		<?
		}
	?>
					</dl>
					
					<br />
					
					<dl>
	<?
		$objField = new RegistrationFieldValueX();
		$objField->loadByRegistrationId($objRegistration->getId());
		
		while ($objField->loadNext()) {
		?>
						<dt><?= $objField->getFieldName() ?>:</dt>
						<dd><?= $objField->getValueDisplay() ?></dd>
		<?
		}
	?>
						
					</dl>
					
					<div class="clear"></div>
					
					<br />
	
<?
	if (1) {
	?>				
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
						<input type="hidden" name="business" value="UT69MYKGQC7KJ"/>
	<?
	} else {
	?>
						<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
						<input type="hidden" name="business" value="MH4SC7MKLQ83U">
	<?
	}
?>				
						<input type="hidden" name="cmd" value="_xclick" />

						<input type="hidden" name="lc" value="US" />
						<input type="hidden" name="bn" value="BMOREAroundTown_BuyNow_WPS_US" />
						
						<input type="hidden" name="item_name" value="<?= $objEvent->getName() ?>, <?= $objPackage->getName() ?>" />
						<input type="hidden" name="item_number" value="<?= $objRegistration->getRegistrationCode() ?>" />
						<input type="hidden" name="custom" value="<?= $objRegistration->getRegistrationCode() ?>" />
						<input type="hidden" name="amount" value="<?= number_format($objRegistration->getAmountPaid(), 2) ?>" />
						<input type="hidden" name="tax_rate" value="0.000" />
						<input type="hidden" name="shipping" value="0.00" />
						<input type="hidden" name="currency_code" value="USD" />
						
						<input type="hidden" name="button_subtype" value="services" />
						<input type="hidden" name="no_note" value="1" />
						<input type="hidden" name="no_shipping" value="1" />
						<input type="hidden" name="rm" value="1" />
						
						<input type="hidden" name="return" value="http://www.bmorearoundtown.com/events/complete.php?code=<?= $objRegistration->getRegistrationCode() ?>" />
						<input type="hidden" name="rm" value="1" />
						<input type="hidden" name="cancel_return" value="http://www.bmorearoundtown.com/events/cancel.php?code=<?= $objRegistration->getRegistrationCode() ?>" />
						<input type="hidden" name="notify_url" value="http://www.bmorearoundtown.com/events/notify.php" />
						
						<input type="hidden" name="image_url" value="http://bmorearoundtown.com/_assets/_images/bmorearoundtown129x90.png" />
						
						<input type="hidden" name="first_name" value="<?= $objRegistration->getFirstName() ?>" />
						<input type="hidden" name="last_name" value="<?= $objRegistration->getLastName() ?>" />
						<input type="hidden" name="address1" value="<?= $objRegistration->getAddress1() ?>" />
						<input type="hidden" name="address2" value="<?= $objRegistration->getAddress2() ?>" />
						<input type="hidden" name="city" value="<?= $objRegistration->getCity() ?>" />
						<input type="hidden" name="state" value="<?= $objRegistration->getState() ?>" />
						<input type="hidden" name="zip" value="<?= $objRegistration->getZipCode() ?>" />
						<input type="hidden" name="email" value="<?= $objRegistration->getEmailAddress() ?>" />
						<input type="hidden" name="night_phone_a" value="<?= $objRegistration->getPhoneNumberDisplay() ?>" />
						
						<button class="uiButton">Pay Now &raquo;</button>
						
						<img alt="" border="0" src="https://www.paypalobjects.com/WEBSCR-640-20110429-1/en_US/i/scr/pixel.gif" width="1" height="1" />
						
					</form>

					
	<?
	} else {
	?>
	            
					<h1>Register For Event</h1>
					
					<br /><br />
					
					<div id="event-details">
					
						<span class="event-name"><?= $objEvent->getName() ?></span>
						
						<div class="third-x2">
	<?
		if ($objEvent->getHasLogo()) {
		?>
							<img src="<?= $objEvent->getLogoImageUrl() ?>" width="200" alt="<?= $objEvent->getName() ?>" id="event-logo" />
		<?
		}
	?>
						
							<p class="event-details"><?= nl2br($objEvent->getDescription()) ?></p>
						
						</div>
						
						<div class="third">
						
							<dl class="flat">
							
								<dt><h3>When</h3></dt>
								<dd>
								
									<span class="event-date"><?= $objEvent->getDatesDisplay() ?></span><br />
									
								</dd>
								
								<dt><h3>Where</h3></dt>
								<dd>
								
									<span class="event-location"><a href="http://maps.google.com/maps?q=<?= urlencode($objLocation->getAddressDisplay(true)) ?>" target="_blank"><?= $objEvent->getLocationName() ?></a></span><br />
									<?= $objLocation->getAddressDisplay() ?><br />
									
									<a href="http://maps.google.com/maps?q=<?= urlencode($objLocation->getAddressDisplay(true)) ?>" target="_blank"><img src="http://maps.google.com/maps/api/staticmap?markers=color:0x276FED|<?= urlencode($objLocation->getAddressDisplay(true)) ?>&zoom=14&size=177x177&maptype=roadmap&sensor=false" width="177" height="177" alt="<?= $objEvent->getLocationName() ?>" class="location-map" /></a>
																		
	<?
		if ($objLocation->getDescription()) {
		?>
									<p id="location-description"><?= $objLocation->getDescription() ?></p>
		<?
		}
	?>

								</dd>
								
							</dl>
													
						</div>
						
						<div class="clear"></div>
						
						<br /><br />
						
						<h2 id="register">Event Registration</h2>
						
	<?
		if (count($_SESSION['forms']['register']['errors'])) {
		?>
						<div class="errorAlert">
						
							<p>There <?= count($_SESSION['forms']['register']['errors']) > 1 ? 'were errors' : 'was an error' ?> with your registration. Please make the corrections listed below and try again.</p>
							
							<ul>
		<?
			foreach ($_SESSION['forms']['register']['errors'] as $strErrorMessage) {
			?>
								<li><?= $strErrorMessage ?></li>
			<?
			}
		?>
							</ul>
							
						</div>
		<?
		}
	?>

						<form action="action.register.php" method="post" class="validdate">
						
							<fieldset>
							
								<input type="hidden" name="eventId" value="<?= $objEvent->getId() ?>" />
								<input type="hidden" name="packageId" value="<?= $objPackage->getId() ?>" />
								<input type="hidden" name="eventCode" value="<?= $_GET['event'] ?>" />
							
								<dl>
								
									<dt>First Name:</dt>
									<dd>
										<input type="text" name="firstName" size="25" maxlength="35" value="<?= $_SESSION['forms']['register']['firstName'] ?>" class="uiText required" />
										<div class="error" id="advice-firstName" style="display: none;">Enter your first name</div>
									</dd>
									
									<dt>Last Name:</dt>
									<dd>
										<input type="text" name="lastName" size="25" maxlength="35" value="<?= $_SESSION['forms']['register']['lastName'] ?>" class="uiText required" />
										<div class="error" id="advice-lastName" style="display: none;">Enter your last name</div>
									</dd>
									
									<dt>Address:</dt>
									<dd>
										<input type="text" name="address1" size="35" maxlength="50" value="<?= $_SESSION['forms']['register']['address1'] ?>" class="uiText required" /><br />
										<input type="text" name="address2" size="35" maxlength="50" value="<?= $_SESSION['forms']['register']['address2'] ?>" class="uiText" />
										<div class="error" id="advice-address1" style="display: none;">Enter your address</div>
									</dd>
									
									<dt>City:</dt>
									<dd>
										<input type="text" name="city" size="25" maxlength="50" value="<?= $_SESSION['forms']['register']['city'] ?>" class="uiText required" />
										<div class="error" id="advice-city" style="display: none;">Enter your city</div>
									</dd>
									
									<dt>State:</dt>
									<dd>
										<select name="state" class="uiSelect required validate-selection">
											<option value="">- Select a state -</option>
											<option value="AL"<?= $_SESSION['forms']['register']['state'] == 'AL' ? ' selected="selected"' : '' ?>>Alabama</option> 
											<option value="AK"<?= $_SESSION['forms']['register']['state'] == 'AK' ? ' selected="selected"' : '' ?>>Alaska</option> 
											<option value="AZ"<?= $_SESSION['forms']['register']['state'] == 'AZ' ? ' selected="selected"' : '' ?>>Arizona</option> 
											<option value="AR"<?= $_SESSION['forms']['register']['state'] == 'AR' ? ' selected="selected"' : '' ?>>Arkansas</option> 
											<option value="CA"<?= $_SESSION['forms']['register']['state'] == 'CA' ? ' selected="selected"' : '' ?>>California</option> 
											<option value="CO"<?= $_SESSION['forms']['register']['state'] == 'CO' ? ' selected="selected"' : '' ?>>Colorado</option> 
											<option value="CT"<?= $_SESSION['forms']['register']['state'] == 'CT' ? ' selected="selected"' : '' ?>>Connecticut</option> 
											<option value="DE"<?= $_SESSION['forms']['register']['state'] == 'DE' ? ' selected="selected"' : '' ?>>Delaware</option> 
											<option value="DC"<?= $_SESSION['forms']['register']['state'] == 'DC' ? ' selected="selected"' : '' ?>>District Of Columbia</option> 
											<option value="FL"<?= $_SESSION['forms']['register']['state'] == 'FL' ? ' selected="selected"' : '' ?>>Florida</option> 
											<option value="GA"<?= $_SESSION['forms']['register']['state'] == 'GA' ? ' selected="selected"' : '' ?>>Georgia</option> 
											<option value="HI"<?= $_SESSION['forms']['register']['state'] == 'HI' ? ' selected="selected"' : '' ?>>Hawaii</option> 
											<option value="ID"<?= $_SESSION['forms']['register']['state'] == 'ID' ? ' selected="selected"' : '' ?>>Idaho</option> 
											<option value="IL"<?= $_SESSION['forms']['register']['state'] == 'IL' ? ' selected="selected"' : '' ?>>Illinois</option> 
											<option value="IN"<?= $_SESSION['forms']['register']['state'] == 'IN' ? ' selected="selected"' : '' ?>>Indiana</option> 
											<option value="IA"<?= $_SESSION['forms']['register']['state'] == 'IA' ? ' selected="selected"' : '' ?>>Iowa</option> 
											<option value="KS"<?= $_SESSION['forms']['register']['state'] == 'KS' ? ' selected="selected"' : '' ?>>Kansas</option> 
											<option value="KY"<?= $_SESSION['forms']['register']['state'] == 'KY' ? ' selected="selected"' : '' ?>>Kentucky</option> 
											<option value="LA"<?= $_SESSION['forms']['register']['state'] == 'LA' ? ' selected="selected"' : '' ?>>Louisiana</option> 
											<option value="ME"<?= $_SESSION['forms']['register']['state'] == 'ME' ? ' selected="selected"' : '' ?>>Maine</option> 
											<option value="MD"<?= $_SESSION['forms']['register']['state'] == 'MD' ? ' selected="selected"' : '' ?>>Maryland</option> 
											<option value="MA"<?= $_SESSION['forms']['register']['state'] == 'MA' ? ' selected="selected"' : '' ?>>Massachusetts</option> 
											<option value="MI"<?= $_SESSION['forms']['register']['state'] == 'MI' ? ' selected="selected"' : '' ?>>Michigan</option> 
											<option value="MN"<?= $_SESSION['forms']['register']['state'] == 'MN' ? ' selected="selected"' : '' ?>>Minnesota</option> 
											<option value="MS"<?= $_SESSION['forms']['register']['state'] == 'MS' ? ' selected="selected"' : '' ?>>Mississippi</option> 
											<option value="MO"<?= $_SESSION['forms']['register']['state'] == 'MO' ? ' selected="selected"' : '' ?>>Missouri</option> 
											<option value="MT"<?= $_SESSION['forms']['register']['state'] == 'MT' ? ' selected="selected"' : '' ?>>Montana</option> 
											<option value="NE"<?= $_SESSION['forms']['register']['state'] == 'NE' ? ' selected="selected"' : '' ?>>Nebraska</option> 
											<option value="NV"<?= $_SESSION['forms']['register']['state'] == 'NV' ? ' selected="selected"' : '' ?>>Nevada</option> 
											<option value="NH"<?= $_SESSION['forms']['register']['state'] == 'NH' ? ' selected="selected"' : '' ?>>New Hampshire</option> 
											<option value="NJ"<?= $_SESSION['forms']['register']['state'] == 'NJ' ? ' selected="selected"' : '' ?>>New Jersey</option> 
											<option value="NM"<?= $_SESSION['forms']['register']['state'] == 'NM' ? ' selected="selected"' : '' ?>>New Mexico</option> 
											<option value="NY"<?= $_SESSION['forms']['register']['state'] == 'NY' ? ' selected="selected"' : '' ?>>New York</option> 
											<option value="NC"<?= $_SESSION['forms']['register']['state'] == 'NC' ? ' selected="selected"' : '' ?>>North Carolina</option> 
											<option value="ND"<?= $_SESSION['forms']['register']['state'] == 'ND' ? ' selected="selected"' : '' ?>>North Dakota</option> 
											<option value="OH"<?= $_SESSION['forms']['register']['state'] == 'OH' ? ' selected="selected"' : '' ?>>Ohio</option> 
											<option value="OK"<?= $_SESSION['forms']['register']['state'] == 'OK' ? ' selected="selected"' : '' ?>>Oklahoma</option> 
											<option value="OR"<?= $_SESSION['forms']['register']['state'] == 'OR' ? ' selected="selected"' : '' ?>>Oregon</option> 
											<option value="PA"<?= $_SESSION['forms']['register']['state'] == 'PA' ? ' selected="selected"' : '' ?>>Pennsylvania</option> 
											<option value="RI"<?= $_SESSION['forms']['register']['state'] == 'RI' ? ' selected="selected"' : '' ?>>Rhode Island</option> 
											<option value="SC"<?= $_SESSION['forms']['register']['state'] == 'SC' ? ' selected="selected"' : '' ?>>South Carolina</option> 
											<option value="SD"<?= $_SESSION['forms']['register']['state'] == 'SC' ? ' selected="selected"' : '' ?>>South Dakota</option> 
											<option value="TN"<?= $_SESSION['forms']['register']['state'] == 'TN' ? ' selected="selected"' : '' ?>>Tennessee</option> 
											<option value="TX"<?= $_SESSION['forms']['register']['state'] == 'TX' ? ' selected="selected"' : '' ?>>Texas</option> 
											<option value="UT"<?= $_SESSION['forms']['register']['state'] == 'UT' ? ' selected="selected"' : '' ?>>Utah</option> 
											<option value="VT"<?= $_SESSION['forms']['register']['state'] == 'VT' ? ' selected="selected"' : '' ?>>Vermont</option> 
											<option value="VA"<?= $_SESSION['forms']['register']['state'] == 'VA' ? ' selected="selected"' : '' ?>>Virginia</option> 
											<option value="WA"<?= $_SESSION['forms']['register']['state'] == 'WA' ? ' selected="selected"' : '' ?>>Washington</option> 
											<option value="WV"<?= $_SESSION['forms']['register']['state'] == 'WV' ? ' selected="selected"' : '' ?>>West Virginia</option> 
											<option value="WI"<?= $_SESSION['forms']['register']['state'] == 'WI' ? ' selected="selected"' : '' ?>>Wisconsin</option> 
											<option value="WY"<?= $_SESSION['forms']['register']['state'] == 'WY' ? ' selected="selected"' : '' ?>>Wyoming</option>
										</select>
										<div class="error" id="advice-state" style="display: none;">Select your state</div>
									</dd>
									
									<dt>Zip Code:</dt>
									<dd>
										<input type="text" name="zipCode" size="15" maxlength="10" value="<?= $_SESSION['forms']['register']['zipCode'] ?>" class="uiText required" />
										<div class="error" id="advice-zipCode" style="display: none;">Enter your zip code</div>
									</dd>
									
								</dl>
								
								<br />
								
								<dl>
								
									<dt>Email Address:</dt>
									<dd>
										<input type="text" name="emailAddress" size="35" maxlength="50" value="<?= $_SESSION['forms']['register']['emailAddress'] ?>" class="uiText required validate-email" />
										<div class="error" id="advice-emailAddress" style="display: none;">Enter your email address</div>
									</dd>
								
									<dt>Phone Number:</dt>
									<dd>
										<input type="text" name="phoneNumber" size="20" value="<?= $_SESSION['forms']['register']['phoneNumber'] ?>" class="uiText" rel="mask=us" />
									</dd>
								
								</dl>
								
	<?
		$objField = new RegistrationField();
		$objField->loadByEventId($objEvent->getId());
		
		if (!$objField->isValid()) {
		?>
								<br />
								
								<dl>
		<?
			while ($objField->loadNext()) {
			?>
									<dt><?= $objField->getName() ?></dt>
									<dd>
										<?= $objField->getFieldHtml($_SESSION['forms']['register']['fields'][$objField->getId()]) ?>
									</dd>
			<?
			}
		?>
								</dl>
		<?	
		}
	?>
								
								<br /><br />
								
	<?
		$objPackage = new PackageX();
		$objPackage->loadByRegistrationCode($_GET['event']);
		
		$dblPrice = $_SESSION['forms']['register']['packageQuantity'] ? $objPackage->getPrice() * $_SESSION['forms']['register']['packageQuantity'] : $objPackage->getPrice() * $objPackage->getTicketsPerPackage();
		
		$arrFields = array(
			'name'		=> 'Item Description',
			'price'		=> 'Price',
			'quantity'	=> 'Quantity'
		);
		$arrData = array();
		
		
		// Package options

		$arrData[$objPackage->getId() . 'p'] = array(
			'name'	=> '<span class="package-name">' . $objPackage->getName() . '</span><br /><div>' . nl2br($objPackage->getDescription()) . '</div><br /><span class="package-deadline">Time left to register: <strong' . ($objPackage->lessThanDayLeft() ? ' class="deadline-alert"' : '') . '>' . $objPackage->getRegistrationTimeLeft() . '</strong></span>',
			'price'	=> '<span class="package-price">$' . number_format($objPackage->getPrice(), 2) . '</span><span class="per-person">Per Ticket</span><input type="hidden" name="packagePrice" id="packagePrice" value="' . number_format($objPackage->getPrice(), 2) . '" />'
		);
		
		if ($objEvent->isSoldOut() || $objPackage->isSoldOut())
			$arrData[$objPackage->getId() . 'p']['quantity'] = '<span class="sold-out">Sold Out</span>';
		else {
		
			$arrData[$objPackage->getId() . 'p']['quantity'] = '
				<select name="packageQuantity" id="packageQuantity">';
				
			$intIncrementCount = $objPackage->getTicketsPerPackage() ? $objPackage->getTicketsPerPackage() : 1;
				
			for ($intI = $intIncrementCount; $intI <= min($objEvent->getMaxTicketsPerRegistration(), $objPackage->getAvailableTickets(), $objEvent->getAvailableTickets()); $intI += $intIncrementCount) {

				$arrData[$objPackage->getId() . 'p']['quantity'] .= '
					<option value="' . $intI . '"' . ($_SESSION['forms']['register']['packageQuantity'] == $intI ? ' selected="selected"' : '') . '>' . $intI . '</option>';
				}
				
			$arrData[$objPackage->getId() . 'p']['quantity'] .= '
				</select>';
				
		}
			
		
		
		// Upgrades
		
		$objUpgrade = new Upgrade();
		$objUpgrade->loadByEventId($objEvent->getId());
		
		while ($objUpgrade->loadNext()) {
		
			$dblPrice += ($objUpgrade->getPrice() * $_SESSION['forms']['register']['upgradeQuantity'][$objUpgrade->getId()]);
		
			$arrData[$objUpgrade->getId() . 'u'] = array(
				'name'	=> '<span class="package-name">' . $objUpgrade->getName() . '</span><br /><div>' . nl2br($objUpgrade->getDescription()) . '</div>',
				'price'	=> '<span class="package-price">$' . number_format($objUpgrade->getPrice(), 2) . '</span><input type="hidden" name="upgradePrice[' . $objUpgrade->getId() . ']" id="upgradePrice_' . $objUpgrade->getId() . '" value="' . number_format($objUpgrade->getPrice(), 2) . '" />'
			);
			
			if ($objEvent->isSoldOut())
				$arrData[$objUpgrade->getId() . 'u']['quantity'] = '<span class="sold-out">Sold Out</span>';
			else {
			
				$arrData[$objUpgrade->getId() . 'u']['quantity'] = '
					<select name="upgradeQuantity[' . $objUpgrade->getId() . ']" id="upgradeQuantity_' . $objUpgrade->getId() . '">';
					
				for ($intI = 0; $intI <= $objUpgrade->getMaxPerRegistrant(); $intI++)
					$arrData[$objUpgrade->getId() . 'u']['quantity'] .= '
						<option value="' . $intI . '"' . ($_SESSION['forms']['register']['upgradeQuantity'][$objUpgrade->getId()] == $intI ? ' selected="selected"' : '') . '>' . $intI . '</option>';
					
				$arrData[$objUpgrade->getId() . 'u']['quantity'] .= '
					</select>';
					
			}
		
		}
			
		$objGrid = new DataGrid('purchases', $arrFields, $arrData);
		
		echo $objGrid->draw();
	?>
								
								<div class="half">
	<?
		if ($objEvent->isSoldOut() || $objPackage->isSoldOut()) {
		?>
	
								<div class="clear"></div>
								
								<br /><br /><br /><br /><br />
		<?
		} else {
		?>
									<div id="discount-code-div">
		<?
			if ($_SESSION['forms']['register']['discountAmount']) {
				
				$objDiscount = new DiscountCode();
				$objDiscount->loadByDiscountCode($_SESSION['forms']['register']['discountCode'], $_SESSION['forms']['register']['eventId']);
				
				if (!$objDiscount->isValid())
					unset($objDiscount);
				else
					$dblPrice = $objDiscount->applyDiscount($dblPrice);
			
			}
		?>
										<div id="apply-discount-code"<?= $objDiscount ? ' style="display: none;"': '' ?>>
											Have a discount code?
											<input type="text" name="discountCode" id="discountCode" value="<?= $objDiscount ? $objDiscount->getCode() : '' ?>" size="15" class="uiText" />
											<a href="#" onclick="applyDiscountCode(event);" class="icon-accept">Apply</a>
											<div class="error" id="advice-discountCode" style="display: none;">Enter a discount code to be applied</div>
										</div>
										
										<div id="applied-discount-code"<?= $objDiscount ? '' : ' style="display: none;"' ?>>
											Discount code applied: <span id="discount-code"><?= $objDiscount ? $objDiscount->getCode() : '' ?></span> - <strong><span id="discount-code-amount"><?= $objDiscount ? ($objDiscount->getDiscountType() == 'fixed' ? '$' . number_format($objDiscount->getDiscount(), 2) : $objDiscount->getDiscount() . '%') : '' ?></span> off</strong>
											<input type="hidden" name="discountAmount" id="discountAmount" value="<?= $objDiscount ? $objDiscount->getDiscount() : '' ?>" />
											<input type="hidden" name="discountType" id="discountType" value="<?= $objDiscount ? $objDiscount->getDiscountType() : '' ?>" />
										</div>
										
									</div>
									
									&nbsp;
									
								</div>

								<div class="half">
	
									<div id="total-price-div">
									
										Total Price: <span id="total-price">$<?= number_format($dblPrice, 2) ?></span>
									
									</div>
									
								</div>
								
								<div class="clear"></div>
								
								<br /><br />
								
								<button class="uiButton">Continue &raquo;</button>
								
		<?
		}
	?>
								
							</fieldset>
							
						</form>
						
					</div>
					
	<?
	}
?>

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