<?php
	
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/_includes/config.php' );
	
	/* Set active menu item */
	$GLOBALS['config']->setParam('activeMenuItem', 'events' );

	/*--- Build objects ---*/
	
	$objEvent = new EventX();
	$objEvent->loadUpcomingEvents( true, 0, $_GET[ 'filter' ] );

	$objCategory = new Category();
	$objCategory->loadCurrentCategories();
	
	/*--- head and header includes ---*/
	
	require_once( "head.php" ); // includes body tag
	
	require_once( "header.php" );
	
	$eventViewType = "list"; // default view type
	
	$incomingFilterId = $_GET[ 'filter' ];
	$idExists = ( isset( $incomingFilterId ) && $incomingFilterId > 0 );
	$hasFilter = $idExists;
	$hasBanner = false;
	$filteredCategoryText = $hasFilter ? filteredText( $incomingFilterId ) : 'All';
	
	if( $hasFilter ){
		
		$bannerSource = filteredBanner( $incomingFilterId );
		$hasBanner = $bannerSource != '';
		
	}
	
?>

<div class="container">
	
	<div class="row">
		
		<div id="eventsMainContentWrapper" class="wrapper highlight-layer">
			
			<h1 class="page-title">Upcoming Events <small><?= $filteredCategoryText ?></small></h1>
						
			<section class="col-md-12">
				
				<div class="row">
					
					<div class="col-xs-12">
						
						<div id="eventFilterBar">
							
							<?php if(count( $objCategory ) ) { ?>
							
								<?php  if( !$detect->isMobile() && !$detect->isTablet() ){ ?>
								
									 <ul id="categoryFilterListing" class="list-inline list-unstyled category-listing">
		
										<?php
											
											$arrCategories = array( '<li><a href="index.php">All Categories</a></li>' );
										
											while ( $objCategory->loadNext() ){
												
												$arrCategories[] = '<li><a href="?filter=' . $objCategory->getId() . '">' . $objCategory->getName() . '</a></li>';
											
											}
											
											echo implode(' | ', $arrCategories);
											
										?>
									
									</ul>
								
								<?php } else { ?>
									
									<select class="form-control cushion-top" onchange="window.open(this.options[this.selectedIndex].value,'_top')">
										
										<option value="">- Filter by event category -</option>
	
										<?php
											
											$arrCategories = array( '<option value="index.php">All Categories</option>' );
										
											while ( $objCategory->loadNext() ){
												
												$arrCategories[] = '<option value="?filter=' . $objCategory->getId() . '#eventView">' . $objCategory->getName() . '</option>';
											
											}
											
											echo implode(' | ', $arrCategories);
											
										?>
									
									</select>
									
								<?php } ?>
							
							<?php } ?>
							
						</div>
					
					</div>
					
				</div>

				<?php if( $hasBanner ){ ?>
				
				<div id="eventsBannerWrapper" class="row hidden-xs" style="margin: 10px 0 10px 0;">
					
					<div class="col-md-12">
						
						<img src="<?= $bannerSource ?>" title="event banner" class="events-banner img-responsive" />
					
					</div>		
				
				</div>
				
				<?php } ?>
			
				<div class="row">

					<div class="col-xs-12">
						
						<ul id="eventView" class="list-unstyled">
							
						<?php 
							
							if( count( $objEvent ) > 0 ){
								
								while ( $objEvent->loadNext() ) {
	
									$eventIsPublished = $objEvent->getIsPublished() != 0;
									
									if( $eventIsPublished ){
		
							?>
								
								<li id="event_<?= $objEvent->getId() ?>" class="event-list-item highlight-layer">
									
									<div class="row">
										
										<div class="col-md-3 text-center">
											
											<a href="/events/view.php?event=<?= $objEvent->getRegistrationCode() ?>" title="Go to full event information page">
												<img src="<?= $objEvent->getImageUrl() ?>" alt="<?= $objEvent->getName() ?>" />
											</a>
											
										</div>
										
										<div class="col-md-6">
	
											<div class="content-container">
		      		
								      			<p class="date"><?= date('M j', $objEvent->getStartDate()) ?></p>
								      			
								      			<a href="/events/view.php?event=<?= $objEvent->getRegistrationCode() ?>" class="name <?= $objEvent->getEventClassName() ?>">
								      				<?= $objEvent->getName() ?>
								      			</a>
												
												<p class="description"><?= $objEvent->getDescription(350) ?></p>
												
											</div>
					
										</div>
										
										<div class="col-md-3 text-center">
											
											<p class="price"><?= $objEvent->getPriceRangeDisplay() ?></p>
											
											<p>
											
												<a href="/events/view.php?event=<?= $objEvent->getRegistrationCode() ?>" class="btn btn-lg btn-default squared" title="View additional event information">
									      			More Info <i class="fa fa-angle-double-right"></i>
									      		</a>
								      		
											</p>
											
											<p>
												<a href="/cart/quickAdd.php?event=<?= $objEvent->getId() ?>" class="btn btn-lg btn-success add-to-cart quick-add squared" title="Click to add a package to your cart">
													 <i class="fa fa-plus"></i> Add to Cart
												</a>
								      		</p>
								      		
										</div>
										
									</div>
	
								</li>
							
							<?php
							
								}
									
							} 
							
							?>
							
							<?php 
							
							} else {
							
							?>
							
								<li class="text-center"><h2 style="padding-top: 20px;">No events have been created!</h2></li>

							<?php } ?>
							
						</ul>
					
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
