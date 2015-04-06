<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/config.php');
	
	/*--- Add additional CSS --- */
	$GLOBALS['config']->addPageCSSManual( 'events/events.css' );
	$GLOBALS['config']->addPageCSSManual( 'events/view.css' );
	
	$objEvent = new EventX($_GET['event']);
	
	$buster = rand( 100000, 999999 );
	
	require_once( 'header.php' );
?>

	<ul id="quick-links" class="quick-links hidden-xs hidden-sm">
		<li><a href="#packageInformation" class="muted"><i class="fa fa-list"></i> View Packages</a></li>
		<li><a href="#registrationInformation" class="muted"><i class="fa fa-users"></i> View Registrants</a></li>
		<li><a href="http://www.bmorearoundtown.com/events/view.php?event=<?= $objEvent->getRegistrationCode() ?>" target="_blank" class="muted"><i class="fa fa-eye"></i>View Live Event</a></li>
		<li><a href="/cp/events/edit.php?event=<?= $objEvent->getId() ?>" class="text-warning"><i class="fa fa-pencil"></i>Edit Event</a></li>
		<li><a href="#" class="text-danger"><i class="fa fa-times"></i> Delete Event</a></li>
		<li><a href="/cp/events/index.php"><i class="fa fa-arrow-left"></i> Back to events</a></li>
	</ul>
	
	<div class="menu visible-xs visible-sm" style="margin-top: 20px;">
		
		<form name="menuNav"> 
			<select name="navMenu" class="form-control" onChange="window.location=document.menuNav.navMenu.options[document.menuNav.navMenu.selectedIndex].value">
				<option value="#"> - Select an action -</option>
				<option value="http://www.bmorearoundtown.com/events/view.php?event=<?= $objEvent->getRegistrationCode() ?>">View Live Event</option>
				<option value="/cp/events/edit.php?event=<?= $objEvent->getId() ?>">Edit Event</option>
				<option value="#">Delete Event</option>
				<option value="/cp/events/index.php">Back to all events</option>
			</select>
		</form>
		
	</div>
	
	<div class="page-header">
		<h1 style="font-size: 2.75em; color: #3a3a3a;"><?= $objEvent->getName() ?></h1>
	</div>

	<?
		if ($_SESSION['forms']['package']['success']) {
		?>
		<div class="alert alert-success">
			<?= $_SESSION['forms']['package']['successMessage'] ?>
		</div>
		<?
		
			$_SESSION['forms']['package'] = array();
		
		}
		
		if ($_SESSION['forms']['package']['error']) {
		?>
		<div class="alert alert-danger">
			<?= $_SESSION['forms']['package']['errorMessage'] ?>
		</div>
		<?
			$_SESSION['forms']['package']['error'] = '';
		}
	?>
	
	<div class="row">
	
		<div class="col-sm-12 col-md-6">

			<div class="panel panel-default">

				<div class="panel-heading">
					<h3 class="panel-title">Event Details</h3>
				</div>
				
				<div class="panel-body">
					
					<dl class="event-details-list">
				
						<div class="row">
							<dt class="col-xs-2">When:</dt>
							<dd class="col-xs-10"><?= $objEvent->getDatesDisplay() ?></dd>				
						</div>

						<div class="row">
							<dt class="col-xs-2">Category:</dt>				
							<dd class="col-xs-10"><?= $objEvent->getCategoryName() ?></dd>
						</div>
						
						<div class="row">
							<dt class="col-xs-2">Image:</dt>
							<dd class="col-xs-10">
								
								<? 
									
									$sourceImage = $_SERVER['DOCUMENT_ROOT'] . '/_assets/_images/logos/events/' . $objEvent->getRegistrationCode() . ".png";
									
									if( file_exists( $sourceImage ) ) { 
			
								?>
									
									<img class="img-thumbnail" src="<?= $objEvent->getLogoImageUrl() . '?buster=' . $buster ?>"/> <!--- stop caching --->
								
								<? } else { ?>
									
									N/A
									
								<? } ?>
								
							</dd>				
						</div>
						
						<div class="row">
						
							<?
								$objHotel = new Hotel();
								$objHotel->loadByEventId($objEvent->getId());
								
								if (count($objHotel)) {
								
									$arrHotels = array();
									
									while ($objHotel->loadNext())
										$arrHotels[] = $objHotel->getName();
								
								?>	
										<dt>Hotel<?= count($objHotel) > 1 ? 's' : '' ?>:</dt>
										<dd><?= implode(', ', $arrHotels) ?></dd>
								<?
								}
							?>		
							
						</div>
					
						<div class="row">
							
							<label class="col-xs-2">Description:</label>
							
							<div class="col-xs-10">
							
								<div class="well well-sm description"> 
									<p><?= nl2br($objEvent->getDescription()) ?></p>
								</div>
								
							</div>
							
						</div>
					
					</dl>
			
				</div>
				
			</div>
						
		</div>
				
		<div class="col-sm-12 col-md-6">

			<div class="row">
				
				<div class="col-xs-12">

					<div class="panel panel-info">

						<div class="panel-heading">
							<h3 class="panel-title">Event Register Information</h3>
						</div>
						
						<div class="panel-body">
				
							<dl class="event-registration-list">

								<div class="row">
									<dt class="col-xs-4 col-md-2">Code:</dt>
									<dd class="col-xs-8 col-md-10"><?=  $objEvent->getRegistrationCode() ?></dd>
								</div>
								
								<div class="row">
									<dt class="col-xs-4 col-md-2">Deadline:</dt>
									<dd class="col-xs-8 col-md-10"><?= date('M j, Y g:i A', $objEvent->getRegistrationDeadlineDate()) ?> (ET)</dd>
								</div>
								
								<div class="row">
									<dt class="col-xs-4 col-md-2">Total Tickets:</dt>
									<dd class="col-xs-8 col-md-10"><?= $objEvent->getMaxParticipants() ?></dd>
								</div>
								
								<div class="row">
									<dt class="col-xs-4 col-md-2">Max Tickets:</dt>
									<dd class="col-xs-8 col-md-10"><?= $objEvent->getMaxTicketsPerRegistration() ?></dd>
								</div>

								<div class="row">
									<dt class="col-xs-4 col-md-2">Has Logo:</dt>
									<dd class="col-xs-8 col-md-10"><?= (  $objEvent->getHasLogo() ? 'yes' : 'no')  ?></dd>
								</div>
								
								<div class="row">
									<dt class="col-xs-4 col-md-2">Is Active:</dt>
									<dd class="col-xs-8 col-md-10"><?= (  $objEvent->getIsActive() ? 'yes' : 'no')  ?></dd>
								</div>
								
								<div class="row">
									<dt class="col-xs-4 col-md-2">Is Published:</dt>
									<dd class="col-xs-8 col-md-10"><?= (  $objEvent->getIsPublished() ? 'yes' : 'no')  ?></dd>
								</div>
								
							</dl>
					
						</div>
						
					</div>
				
				</div>
				
			</div>
				
			<div class="row">
				
				<div class="col-xs-12">

					<div class="panel panel-info">

						<div class="panel-heading">
							<h3 class="panel-title">Event Location</h3>
						</div>
						
						<div class="panel-body">
					
							<dl class="event-location-list">

								<div class="row">
								
									<dt class="col-xs-2">Locations:</dt>
									
									<dd class="col-xs-10">
										
										<ul>
										
											<?
												$objLocation = new Location();
												$objLocation->loadByEventId($objEvent->getId(), false);
												
												while ($objLocation->loadNext()) {
												?>
													<li><?= $objLocation->getName() ?></li>
												<?
												}
											?>
											
										</ul>	
										
									</dd>
									
								</div>					
							
							</dl>
					
						</div>
					
					</div>
					
				</div>
				
			</div>

		</div>
	
	</div>
	
	<hr>
	
	<div class="row">
	
			<div id="packageInformation" class="col-xs-12">

				<h1 class="collpase-box" style="font-size: 2.1em;">
					Event Packages
					<a href="/cp/events/packages/add.php?event=<?= $objEvent->getId() ?>" class="pull-right" style="font-size: .7em; top: 15px; position: relative; z-index: 1020;">
						<i class="fa fa-plus cushion-right"></i>Add Package
					</a>			
				</h1>
				
				<div class="table-responsive">
				<?
					$objPackage = new Package();
					$objPackage->loadByEventId($objEvent->getId());
					
					if (count($objPackage)) {
				?>

					<?
						
						$packageArrFields = array(
							'action'						=> 'Actions',
							'id'							=> 'ID',
							'name'							=> 'Package Description',
							'price'							=> 'Price',
							'registrationStartDate'			=> 'Start Date',
							'registrationDeadlineDate'		=> 'End Date',
							'maxParticipants'				=> 'Tickets Availiable'
						);
						$packageArrData = array();
						
						while ( $objPackage->loadNext() ) {
						
							$packageArrData[$objPackage->getId()] = array(
								'id'					=> 	$objPackage->getId(),
								'name'					=> '<span class="package-name">' . $objPackage->getName() . '</span><br /><div>' . nl2br($objPackage->getDescription()) . '</div>',
								'price'							=> number_format($objPackage->getPrice(), 2),
								'registrationStartDate'			=> $objPackage->getStartDateDisplay(),
								'registrationDeadlineDate'		=> $objPackage->getEndDateDisplay(),
								'maxParticipants'				=> $objPackage->getMaxParticipants(),
								'action'						=> '<div class="text-center"><a href="/cp/events/packages/edit.php?package=' . $objPackage->getId() . '" class="text-warning cushion-right"><i class="fa fa-pencil"></i></a><a href="#" onclick="deletePackage( event, ' . $objPackage->getId() . ' )" class="text-danger cushion-right"><i class="fa fa-times"></i></a></div>'
								
							);
							
						}
							
						$objGrid = new DataGrid('packages', $packageArrFields, $packageArrData);
						
						echo $objGrid->draw();
						
					?>

					<?
					} else {
					?>										

						<p>No packages have been set up for this event.</p>
						
					<?
					}
					?>
				
				</div>
			
			</div>
			
			<div id="registrationInformation" class="col-xs-12">
			
				<h1 class="collpase-box" style="font-size: 2.1em;">
					Event Registrants
					<a href="#" class="pull-right" style="font-size: .7em; top: 50px; position: relative; z-index: 1020;"><i class="fa fa-plus cushion-right"></i>Add Registration</a>	
				</h1>
				
				<div class="row">
					
					<div class="col-sm-12">

						<ul class="nav nav-tabs" role="tablist">
						  <li class="active"><a href="#completeRegistrations" class="text-success" role="tab" data-toggle="tab">Completed</a></li>
						  <li><a href="#incompleteRegistrations" class="text-danger" role="tab" data-toggle="tab">Incomplete</a></li>
						</ul>

						<div class="tab-content">
						
							<div class="tab-pane active" id="completeRegistrations">

								<div class="panel panel-success">
											
									<?

										
										$arrFields = array(
											'name'					=> 'Name',
											'packageName'			=> 'Package',
											'numTickets'			=> '# Tickets',
											'date'					=> 'Date Paid',
											'amountPaid'			=> 'Amount Paid',							
											'paypalEmail'			=> 'Email',	
											'registrationCode'		=> 'Ticket Code',
											'confirmationCode'		=> 'Confirmation Code',
											'sendEmail'				=> 	'Email Confirmation'
										);
										$arrData = array();
										
										$objRegistration = new RegistrationX();
										$objRegistration->loadCompleteByEventId($objEvent->getId());
										$totalPaid = 0;
										$totalTickets = 0;
										
										while ($objRegistration->loadNext()){
											
											$currentRegistrationTickets = $objRegistration->getNumberOfTickets();
											$currentRegistrationTotal = $objRegistration->getAmountPaid();
											$totalPaid += $currentRegistrationTotal;
											$totalTickets += $currentRegistrationTickets;
											
											$arrData[$objRegistration->getId()] = array(
												'name'			=> '<a href="/cp/events/packages/registrations/view.php?registration=' . $objRegistration->getId() . '">' . $objRegistration->getNameDisplay() . '</a>',
												'date'			=> date('F jS, Y h:i:sA', $objRegistration->getDatePaid()),
												'packageName'	=> $objRegistration->getPackageName(),
												'numTickets'	=> $currentRegistrationTickets,
												'amountPaid'	=> "$" . number_format( $currentRegistrationTotal, 2 ),
												'paypalEmail'	=> $objRegistration->getPaypalEmailAddress(),
												'registrationCode'	=> $objRegistration->getRegistrationCode(),
												'confirmationCode'	=> $objRegistration->getConfirmationNumber(),
												'sendEmail'	=> '<a href="/cp/events/packages/registrations/action.sendConfirmationEmail.php?registration=' . $objRegistration->getId() . '">Resend Confirmation Email</a>'
											);
										
										}
										
										$objCompleteRegistrationsGrid = new DataGrid('completeRegistrations', $arrFields, $arrData, '', 'There are no complete registrations');
									?>

									<div class="table-responsive max-height-30">
										<?= $objCompleteRegistrationsGrid->draw() ?>
									</div>
									
									 <div class="panel-footer">
										
										<table class="table" style="margin-bottom: 0px;">
											
											<tfoot style="color: #3a3a3a;">
												
												<tr>
												
													<th><label>Grand Totals:</label></th>
													<th></th>
													<th># Tickets: <?= $totalTickets ?></th>
													<th>Paid: <?=  "$" . number_format( $totalPaid, 2 ) ?></th>
													
												</tr>
												
											</tfoot>
											
										</table>
										
										<div class="registration-printing">
											<a href="#" >
												<small><i class="fa fa-print" style="margin-right: 7px;"></i> Print Complete Registrations</small>
											</a>
										</div>
										
									</div>
									
								
								</div>
						
							</div>
							
							<div class="tab-pane" id="incompleteRegistrations">
								
								<div class="panel panel-danger">
									
									<?
										$arrFields = array(
											'name'					=> 'Registration Name',
											'packageName'			=> $objRegistration->getPackageName(),							
											'registrationCode'		=> 'Ticket Code',
											'numTickets'			=> '# Tickets'
										);					
										$arrData = array();
										
										$objRegistration = new RegistrationX();
										$objRegistration->loadIncompleteByEventId($objEvent->getId());
										
										while ($objRegistration->loadNext()){
										
											$arrData[$objRegistration->getId()] = array(
												'name'						=> '<a href="/cp/events/packages/registrations/view.php?registration=' . $objRegistration->getId() . '">' . $objRegistration->getNameDisplay() . '</a>',
												'packageName'				=> $objRegistration->getPackageName(),
												'registrationCode'			=> $objRegistration->getRegistrationCode(),
												'numTickets'				=> $objRegistration->getNumberOfTickets()
											);
										
										}
										
										$objIncompleteRegistrationsGrid = new DataGrid('incompleteRegistrations', $arrFields, $arrData, '', 'There are no incomplete registrations');
									?>

									<div class="table-responsive max-height-30">
										<?= $objIncompleteRegistrationsGrid->draw() ?>
									</div>
									
									<div class="panel-footer">
									
										<div class="registration-printing">
											<a href="#" >
												<small><i class="fa fa-print" style="margin-right: 7px;"></i> Print Incomplete Registrations</small>
											</a>
										</div>
										
									</div>
									
								</div>						
								
							</div>
							
						</div>

					</div>
				
				</div>
				
			</div>	
		
		</div>

	</div>

	<script>
		
		function deletePackage( e, packageId ) {

			e.preventDefault();

			if ( confirm( 'Are you sure you want to delete this package? This could cause some orphan records if you proceed. If you want another option try setting the package to hidden instead.' ) )
				location.href = '/cp/events/packages/delete.php?package=' + packageId;

		}
		
	</script>
	