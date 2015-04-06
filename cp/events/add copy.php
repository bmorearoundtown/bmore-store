<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/cp/config.php');
	
	
		
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/cp/header.php');
?>

	<h1>Create Event</h1>
	
	<form action="action.add.php" method="post" class="validate">
	
		<div class="third-x2">
		
			<h2>Event Details</h2>
		
			<dl>
			
				<dt>Event Name:</dt>
				<dd>
					<input type="text" name="name" value="" size="35" class="uiText required" />
					<div class="error" id="advice-name" style="display: none;">Enter a name for the event</div>
				</dd>
				
				<dt>Category:</dt>
				<dd>
			
					<select name="categoryId" class="required validate-selection uiSelect">
						<option value=""> - Select a category -</option>
<?
	$objCategory = new Category();
	$objCategory->loadForForm();
	
	while ($objCategory->loadNext()) {
	?>
						<option value="<?= $objCategory->getId() ?>"><?= $objCategory->getName() ?></option>
	<?
	}
?>
					</select>
					<div class="error" id="advice-categoryId" style="display: none;">Select a category for the event</div>
					
				</dd>
				
				<dt>Description:</dt>
				<dd>
					<textarea name="description" rows="8" cols="50" class="uiText required"></textarea>
					<div class="error" id="advice-description" style="display: none;">Enter a description for the event</div>
				</dd>
				
			</dl>
			
			<div class="clear"></div>
			
			<br />
			
			<dl>
				
				<dt>Start Date:</dt>
				<dd>
					<input type="text" name="startDate" value="" size="15" class="uiText datepicker required" /> @
					<input type="text" name="startTime" value="" size="12" class="uiText timepicker required" />
					<div class="error" id="advice-startDate" style="display: none;">Enter a start date for the event</div>
					<div class="error" id="advice-startTime" style="display: none;">Enter a start time for the event</div>
				</dd>
				
				<dt>End Date:</dt>
				<dd>
					<input type="text" name="endDate" value="" size="15" class="uiText datepicker" /> @
					<input type="text" name="endTime" value="" size="12" class="uiText timepicker" />
				</dd>
				
				<dt>Is Published:</dt>
				<dd>
					<select name="isPublished" class="uiSelect">
						<option value="1">Yes</option>
						<option value="0" selected="selected">No</option>
					</select>
				</dd>
				
			</dl>
			
			<div class="clear"></div>
			
			<br />
			
			<dl>
				
				<dt>Event Logo:</dt>
				<dd>
					<input type="file" name="eventLogoFile" value="" size="35" class="uiText" />
				</dd>
			
			</dl>
			
			<div class="clear"></div>
		
		</div>
	
		<div class="third">
			
			<h2>Location &amp; Hotels</h2>
			
			<dl class="flat">
			
				<dt>Location:</dt>
				<dd>
				
					<select name="locationId" class="uiSelect">
<?
	$objLocation = new Location();
	$objLocation->loadForForm();
	
	if (count($objLocation)) {
	?>
						<option value=""> - Select a location - </option>
	<?
	} else {
	?>
						<option value="">- There are no locations configured -</option>
	<?
	}
	
	while ($objLocation->loadNext()) {
	?>
						<option value="<?= $objLocation->getId() ?>"><?= $objLocation->getName() ?></option>
	<?
	}
?>
					</select>
					
					<div id="locationInfo"><div>
					
					</div></div>
					
					<br />
				
				</dd>
				
				<dt>Hotels:</dt>
				<dd>
				
					<select name="hotelIds[]" class="uiSelect">
<?
	$objHotel = new Hotel();
	$objHotel->loadForForm();
	
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
						<option value="<?= $objHotel->getId() ?>"><?= $objHotel->getName() ?></option>
	<?
	}
?>
					</select>
				
				</dd>
			
			</dl>
			
			<div class="clear"></div>
		
		</div>
		
		<div class="clear"></div>
		
		<br /><br />
		
		<div class="third-x2">
		
			<h2>Custom Registration Fields</h2>
			
<?
	$arrFields = array(
		'fieldName'	=> 'Field Name',
		'fieldType'	=> 'Field Type',
		'tools'		=> '&nbsp;'
	);
	$arrData = array();
	
	$arrData['newField'] = array(
		'fieldName' => '<input type="text" name="registrationFieldName" id="registrationFieldName" size="15" class="uiText" />',
		'fieldType'	=> '<input type="radio" name="registrationFieldType" value="text" id="registrationFieldType_text" /> <label for="registrationFieldType_text">Text Input</label> &nbsp; &nbsp;
						<input type="radio" name="registrationFieldType" value="select" id="registrationFieldType_select" /> <label for="registrationFieldType_select">Drop-Down List</label> &nbsp; &nbsp;
						<input type="radio" name="registrationFieldType" value="checkbox" id="registrationFieldType_checkbox" /> <label for="registrationFieldType_checkbox">Checkbox</label>
						<div class="error" id="advice-registrationField" style="display: none;">Enter a name and select a type for the field</div>',
		'tools'		=> '<a href="#" class="uiButton" onclick="addRegistrationField(event);"><img src="/cp/_assets/_images/icons/add.png" alt="" />Add Field</a>'
	);
	
	$objGrid = new DataGrid('registrationFields', $arrFields, $arrData, '', 'There are no custom registration fields');
	
	echo $objGrid->draw();
	
