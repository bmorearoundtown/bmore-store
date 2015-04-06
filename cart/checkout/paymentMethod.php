<div class="row">

	<div class="col-md-12">

		<div id="paymentInformationContainer" class="well well-lg" style="color: #222; padding: 30px 30px;">
			
			<div style="padding: 30px 30px;">
			
				<h2 class="collapse-box" style="margin-bottom: 30px;">Payment Method</h2>
				
				<label style="font-size: 1.5em; font-family: 'Oswald', sans-serif;">
					<input name="paymentMethod" type="radio" value="1" disabled /> Credit/Debit Card by AUTHORIZE.NET 
					<p class="text-danger" style="padding-left: 20px;">Coming Soon</p>
				</label>
				
				<p class="help-block text-muted">We do not store any information that is entered on this page.</p>
				
				<fieldset id="authorizeNetPayment" style="margin-top: 10px;">
	
					<div class="form-group">
						
						<label class="col-sm-3 control-label" for="card-holder-name">Name on Card</label>
						
						<div class="col-sm-9">
						
							<input type="text" class="form-control" name="card-holder-name" id="card-holder-name" placeholder="Card Holder's Name" title="Please enter your name as it appears exactly on your card">
							
							<p class="help-block validation-error text-danger">Please enter your name as it appears exactly on your card.</p>
							
						</div>
						
					</div>
					
					<div class="form-group">
						
						<label class="col-sm-3 control-label" for="card-number">Card Number</label>
						
						<div class="col-sm-9">
						
							<input type="text" class="form-control" name="card-number" id="card-number" placeholder="Debit/Credit Card Number" title="Please enter your card number">
							
							<p class="help-block validation-error text-danger">Please enter your card number.</p>
							
						</div>
						
					</div>
					
					<div class="form-group">
					
						<label class="col-sm-3 control-label" for="expiry-month">Expiration Date</label>
						
						<div class="col-sm-9">
						
							<div class="row">
							
								<div class="col-xs-6">
								
									<select class="form-control col-sm-2" name="expiry-month" title="Please select an expiration month" id="expiry-month">
										<option value="" selected>Month</option>
										<option value="1">Jan (01)</option>
										<option value="2">Feb (02)</option>
										<option value="3">Mar (03)</option>
										<option value="4">Apr (04)</option>
										<option value="5">May (05)</option>
										<option value="6">June (06)</option>
										<option value="7">July (07)</option>
										<option value="8">Aug (08)</option>
										<option value="9">Sep (09)</option>
										<option value="10">Oct (10)</option>
										<option value="11">Nov (11)</option>
										<option value="12">Dec (12)</option>
									</select>
									
									<p class="help-block validation-error text-danger">Please select an expiration month.</p>
									
								</div>
								
								<div class="col-xs-6">
								
									<select class="form-control" name="expiry-year">
										<option value="14" selected>2014</option>
										<option value="15">2015</option>
										<option value="16">2016</option>
										<option value="17">2017</option>
										<option value="18">2018</option>
										<option value="19">2019</option>
										<option value="20">2020</option>
										<option value="21">2021</option>
										<option value="22">2022</option>
										<option value="23">2023</option>
									</select>
									
								</div>
								
							</div>
							
						</div>
						
					</div>
					
					<div class="form-group">
					
						<label class="col-sm-3 control-label" for="cvv">Card CVV</label>
						
						<div class="col-sm-3">
						
							<input type="text" class="form-control" name="cvv" id="cvv" placeholder="Security Code" title="Please enter your security code">
							
							<p class="help-block validation-error text-danger">Please enter your security code.</p>
							
						</div>
						
					</div>
	
				</fieldset>
				
				<fieldset style="padding-top: 20px; border-top: 1px solid #ccc;">
				
					<label style="font-size: 1.5em; font-family: 'Oswald', sans-serif;">
						<input name="paymentMethod" type="radio" value="0" checked="checked" /> PayPal
					</label>
	
					<p class="help-block text-muted">Upon confirmation you will be taken to through our paypal payment processor.</p>
					
				</fieldset>
				
				<div class="row text-center" style="margin-top: 30px;">
	
					<a href="javascript:void(0)" class="btn btn-lg btn-default back-step"> 
						<i class="fa fa-angle-double-left cushion-right"></i>Go Back to Step 1
					</a>
					
					<button type="button" class="btn btn-lg btn-info next-step"> 
						<i class="fa fa-angle-double-right cushion-right"></i>Go to Final Step
					</button>
	
				</div>
			
			</div>
			
		</div>

	</div>

</div>

