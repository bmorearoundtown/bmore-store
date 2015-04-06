<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/config.php');
	
	/*--- Add additional CSS --- */
	$GLOBALS['config']->addPageCSSManual( 'events/events.css' );
	
	/*--- Build the grid ---*/
	
	$objEvent = new EventX();
	$objEvent->loadForAdmin(true, 0, true);
	
	$arrFields = array(
		'name'				=> 'Name',
		'dates'				=> 'Date(s)',
		'registrations'		=> 'Registrations',
		'isPublished'		=> 'Is Published',
		'isActive'		=> 'Is Active'
	);
	$arrData = array();
	
	/*--- Add back into registrations the number paid <a href="#">' . $objEvent->getTotalPaidRegistrations() . ' paid</a> ---*/
	while ($objEvent->loadNext())
		$arrData[$objEvent->getId()] = array(
			'name'			=> '<a href="view.php?event=' . $objEvent->getId() . '">' . $objEvent->getName() . '</a>',
			'dates'			=> date('M j, Y', $objEvent->getStartDate()) . ($objEvent->getIsMultiDay() ? ' &ndash; ' . date('M j, Y', $objEvent->getEndDate()) : ''),
			'registrations'	=> '<a href="#">' . $objEvent->getTotalRegistrations() . ' total<?a>',
			'isPublished'	=> $objEvent->isPublished() ? '<span class="published">Published</span>' : '<span class="not-published">Not Published</span>',
			'isActive'	=> $objEvent->isActive() ? '<span class="active-event">Is Active</span>' : '<span class="not-active-event">Not Active</span>'
		);
		
	$objGrid = new DataGrid('events', $arrFields, $arrData, '', 'There are no events to display');
	
	require_once('header.php');

?>
	
	<div class="page-header">
		
		<h1 class="pull-left collapse-box">Event Listing</h1>
		
		<div class="pull-right" style="margin-right: 20px;">			
			<a href="/cp/events/add.php" style="color: #3a3a3a;"><i class="fa fa-plus"></i> Create New Event</a>
		</div>
		
		<div class="clearfix"></div>
		
	</div>
	
<?
	if ($_SESSION['forms']['event']['success']) {
	?>
	<div class="success">
		<?= $_SESSION['forms']['event']['success'] ?>
	</div>
	<?
	
		$_SESSION['forms']['event'] = array();
	
	}
	
	if ($_SESSION['forms']['event']['error']) {
	?>
	<div class="errorAlert">
		<?= $_SESSION['forms']['event']['error'] ?>
	</div>
	<?
		$_SESSION['forms']['event']['error'] = '';
	}
?>
	
	<div class="table-responsive">
	
		<?= $objGrid->draw() ?>
	
	</div>
	
<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/footer.php');