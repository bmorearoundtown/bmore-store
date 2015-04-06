<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/config.php');
	
	/*--- Add additional CSS --- */
	$GLOBALS['config']->addPageCSSManual( 'blogs/blogs.css' );
	
	/*--- Build the grid ---*/
	
	$objBlog = new Blog();
	$objBlog->loadForAdmin();
	
	$arrFields = array(
		'id'					=> 'ID',
		'title'					=> 'Blog Title',
		'author'				=> 'Author',
		'isActive'				=> 'Is Active',
		'dateCreated'			=> 'Date Created',
		'action'				=> 'Actions'
	);
	
	$arrData = array();
	
	while ($objBlog->loadNext())
		$arrData[$objBlog->getId()] = array(
			'id'			=> $objBlog->getId(),
			'title'			=> $objBlog->getTitle(),
			'author'		=> $objBlog->getAuthor(),
			'isActive'		=> $objBlog->getIsActive() ? '<span class="active-blog">Active</span>' : '<span class="not-active-blog">Inactive</span>',
			'dateCreated'	=> $objBlog->getDateCreated(),
			'action'		=> '<div class="text-center"><a href="/cp/blogs/view.php?blog=' . $objBlog->getId() . '"><i class="fa fa-eye"></i></a><a href="/cp/blogs/edit.php?blog=' . $objBlog->getId() . '" class="cushion-left cushion-right"><i class="fa fa-pencil text-warning"></i></a><a href="#" onclick="deleteBlog(event, ' . $objBlog->getId() . ')"><i class="fa fa-times text-danger"></i></a></div>'
		);
	
	$objGrid = new DataGrid('blogs', $arrFields, $arrData, '', 'There are no blogs to display');
	
	require_once('header.php');

?>
	
	<div class="page-header">
		
		<h1 class="pull-left collapse-box">Blog Entry</h1>
		
		<div class="pull-right" style="margin-right: 20px;">
			<a href="/cp/blogs/add.php" style="color: #3a3a3a;"><i class="fa fa-plus"></i> Create New Blog Entry</a>
		</div>
		
		<div class="clearfix"></div>
		
	</div>
	
<?
	if ($_SESSION['forms']['blog']['success']) {
	?>
	<div class="alert alert-success">
		<?= $_SESSION['forms']['blog']['success'] ?>
	</div>
	<?
	
		$_SESSION['forms']['blog'] = array();
	
	}
	
	if ($_SESSION['forms']['blog']['error']) {
	?>
	<div class="alert alert-danger">
		<?= $_SESSION['forms']['blog']['error'] ?>
	</div>
	<?
		$_SESSION['forms']['blog']['error'] = '';
	}
?>
	
	<div class="table-responsive">
	
		<?= $objGrid->draw() ?>
	
	</div>
	
	<script>
		
		function deleteBlog( e, blogId ){

			e.preventDefault();
			
			if (confirm('Are you sure you want to remove this blog?'))
				location.href = 'delete.php?blog=' + blogId;
			
			return;
			
		}
		
	</script>
	
<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/cp/footer.php');