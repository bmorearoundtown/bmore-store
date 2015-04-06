<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/config.php');
	
	require_once( 'header.php' );
?>

	<div class="page-header">
	
		<h1 class="pull-left collapse-box">Create Location</h1>
		
		<div class="pull-right">
			<a href="/cp/locations/index.php"><i class="fa fa-arrow-left"></i> Back to locations</a>
		</div>
		
		<div class="clearfix"></div>
		
	</div>

	<?
		if ($_SESSION['forms']['add-location']['error']) {
		?>
		<div class="alert alert-danger">
			<p>There was an error creating the location. Please try again.</p>
		</div>
		<?
		}
	?>
	
	<div class="well well-lg">
		
		<form action="action.add.php" method="post" class="form form-horizontal validate">

			<fieldset>
			
				<legend>Location Details</legend>
				
				<div class="form-group">
					
					<label for="locationName" class="col-sm-2 control-label">Name:</label>
					
					<div class="col-sm-10">
						
						<input id="locationName" type="text" name="name" value="<?= $_SESSION['forms']['add-location']['name'] ?>" size="35" class="form-control input-sm" required />
						
					</div>
					
				</div>

				<div class="form-group">
					
					<label for="locationDescription" class="col-sm-2 control-label">Description:</label>
					
					<div class="col-sm-10">
						
						<input id="locationDescription" type="text" name="description" value="<?= $_SESSION['forms']['add-location']['description'] ?>" size="50" class="form-control input-sm" />
						
					</div>
					
				</div>

				<div class="form-group">
					
					<label for="locationAddress1" class="col-sm-2 control-label">Address 1:</label>
					
					<div class="col-sm-10">
						
						<input id="locationAddress1" type="text" name="address1" value="<?= $_SESSION['forms']['add-location']['address1'] ?>" size="25" class="form-control input-sm" required />
						
					</div>
					
				</div>

				<div class="form-group">
					
					<label for="locationAddress2" class="col-sm-2 control-label">Address 2:</label>
					
					<div class="col-sm-10">
						
						<input id="locationAddress2" type="text" name="address2" value="<?= $_SESSION['forms']['add-location']['address2'] ?>" size="25" class="form-control input-sm" />
						
					</div>
					
				</div>

				<div class="form-group">
					
					<label for="locationCity" class="col-sm-2 control-label">City:</label>
					
					<div class="col-sm-10">
						
						<input id="locationCity" style="width: 50%;" type="text" name="city" value="<?= $_SESSION['forms']['add-location']['city'] ?>" size="25" class="form-control input-sm" required />
						
					</div>
					
				</div>

				<div class="form-group">
					
					<label for="locationState" class="col-sm-2 control-label">State:</label>
					
					<div class="col-sm-10">
						
						<input id="locationState" style="width: 20%;" type="text" name="state" value="<?= $_SESSION['forms']['add-location']['state'] ?>" size="5" maxlength="2" class="form-control input-sm" required />
						
						<span class="help-block">Enter 2 letter state abbreviation</span>
					</div>
					
				</div>

				<div class="form-group">
					
					<label for="locationZip" class="col-sm-2 control-label">Zip Code:</label>
					
					<div class="col-sm-10">
						
						<input id="locationZip" style="width: 20%;" type="text" name="zipCode" value="<?= $_SESSION['forms']['add-location']['zipCode'] ?>" size="12" maxlength="10" class="form-control input-sm" placeholder="Ex. 21113" required />
						
					</div>
					
				</div>
				
			</fieldset>

			<hr>
			
			<div class="form-group">
			
				<button type="submit" class="btn btn-lg btn-success col-sm-2 col-md-offset-2"><i class="fa fa-plus"></i> Create Location</button>
			
			</div>

		</form>
	
	</div>
	
<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/cp/footer.php');