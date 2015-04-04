<form id="checkoutTableForm" class="form" role="presentation">
	<input type="hidden" name="return_url" value="<?= $url ?>" />
	<input type="hidden" name="oper" value="changeQty" />
	<input type="hidden" name="ajax" value="true" />
	
	<div class="table-responsive">
		
		<table id="checkoutTable" class="table table-striped table-bordered table-hover">
		
			<thead>
				
				<tr>
					<th>Event Image</th>
					<th style="width: 30em;">Name</th>
					<th>Price / Package</th>
					<th style="width: 8em;">Quantity</th>
					<th>Configuration Total</th>
					<th>Remove</th>
				</tr>
				
			</thead>
			
			<tbody>
				
				<?php if( isset( $_SESSION["packages"] ) ){
				    
					$hasPackages = count( $_SESSION["packages"] ) > 0;
					
					foreach ( $_SESSION["packages"] as $cartItem ) { 
						
						$packageId = $cartItem["id"];
						
						$currentPackage = new Package( $packageId );
						$packageEvent = new Event( $currentPackage->getEventId() );
						
						$eventName = $packageEvent->getName();
						$packageName = $cartItem["name"];
						$packagePrice = $cartItem["price"];
						$packageQty = $cartItem["qty"];
						$packageSubtotal = $packagePrice * $packageQty;
						
						$cartItems += 1;
						$checkoutTotal += $packageSubtotal;

						$numTicketsPerPackage = $currentPackage->getTicketsPerPackage();
						
					?>
						
						<tr class="cart-item" data-id="<?= $packageId ?>" data-quantity="<?= $packageQty ?>">
							
							<td>
								<a href="/events/view.php?event=<?= $packageEvent->getRegistrationCode() ?>" style="display: inline-block;">
									<img src="<?= $packageEvent->getLogoImageUrl() ?>" class="img-responsive checkout-image" />
									<span class="help-block text-muted">Click image to view event</span>
								</a>
							</td>
							
							<td>
								<h4><?= $eventName  . " - " . $packageName ?><h4>
								
								<p class="text-muted"><?= $currentPackage->getDescription( 200 ) ?></p>
								
							</td>
							
							<td><?= "$" . number_format( $packagePrice, 2 ) . " (#tickets: " . $numTicketsPerPackage . ")" ?></td>
							
							<td>
								
								<div class="quantity-wrapper">
								
									<input type="text" class="form-control text-right" name="quantity_<?= $packageId ?>" value="<?= $packageQty ?>" />

									<div class="btn-group cushion-top">
										<button type="button" class="btn btn-success quantity-up"><i class="fa fa-plus"></i></button>
										<button type="button" class="btn btn-danger quantity-down"><i class="fa fa-minus text-danger"></i></button>
									</div>
								
								</div>
								
							</td>
							
							<td>
								<?= $packagePrice ?> x <?= $packageQty ?> = <?= " $" . number_format( $packageSubtotal, 2 ) ?>
								
								<?php if( !$packageQty ){ ?>
									
									<p class="text-danger cushion-top"><strong>No quantity selected</strong></p>
									
								<?php } ?>
								
							</td>
							
							<td>
							
								<a href="/cart/action.cartUpdate.php?removep=<?= $packageId ?>&return_url=<?= $url ?>" 
									class="remove-cart-item" title="remove item from cart">
									<i class="fa fa-trash fa-2x text-danger"></i>
								</a>
								
							</td>
							
						</tr>
					
					<?php } ?>
					
				<?php } else { ?>
				   
				   <tr>
				   		
				   		<td colspan="6"><h4>Your shopping cart is empty</h4></td>
				   		
				   </tr>
				
				<?php } ?>
			
			</tbody>
			
			<tfoot>

				<tr>
					<td colspan="6" class="text-right">
					
						<p style="margin: 10px 0;">
							
							<span style="font-size: 2em; font-weight: bold; font-family: 'Oswald';">
								GRAND TOTAL <small class="text-muted" style="font-size: 75%; font-weight: 300;"><?= $cartItems ?> Package(s)</small> :
							</span>
							
							<span style="color: #222; margin-left: 30px; font-size: 2em; font-weight: bold; font-family: 'Oswald';">
								<?= "$" . number_format( $checkoutTotal, 2 ) ?>
							</span>
							
						</p>
						
						<p>
							<span class="text-muted" style="font-size: 1.2em; padding: 5px 10px 0 0;">Quantity changed? </span>
							<a id="checkoutCartUpdate" href="javascript:void(0);" style="font-size: 1.2em; padding: 5px 10px 0 0;" 
							class="checkout-cart-update text-info">Update cart</a>
						</p>
						
						<p style="margin: 12px 0;">
							
							<a href="/events/index.php" class="btn btn-lg btn-default"><i class="fa fa-shopping-cart"></i> Continue Shopping</a>
							
							<?php if( $hasPackages ) { ?>
							<a href="/cart/checkout/register.php" class="btn btn-lg btn-success"><i class="fa fa-check"></i> Checkout</a>
							<?php } ?>
							
						</p>
						
					</td>
				</tr>
				
			</tfoot>
			
		</table>
	
	</div>
	
</form>