?>
			<div class="clear"></div>
			
			<br /><br /><br />
		
			<h2>Registration Packages</h2>
		
			<ul id="packages">
			
				<li class="package">
				
					<dl>
					
						<dt>Package Name:</dt>
						<dd>
							<input type="text" name="packageNames[]" size="25" value="" class="uiText" />
						</dd>
						
						<dt>Description:</dt>
						<dd>
							<textarea name="packageDescriptions[]" value="" class="uiText" rows="5" cols="50"></textarea>
						</dd>
						
						<dt>Price:</dt>
						<dd>
							<input type="text" name="packagePrice[]" size="10" value="" class="uiText" />
						</dd>
						
						<dt>Package Deadline:</dt>
						<dd>
							<input type="text" name="packageRegistrationDeadlineDate[]" size="15" value="" class="uiText datepicker" /> @
							<input type="text" name="packageRegistrationDeadlineTime[]" size="12" value="" class="uiText timepicker" />
						</dd>
						
						<dt>Max Packages:</dt>
						<dd>
						
							<div class="float-right">
								<a href="#" class="uiButton" onclick="deletePackage(event);"><img src="/cp/_assets/_images/icons/box_delete.png" alt="" />Remove Package</a>
							</div>
						
							<input type="text" name="packageMaxParticipants[]" size="3" value="" class="uiText" />
						
						</dd>
					
					</dl>
					
					<div class="clear"></div>
				
				</li>
				
				<li class="text-right">
					<a href="#" class="uiButton" onclick="addPackage(event);"><img src="/cp/_assets/_images/icons/box_add.png" alt="" />Add Package</a>
				</li>
			
			</ul>
			
		</div>
		
		<div class="third">
		
			<h2>Registration Options</h2>
		
			<dl>
				
				<dt>Registration Deadline:</dt>
				<dd>
					<input type="text" name="registrationDeadlineDate" value="" size="15" class="uiText datepicker required" /><br />
					<div class="error" id="advice-registrationDeadlineDate" style="display: none;">Enter a date for the registration deadline</div>
					<input type="text" name="registrationDeadlineTime" value="" size="12" class="uiText timepicker required" />
					<div class="error" id="advice-registrationDeadlineTime" style="display: none;">Enter a time for the registration deadline</div>
				</dd>
			
				<dt>Total Tickets:</dt>
				<dd>
					<input type="text" name="maxParticipants" value="" size="5" class="uiText required" />
				</dd>
			
			</dl>
			
			<div class="clear"></div>
			
			<div class="error" id="advice-maxParticipants" style="display: none;">Enter the total number of tickets for this event</div>
			
			<dl>
				
				<dt>Max Tickets / Registration:</dt>
				<dd>
					<input type="text" name="maxTicketsPerRegistration" value="" size="5" class="uiText" />
				</dd>
				
			</dl>
			
			<div class="clear"></div>
					
		</div>
		
		<div class="clear"></div>
		
		<div class="error" id="advice-packages" style="display: none;">There must be at least one package for this event</div>
		
		
		
		<br /><br />
		
		<div class="box">
		
			<a href="#" class="icon-brick-add">Add Registration Upgrades</a>
		
		</div>
		
		<br />
		
		<div class="box" id="discountsBox"><div>
			<a href="#" onclick="showDiscounts(event);" class="icon-money-add">Add Discount Codes</a>
		</div></div>
			
		<div id="discounts" style="display: none;">
<?
	$arrFields = array(
		'code'			=> 'Discount Code',
		'discountType'	=> 'Type',
		'discount'		=> 'Discount Amount',
		'maxDiscounts'	=> 'Max Discounts',
		'tools'			=> '&nbsp;'
	);
	$arrData = array(
		'new'	=> array(
			'code'			=> '<input type="text" id="discountCode" size="20" maxlength="20" class="uiText" rel="placeholder=Code" />',
			'discountType'	=> '<select id="discountType"><option value="fixed">Fixed Amount</option><option value="percentage">Percentage</option></select>',
			'discount'		=> '<input type="text" id="discountAmount" size="10" class="uiText" rel="placeholder=$0.00" />',
			'maxDiscounts'	=> '<input type="text" id="discountMax" size="5" class="uiText" rel="placeholder=##" />',
			'tools'			=> '<a href="#" class="uiButton"><img src="/cp/_assets/_images/icons/accept.png" alt="" />Add Discount</a>'
		)
	);
	
	$objGrid = new DataGrid('discounts', $arrFields, $arrData, '', 'You haven\'t added any discounts yet');
	
	echo $objGrid->draw();
?>
		
		</div>
		
	</form>
	
<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/cp/footer.php');