<?php 

	$incomingEvent = $_GET['event'];
	
	$objEventSelectOptionHelper = new EventX();
	$objEventSelectOptionHelper->loadByRegistrationCode( $incomingEvent );
	
	if( count( $objEventSelectOptionHelper ) == 0 ){
		
		$objEventSelectOptionHelper = new EventX( $incomingEvent );
		
		if( count( $objEventSelectOptionHelper ) == 0 ){
			
			die( "Error trying to load.");
			
		}
		
	}
	
	$objPackage = new Package();
	$objPackage->loadAvailablePackagesByEventRegistrationCode( $objEventSelectOptionHelper->getRegistrationCode() );
	
	$url = base64_encode( $_SERVER[REQUEST_URI] );
	
?>

<div class="package-options-select row">
		
	<?php if ( count( $objPackage ) ) { ?>
	
	<form id="selectAddToCartForm" class="form" role="form">
		<input type="hidden" name="eventId" value="<?= $objEventSelectOptionHelper->getId() ?>" />
		<input type="hidden" name="oper" value="add" />
		<input type="hidden" name="return_url" value="<?= $url ?>" />
		<input type="hidden" name="ajax" value="true" />
		
		<div class="form-group">
		
			<label for="packageSelectList">Package Options:</label>
			
			<select id="packageSelectList" name="id" class="form-control input-lg" style="width: 100%;" required>
			
				<option value=""> - Select an available package -</option>
				
			<?php while ( $objPackage->loadNext() ) { ?>
				
					<option value="<?= $objPackage->getId() ?>" data-price="<?= $objPackage->getPrice()  * $objPackage->getTicketsPerPackage() ?>"><?= $objPackage->getName() ?> - <?= "$" . number_format( $objPackage->getPrice()  * $objPackage->getTicketsPerPackage(), 2 ) ?></option>
					
			<?php } ?>
			
			</select>
		
		</div>
		
		<div class="form-group quantity-wrapper">
		
			<label for="packageQuantityInput" class="pull-left">Quantity:</label>
			
			<input id="packageQuantityInput" name="quantity" type="text" value="0" 
				class="form-control input-lg pull-left cushion-right cushion-left" style="width: 100px;" required />
			
			<div class="btn-group">
				<button type="button" class="btn btn-lg btn-success quantity-up"><i class="fa fa-plus"></i></button>
				<button type="button" class="btn btn-lg btn-danger quantity-down"><i class="fa fa-minus text-danger"></i></button>
			</div>
		
		</div>
			
	</form>
	
	<?php } else { ?>
		
		<h4 class="text-danger" style="padding: 20px 0 0 20px;">No packages are available for this event.</h4>
		
	<?php } ?>
	
</div>

<?php if ( count( $objPackage ) ) { ?>

	<hr>
	
	<div class="row">
	
		<div class="configurationSummary">
		
			<div class="col-md-6 text-center">
				
				<div class="row">
				
					<label class="hidden-xs">Total:</label>
					
					<br>
					
					<div class="cushion-top">
						
						<p id="configDollarTotal" style="font-size: 1.7em;">$ 0.00</p>
						
					</div>
				
				</div>
				
			</div>
	
			<div class="col-md-6 text-center" style="padding-top: 20px; padding-bottom: 20px;">
				
				<div class="btn-group">
				
					<button id="packageAddToCartEventViewButton" type="button" class="btn btn-lg btn-success squared"><i class="fa fa-plus"></i> Add To Cart</button>
				
				</div>
				
			</div>
				
		</div>
	
	</div>

<?php } ?>

	
	
