<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/config.php');
	
	/*--- Add additional CSS --- */
	$GLOBALS['config']->addPageCSSManual( 'index.css' );
	
	/*--- Build Recent Registrations Grid ---*/
	
	$arrFields = array(
		'details'		=> 'Details',
		'eventName'		=> 'Event',
		'name'			=> 'Registration Name',
		'date'			=> 'Date Paid',
		'amountPaid'	=> 'Amount Paid',
		'numTickets'	=> '# Tickets',
		'sendEmail'				=> 	'Email Confirmation'
	);
	$arrData = array();
	
	$objRegistration = new RegistrationX();
	$objRegistration->loadRecentRegistrations(50);
	
	while ($objRegistration->loadNext()){
		$arrData[$objRegistration->getId()] = array(
			'details'		=> '<div class="text-center"><a href="/cp/events/packages/registrations/view.php?modal=true&registration=' . $objRegistration->getId() . '" class="open-registration-modal" ><i class="fa fa-eye text-muted"></i></a></div>',
			'eventName'		=> '<a href="/cp/events/view.php?event=' . $objRegistration->getEventId() . '">' . $objRegistration->getEventName() . '</a>',
			'name'			=> $objRegistration->getNameDisplay(),
			'date'			=> $objRegistration->getDatePaidDisplay(),
			'amountPaid'	=> $objRegistration->getAmountPaidDisplay(),
			'numTickets'	=> $objRegistration->getNumberOfTickets(),
			'sendEmail'	=> '<a href="/cp/events/packages/registrations/action.sendConfirmationEmail.php?registration=' . $objRegistration->getId() . '">Resend Confirmation Email</a>'
		);
	}
	
	$objRegistrationsGrid = new DataGrid('registrations', $arrFields, $arrData, '', 'There are no recent registrations');
	
	/*--- Load Current/Upcoming Events ---*/
	
	$objEvent = new EventX();
	$objEvent->loadUpcomingEvents(true, 8);

	require_once('header.php');

?>
	
	<!--- Loaded remotely --->
	<div id="registration-info-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="registraionInfoModal" aria-hidden="true">		
		<div class="modal-dialog modal-lg">		
			<div class="modal-content"></div>			
		</div>		
	</div>
	
	<div class="page-header">
		<h1>Administration Panel</h1>
	</div>
	
	<section class="col-md-7">

		<div class="panel panel-default">

			<div class="panel-heading">
				<h3 class="panel-title">Recently Completed Registrations</h3>
			</div>
			
			<div class="table-responsive">
				<?= $objRegistrationsGrid->draw() ?>
			</div>
			
		</div>
		
	</section>
	
	<section class="col-md-5">

	
		<div class="panel panel-warning">

			<div class="panel-heading">
				
				<h3 class="panel-title">Current and Upcoming Events</h3>
			
			</div>

			<div class="list-group">
			
				<? while ($objEvent->loadNext()) { ?>
					
					<div class="list-group-item">
					
						<h4 class="list-group-item-heading">
							<a href="/cp/events/view.php?event=<?= $objEvent->getId() ?>" style="margin-bottom: 5px;"><?= $objEvent->getName() ?>
								<small> on <?= date('M j, Y', $objEvent->getStartDate()) ?><?= $objEvent->getIsMultiDay() ? ' &ndash; ' . date('M j, Y', $objEvent->getEndDate()) : '' ?></small>
							</a>
						</h4>
						
						<div class="clearfix"></div>
						
						<h5 class="text-success"><strong>Registrations:</strong></h5>
						
						<div class="list-group-item-text" style="padding-left: 10px;">

							<? if ($objEvent->getTotalRegistrations()) { ?>
	
								<p>
									<i class="fa fa-user" style="margin-right: 7px;"></i>
									<?= $objEvent->getTotalRegistrations() ?> total [<?= $objEvent->getTotalTickets() ?> ticket(s)]
								</p>
		
								<p>
									<i class="fa fa-usd" style="margin-right: 10px;"></i>
									<?= $objEvent->getTotalPaidRegistrations() ?> paid [<?= $objEvent->getTotalPaidTickets() ?> ticket(s)]
								</p>
								
							<? } else { ?>
							
								<span class="text-warning">No registrations</span>
								
							<? } ?>
						
						</div>

						<div class="list-group-item-text" style="padding-left: 10px;">

							<? if ($objEvent->getTotalRegistrations()) { ?>
	
								<strong>Grand Total Paid: <?= $objEvent->getTotalAmountPaidRegistrations() ?></strong>
								
							<? } ?>
						
						</div>
						
						<a href="/cp/events/temp_registration_list.php?event=<?=  $objEvent->getId() ?>" class="hidden-xs hidden-sm text-muted" style="position: absolute; right: 10px; bottom: 10px;"><i class="fa fa-print cushion-right"></i> Print Event Labels</a>
						
					</div>
					
				<? } ?>
				
				<a href="/cp/events/index.php" class="list-group-item">
					<p class="list-group-item-text">View all events &raquo;</p>
				</a>
				
			</div>
			
		</div>
	
	</section>
	
	<script>
		
		var Dashboard = (function(){
			
			var $registrationModal = null;
			
			var afterModalLoad = function( htmlPage, status ){
				
				if( status !== "success" ){
					alert( "Error occurred while trying to load the data!" );
				}
				
				return;
			};
			
			var loadRegistrationModal = function( e ){
				
				e.preventDefault(); // stop loading of current href
				
				// load content dynamically using jquery load based on elements href attribute
				var $currentTarget = $( e.currentTarget );
				var $content = $registrationModal.find( ".modal-content" );
				var navigationHREF = $currentTarget.attr( "href" );
				
				$content.load( navigationHREF, afterModalLoad );
				
				$registrationModal.modal( "show" );
				
				return;
			};
			
			var bindEvents = function(){
				
				$( ".open-registration-modal" ).on( "click", loadRegistrationModal );
				
				return;
			};
			
			var bindDOM = function(){
				
				$registrationModal = $( "#registration-info-modal" );
				
				return;
			}
			
			var init = function(){
				
				bindDOM();
				
				bindEvents();
				
				return;
			};
			
			return {
				
				"init": init
				
			}
		}());
		
		$( function(){
			
			Dashboard.init();
			
		});
		
	</script>