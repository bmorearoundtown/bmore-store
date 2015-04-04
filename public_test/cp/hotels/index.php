<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/config.php');
	
	/*--- Build the grid ---*/
	
	$objHotel = new Hotel();
	$objHotel->loadForAdmin();
	
	$arrFields = array(
		'name'		=> 'Name',
		'address'	=> 'Address',
		'url'		=> 'URL',
		'isActive'	=> '&nbsp;',
		'tools'		=> '&nbsp;'
	);
	$arrData = array();
	
	while ($objHotel->loadNext())
		$arrData[$objHotel->getId()] = array(
			'name'			=> $objHotel->getName(),
			'address'		=> $objHotel->getAddressDisplay(true),
			'url'			=> $objHotel->getUrlDisplay(),
			'isActive'		=> $objHotel->getIsActive() ? '&nbsp;' : '<span class="icon-cross inactive">Inactive</span>',
			'tools'			=> '<a href="edit.php?hotel=' . $objHotel->getId() . '"><img src="/cp/_assets/_images/icons/building_edit.png" class="iconbutton" alt="Click here to edit this hotel" /></a>
								<a href="#" onclick="deleteHotel(event, ' . $objHotel->getId() . ')"><img src="/cp/_assets/_images/icons/building_delete.png" class="iconbutton" alt="Click here to delete this hotel" /></a>'
		);
		
	$objGrid = new DataGrid('hotels', $arrFields, $arrData, '', 'There are no hotels');
	
	
		
	require_once( 'header.php');
	
?>

	<div class="page-header">
		
		<h1 class="pull-left collapse-box">Event Hotels</h1>
		
		<div class="pull-right" style="margin-right: 20px;">
			<a href="/cp/hotels/add.php" style="color: #3a3a3a;"><i class="fa fa-plus"></i> Create New Hotel Location</a>
		</div>
		
		<div class="clearfix"></div>
		
	</div>
	
<?
	if ($_SESSION['forms']['hotel']['success']) {
	?>
	<div class="alert alert-success">
		<?= $_SESSION['forms']['hotel']['success'] ?>
	</div>
	<?
	
		$_SESSION['forms']['hotel'] = array();
	
	}
	
	if ($_SESSION['forms']['hotel']['error']) {
	?>
	<div class="alert alert-danger">
		<?= $_SESSION['forms']['hotel']['error'] ?>
	</div>
	<?
		$_SESSION['forms']['hotel']['error'] = '';
	}
?>
	
	<div>
	
		<?= $objGrid->draw() ?>
	
	</div>
	
	<script>

		function deleteHotel( e, hotelId ) {

			e.preventDefault();
			
			if (confirm('Are you sure you want to remove this hotel?'))
				location.href = 'delete.php?hotel=' + hotelId;
			
			return;
		}
		
	</script>
	
<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/cp/footer.php');