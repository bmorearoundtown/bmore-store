<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/config.php');
	
	$objCategory = new Category($_GET['category'] ? $_GET['category'] : $_SESSION['forms']['edit-category']['categoryId']);

	if (!$_SESSION['forms']['edit-category']['categoryId'])
		$_SESSION['forms']['edit-category'] = $objCategory->getDataArray();
	
	
	require_once( 'header.php' );
?>

	<div class="page-header">
	
		<h1 class="pull-left collapse-box">Edit Category <small> ( <?= $_SESSION['forms']['edit-category']['name'] ?> ) </small></h1>
		
		<div class="pull-right">
			<a href="/cp/categories/index.php"><i class="fa fa-arrow-left"></i> Back to categories</a>
		</div>
		
		<div class="clearfix"></div>
		
	</div>
	
<?
	if ($_SESSION['forms']['edit-category']['error']) {
	?>
	<div class="alert alert-danger">
		<p>There was an error saving the category. Please try again.</p>
	</div>
	<?
	}
?>

	<div class="well well-lg">
		
		<form action="action.edit.php" method="post" class="form form-horizontal validate">
			<input type="hidden" name="categoryId" value="<?= $_SESSION['forms']['edit-category']['categoryId'] ?>" />
				
			<fieldset>
			
				<legend>Category Details</legend>
				
				<div class="form-group">
					
					<label for="categoryName" class="col-sm-2 control-label">Name:</label>
					
					<div class="col-sm-10">
						
						<input id="categoryName" type="text" name="name" value="<?= $_SESSION['forms']['edit-category']['name'] ?>" size="35" class="form-control input-sm" required />
						
					</div>
					
				</div>

				<div class="form-group">
					
					<label for="categoryActive" class="col-sm-2 control-label">Is Active:</label>
					
					<div class="col-sm-10 col-sm-7">
						
						<select id="categoryActive" name="isActive" class="form-control input-sm" style="width: 200px;">
							<option value="1"<?= $objCategory->getIsActive() ? ' selected="selected"' : '' ?>>Yes</option>
							<option value="0"<?= !$objCategory->getIsActive() ? ' selected="selected"' : '' ?>>No</option>
						</select>
						
					</div>
					
				</div>
				
			</fieldset>

			<hr>
			
			<div class="form-group">
			
				<button type="submit" class="btn btn-lg btn-success col-sm-2 col-md-offset-2"><i class="fa fa-plus"></i> Save Category</button>
			
			</div>
			
		</form>
	
	</div>
	
<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/cp/footer.php');