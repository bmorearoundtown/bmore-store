<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/config.php');
	
	$GLOBALS['config']->addPageCSSFullPath( '/cp/_assets/_css/summernote.css' );
	$GLOBALS['config']->addPageCSSFullPath( '/cp/_assets/_css/summernote-bs3.css' );
	$GLOBALS['config']->addPageJSFullPath( '/cp/_assets/_js/lib/summernote.min.js' );
	
	require_once('header.php');
	
?>
	
	<div class="page-header">
		<h1>Create Event</h1>
	</div>
	
	<?
		if ($_SESSION['forms']['add-event']['error']) {
		?>
		<div class="alert alert-danger">
			<p>There was an error creating the event. Please try again.</p>
		</div>
		<?
		}
	?>
	
	<div class="well well-lg">
	
		<form action="action.add.php" enctype="multipart/form-data" method="post" class="form form-horizontal validate">
			
			<fieldset>
			
				<legend>Event Details</legend>
				
				<div class="form-group">
					
					<label for="eventName" class="col-sm-2 control-label">Event Name:</label>
					
					<div class="col-sm-10 col-md-7">
						
						<input id="eventName" type="text" name="name"
								value="<?= $_SESSION['forms']['add-event']['name'] ?>" size="35" 
								class="form-control required" required />
						
					</div>
					
				</div>

				<div class="form-group">
					
					<label for="eventCategory" class="col-sm-2 control-label">Category:</label>
					
					<div class="col-sm-10">
						
						<select id="eventCategory" style="width: auto;" name="categoryId" class="form-control required validate-selection uiSelect" required>
								<option value=""> - Select a category -</option>
								<?
									$objCategory = new Category();
									$objCategory->loadForForm();
									
									while ($objCategory->loadNext()) {
									?>
										<option value="<?= $objCategory->getId() ?>"<?= $_SESSION['forms']['add-event']['categoryId'] == $objCategory->getId() ? ' selected="selected"' : '' ?>><?= $objCategory->getName() ?></option>
									<?
									}
								?>
							</select>
							
							<div class="error" id="advice-categoryId" style="display: none;">Select a category for the event</div>
						
					</div>
					
				</div>
				
				<div class="form-group">
					
					<label for="eventDescription" class="col-sm-2 control-label">Description:</label>
					
					<div class="col-sm-10">

						<textarea id="eventDescription" name="description" rows="8" cols="50" class="form-control summernote" required>
							<?= $_SESSION['forms']['add-event']['description'] ?>
						</textarea>
						
					</div>
					
				</div>

				<div class="form-group">
					
					<label for="startDate" class="col-sm-2 control-label">Start Date:</label>
					
					<div class="col-sm-10"> 

						<div class="input-group" style="width: 200px; float: left; margin-right: 10px;">
							<input type="text" id="startDate" name="startDate" value="<?= $_SESSION['forms']['add-event']['startDate'] ?>" size="15" class="form-control date-picker" required />
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						</div>
						
						<div class="input-group" style="width: 200px;">						
							<input type="text" name="startTime" value="<?= $_SESSION['forms']['add-event']['startTime'] ?>" size="12" class="form-control time-picker" required />
							<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>							
						</div>
						
					</div>
					
				</div>

				<div class="form-group">
					
					<label for="endDate" class="col-sm-2 control-label">End Date:</label>
					
					<div class="col-sm-10">

						<div class="input-group" style="width: 200px; float: left; margin-right: 10px;">
							<input id="endDate" name="endDate" type="text" class="form-control date-picker" size="15" value="<?= $_SESSION['forms']['add-event']['endDate'] ?>">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						</div>
						
						<div class="input-group" style="width: 200px;">	
							<input type="text" name="endTime" value="<?= $_SESSION['forms']['add-event']['endTime'] ?>" size="12" class="form-control time-picker" />
							<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>							
						</div>
						
					</div>
					
				</div>
				
				<div class="form-group">
					
					<label for="eventImage" class="col-sm-2 control-label">Event Image:</label>

					<div class="col-sm-10">
						
						<input type="file" name="eventLogoFile" value="" size="35" class="btn btn-default" />
							
					</div>
					
				</div>
			
			</fieldset>
			
			<fieldset>
				
				<legend>Location and Hotels</legend>

				<div class="form-group">
					
					<label for="eventLocations" class="col-sm-2 control-label">Locations:</label>

					<div class="col-sm-10">
						
						<?
							$objLocation = new Location();
							$objLocation->loadForForm();
						?>				
						
						<select id="eventLocations" style="width: auto;" name="locationId[]" class="form-control"<?= count($objLocation) ? '' : ' disabled="disabled"' ?> multiple>
							<?	
								if (count($objLocation)) {
								?>
									<option value=""> - Select locations - </option>
								<?
								} else {
								?>
									<option value=""> - There are no locations configured - </option>
								<?
								}
								
								while ($objLocation->loadNext()) {
								?>
									<option value="<?= $objLocation->getId() ?>"<?= $_SESSION['forms']['add-event']['locationId'] == $objLocation->getId() ? ' selected="selected"' : '' ?>><?= $objLocation->getName() ?></option>
								<?
								}
							?>
						</select>
						
						<p id="eventLocationInformation" class="help-block"></p>
							
					</div>
					
				</div>

				<div class="form-group">
					
					<label for="eventHotels" class="col-sm-2 control-label">Hotels:</label>

					<div class="col-sm-10">

						<?
							$objHotel = new Hotel();
							$objHotel->loadForForm();
						?>		
						
						<select name="hotelIds[]" class="uiSelect form-control" multiple size="5"<?= count($objHotel) ? '' : ' disabled="disabled"' ?>>
						
							<?	
								if (count($objHotel)) {
								?>
									<option value=""> - Select a hotel - </option>
								<?
								} else {
								?>
									<option value="">- There are no hotels configured -</option>
								<?
								}
								
								while ($objHotel->loadNext()) {
								?>
									<option value="<?= $objHotel->getId() ?>"<?= in_array($objHotel->getId(), $_SESSION['forms']['add-event']['hotelIds']) ? ' selected="selected"' : '' ?>><?= $objHotel->getName() ?></option>
								<?
								}
							?>
							
						</select>
						
					</div>
				
				</div>
				
			</fieldset>

			<fieldset>
				
				<legend>Registration Options</legend>

				<div class="form-group">
					
					<label for="eventDeadline" class="col-sm-2 control-label">Registration Deadline:</label>

					<div class="col-sm-10">
						
						<div class="input-group" style="width: 200px; float: left; margin-right: 10px;">
							<input id="eventDeadline" type="text" name="registrationDeadlineDate" value="<?= $_SESSION['forms']['add-event']['registrationDeadlineDate'] ?>" size="15" class="form-control date-picker required" />
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						</div>
						
						<div class="input-group" style="width: 200px;">	
							<input type="text" name="registrationDeadlineTime" value="<?= $_SESSION['forms']['add-event']['registrationDeadlineTime'] ?>" size="12" class="form-control time-picker" required />
							<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
						</div>
						
					</div>
				
				</div>

				<div class="form-group">
					
					<label for="eventInventory" class="col-sm-2 control-label">Total Ticket Inventory:</label>

					<div class="col-sm-10">

						<input id="eventInventory" type="text" name="maxParticipants" style="width: 100px;" value="<?= $_SESSION['forms']['add-event']['maxParticipants'] ? $_SESSION['forms']['add-event']['maxParticipants'] : 0 ?>" size="5" class="form-control required" required />
						
						<div class="error" id="advice-maxParticipants" style="display: none;">Enter the total number of tickets for this event</div>
						
					</div>
			
				</div>

				<div class="form-group">
					
					<label for="eventMaxTickets" class="col-sm-2 control-label">Max tickets per Registration:</label>

					<div class="col-sm-10">

						<input id="eventMaxTickets" type="text" style="width: 100px;" name="maxTicketsPerRegistration" value="<?= $_SESSION['forms']['add-event']['maxTicketsPerRegistration'] ?  $_SESSION['forms']['add-event']['maxTicketsPerRegistration'] : 4 ?>" size="5" class="form-control" required />
						
					</div>
			
				</div>

				<div class="form-group">
					
					<label class="col-sm-2 control-label">Is Active:</label>

					<div class="col-sm-10">

						<label for="eventIsActiveYes">
						
							<input id="eventIsActiveYes" type="radio" class="checkbox-inline" name="isActive" value="1"
							
							<? if( $_SESSION['forms']['add-event']['isActive'] ) { ?>
								checked="checked" 
							<? } ?>
							
							/> Yes
							
						</label>
						
						<label for="eventIsActiveNo">
						
							<input id="eventIsActiveNo" type="radio" class="checkbox-inline" name="isActive" value="0"
								
								<? if( !$_SESSION['forms']['add-event']['isActive'] ) { ?>
									checked="checked" 
								<? } ?>
							
							/> No
							
						</label>	
						
						
					</div>
			
				</div>

				<div class="form-group">
					
					<label class="col-sm-2 control-label">Is Published:</label>

					<div class="col-sm-10">

						<label for="eventIsPublishedYes">
						
							<input id="eventIsPublishedYes" type="radio" class="checkbox-inline" name="isPublished" value="1"
							
							<? if( $_SESSION['forms']['add-event']['isPublished'] ) { ?>
								checked="checked" 
							<? } ?>
							
							/> Yes
							
						</label>
						
						<label for="eventIsPublishedNo">
						
							<input id="eventIsPublishedNo" type="radio" class="checkbox-inline" name="isPublished" value="0"
								
								<? if( !$_SESSION['forms']['add-event']['isPublished'] ) { ?>
									checked="checked" 
								<? } ?>
							
							/> No
							
						</label>	
						
						
					</div>
			
				</div>
				
			</fieldset>
			
			<hr>
			
			<div class="form-group">
			
				<button type="submit" class="btn btn-lg btn-success col-sm-2 col-md-offset-2"><i class="fa fa-plus"></i> Create Event</button>
			
			</div>
			
		</form>
	
	</div>
	
	<script>
		
		var EventAdd = (function(){
			
			function createDateTimePopups() {

				$( '.date-picker' ).datepicker({});

				$( '.time-picker' ).timepicker({});
				
				return;
			}
			
			function createWYSIWYG(){
				
				$( '.summernote' ).summernote();
				
				return;
				
			}
			
			function bindEvents(){
			
				return;
			}
			
			function init(){
				
				createDateTimePopups();
				
				createWYSIWYG();
				
				return;
			}
			
			return {
			
				"init": init
				
			}
			
		}());
		
		$( function(){
			
			EventAdd.init();
			
		});
		
	</script>
	
<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/cp/footer.php');