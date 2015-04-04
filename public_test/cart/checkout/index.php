<?php
	
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/config.php');
	
	require_once( "head.php" ); // includes body tag
	
	require_once( "header.php" );
	
	$checkoutTotal = 0;
	$cartItems = 0;
	
	$url = base64_encode( full_url( $_SERVER ) );
	
?>

<div class="container">
	
	<div class="row">
		
		<div id="checkoutDetailsWrapper" class="wrapper highlight-layer">
		
			<div class="col-md-12">
				
				<h1>Review Packages 
					
					<?php  if( !$detect->isMobile() ){ ?>
					
						<a href="/events/index.php" class="btn btn-lg btn-default pull-right"><i class="fa fa-shopping-cart"></i> Continue Shopping</a>
					
					<?php } ?>
					
				</h1>
				
				<hr>
				
				<?php 

				if( $detect->isMobile() || $detect->isTablet() ){ 
					
					include("checkoutMobileForm.php");
					
				} else {
					
					include("checkoutTableForm.php");
				}
				
				?>

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