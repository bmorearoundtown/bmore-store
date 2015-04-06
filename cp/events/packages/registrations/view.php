<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/config.php');
	
	/*--- Add additional CSS --- */
	
	$objRegistration = new Registration($_GET['registration']);
	$isModal = $_GET['modal'] ? (boolean) $_GET['modal'] : false;
	
	if( !$isModal ){
		require_once( 'header.php' );
	}
	
?>
	
	<? if( !$isModal ){ ?>
	
		<div class="page-header">
			<h1 style="font-size: 2.25em; color: #222;"><?= $objRegistration->getEventName() ?><small> ( <?= $objRegistration->getPackageName() ?> ) </small></h1>
		</div>
		
	<? } else { ?>
		
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<h4 style="font-size: 2em; color: #222;" class="modal-title" id="registraionInfoModal"><?= $objRegistration->getRegistrationTitle() ?></h4>
		</div>
		
		<div class="modal-body">
			
			<div class="row">
			
				<label class="col-sm-3">Name:</label>
				
				<div class="col-sm-9"><?= $objRegistration->getNameDisplay() ?></div>
				
			</div>

			<div class="row">
			
				<label class="col-sm-3">Address:</label>
				
				<div class="col-sm-9"><?= $objRegistration->getAddressDisplay() ?></div>
				
			</div>

			<div class="row">
			
				<label class="col-sm-3">Email:</label>
				
				<div class="col-sm-9"><?= $objRegistration->getPaypalEmailAddress() ?></div>
				
			</div>

			<div class="row">
			
				<label class="col-sm-3">Tel:</label>
				
				<div class="col-sm-9"><?= ($objRegistration->getPhoneNumber()) ? $objRegistration->getPhoneNumber() : "Not Provided" ?></div>
				
			</div>

			<div class="row">
			
				<label class="col-sm-3">Date Paid:</label>
				
				<div class="col-sm-9"><?= $objRegistration->getDatePaidDisplay() ?></div>
				
			</div>

			<div class="row">
			
				<label class="col-sm-3"># Packages Bought:</label>
				
				<div class="col-sm-9"><?= $objRegistration->getNumberOfTickets() ?></div>
				
			</div>

			<div class="row">
			
				<label class="col-sm-3">Amount Paid:</label>
				
				<div class="col-sm-9"><?= $objRegistration->getAmountPaidDisplay() ?></div>
				
			</div>

			<div class="row">
			
				<label class="col-sm-3">Confirmation Number:</label>
				
				<div class="col-sm-9"><?= $objRegistration->getConfirmationNumber() ?></div>
				
			</div>

			<div class="row">
			
				<label class="col-sm-3">Registraion Code:</label>
				
				<div class="col-sm-9"><?= $objRegistration->getRegistrationCode() ?></div>
				
			</div>

			<div class="row">
			
				<label class="col-sm-3">Ticket Id:</label>
				
				<div class="col-sm-9"><?= $objRegistration->getTicketId() ?></div>
				
			</div>

			<div class="row">
			
				<label class="col-sm-3">PayPal Response:</label>
				
				<div class="col-sm-9"><?= $objRegistration->getIpnResponse() ?></div>
				
			</div>
			
		</div>
		
		<div class="modal-footer">
		
			<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			
		</div>		
		
	<? } ?>
	
<?
	
	if( !$isModal ){
		require_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/cp/footer.php');
	}
	
?>