<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/config.php');
	
	$GLOBALS['config']->addPageCSSFullPath( '/cp/_assets/_css/summernote.css' );
	$GLOBALS['config']->addPageCSSFullPath( '/cp/_assets/_css/summernote-bs3.css' );
	$GLOBALS['config']->addPageJSFullPath( '/cp/_assets/_js/lib/summernote.min.js' );
	
	$incomingEventId = $_GET['event'] ?  $_GET['event'] : 0;

	if( $incomingEventId ){
		$_SESSION['forms']['add-package']['eventId'] = $incomingEventId;
	}
	
	require_once('header.php');
	
?>
	
	<div class="page-header">
		<h1>Create New Package for Event
			<? if( $incomingEventId ){ ?>
				
				<a href="/cp/events/view.php?event=<?= $incomingEventId ?>" style="font-size: .5em; font-weight: normal;" class="pull-right"><i class="fa fa-arrow-left cushion-right"></i>Back to event details page</a>
				
			<? } ?>
		</h1>
	</div>
	
	<?
		if ($_SESSION['forms']['add-package']['error']) {
		?>
		<div class="alert alert-danger">
			<p>There was an error creating the package for the event. Please try again.</p>
			<p><?= $_SESSION['forms']['add-package']['exception'] ?></p>
		</div>
		<?
		}
	?>
	
	<div class="well well-lg">
	
		<form action="action.add.php" method="post" class="form form-horizontal validate">

			<fieldset>
			
				<legend>Package Details</legend>
				
				<div class="form-group">
					
					<label for="eventId" class="col-sm-2 control-label">Associated Event:</label>
					
					<div class="col-sm-10 col-md-7">
						
						<select id="eventId" style="width: auto;" name="eventId" class="form-control" required>
							<option value=""> - Select an event for association -</option>
							<?
								
								$objEventForForm = new Event();
								$objEventForForm->loadForForm();
								
								while ($objEventForForm->loadNext()) {
								?>
									<option value="<?= $objEventForForm->getId() ?>"<?= $_SESSION['forms']['add-package']['eventId'] == $objEventForForm->getId() ? ' selected="selected"' : '' ?>>
										<?= $objEventForForm->getName() ?> - <?= $objEventForForm->getDatesDisplay() ?>
									</option>
								<?
								}
							?>
						</select>						
						
						<p id="selectedEventInformation" class="cushion-top"></p>
						
					</div>
					
				</div>

				<div class="form-group">
					
					<label for="packageName" class="col-sm-2 control-label">Name:</label>
					
					<div class="col-sm-10">

						<input id="packageName" type="text" name="name" value="<?= $_SESSION['forms']['add-package']['name'] ?>" class="form-control" required />

					</div>
					
				</div>
				
				<div class="form-group">
					
					<label for="packageDescription" class="col-sm-2 control-label">Short Description:</label>
					
					<div class="col-sm-10">

						<textarea id="packageDescription" name="description" rows="8" cols="50" class="form-control summernote">
							<?= $_SESSION['forms']['add-package']['description'] ?>
						</textarea>

					</div>
					
				</div>
				
				<div class="form-group">
					
					<label for="registrationStartDate" class="col-sm-2 control-label">Registration Start Date:</label>
					
					<div class="col-sm-10"> 

						<div class="input-group" style="width: 200px; float: left; margin-right: 10px;">
							<input type="text" id="registrationStartDate" name="registrationStartDate" 
								value="<?= $_SESSION['forms']['add-package']['registrationStartDate'] ?>" size="15" class="form-control date-picker" required />
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						</div>
						
						<div class="input-group" style="width: 200px;">						
							<input type="text" name="registrationStartTime" value="<?= $_SESSION['forms']['add-package']['registrationStartTime'] ?>" size="12" class="form-control time-picker" required />
							<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>							
						</div>
						
					</div>
					
				</div>

				<div class="form-group">
					
					<label for="registrationDeadlineDate" class="col-sm-2 control-label">Registration Deadline Date:</label>
					
					<div class="col-sm-10"> 

						<div class="input-group" style="width: 200px; float: left; margin-right: 10px;">
							<input type="text" id="registrationDeadlineDate" name="registrationDeadlineDate" 
								value="<?= $_SESSION['forms']['add-package']['registrationDeadlineDate'] ?>" size="15" class="form-control date-picker" required />
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						</div>
						
						<div class="input-group" style="width: 200px;">						
							<input type="text" name="registrationDeadlineTime" value="<?= $_SESSION['forms']['add-package']['registrationDeadlineTime'] ?>" size="12" class="form-control time-picker" required />
							<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>							
						</div>
						
					</div>
					
				</div>

				<div class="form-group">
					
					<label class="col-sm-2 control-label">Is Hidden:</label>

					<div class="col-sm-10">

						<label for="isHiddenPackageYes">
						
							<input id="isHiddenPackageYes" type="radio" class="checkbox-inline" name="isHidden" value="1"
							
							<? if( $_SESSION['forms']['add-package']['isHidden'] ) { ?>
								checked="checked" 
							<? } ?>
							
							/> Yes
							
						</label>
						
						<label for="isHiddenPackageNo">
						
							<input id="isHiddenPackageNo" type="radio" class="checkbox-inline" name="isHidden" value="0"
								
								<? if( !$_SESSION['forms']['add-package']['isHidden'] ) { ?>
									checked="checked" 
								<? } ?>
							
							/> No
							
						</label>	
						
						<p class="help-block">Determines whether a package should be displayed on the page at all.</p>
						
					</div>
			
				</div>

				<div class="form-group">
					
					<label class="col-sm-2 control-label">At Door:</label>

					<div class="col-sm-10">

						<label for="atdoorYes">
						
							<input id="atdoorYes" type="radio" class="checkbox-inline" name="atDoor" value="1"
							
							<? if( $_SESSION['forms']['add-package']['atDoor'] ) { ?>
								checked="checked" 
							<? } ?>
							
							/> Yes
							
						</label>
						
						<label for="atdoorNo">
						
							<input id="atdoorNo" type="radio" class="checkbox-inline" name="atDoor" value="0"
								
								<? if( !$_SESSION['forms']['add-package']['atDoor'] ) { ?>
									checked="checked" 
								<? } ?>
							
							/> No
							
						</label>	
						
						<p class="help-block">Determines whether a package should be sold at the door.</p>
						
					</div>
			
				</div>
								
			</fieldset>
		
			<fieldset>
			
				<legend>Package Ticket Details</legend>
			
				<div class="form-group">
					
					<label for="packagePrice" class="col-sm-2 control-label">Price:</label>
					
					<div class="col-sm-10">

						<input id="packagePrice" type="text" name="price" style="max-width: 150px;" value="<?= $_SESSION['forms']['add-package']['price'] ? $_SESSION['forms']['add-package']['price'] : 0.00 ?>" class="form-control" required />
						
						<p class="help-block">Enter a float value e.g 50.00</p>
						
					</div>
					
				</div>
				
				<div class="form-group">
					
					<label for="maxParticipants" class="col-sm-2 control-label">Ticket Inventory:</label>
					
					<div class="col-sm-10">

						<input id="maxParticipants" type="text" name="maxParticipants" style="max-width: 150px;" value="<?= $_SESSION['forms']['add-package']['maxParticipants'] ? $_SESSION['forms']['add-package']['maxParticipants'] : 0 ?>" class="form-control" required />
						
						<p class="help-block">Determines whether a package is sold out or not.</p>
						
					</div>
					
				</div>
				
				<div class="form-group">
					
					<label for="ticketsPerPackage" class="col-sm-2 control-label">Tickets per Package:</label>
					
					<div class="col-sm-10">

						<input id="ticketsPerPackage" type="text" name="ticketsPerPackage" style="max-width: 150px;" value="<?= $_SESSION['forms']['add-package']['ticketsPerPackage'] ? $_SESSION['forms']['add-package']['ticketsPerPackage'] : 1 ?>" class="form-control" required />
						
						<p class="help-block">Ex. Quad-Room Packages would have this value set to 4.</p>
						
					</div>
					
				</div>
				
			</fieldset>
			
			<hr>
			
			<div class="form-group">
			
				<button type="submit" class="btn btn-lg btn-success col-sm-2 col-md-offset-2" style="font-size: 1.25em;"><i class="fa fa-plus"></i> Create Package</button>
			
			</div>
			
		</form>
	
	</div>
	
	<script>
		
		var PackageAdd = (function(){
			
			function updateEventInformation( e ) {
				
				var $el = $( e.currentTarget );
				var $informationContainer = $( "#selectedEventInformation" );
				var $selectedEventOption = $el.find( "option:selected" );
				var selectedEventId = $selectedEventOption.val();
				var selectedEventText = $selectedEventOption.text();
				
				$informationContainer.html( "<a href='/cp/events/view.php?event=" + selectedEventId + "' target='_blank'>Click here to view event <span class='text-muted'>[" + selectedEventText + "]</span> in another window.</a>" );
				
				return;
			}
			
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
				
				$( '#eventId' ).on( "change.add.package", updateEventInformation );
				
				return;
			}
			
			function init(){
				
				createDateTimePopups();
				
				createWYSIWYG();
				
				bindEvents();
				
				$( '#eventId' ).trigger( "change.add.package" );
				
				return;
			}
			
			return {
			
				"init": init
				
			}
			
		}());
		
		$( function(){
			
			PackageAdd.init();
			
		});
		
	</script>
	
<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/cp/footer.php');