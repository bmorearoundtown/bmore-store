<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/config.php');
	
	/*--- Build the grid ---*/
	
	$objLocation = new Location();
	$objLocation->loadForAdmin();
	
	$arrFields = array(
		'name'			=> 'Name',
		'address'		=> 'Address',
		'isActive'		=> 'Is Active',
		'tools'			=> 'Action'
	);
	$arrData = array();
	
	while ($objLocation->loadNext())
		$arrData[$objLocation->getId()] = array(
			'name'			=> $objLocation->getName(),
			'address'		=> $objLocation->getAddressDisplay(true),
			'isActive'		=> $objLocation->getIsActive() ? 'Active' : 'Inactive',
			'tools'			=> '<div class="text-center"><a href="edit.php?location=' . $objLocation->getId() . '"><i class="fa fa-pencil text-warning"></i></a>
								<a href="#" onclick="deleteLocation(event, ' . $objLocation->getId() . ')"><i class="fa fa-times text-danger"></i></a></div>'
		);
		
	$objGrid = new DataGrid('locations', $arrFields, $arrData, '', 'There are no locations');
		
	require_once( 'header.php' );
	
?>

	<div class="page-header">
		
		<h1 class="pull-left collapse-box">Event Locations</h1>
		
		<div class="pull-right" style="margin-right: 20px;">
			<a href="/cp/locations/add.php" style="color: #3a3a3a;"><i class="fa fa-plus"></i> Create New Location</a>
		</div>
		
		<div class="clearfix"></div>
		
	</div>
	
<?
	if ($_SESSION['forms']['location']['success']) {
	?>
	<div class="alert alert-success">
		<p><?= $_SESSION['forms']['location']['success'] ?></p>
	</div>
	<?
		
		$_SESSION['forms']['location'] = array();
	
	}
	
	if ($_SESSION['forms']['location']['error']) {
	?>
	<div class="alert alert-danger">
		<?= $_SESSION['forms']['location']['error'] ?>
	</div>
	<?
		$_SESSION['forms']['location']['error'] = '';
	}
?>
	
	<div>
	
		<?= $objGrid->draw() ?>
	
	</div>
	
	<script>
		
		function deleteLocation( e, locationId ) {

			e.preventDefault();

			if ( confirm( 'Are you sure you want to delete this location?' ) )
				location.href = 'delete.php?location=' + locationId;

		}
		
	</script>
	
<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/cp/footer.php');