<div class="row">
	
	<div class="col-md-12">
	
		<div class="table-responsive">

	<?
		$objPackage = new PackageX();
		$objPackage->loadByEventId( $incomingEventId );
		
		if ( count( $objPackage ) ) {
	?>
		<p class="help-block">Adjust the configuration to add to cart by using the +/- buttons</p>
		
		<?
			
			$packageArrFields = array(
				
				'name'							=> 'Description',
				'price'							=> 'Price',
				'registrationDeadlineDate'		=> 'Date',
				'quantity'						=> 	'# Tickets'
					
			);
			
			$packageArrData = array();
			
			while ( $objPackage->loadNext() ) {
				
				$currPackageId = $objPackage->getId();
				$isSoldOut = $objPackage->isSoldOut();
				$isDeadlinePassed = $objPackage->isDeadlinePassed();
				
				if( !$isSoldOut && !$isDeadlinePassed ){
					
				$currQuantityHTML = '<div class="quantity-wrapper">
										<input id="quantity_' . $currPackageId . '" name="quantity_' . $currPackageId . '" value="0" type="text" class="form-control package-quantity pull-left cushion-right text-right" style="width: 60px;" readonly />
										<div class="btn-group cushion-left" style="100px">
											<button type="button" class="btn btn-success quantity-up">
												<i class="fa fa-plus"></i>
											</button>
											<button type="button" class="btn btn-danger quantity-down">
												<i class="fa fa-minus text-danger"></i>
											</button>
										</div>
									</div>';
				
				} else {
					
					if( $isSoldOut ) {
						
						// insert condition for can be sold at door
						$currQuantityHTML = '<p class="sold-out-wrapper text-danger text-center"><strong>SOLD OUT!</strong></p>';
						
					} else if( $isDeadlinePassed ){
						
						$currQuantityHTML = '<p class="sold-out-wrapper text-danger">Deadline has passed for registration.</p>';
						
					}
					
				}
				
				$currCheckboxHTML = '<div class="hide"><input id="select_' . $currPackageId . '" name="select_' . $currPackageId . '" type="checkbox" class="selectable-package" data-id="' . $currPackageId . '" disabled /></div>';
				
				$packageCost = $objPackage->getPrice() * $objPackage->getTicketsPerPackage();
				$perPackage = "";
				
				if( $objPackage->getTicketsPerPackage() > 1 ){
					
					$perPackage = " (" . $objPackage->getPrice() . " per person ) ";
				
				}
				
				$packageArrData[ $currPackageId ] = array(
					
					'name'					=> '<h4 class="package-name">' . $objPackage->getName() . '</h4><p class="text-muted">' . nl2br( $objPackage->getDescriptionWithLimit( 100 ) ) . '</p>',
					'price'						=> '<span class="quickadd-package-price">' . number_format( $packageCost, 2 ) . $perPackage . '</span>',
					'registrationDeadlineDate'	=> $objPackage->getEndDateDisplay(),
					'quantity'				=> 	$currQuantityHTML . $currCheckboxHTML
						
				);
				
			}
				
			$objGrid = new DataGrid( 'packages' , $packageArrFields, $packageArrData );
			
			echo $objGrid->draw();
	
		?>
		
		<?php } else { ?>									
	
			<p>No packages have been set up for this event!</p>
			
		<?php } ?>
		
		</div>
		
	</div>
	
</div>
	
<div class="row">
	
	<div class="col-md-12 text-right">
		
		<h3 style="margin-right: 20px;">Subtotal: $ <span id="configDollarTotal">0.00</span></h3>
	
	</div>
	
</div>