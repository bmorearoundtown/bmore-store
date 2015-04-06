<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/config.php');
	
	$objRegistration = new RegistrationX();
	
	$objRegistration->loadUniquePaypalUsers();
	
	require_once( 'header.php' );
?>
	
	<div class="page-header">
		<h1 style="font-size: 2.25em; color: #222;">View of Unique PayPal User Emails</h1>
		<p class="lead">Email addresses that correspond to completed purchases via PayPal as of 09/26/2014</p>
	</div>
	
	<div class="row">
		
		<div class="col-xs-12">

			<div class="table-responsive">

				<?
					$arrFields = array(
						'name'			=> 'Persons Name',
						'emailAddress'	=> 'PayPal Email Address',
						'firstTimeSeen'	=> 'First Date Seen'
					);
					$arrData = array();
					
					while ( $objRegistration->loadNext() ) {
					
						$arrData[$objRegistration->getId()] = array(
							'name'			=> $objRegistration->getNameDisplay(),
							'emailAddress'	=> $objRegistration->getPaypalEmailAddress(),
							'firstTimeSeen'	=> $objRegistration->getDatePaidDisplay()
						);
						
					}
						
					$objGrid = new DataGrid('packages', $arrFields, $arrData);
					
					echo $objGrid->draw();
					
				?>									
			
			</div>
			
		</div>
		
	</div>
	
<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/cp/footer.php');
?>