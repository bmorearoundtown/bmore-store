<style>

	#checkoutTableForm ol .cart-item label {
		font-size: 1.3em;
	}

	#checkoutTableForm ol .cart-item p {
		font-size: 1.1em;
	}
	
</style>

<form id="checkoutTableForm" class="form form-horizontal" role="presentation">
	<input type="hidden" name="return_url" value="<?= $url ?>" />
	<input type="hidden" name="oper" value="changeQty" />
	<input type="hidden" name="ajax" value="true" />
	
	<div class="col-xs-12">
	
		<ol class="list-unstyled">
	
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
					
				?>
				
				<li class="cart-item" data-id="<?= $packageId ?>" data-quantity="<?= $packageQty ?>" style="border-bottom: 1px solid white;">
					
					<div class="form-group">
						
						<h4><?= $eventName  . " - " . $packageName ?><h4>
						
						<p class="text-muted"><?= $currentPackage->getDescription( 200 ) ?></p>
						
					</div>
					
					<div class="form-group">
						
						<label class="col-xs-5">Price:</label>
						
						<div class="col-xs-7">
							<p><strong class="text-muted"><?= "$" . number_format( $packagePrice, 2 ) ?></strong></p>
						</div>
						
					</div>
	
					<div class="form-group">
						
						<div class="quantity-wrapper">
							
							<label class="col-xs-4">Qty:</label>
							
							<div class="col-xs-8">
							
								<input type="text" class="form-control text-right pull-left cushion-right" name="quantity_<?= $packageId ?>" value="<?= $packageQty ?>" style="width: 60px;"/>
				
								<div class="btn-group">
									<button type="button" class="btn btn-success quantity-up"><i class="fa fa-plus"></i></button>
									<button type="button" class="btn btn-danger quantity-down"><i class="fa fa-minus text-danger"></i></button>
								</div>
							
							</div>
							
						</div>
						
					</div>
	
					<div class="form-group">
						
						<label class="col-xs-5">Subtotal:</label>
						
						<div class="col-xs-7">
							
							<p class="cushion-top">
								<strong class="text-muted">
								
									<?= $packagePrice ?> x <?= $packageQty ?> = <?= " $" . number_format( $packageSubtotal, 2 ) ?>
									
									<?php if( !$packageQty ){ ?>
										
										<span class="text-danger">No quantity selected</span>
										
									<?php } ?>						
								</strong>
							</p>
							
						</div>
						
					</div>
					
					<div class="form-group text-center cushion-top">
	
						<a href="/cart/action.cartUpdate.php?removep=<?= $packageId ?>&return_url=<?= $urlRefer ?>" class="remove-cart-item text-danger" style="margin-right: 10px;">
							<i class="fa fa-trash fa-3x"></i>
						</a>
						
						<a id="checkoutCartUpdate" href="javascript:void(0);" class="checkout-cart-update text-info cushion-left"><i class="fa fa-refresh fa-3x"></i></a>
	
					</div>
					
				</li>
				
			<?php } ?>
				
			<?php } else { ?>
			   
			   <li class="cart-item text-center" style="border-bottom: 1px solid white; padding: 10px 0 20px 0;">
			   		
			   		<h4 class="text-danger">Your shopping cart is empty</h4>
			   		
			   </li>
			
			<?php } ?>
			
		</ol>
	
		<p class="text-center">
			
			<span style="font-size: 1.75em; font-weight: bold; font-family: 'Oswald';">
				GRAND TOTAL <small class="text-muted" style="font-size: 75%; font-weight: 300;"><?= $cartItems ?> Package(s)</small>
			</span>
			
			<br>
			
			<span class="text-success" style="font-size: 2em; font-weight: bold; font-family: 'Oswald';">
				<?= "$" . number_format( $checkoutTotal, 2 ) ?>
			</span>
			
		</p>
			
		<p class="text-center">
			
			<a href="/cart/checkout/register.php" class="btn btn-lg btn-success cushion-top"><i class="fa fa-check"></i> Complete Checkout</a>
			
			<br>
			
			<?php if( $hasPackages ) { ?>
			<a href="/events/index.php" class="btn btn-lg btn-info cushion-top"><i class="fa fa-shopping-cart"></i> Continue Shopping</a>
			<?php } ?>
		</p>
	
	</div>
						
</form>