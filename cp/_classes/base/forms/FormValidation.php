<?
class FormValidation {

	const REQUIRED			= 1;
	const REQUIRED_ANY		= 2;
	const REQUIRED_ALL		= 4;
	
	const ALPHA				= 16;
	const NUMERIC			= 32;
	const ALPHANUMERIC		= 64;
	const DIGITS			= 128;
	
	const DATE				= 1024;
	
	const EMAIL_ADDRESS		= 2048;
	const URL				= 4096;
	
	const PHONE_NUMBER10	= 16384;
	const PHONE_NUMBER10X	= 32768;
	
	const US_STATE			= 262144;
	const US_ZIP_CODE		= 524288;
	const US_CURRENCY		= 1048576;
	
	
	
	public function FormValidation() {}
	
	
	
	protected function _validate($mxdValue, $intRules) {
			
		$intFailed = 0;

		if ($intRules & self::REQUIRED)
			if (!trim($mxdValue)) $intFailed |= self::REQUIRED;
			
		if (trim($mxdValue)) {

			if ($intRules & self::ALPHA)
				if (!preg_match('/^[\w\s]+$/', $mxdValue)) $intFailed |= self::ALPHA;
				
			if ($intRules & self::NUMERIC)
				if (!is_numeric($mxdValue)) $intFailed |= self::NUMERIC;
				
			if ($intRules & self::ALPHANUMERIC)
				if (!preg_match('/^[\w\d\s]+$/', $mxdValue)) $intFailed |= self::ALPHANUMERIC;
				
			if ($intRules & self::DIGITS)
				if (!preg_match('/^\d+$/', $mxdValue)) $intFailed |= self::DIGITS;
				
			if ($intRules & self::DATE)
				die('DO DATE VALIDATION');
				
			if ($intRules & self::EMAIL_ADDRESS)
				if (!preg_match('/^[a-z0-9]([a-z0-9._]*[a-z0-9]|())@[a-z0-9]([a-z0-9.\-]*[a-z0-9]|())\.[a-z]+$/i', $mxdValue)) $intFailed |= self::EMAIL_ADDRESS;
				
			if ($intRules & self::URL)
				if (!preg_match('/^((https?:\/\/(www)?)|(www))[.a-z0-9-]+\.[a-z]{2,4}\/?[._a-z0-9\/~?=-]*$/i', $mxdValue)) $intFailed |= self::URL;
				
			if ($intRules & self::PHONE_NUMBER10)
				if (strlen(StringX::getDigits($mxdValue)) != 10) $intFailed |= self::PHONE_NUMBER10;
				
			if ($intRules & self::PHONE_NUMBER10X)
				if (strlen(StringX::getDigits($mxdValue)) < 10) $intFailed |= self::PHONE_NUMBER10;
				
			if ($intRules & self::US_STATE)
				if (strlen($mxdValue) != 2 || !in_array(strtoupper($mxdValue), array('AK','AL','AR','AZ','CA','CO','CT','DC','DE','FL','GA','HI','IA','ID','IL','IN','KS','KY','LA','MA','MD','ME','MI','MN','MO','MS','MT','NC','ND','NE','NH','NJ','NM','NV','NY','OH','OK','OR','PA','RI','SC','SD','TN','TX','UT','VA','VT','WA','WI','WV','WY'))) $intFailed |= self::US_STATE;
				
			if ($intRules & self::US_ZIP_CODE)
				if (strlen(StringX::getDigits($mxdValue)) != 5 && strlen(StringX::getDigits($mxdValue)) != 9) $intFailed |= self::US_ZIP_CODE;
				
			if ($intRules & self::US_CURRENCY)
				die('DO CURRENCY VALIDATION');
				
		}
		
		return $intFailed;
	
	}
	
	
	
	public function validate($mxdValue, $intRules) {
	
		$intFailed = 0;

		if (is_array($mxdValue)) {
			if (!self::validateAny($mxdValue, $intRules)) $intFailed |= self::REQUIRED_ANY;
		} else {

			$intFailed |= self::_validate($mxdValue, $intRules);
		}
			
		return $intFailed;
	
	}
	
	public function validateAny($arrValues, $intRules) {
	
		if (!is_array($arrValues))
			throw new InvalidArgumentException('validateAny() requires an array of values');
			

			
		foreach ($arrValues as $mxdValue)
			if (self::_validate($mxdValue, $intRules) == 0)
				return true;
				
		return false;
	
	}
	
	
	
	public function stringify($strLabel, $intResult, $arrLabels = array()) {
		
		$arrResults = array();
		
		$strLabel = ucwords($strLabel);
		
		if ($intResult & self::REQUIRED)
			$arrResults[] = $strLabel . ' is a required field';
			
		if ($intResult & self::REQUIRED_ANY)
			$arrResults[] = 'Either ' . StringX::oxfordComma(StringX::ucwords($arrLabels), 'or') . ' are required';
			
		if ($intResult & self::ALPHA)
			$arrResults[] = $strLabel . ' must contain only letters';
			
		if ($intResult & self::NUMERIC)
			$arrResults[] = $strLabel . ' must be a valid number';
			
		if ($intResult & self::ALPHANUMERIC)
			$arrResults[] = $strLabel . ' must contain only letters and numbers';
			
		if ($intResult & self::DIGITS)
			$arrResults[] = $strLabel . ' must contain only numbers';
			
		if ($intResult & self::DATE)
			$arrResults[] = $strLabel . ' must be a valid date';
			
		if ($intResult & self::EMAIL_ADDRESS)
			$arrResults[] = $strLabel . ' must be a valid email address';
			
		if ($intResult & self::URL)
			$arrResults[] = $strLabel . ' must be a valid URL';
			
		if ($intResult & self::PHONE_NUMBER10 || $intResult & self::PHONE_NUMBER10X)
			$arrResults[] = $strLabel . ' must be a valid 10-digit phone number';
			
		if ($intResult & self::US_STATE)
			$arrResults[] = $strLabel . ' must be a valid U.S. state abbreviation';
			
		if ($intResult & self::US_ZIP_CODE)
			$arrResults[] = $strLabel . ' must be a valid 5- or 9-digit U.S. zip code';
			
		if ($intResult & self::US_CURRENCY)
			$arrResults[] = $strLabel . ' must be a valid U.S. currency value';
			
		return $arrResults;
	
	}

}