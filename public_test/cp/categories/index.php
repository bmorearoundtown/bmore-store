<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/config.php');
	
	
	/*--- Build the grid ---*/
	
	$objCategory = new CategoryX();
	$objCategory->loadForAdmin();
	
	$arrFields = array(
		'name'					=> 'Name',
		'upcomingEventCount'	=> '# of Upcoming Events',
		'isActive'				=> 'Is Active',
		'tools'					=> 'Action'
	);
	$arrData = array();
	
	while ($objCategory->loadNext())
		$arrData[$objCategory->getId()] = array(
			'name'					=> $objCategory->getName(),
			'upcomingEventCount'	=> $objCategory->getUpcomingEventCount() ? $objCategory->getUpcomingEventCount() : '0',
			'isActive'				=> $objCategory->getIsActive() ? 'Active' : 'Inactive',
			'tools'					=> '<div class="text-center"><a href="edit.php?category=' . $objCategory->getId() . '"><i class="fa fa-pencil text-warning"></i></a></div>'
		);
		
	$objGrid = new DataGrid('categories', $arrFields, $arrData, '', 'There are no categories');
	
	require_once( 'header.php' );
	
?>

	<div class="page-header">
		
		<h1 class="pull-left collapse-box">Event Categories</h1>
		
		<div class="pull-right" style="margin-right: 20px;">
			<a href="/cp/categories/add.php" style="color: #3a3a3a;"><i class="fa fa-plus"></i> Create New Category</a>
		</div>
		
		<div class="clearfix"></div>
		
	</div>
	
<?
	if ($_SESSION['forms']['category']['success']) {
	?>
	<div class="alert alert-success">
		<?= $_SESSION['forms']['category']['success'] ?>
	</div>
	<?
	
		$_SESSION['forms']['category'] = array();
	
	}
	
	if ($_SESSION['forms']['category']['error']) {
	?>
	<div class="alert alert-danger">
		<?= $_SESSION['forms']['category']['error'] ?>
	</div>
	<?
		$_SESSION['forms']['category']['error'] = '';
	}
?>
	
	<div>
	
		<?= $objGrid->draw() ?>
	
	</div>
	
<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/cp/footer.php');