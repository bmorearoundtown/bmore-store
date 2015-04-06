<?php 
	
	$cartItems = 0;
	$checkoutTotal = 0;
	
	// since this can be loaded dynamically need to make sure session has started since it wont call config.php
	if (session_id() == '') {
		session_start();
		require_once('Mobile_Detect.php');
		$detect = new Mobile_Detect;
	}
	
	$urlRefer = base64_encode( $_SERVER['HTTP_REFERER'] );
	$hasPackages = false;
	
?>

<div id="topbar" class="navbar navbar-transparent orange topbar-hoverer" role="navigation">
	
	<div id="topbarCartContents" class="topbarCartContents">
	
		<div class="row">
		
			<div class="col-xs-12">
				
				<div class="max-height-container" style="overflow: auto; height: auto;">
				
					<ol class="list-unstyled">
		
						<?php if( isset( $_SESSION["packages"] ) ){
						    
							$hasPackages = count( $_SESSION["packages"] ) > 0;
							
							foreach ( $_SESSION["packages"] as $cartItem ) { 
								
								$packageId = $cartItem["id"];
								$packageName = $cartItem["name"];
								$packagePrice = $cartItem["price"];
								$packageQty = $cartItem["qty"];
								$packageSubtotal = $packagePrice * $packageQty;
								
								$cartItems += 1;
								$checkoutTotal += $packageSubtotal;
								
							?>
								
								<li class="cart-item">
									
									<h4><?= $packageName ?></h4>
									
									<label class="text-muted">Configuration: </label><span><?= $packagePrice ?> x <?= $packageQty ?></span>
									
									<br>
									
									<label class="text-success">Subtotal: </label><span><?= " $" . number_format( $packageSubtotal, 2 ) ?></span>
									
									<a href="/cart/action.cartUpdate.php?removep=<?= $packageId ?>&return_url=<?= $urlRefer ?>" class="remove-cart-item">
										<i class="fa fa-times fa-lg text-danger"></i>
									</a>
									
								</li>
							
							<?php } ?>
							
						<?php } else { ?>
						   
						   <li class="cart-item">
						   		
						   		<h4>Your shopping cart is empty</h4>
						   		
						   </li>
						
						<?php } ?>
						
					</ol>
				
				</div>
				
			</div>
			
		</div>

		<div class="row text-center">
		
			<div class="col-xs-12">
				
				<div class="btn-group" style="padding: 10px;">
					
					<?php if( $hasPackages ){ ?>
					
						<a href="/cart/action.cartUpdate.php?emptycart=1&return_url=<?= $urlRefer ?>" class="btn btn-danger"><i class="fa fa-times cushion-right"></i> Empty Cart</a>
					
					<?php } ?>
					
					<a href="/cart/checkout" class="btn btn-success"><i class="fa fa-check cushion-right"></i> Checkout</a>
				
				</div>
				
			</div>
		
		</div>
	
	</div>
	
	<?php if( $detect->isMobile() || $detect->isTablet() ){ ?>
	
		<div class="col-xs-6" style="padding: 10px 10px 5px 20px;">
			
			<a id="topbarCartDisplay" href="javascript: void(0)" style="color: #fff;"><i class="fa-fw fa fa-shopping-cart fa-lg topbar-cart"></i>Cart
				<span id="cartItemCount" class="text-bright items-count">( <?= $cartItems ?> Items )</span>
			</a>		
			
		</div>
	
		<div class="col-xs-6 text-center" style="padding: 10px 10px 5px 20px;">
			
			<a href="/cart/checkout" class="topbar-checkout" style="color: #fff;">
				<p class="insetting"><span class="hidden-xs">Checkout</span><i class="fa fa-check visible-xs pull-left"></i>
					<span id="checkoutDollarTotal" class="items-dollar">( <?= "$" . number_format( $checkoutTotal, 2 ) ?> )</span>
				</p>
			</a>	
			
		</div>
	
	<?php } else { ?>

		<div class="col-xs-6 visible-xs hidden-sm hidden-md hidden-lg" style="padding: 10px 10px 5px 20px;">
			
			<a id="topbarCartDisplay" href="javascript: void(0)" style="color: #fff;"><i class="fa-fw fa fa-shopping-cart fa-lg topbar-cart"></i>Cart
				<span id="cartItemCount" class="text-bright items-count">( <?= $cartItems ?> Items )</span>
			</a>		
			
		</div>
	
		<div class="col-xs-6 visible-xs hidden-sm hidden-md hidden-lg text-center" style="padding: 10px 10px 5px 20px;">
			
			<a href="/cart/checkout" class="topbar-checkout" style="color: #fff;">
				<p class="insetting"><span class="hidden-xs">Checkout</span><i class="fa fa-check visible-xs pull-left"></i>
					<span id="checkoutDollarTotal" class="items-dollar">( <?= "$" . number_format( $checkoutTotal, 2 ) ?> )</span>
				</p>
			</a>	
			
		</div>
		
		<ul class="nav navbar-nav hidden-xs">
		
			<li>
				<a id="topbarCartDisplay" href="javascript: void(0)"><i class="fa-fw fa fa-shopping-cart fa-lg topbar-cart"></i>Cart
					<span id="cartItemCount" class="text-bright items-count">( <?= $cartItems ?> Items )</span>
				</a>
			</li>
			<li>
				<a href="/cart/checkout" class="topbar-checkout">
					<p class="insetting"><span class="hidden-xs">Checkout</span><i class="fa fa-check visible-xs pull-left cushion-right"></i>
						<span id="checkoutDollarTotal" class="items-dollar">( <?= "$" . number_format( $checkoutTotal, 2 ) ?> )</span>
					</p>
				</a>
			</li>
			
		</ul>
	
	<?php } ?>

</div>