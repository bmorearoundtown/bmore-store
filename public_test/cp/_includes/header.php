<!doctype html>
<html>
<head>

	<title><?= $GLOBALS['config']->getPageTitle() ?></title>
	
	<meta name="viewport" content="width=device-width, user-scalable=no">
	
	<link rel="shortcut icon" href="http://www.bmorearoundtown.com/_assets/_images/favicon.png" />
	
	<link rel="stylesheet" href="/cp/_assets/_css/bootstrap.min.css" />
	<link rel="stylesheet" href="/cp/_assets/_css/datepicker.css" />
	<link rel="stylesheet" href="/cp/_assets/_css/datepicker-bs3.css" />
	<link rel="stylesheet" href="/cp/_assets/_css/timepicker.css" />
	<link rel="stylesheet" href="/cp/_assets/_css/font-awesome.css" />
	<link rel="stylesheet" media="only screen and (min-width: 401px)" href="/cp/_assets/_css/screen.css" />
	<link rel="stylesheet" media="only screen and (max-width: 400px)" href="/cp/_assets/_css/mobile.css" />
	
	<link rel="stylesheet" href="/cp/_assets/_css/global.css" />
	
	<?
		foreach ($GLOBALS['config']->getAdditionalCSS() as $strCSS) {
		?>
		<link rel="stylesheet" href="<?= $strCSS ?>" />
		<?
		}
	?>

	<script src="/cp/_assets/_js/lib/jquery.min.js"></script>
	<script src="/cp/_assets/_js/lib/jquery.validate.js"></script>
	<script src="/cp/_assets/_js/lib/jquery.parseparams.js"></script>
	<script src="/cp/_assets/_js/lib/jquery.duframework.js"></script>
	<script src="/cp/_assets/bootstrap/js/bootstrap.js"></script>
	<script src="/cp/_assets/bootstrap/js/bootstrap-datepicker.js"></script>
	<script src="/cp/_assets/bootstrap/js/bootstrap-timepicker.js"></script>
	
<?
	foreach ($GLOBALS['config']->getAdditionalJS() as $strJs) {
	?>
	<script src="<?= $strJs ?>"></script>
	<?
	}
?>
	
</head>

<body>
	
	<header id="header">

		<div class="navbar navbar-default navbar-fixed-top" role="navigation">
		
			<div class="navbar-header">
			
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			  
				<a class="navbar-brand" href="/">bmorearoundtown.com</a>
						
			</div>
			
			<div class="navbar-collapse collapse">
			
				<ul class="nav navbar-nav">
					<li><a href="/cp/"><i class="fa fa-home fa-lg cushion-right"></i>Dashboard</a></li>
					<li class="dropdown">
						
						<a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="fa fa-calendar fa-lg cushion-right"></i> Events <span class="caret"></span></a>
						
						<ul role="menu" class="dropdown-menu">
							<li class="dropdown-header">Event Actions</li>
							<li><a href="/cp/events/add.php"><i class="fa fa-plus cushion-right"></i> Create Event</a></li>
							<li><a href="/cp/events/temp_registration_list.php" class="muted" target="_blank"><i class="fa fa-list-ul cushion-right"></i>Print Registration Lists</a></li>
							<li class="divider"></li>
							<li class="dropdown-header">Event Packages</li>
							<li><a href="/cp/events/packages/add.php"><i class="fa fa-plus cushion-right"></i> Create Package for Event</a></li>
							<li class="divider"></li>
							<li class="dropdown-header">General</li>
							<li><a href="/cp/events/index.php">View All Events</a></li>
							<li><a href="/cp/events/packages/index.php">View All Packages</a></li>
						</ul>
						
					</li>

					<li class="dropdown">
						
						<a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="fa fa-bank fa-lg cushion-right"></i> Hotels <span class="caret"></span></a>
						
						<ul role="menu" class="dropdown-menu">
							<li class="dropdown-header">Hotel Actions</li>
							<li><a href="/cp/hotels/add.php"><i class="fa fa-plus cushion-right"></i> Create Hotel</a></li>
							<li class="divider"></li>
							<li class="dropdown-header">General</li>
							<li><a href="/cp/hotels/index.php">View All Hotels</a></li>
						</ul>
						
					</li>

					<li class="dropdown">
						
						<a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="fa fa-map-marker fa-lg cushion-right"></i> Locations <span class="caret"></span></a>
						
						<ul role="menu" class="dropdown-menu">
							<li class="dropdown-header">Location Actions</li>
							<li><a href="/cp/locations/add.php"><i class="fa fa-plus cushion-right"></i> Create Location</a></li>
							<li class="divider"></li>
							<li class="dropdown-header">General</li>
							<li><a href="/cp/locations/index.php">View All Event Locations</a></li>
						</ul>
						
					</li>

					<li class="dropdown">
						
						<a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="fa fa-list-ul fa-lg cushion-right"></i> Category <span class="caret"></span></a>
						
						<ul role="menu" class="dropdown-menu">
							<li class="dropdown-header">Category Actions</li>
							<li><a href="/cp/categories/add.php"><i class="fa fa-plus cushion-right"></i>Create Category</a></li>
							<li class="divider"></li>
							<li class="dropdown-header">General</li>
							<li><a href="/cp/categories/index.php">View All Event Categories</a></li>
						</ul>
						
					</li>

					<li class="dropdown">
						
						<a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="fa fa-comments fa-lg cushion-right"></i> Blog <span class="caret"></span></a>
						
						<ul role="menu" class="dropdown-menu">
							<li class="dropdown-header">Blog Actions</li>
							<li><a href="/cp/blogs/add.php"><i class="fa fa-plus cushion-right"></i>Create Blog Entry</a></li>
							<li class="divider"></li>
							<li class="dropdown-header">General</li>
							<li><a href="/cp/blogs/index.php">View All Blog Entries</a></li>
						</ul>
						
					</li>

					<li class="dropdown">
						
						<a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="fa fa-info fa-lg cushion-right"></i> Misc. <span class="caret"></span></a>
						
						<ul role="menu" class="dropdown-menu">
							<li class="dropdown-header">Miscellanous</li>
							<li><a href="/cp/newsletter/index.php"><i class="fa fa-eye cushion-right"></i>Newsletter</a></li>
							<li><a href="/cp/reports/index.php"><i class="fa fa-bar-chart cushion-right"></i>Reports</a></li>
							<li class="divider"></li>
							<li class="dropdown-header">PayPal</li>						
							<li><a href="/cp/reports/uniquepaypalusers.php"><i class="fa fa-cc-paypal cushion-right"></i>Unique PayPal Email Addresses</a></li>
						</ul>
						
					</li>
					
				</ul>

				<ul class="nav navbar-nav pull-right hidden-md">
					<li><a class="text-info" href="#">Welcome, Administrator</a></li>
				</ul>
			
			</div>
				
		</div>
	
	</header>
	
	<section id="content">
	
		<div class="content-container">