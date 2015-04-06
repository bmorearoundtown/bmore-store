<div class="row">

	<div class="col-xs-12">
		
		<h1 class="visible-xs visible-sm text-center" style="padding-top: 10px;">Featured Events</h1>
		
		<h4 class="visible-xs text-center text-muted" style="padding-top: 10px;"><i class="fa fa-chevron-left cushion-right"></i>Swipe to view<i class="fa fa-chevron-right cushion-left"></i></h4>
		
		<div id="featuredEventsCarousel" class="carousel slide" style="display: none;">
			
	    	<?php
			
			$objEvent = new EventX();
			$objEvent->loadUpcomingEvents( true, 12 );
			
			while( $objEvent->loadNext() ) {
				
			?>
			
			<div class="item">
			
	      		<?php if( $objEvent->getHasLogo() ) { ?>
				
				<div class="img-text-overlay">
				
					<img class="lazyOwl img-responsive" data-src="<?= $objEvent->getImageUrl() ?>" alt="<?= $objEvent->getName() ?>" />
					
					<p class="description" style="display: none;">
		      			<a href="/events/view.php?event=<?= $objEvent->getRegistrationCode() ?>" class="unstyled"><?= $objEvent->getDescription(250) ?></a>
					</p>
	
					<a href="/cart/quickAdd.php?event=<?= $objEvent->getId() ?>" class="add-to-cart quick-add" title="Click to add a package to your cart">
						 <i class="fa fa-plus fa-3x"></i>
					</a>
	      			
				</div>
				
				<?php } ?>
				
				<div class="content-container">
	      		
	      			<p class="date"><?= date('M j', $objEvent->getStartDate()) ?></p>
	      			
	      			<a href="/events/view.php?event=<?= $objEvent->getRegistrationCode() ?>" class="name <?= $objEvent->getEventClassName() ?>">
	      				<?= $objEvent->getName() ?>
	      			</a>
				
					<p class="price"><?= $objEvent->getPriceRangeDisplay() ?></p>
				
				</div>
				
	      	 </div>
	      	 
	      	<?php } ?>
		
		</div>
	
		<div class="custom-carousel-nav hidden-xs">
			<a class="btn prev carousel-control left"><i class="fa fa-chevron-left fa-2x"></i></a>
			<a class="btn next carousel-control right"><i class="fa fa-chevron-right fa-2x"></i></a>
		</div>
		
	</div>
		
	<div class="col-xs-12 text-center cushion-top">
		
		<a href="/events/" class="btn btn-success view-all-events">View All Events</a>
		
	</div>
	
</div>
