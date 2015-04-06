<?
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/config.php');
	
	$strHtml = '';
	$arrResponse = array(
		'success'			=> false,
		'error'				=> '',
		'discountCode'		=> $_POST['discountCode'],
		'discountType'		=> '',
		'discountAmount'	=> 0
	);
	
	try {
		
		if (!preg_match('/^([a-z]{3})/i', $_POST['registrationCode'], $arrMatches))
			throw new UnexpectedValueException('There was an error loading the discount code.');
		
		$objDiscount = new DiscountCode();
		$objDiscount->loadByDiscountCode($_POST['discountCode'], $arrMatches[1]);
		
		if (!$objDiscount->isValid())
			throw new InvalidArgumentException('The discount code provided does not exist.');
			
		if ($objDiscount->getMaxDiscounts() > 0 && $objDiscount->getDiscountsUsed() >= $objDiscount->getMaxDiscounts())
			throw new OutOfBoundsException('All available discounts for this code have been used.');
			
		$arrResponse['discountType'] = $objDiscount->getDiscountType();
		$arrResponse['discountAmount'] = $objDiscount->getDiscount();
		
		$arrResponse['success'] = true;
		
	} catch (Exception $objException) {
		$arrResponse['error'] = $objException->getMessage() ? $objException->getMessage() : 'There was an error saving the permissions [' . $objException->getCode() . ']';
	}
						  
	// Output JSON
	$objJson = new Services_JSON();
	header('X-JSON: ' . $objJson->encode($arrResponse));

	echo $strHtml;

	exit;