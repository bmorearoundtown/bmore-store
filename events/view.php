<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/config.php');

	/*--- Build objects ---*/
	
	$objEvent = new EventX();
	$objEvent->loadByRegistrationCode( $_GET[ 'event' ] );
	
	$objLocation = new Location();
	$objLocation->loadByEventId( $objEvent->getId(), false );
	
	if (!$objEvent->isValid()) {
		header('Location: index.php');
		exit;
	}

	/*--- head and header includes ---*/

	require_once( "head.php" ); // includes body tag
	
	require_once( "header.php" );
		
?>

<div class="container">
	
	<div class="row">
		
		<div class="col-md-12">
		
			<section id="fullEventDetailsWrapper" class="wrapper highlight-layer">

				<div class="row">
				
					<div class="col-md-12">
						
						<a href="/events/index.php" title="Go back to the event list view" class="text-muted back-link">
							<i class="fa fa-arrow-left"></i> Back to All Events View
						</a>
						
					</div>
				
				</div>
							
				<div class="row">
				
					<div class="col-md-9">
						
						<h1 class="page-title"><?= $objEvent->getName() ?></h1>
						
						<p class="page-date <?= $objEvent->getEventClassName() ?>"><?= $objEvent->getDatesDisplaySimple() ?></p>
						
					</div>
		
					<div class="col-md-3 text-center hidden-xs">
						
						<ul class="social-links list-inline list-unstyled">
							<li><a href="http://instagram.com/bmorearoundtown"><i class="fa fa-instagram fa-3x"></i></a></li>
							<li><a href="https://plus.google.com/111265555366021608536/about"><i class="fa fa-google-plus fa-3x"></i></a></li>
							<li><a href="https://www.facebook.com/bmorearoundtown"><i class="fa fa-facebook fa-3x"></i></a></li>
							<li><a href="https://www.twitter.com/bmorearoundtown"><i class="fa fa-twitter fa-3x"></i></a></li>
						</ul>
						
					</div>
				
				</div>

				<div class="row">
					
					<div class="col-md-9">
						
						<div class="row">
							
							<div class="col-md-5">
								
								<div class="row">
								
									<div class="col-md-12">
		
										<?php if ( $objEvent->getHasLogo() ) { ?>
										
											<img src="<?= $objEvent->getLogoImageUrl() ?>" class="full-event-image" alt="<?= $objEvent->getName() ?>" />
											
										<?php } ?>
																	
									</div>
								
								</div>
								
								<div class="row">
								
									<div class="col-md-12">
		
										<div id="eventPackageSelectListWrapper">
											
											<?php include("../cart/packageSelectListing.php"); ?>
										
										</div>
																	
									</div>
																
								</div>
							
							</div>
							
							<div id="fullEventDescriptionWrapper" class="col-md-7">
	
								<div id="fullEventDescriptionContainer">
									
									<h3 class="ticket-availability"><span class="text-success">Tickets available</span> from</h3>
									
									<p class="price"><?= $objEvent->getPriceRangeDisplay() ?></p>
									
									<div class="description-text-wrap">
										
										<div class="description"><?= $objEvent->getHTMLEnabledDescription() ?></div> <!-- replace with short desc field once it exists -->
										
									</div>
									
								</div>
					
							</div>
							
						</div>
											
						<div class="row" style="margin-top: 30px;">
						
							<div class="col-md-12">

								<div id="additionalEventInformationWrapper">
									
								
								</div>
															
							</div>
														
						</div>
												
					</div>
					
					<div class="col-md-3">
						
						<div class="row">
							
							<div class="col-md-12">
								
								<h3>When</h3>
								
								<p class="event-when text-muted"><?= $objEvent->getDatesDisplayWithBreak() ?></p>
								
								<h3>Where</h3>
								
								<ul class="locations list-unstyled">
									
									<?php if( count( $objLocation ) ){ ?>
									
										<?php while ( $objLocation->loadNext() ) { ?>
										
										<li>
											
											<div class="row">
												
												<div class="col-md-12">
													
													<p class="event-where">
														
														<a href="http://maps.google.com/maps?q=<?= urlencode($objLocation->getAddressDisplay(true)) ?>" 
															class="text-info" target="_blank" title="location map"><?= $objLocation->getName() ?>
														</a>
														
													</p>
													
													<p class="event-address text-muted">
														<?= $objLocation->getAddressDisplay() ?>
													</p>

													<p class="event-map">
														
														<a href="http://maps.google.com/maps?q=<?= urlencode($objLocation->getAddressDisplay(true)) ?>" target="_blank">
															<img src="http://maps.google.com/maps/api/staticmap?markers=color:0x276FED|<?= urlencode($objLocation->getAddressDisplay(true)) ?>&zoom=14&size=300x300&maptype=roadmap&sensor=false"
																 alt="<?= $objEvent->getLocationName() ?>" />
														</a>
												
													</p>

													<?php if ( $objLocation->getDescription() ) { ?>
													
														<p class="event-location-description">
															<?= $objLocation->getDescription() ?>
														</p>
													
													<?php } ?>
														
												</div>
												
											</div>

										</li>
									
									<?php } ?>
									
								<?php }	 else { ?>
									
									<li><h4 class="text-muted">There are no locations assigned to this event!</h4></li>
									
								<?php } ?>
								
								</ul>
						
							</div>
							
						</div>
						
					</div>
										
				</div>
							
			</section>
		
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
