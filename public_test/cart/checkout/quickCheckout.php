<div class="row">

	<div class="col-md-12">
		
		<div id="billingInformationContainer" class="well" style="border-radius: 0;">
			
			<h1 class="text-center collapse-box text-primary">Store Checkout</h1>
			
			<hr>
			
			<fieldset>

				<h2 class="collapse-box text-ravens" style="margin-bottom: 20px;">Billing Information</h2>
				
				<p class="help-block validation-error text-danger">There were some issues with your submission.</p>

				<?php if( $detect->isMobile() || $detect->isTablet() ){ ?>
					
					<div class="form-group">

						<label class="col-md-3"><span class="text-danger">*</span>Email:</label>

						<div class="col-md-5">

							<input type="email" name="emailAddress" size="35"
								maxlength="50" placeholder="Email Address" title="Please enter your email"
								value="<?= $_SESSION['forms']['register']['emailAddress'] ?>"
								class="form-control" required />

							<p class="help-block validation-error text-danger">Please enter your email.</p>

						</div>

					</div>
				
				<?php } ?>
					
				<div class="form-group">
										
					<label class="col-md-3"><span class="text-danger">*</span>First Name:</label>

					<div class="col-md-4">

						<input type="text" name="firstName" size="25"
							maxlength="35" placeholder="First Name" title="Please enter your first name"
							value="<?= $_SESSION['forms']['register']['firstName'] ?>"
							class="form-control" required />

						<p class="help-block validation-error text-danger">Please enter your first name.</p>

					</div>

				</div>

				<div class="form-group">

					<label class="col-md-3"><span class="text-danger">*</span>Last Name:</label>

					<div class="col-md-4">

						<input type="text" name="lastName" size="25"
							maxlength="35" placeholder="Last Name" title="Please enter your last name"
							value="<?= $_SESSION['forms']['register']['lastName'] ?>"
							class="form-control" required />

						<p class="help-block validation-error text-danger">Please enter your last name.</p>

					</div>

				</div>
				
				<?php if( !($detect->isMobile() || $detect->isTablet()) ){ ?>
				
					<div class="form-group">

						<label class="col-md-3"><span class="text-danger">*</span>Email:</label>

						<div class="col-md-5">

							<input type="email" name="emailAddress" size="35"
								maxlength="50" placeholder="Email Address" title="Please enter your email"
								value="<?= $_SESSION['forms']['register']['emailAddress'] ?>"
								class="form-control" required />

							<p class="help-block validation-error text-danger">Please enter your email.</p>

						</div>

					</div>
				
				<?php } ?>
				
				<div class="form-group">

					<label class="col-md-3"><span class="text-danger">*</span>Phone Number:</label>

					<div class="col-md-4">

						<input type="tel" name="phoneNumber" size="20" placeholder="Phone Number"
							value="<?= $_SESSION['forms']['register']['phoneNumber'] ?>"
							class="form-control" rel="mask=us" required />
						
						<p class="help-block validation-error text-danger">Please enter your phone number.</p>
						
					</div>

				</div>

				<div class="form-group">

					<label class="col-md-3"><span class="text-danger">*</span>Address 1:</label>

					<div class="col-md-6">

						<input type="text" name="address1" size="35"
							maxlength="50" placeholder="Primary Address" title="Please enter your address"
							value="<?= $_SESSION['forms']['register']['address1'] ?>"
							class="form-control" required />

						<p class="help-block validation-error text-danger">Please enter your address.</p>

					</div>

				</div>

				<div class="form-group">

					<label class="col-md-3">Address 2:</label>

					<div class="col-md-6">

						<input type="text" name="address2" size="35"
							maxlength="50" placeholder="Secondary Address"
							value="<?= $_SESSION['forms']['register']['address2'] ?>"
							class="form-control" />

					</div>

				</div>

				<div class="form-group">

					<label class="col-md-3"><span class="text-danger">*</span>City:</label>

					<div class="col-md-4">

						<input type="text" name="city" size="25" maxlength="50" placeholder="City" title="Please enter your city"
							value="<?= $_SESSION['forms']['register']['city'] ?>"
							class="form-control" required />

						<p class="help-block validation-error text-danger">Please enter your city.</p>

					</div>

				</div>

				<div class="form-group">

					<label class="col-md-3"><span class="text-danger">*</span>State:</label>

					<div class="col-md-3">

						<select name="state" class="form-control" title="Please select a state" required>
							<option value="">State</option>
							<option value="AL"
								<?= $_SESSION['forms']['register']['state'] == 'AL' ? ' selected="selected"' : '' ?>>Alabama</option>
							<option value="AK"
								<?= $_SESSION['forms']['register']['state'] == 'AK' ? ' selected="selected"' : '' ?>>Alaska</option>
							<option value="AZ"
								<?= $_SESSION['forms']['register']['state'] == 'AZ' ? ' selected="selected"' : '' ?>>Arizona</option>
							<option value="AR"
								<?= $_SESSION['forms']['register']['state'] == 'AR' ? ' selected="selected"' : '' ?>>Arkansas</option>
							<option value="CA"
								<?= $_SESSION['forms']['register']['state'] == 'CA' ? ' selected="selected"' : '' ?>>California</option>
							<option value="CO"
								<?= $_SESSION['forms']['register']['state'] == 'CO' ? ' selected="selected"' : '' ?>>Colorado</option>
							<option value="CT"
								<?= $_SESSION['forms']['register']['state'] == 'CT' ? ' selected="selected"' : '' ?>>Connecticut</option>
							<option value="DE"
								<?= $_SESSION['forms']['register']['state'] == 'DE' ? ' selected="selected"' : '' ?>>Delaware</option>
							<option value="DC"
								<?= $_SESSION['forms']['register']['state'] == 'DC' ? ' selected="selected"' : '' ?>>District
								Of Columbia</option>
							<option value="FL"
								<?= $_SESSION['forms']['register']['state'] == 'FL' ? ' selected="selected"' : '' ?>>Florida</option>
							<option value="GA"
								<?= $_SESSION['forms']['register']['state'] == 'GA' ? ' selected="selected"' : '' ?>>Georgia</option>
							<option value="HI"
								<?= $_SESSION['forms']['register']['state'] == 'HI' ? ' selected="selected"' : '' ?>>Hawaii</option>
							<option value="ID"
								<?= $_SESSION['forms']['register']['state'] == 'ID' ? ' selected="selected"' : '' ?>>Idaho</option>
							<option value="IL"
								<?= $_SESSION['forms']['register']['state'] == 'IL' ? ' selected="selected"' : '' ?>>Illinois</option>
							<option value="IN"
								<?= $_SESSION['forms']['register']['state'] == 'IN' ? ' selected="selected"' : '' ?>>Indiana</option>
							<option value="IA"
								<?= $_SESSION['forms']['register']['state'] == 'IA' ? ' selected="selected"' : '' ?>>Iowa</option>
							<option value="KS"
								<?= $_SESSION['forms']['register']['state'] == 'KS' ? ' selected="selected"' : '' ?>>Kansas</option>
							<option value="KY"
								<?= $_SESSION['forms']['register']['state'] == 'KY' ? ' selected="selected"' : '' ?>>Kentucky</option>
							<option value="LA"
								<?= $_SESSION['forms']['register']['state'] == 'LA' ? ' selected="selected"' : '' ?>>Louisiana</option>
							<option value="ME"
								<?= $_SESSION['forms']['register']['state'] == 'ME' ? ' selected="selected"' : '' ?>>Maine</option>
							<option value="MD"
								<?= $_SESSION['forms']['register']['state'] == 'MD' ? ' selected="selected"' : '' ?>>Maryland</option>
							<option value="MA"
								<?= $_SESSION['forms']['register']['state'] == 'MA' ? ' selected="selected"' : '' ?>>Massachusetts</option>
							<option value="MI"
								<?= $_SESSION['forms']['register']['state'] == 'MI' ? ' selected="selected"' : '' ?>>Michigan</option>
							<option value="MN"
								<?= $_SESSION['forms']['register']['state'] == 'MN' ? ' selected="selected"' : '' ?>>Minnesota</option>
							<option value="MS"
								<?= $_SESSION['forms']['register']['state'] == 'MS' ? ' selected="selected"' : '' ?>>Mississippi</option>
							<option value="MO"
								<?= $_SESSION['forms']['register']['state'] == 'MO' ? ' selected="selected"' : '' ?>>Missouri</option>
							<option value="MT"
								<?= $_SESSION['forms']['register']['state'] == 'MT' ? ' selected="selected"' : '' ?>>Montana</option>
							<option value="NE"
								<?= $_SESSION['forms']['register']['state'] == 'NE' ? ' selected="selected"' : '' ?>>Nebraska</option>
							<option value="NV"
								<?= $_SESSION['forms']['register']['state'] == 'NV' ? ' selected="selected"' : '' ?>>Nevada</option>
							<option value="NH"
								<?= $_SESSION['forms']['register']['state'] == 'NH' ? ' selected="selected"' : '' ?>>New
								Hampshire</option>
							<option value="NJ"
								<?= $_SESSION['forms']['register']['state'] == 'NJ' ? ' selected="selected"' : '' ?>>New
								Jersey</option>
							<option value="NM"
								<?= $_SESSION['forms']['register']['state'] == 'NM' ? ' selected="selected"' : '' ?>>New
								Mexico</option>
							<option value="NY"
								<?= $_SESSION['forms']['register']['state'] == 'NY' ? ' selected="selected"' : '' ?>>New
								York</option>
							<option value="NC"
								<?= $_SESSION['forms']['register']['state'] == 'NC' ? ' selected="selected"' : '' ?>>North
								Carolina</option>
							<option value="ND"
								<?= $_SESSION['forms']['register']['state'] == 'ND' ? ' selected="selected"' : '' ?>>North
								Dakota</option>
							<option value="OH"
								<?= $_SESSION['forms']['register']['state'] == 'OH' ? ' selected="selected"' : '' ?>>Ohio</option>
							<option value="OK"
								<?= $_SESSION['forms']['register']['state'] == 'OK' ? ' selected="selected"' : '' ?>>Oklahoma</option>
							<option value="OR"
								<?= $_SESSION['forms']['register']['state'] == 'OR' ? ' selected="selected"' : '' ?>>Oregon</option>
							<option value="PA"
								<?= $_SESSION['forms']['register']['state'] == 'PA' ? ' selected="selected"' : '' ?>>Pennsylvania</option>
							<option value="RI"
								<?= $_SESSION['forms']['register']['state'] == 'RI' ? ' selected="selected"' : '' ?>>Rhode
								Island</option>
							<option value="SC"
								<?= $_SESSION['forms']['register']['state'] == 'SC' ? ' selected="selected"' : '' ?>>South
								Carolina</option>
							<option value="SD"
								<?= $_SESSION['forms']['register']['state'] == 'SC' ? ' selected="selected"' : '' ?>>South
								Dakota</option>
							<option value="TN"
								<?= $_SESSION['forms']['register']['state'] == 'TN' ? ' selected="selected"' : '' ?>>Tennessee</option>
							<option value="TX"
								<?= $_SESSION['forms']['register']['state'] == 'TX' ? ' selected="selected"' : '' ?>>Texas</option>
							<option value="UT"
								<?= $_SESSION['forms']['register']['state'] == 'UT' ? ' selected="selected"' : '' ?>>Utah</option>
							<option value="VT"
								<?= $_SESSION['forms']['register']['state'] == 'VT' ? ' selected="selected"' : '' ?>>Vermont</option>
							<option value="VA"
								<?= $_SESSION['forms']['register']['state'] == 'VA' ? ' selected="selected"' : '' ?>>Virginia</option>
							<option value="WA"
								<?= $_SESSION['forms']['register']['state'] == 'WA' ? ' selected="selected"' : '' ?>>Washington</option>
							<option value="WV"
								<?= $_SESSION['forms']['register']['state'] == 'WV' ? ' selected="selected"' : '' ?>>West
								Virginia</option>
							<option value="WI"
								<?= $_SESSION['forms']['register']['state'] == 'WI' ? ' selected="selected"' : '' ?>>Wisconsin</option>
							<option value="WY"
								<?= $_SESSION['forms']['register']['state'] == 'WY' ? ' selected="selected"' : '' ?>>Wyoming</option>
						</select>

						<p class="help-block validation-error text-danger">Please select your state.</p>

					</div>

				</div>

				<div class="form-group">

					<label class="col-md-3"><span class="text-danger">*</span>Zip Code:</label>

					<div class="col-md-3">

						<input type="text" name="zipCode" size="15" maxlength="10" title="Please enter a zip code"
							placeholder="Zip Code" value="<?= $_SESSION['forms']['register']['zipCode'] ?>" class="form-control" required />

						<p class="help-block validation-error text-danger">Please enter zip.</p>

					</div>

				</div>
			
			</fieldset>

			<div class="row">

				<div class="col-md-12">
					
					<div id="paymentInformationContainer" style="color: #222; margin-top: 20px;">
						
						<div>
						
							<h2 class="collapse-box text-ravens" style="margin-bottom: 20px;">Payment Method</h2>
							
							<div class="clearfix"></div>
							
							<label style="font-size: 1.5em; font-family: 'Oswald', sans-serif;">
								<input name="paymentMethod" type="radio" value="1" disabled /> Credit/Debit Card by AUTHORIZE.NET 
								<span class="text-danger" style="padding-left: 20px;">Coming Soon</span>
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
									<input name="paymentMethod" type="radio" value="0" checked="checked" /> Paypal/Debit/Credit Card by PayPal
								</label>
				
								<p class="help-block text-muted">Upon confirmation you will be taken to through our paypal payment processor.</p>
								
							</fieldset>
							
						</div>
						
					</div>
			
				</div>
			
			</div>

			<div class="row text-center">
				
				<br>
				
				<button type="button" class="btn btn-lg btn-success buy-now"><i class="fa fa-check cushion-right"></i>BUY NOW</button>
				
				<p class="help-block text-muted">You only need to click the "BUY NOW" button once. Multiple clicks could cause some transaction issues.</p>

			</div>

		</div>

	</div>

</div>