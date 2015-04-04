<?php 
	
	$checkoutTotal = 0;
	$cartItems = 0;
	$packageCount = 0;
?>

<div class="row">

	<div class="col-md-12">

		<div id="orderSummaryContainer" class="well well-lg" style="color: #222; padding: 30px 30px;">
			
			<div style="padding: 30px 30px;">
				
				<h2 class="collapse-box">
					
					Order Summary

					<div class="pull-right">

						<a href="javascript:void(0)" class="btn btn-lg btn-default back-step"> 
							<i class="fa fa-angle-double-left cushion-right"></i>Go Back to Step 2
						</a>
						
						<button type="button" class="btn btn-lg btn-success buy-now"><i class="fa fa-check cushion-right"></i>BUY NOW</button>
						
					</div>
									
				</h2>
				
				<h4 class="help-block text-muted">Please review all the information provided entered below before clicking "BUY NOW".</h4>
				
				<div class="form-group">
				
					<div class="col-md-12">
						
						<div id="itemSummary">
							
							<h2>Package Summary</h2>
							
							<?php  if( !$detect->isMobile() ){ ?>
							
								<div class="table-responsive">
								
									<table class="table table-striped table-hover table-bordered">
										
										<thead>
											<tr>
												<th style="width: 30em;">Package Name</th>
												<th>Package Price</th>
												<th style="width: 8em;">Quantity</th>
												<th>Subtotal</th>
											</tr>
										<thead>
										
										<tbody>
										
											<?php if( isset( $_SESSION["packages"] ) ){
										    
												foreach ( $_SESSION["packages"] as $cartItem ) { 
													
													$packageId = $cartItem["id"];
													
													$currentPackage = new Package( $packageId );
													$packageEvent = new Event( $currentPackage->getEventId() );
													
													$packageName = $cartItem["name"];
													$packagePrice = $cartItem["price"];
													$packageQty = $cartItem["qty"];
													$packageSubtotal = $packagePrice * $packageQty;
													
													$packageCount += 1;
													$cartItems += $packageQty;
													$checkoutTotal += $packageSubtotal;										
											?>
											
														<tr>
															<td><h4><?= $packageName ?></h4><p><?= $currentPackage->getDescription() ?></p></td>
															<td><?= "$" . number_format( $packagePrice, 2 ) ?></td>
															<td><?= $packageQty ?></td>
															<td><?= "$" . number_format( $packageSubtotal, 2 ) ?></td>
														</tr>	
											
												<?php } ?>
											
											<?php } ?>
																	
										</tbody>
										
										<tfoot>
											<tr>
												<td colspan="3" class="text-right"><strong>Items:</strong></td>
												<td>
													<p>Packages: <?= $packageCount ?></p>
													<p>Tickets: <?= $cartItems ?></p>
												</td>
											</tr>								
											<tr>
												<td colspan="3" class="text-right"><strong>Grand Total:</strong></td>
												<td><?= "$" . number_format( $checkoutTotal, 2 ) ?></td>
											</tr>
										</tfoot>
									</table>
								
								</div>
							
							<?php } else { ?>
								
								<p>Mobile Display Version</p>
								
							<?php } ?>
							
						</div>
						
					</div>
				
				</div>
				
				<div class="form-group">
				
					<div class="col-md-6">
						
						<div id="billingInfoSummary">
							
							<h2 style="margin-bottom: 20px;">Billing Information Summary</h2>
							
							<div id="billingInfoSummaryLoader"></div>
							
						</div>
						
					</div>
					
					<div class="col-md-6">

						<div id="paymentMethodSummary">
							
							<h2 style="margin-bottom: 20px;">Payment Method Summary</h2>
							
							<div id="paymentMethodSummaryLoader"></div>
							
						</div>
						
					</div>
				
				</div>
				
				<hr>
				
				<div class="form-group">
					
					<div class="col-md-12 text-center">

						<a href="javascript:void(0)" class="btn btn-lg btn-default back-step"> 
							<i class="fa fa-angle-double-left cushion-right"></i>Go Back to Step 2
						</a>
						
						<button type="button" class="btn btn-lg btn-success buy-now"><i class="fa fa-check cushion-right"></i>BUY NOW</button>
						
						<p class="help-block text-muted">You only need to click the "BUY NOW" button once. Multiple clicks could cause some transaction issues.</p>
						
					</div>
					
				</div>
				
			</div>
			
		</div>

	</div>

</div>