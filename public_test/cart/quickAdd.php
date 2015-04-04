<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/config.php');
	
	$incomingEventId = $_GET['event'];
	
?>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 style="font-size: 2em; color: #222;" class="modal-title">Availiable Packages</h4>
</div>

<div class="modal-body">
	
	<?php 
	
	if( $detect->isMobile() || $detect->isTablet() ){ 
		
		include("../cart/packageSelectListing.php");
		
	} else {
	
	?>
	
		<form id="quickAddToCartForm" class="form" role="form">
		
			<input type="hidden" name="eventId" value="<?= $incomingEventId ?>" />
			<input type="hidden" name="oper" value="add" />
			<input type="hidden" name="return_url" value="<?= $url ?>" />
			<input type="hidden" name="ajax" value="true" />
			
			<div class="row">
				
				<div class="col-xs-12">
					
					<?php include("../cart/packageListingTable.php"); ?>
					
				</div>
		
			</div>
			
		</form>
		
	<?php } ?>
	
</div>

<?php  if( !$detect->isMobile() && !$detect->isTablet() ){ ?>

<div class="modal-footer">
	
	<button id="quickAddToCartButton" type="button" class="btn btn-success"><i class="fa fa-plus"></i> Add To Cart</button>
	
	<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
	
</div>

<?php } ?>