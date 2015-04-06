<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/config.php');
	
	require_once( 'header.php' );
?>

	<h1>Add New Hotel</h1>
	
	<form action="action.add.php" method="post" class="validate">
	
		<div class="third-x2">
		
			<h2>Hotel Details</h2>
		
			<dl>
			
				<dt>Hotel Name:</dt>
				<dd>
					<input type="text" name="name" value="<?= $_SESSION['forms']['add-hotel']['name'] ?>" size="35" class="uiText required" />
					<div class="error" id="advice-name" style="display: none;">Enter the name of the hotel</div>
				</dd>
				
				<dt>Address:</dt>
				<dd>
					<input type="text" name="address1" value="<?= $_SESSION['forms']['add-hotel']['address1'] ?>" size="25" class="uiText required" /><br />
					<input type="text" name="address2" value="<?= $_SESSION['forms']['add-hotel']['address2'] ?>" size="25" class="uiText" />
					<div class="error" id="advice-address1" style="display: none;">Enter an address for the hotel</div>
				</dd>
				
				<dt>City:</dt>
				<dd>
					<input type="text" name="city" value="<?= $_SESSION['forms']['add-hotel']['city'] ?>" size="25" class="uiText required" />
					<div class="error" id="advice-city" style="display: none;">Enter a city for the hotel's address</div>
				</dd>
				
				<dt>State:</dt>
				<dd>
					<input type="text" name="state" value="<?= $_SESSION['forms']['add-hotel']['state'] ?>" size="5" maxlength="2" class="uiText required" />
					<div class="error" id="advice-state" style="display: none;">Enter a state for the hotel's address</div>
				</dd>
				
				<dt>Zip Code:</dt>
				<dd>
					<input type="text" name="zipCode" value="<?= $_SESSION['forms']['add-hotel']['zipCode'] ?>" size="12" maxlength="10" class="uiText required" />
					<div class="error" id="advice-zipCode" style="display: none;">Enter a zip code for the hotel's address</div>
				</dd>
				
			</dl>
			
			<br />
			
			<dl>
			
				<dt>Phone Numbers:</dt>
				<dd>
					<input type="text" name="phone1" value="<?= $_SESSION['forms']['add-hotel']['phone1'] ?>" size="15" class="uiText required" rel="mask=us" /><br />
					<input type="text" name="phone2" value="<?= $_SESSION['forms']['add-hotel']['phone2'] ?>" size="15" class="uiText" rel="mask=us" />
					<div class="error" id="advice-phone1" style="display: none;">Enter a phone number for the hotel</div>
				</dd>
				
			</dl>
			
			<br />
			
			<dl>
				
				<dt>URL:</dt>
				<dd>
					<input type="text" name="url" value="<?= $_SESSION['forms']['add-hotel']['url'] ?>" size="35" class="uiText validate-url" />
					<div class="error" id="advice-url" style="display: none;">Enter a valid URL for the hotel (including http://)</div>
				</dd>
			
			</dl>
		
		</div>
		
		<div class="clear"></div>
		
		<br />
		
		<button class="uiButton"><img src="/cp/_assets/_images/icons/add.png" alt="" />Add Hotel</button>
		
	</form>
	
<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/cp/footer.php');