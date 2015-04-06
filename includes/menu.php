<?php 
	
	$isHome = $GLOBALS['config']->getParam( 'activeMenuItem' ) === 'home';
	$isEvents = $GLOBALS['config']->getParam( 'activeMenuItem' ) === 'events';
	$isPhotos = $GLOBALS['config']->getParam( 'activeMenuItem' ) === 'photos';
	$isBlog = $GLOBALS['config']->getParam( 'activeMenuItem' ) === 'blogs';
	$isTestimonials = $GLOBALS['config']->getParam( 'activeMenuItem' ) === 'testimonials';
	$isContact = $GLOBALS['config']->getParam( 'activeMenuItem' ) === 'contact';
	
?>

<div class="nav-select hidden-xs visible-sm visible-md hidden-lg">

	<select onchange="location = this.options[this.selectedIndex].value;">
		<option value="/">Select a location...</option>
		<option value="/">Home</option>
		<option value="/events">Events</option>
		<option value="/photos">Photos</option>
		<option value="/blogs">Articles / Blog</option>
		<option value="/testimonials">Testimonials</option>
		<option value="/contact">Contact</option>
	</select>

</div>

<div class="navbar navbar-transparent highlight-layer hidden-sm hidden-md" role="navigation">

	<div class="navbar-header">

		<button type="button" class="navbar-toggle collapsed"
			data-toggle="collapse" data-target=".navbar-collapse">
			<span class="sr-only">Toggle navigation</span>
			Menu <i class="fa fa-sort-desc cushion-left" style="position: relative; top: -3px;"></i>
		</button>

	</div>

	<div class="navbar-collapse collapse">

		<ul class="nav navbar-nav">
			
			<li class="<?= $isHome ? 'active' : '' ?>"><a href="/">Home</a></li>
			
			<li class="dropdown <?= $isEvents ? 'active' : '' ?>">
				
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">Events <span class="caret"></span></a>
				
				<ul class="dropdown-menu" role="menu">
					<li class="dropdown-header">General</li>
					<li><a href="/events/index.php">All Events</a></li>
					<li class="divider"></li>
					<li class="dropdown-header">Sporting Events By Team</li>
					<li><a href="/events/index.php?filter=2">Baltimore Orioles</a></li>
					<li><a href="/events/index.php?filter=1">Baltimore Ravens</a></li>
					<li><a href="/events/index.php?filter=3">Washington Capitals</a></li>
					<li><a href="/events/index.php?filter=5">Washington Wizards</a></li>					
<!-- 					<li><a href="#">Washington Redskins</a></li>
					<li><a href="#">Washington Nationals</a></li>
					<li><a href="#">Washington Capitals</a></li>
					<li><a href="#">Washington Wizards</a></li> -->
					<li><a href="/events/index.php?filter=4">Other</a></li>
<!-- 					<li class="divider"></li>
					<li class="dropdown-header">Non Sporting Events</li>
					<li><a href="#">Concerts, Banquets, Socials, etc.</a></li> -->
				</ul>
				
			</li>
			
			<li class="<?= $isPhotos ? 'active' : '' ?>"><a href="/photos">Photos</a></li>
			<li class="<?= $isBlog ? 'active' : '' ?>"><a href="/blogs">Articles/Blog</a></li>
			<li class="<?= $isTestimonials ? 'active' : '' ?>"><a href="/testimonials">Testimonials</a></li>
			<li class="<?= $isContact ? 'active' : '' ?>"><a href="/contact">Contact</a></li>
		</ul>

	</div>

</div>
