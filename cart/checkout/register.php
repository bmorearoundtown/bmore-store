<?php
	
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/config.php');
		
	/*--- head and header includes ---*/
	
	require_once( "head.php" ); // includes body tag
	
	require_once( "header.php" );
	
	$url = base64_encode( full_url( $_SERVER ) );
	
	$paypalSource = $isProduction ? "https://www.paypal.com/cgi-bin/webscr" : "https://www.sandbox.paypal.com/cgi-bin/webscr";
	$paypalBusiness = $isProduction ? "UT69MYKGQC7KJ" : "scottymitch88@gmail.com";
?>

<div class="container">

	<div id="registrationWrapper" class="highlight-layer">

		<div class="row">

			<div class="col-md-12">

					<form id="registrationForm" class="form form-horizontal" role="form">

						<?php include( "quickCheckout.php" ) ?>
								
						<!--		
						<div id="wizardInfo">

							<ul class="nav nav-wizard" data-tabs="tabs">
	
								<li class="active" data-next="2" data-prev="1" data-step="1">
									<a href="#step1billing" class="tab-toggler" role="tab">Step 1 - Billing</a>
								</li>
	
								<li data-next="3" data-prev="1" data-step="2">
									<a href="#step2payment" class="tab-toggler" role="tab">Step 2 - Payment Method </a>
								</li>
	
								<li data-next="3" data-prev="2" data-step="3">
									<a href="#step3confirm" class="tab-toggler" role="tab">Step 3 - Confirmation</a>
								</li>
	
							</ul>
						
							<div class="tab-content">
										
								<div id="step1billing" class="tab-pane active">
									
									<?php // include( "billingInformationForm.php" ) ?>
									
								</div>
	
								<!--<div id="step2payment" class="tab-pane">
									
									<?php //include( "paymentMethod.php" ) ?>
	
								</div>
														
								<div id="step3confirm" class="tab-pane">
								
									<?php //include( "orderSummary.php" ) ?>
	
								</div> 
	
							</div>

						</div>-->

				</form>

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

<div style="display: none">
	
	<form id="paypalForm" name="paypalForm" action="<?= $paypalSource ?>" method="post">
		
		<input type="hidden" name="business" value="<?= $paypalBusiness ?>">
		<input type="hidden" name="cmd" value="_cart" />		
		<input type="hidden" name="lc" value="US" />
		<input type="hidden" name="upload" value="1">
		<input type="hidden" name="tax_cart" value="0">
		<input type="hidden" name="currency_code" value="USD" />
		<input type="hidden" name="button_subtype" value="services" />
		<input type="hidden" name="no_note" value="1" />
		<input type="hidden" name="no_shipping" value="1" />
		<input type="hidden" name="rm" value="1" />
		<input type="hidden" name="notify_url" value="<?= $_SERVER['SERVER_NAME'] . "/cart/checkout/ipnNotification.php" ?>" />
		<input type="hidden" name="cpp_logo_image" value="<?= $_SERVER['SERVER_NAME'] . "/_assets/_css/_images/bmorearoundtown150x105.png" ?>" />
		<img alt="" border="0" src="https://www.paypalobjects.com/WEBSCR-640-20110429-1/en_US/i/scr/pixel.gif" width="1" height="1" />
		
	</form>
									
</div>

<div id="transaction-overlay">

	<h1>Transaction is processing...please wait <br> <i class="fa fa-refresh fa-spin cushion-top"></i></h1>
	
</div>

</body>

</html>
