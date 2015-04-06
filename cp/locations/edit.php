<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/config.php');
	
	$objLocation = new Location($_GET['location'] ? $_GET['location'] : $_SESSION['forms']['edit-location']['locationId']);

	if (!$_SESSION['forms']['edit-location']['locationId'])
		$_SESSION['forms']['edit-location'] = $objLocation->getDataArray();
	
	require_once( 'header.php' );
?>

	<div class="page-header">
	
		<h1 class="pull-left collapse-box">Edit Location <small> ( <?= $_SESSION['forms']['edit-location']['name'] ?> ) </small></h1>
		
		<div class="pull-right">
			<a href="/cp/locations/index.php"><i class="fa fa-arrow-left"></i> Back to locations</a>
		</div>
		
		<div class="clearfix"></div>
		
	</div>
	
	<?
		if ($_SESSION['forms']['edit-location']['error']) {
		?>
		<div class="errorAlert">
			There was an error saving the location. Please try again.
		</div>
		<?
			$_SESSION['forms']['edit-location']['error'] = false;
		}
	?>
	
	<div class="well well-lg">
		
		<form action="action.edit.php" method="post" class="form form-horizontal validate">
			<input type="hidden" name="locationId" 
				value="<?= $_SESSION['forms']['edit-event']['locationId'] ? $_SESSION['forms']['edit-event']['locationId'] : $objLocation->getId() ?>" />
				
			<fieldset>
			
				<legend>Location Details</legend>
				
				<div class="form-group">
					
					<label for="locationName" class="col-sm-2 control-label">Name:</label>
					
					<div class="col-sm-10">
						
						<input id="locationName" type="text" name="name" value="<?= $_SESSION['forms']['edit-location']['name'] ?>" size="35" class="form-control input-sm" required />
						
					</div>
					
				</div>

				<div class="form-group">
					
					<label for="locationDescription" class="col-sm-2 control-label">Description:</label>
					
					<div class="col-sm-10">
						
						<input id="locationDescription" type="text" name="description" value="<?= $_SESSION['forms']['edit-location']['description'] ?>" size="50" class="form-control input-sm" />
						
					</div>
					
				</div>

				<div class="form-group">
					
					<label for="locationAddress1" class="col-sm-2 control-label">Address 1:</label>
					
					<div class="col-sm-10">
						
						<input id="locationAddress1" type="text" name="address1" value="<?= $_SESSION['forms']['edit-location']['address1'] ?>" size="25" class="form-control input-sm" required />
						
					</div>
					
				</div>

				<div class="form-group">
					
					<label for="locationAddress2" class="col-sm-2 control-label">Address 2:</label>
					
					<div class="col-sm-10">
						
						<input id="locationAddress2" type="text" name="address2" value="<?= $_SESSION['forms']['edit-location']['address2'] ?>" size="25" class="form-control input-sm" />
						
					</div>
					
				</div>

				<div class="form-group">
					
					<label for="locationCity" class="col-sm-2 control-label">City:</label>
					
					<div class="col-sm-10">
						
						<input id="locationCity" style="width: 50%;" type="text" name="city" value="<?= $_SESSION['forms']['edit-location']['city'] ?>" size="25" class="form-control input-sm" required />
						
					</div>
					
				</div>

				<div class="form-group">
					
					<label for="locationState" class="col-sm-2 control-label">State:</label>
					
					<div class="col-sm-10">
						
						<input id="locationState" style="width: 20%;" type="text" name="state" value="<?= $_SESSION['forms']['edit-location']['state'] ?>" size="5" maxlength="2" class="form-control input-sm" required />
						
						<span class="help-block">Enter 2 letter state abbreviation</span>
					</div>
					
				</div>

				<div class="form-group">
					
					<label for="locationZip" class="col-sm-2 control-label">Zip Code:</label>
					
					<div class="col-sm-10">
						
						<input id="locationZip" style="width: 20%;" type="text" name="zipCode" value="<?= $_SESSION['forms']['edit-location']['zipCode'] ?>" size="12" maxlength="10" class="form-control input-sm" placeholder="Ex. 21113" required />
						
					</div>
					
				</div>

				<div class="form-group">
					
					<label for="locationActive" class="col-sm-2 control-label">Is Active:</label>
					
					<div class="col-sm-10">
						
						<select id="locationActive" name="isActive" class="form-control input-sm" style="width: 200px;">
							<option value="1"<?= $objLocation->getIsActive() ? ' selected="selected"' : '' ?>>Yes</option>
							<option value="0"<?= !$objLocation->getIsActive() ? ' selected="selected"' : '' ?>>No</option>
						</select>
						
					</div>
					
				</div>
				
			</fieldset>

			<hr>
			
			<div class="form-group">
			
				<button type="submit" class="btn btn-lg btn-success col-sm-2 col-md-offset-2"><i class="fa fa-plus"></i> Save Location</button>
			
			</div>

		</form>
	
	</div>
	
<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/cp/footer.php');