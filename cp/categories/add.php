<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/config.php');
	
	require_once( 'header.php' );
?>

	<div class="page-header">
	
		<h1 class="pull-left collapse-box">Create Category</h1>
		
		<div class="pull-right">
			<a href="/cp/categories/index.php"><i class="fa fa-arrow-left"></i> Back to categories</a>
		</div>
		
		<div class="clearfix"></div>
		
	</div>
	
<?
	if ($_SESSION['forms']['add-category']['error']) {
	?>
	<div class="alert alert-danger">
		<p>There was an error adding the category. Please try again.</p>
	</div>
	<?
	}
?>
		
	<div class="well well-lg">
		
		<form action="action.add.php" method="post" class="form form-horizontal validate">

			<fieldset>
			
				<legend>Category Details</legend>
				
				<div class="form-group">
					
					<label for="categoryName" class="col-sm-2 control-label">Name:</label>
					
					<div class="col-sm-10">
						
						<input id="categoryName" type="text" name="name" value="<?= $_SESSION['forms']['add-category']['name'] ?>" size="35" class="form-control input-sm" required />
						
					</div>
					
				</div>
			
			</fieldset>
			
			<hr>
			
			<div class="form-group">
			
				<button type="submit" class="btn btn-lg btn-success col-sm-2 col-md-offset-2"><i class="fa fa-plus"></i> Create Category</button>
			
			</div>
			
		</form>
	
	</div>
	
<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/cp/footer.php');