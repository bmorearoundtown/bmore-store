<?
class NumberX {

	public static function getOrdinalSuffix($intNumber, $boolSuperScript) {
	
		if (!is_numeric($intNumber))
			throw new UnexpectedValueException('Not a number: ' . $intNumber);
			
		$strSuffix = '';
			
		if (substr($intNumber, -2) == 11 || substr($intNumber, -2) == 12 || substr($intNumber, -2) == 13)
			$strSuffix = 'th';
		elseif (substr($intNumber, -1) == 1)
			$strSuffix = 'st';
		elseif (substr($intNumber, -1) == 2)
			$strSuffix = 'nd';
		else if (substr($intNumber, -1) == 3)
			$strSuffix = 'rd';
		else
			$strSuffix = 'th';

		if($boolSuperScript)
			$strSuffix = '<sup>' . $strSuffix . '</sup>';

		return $intNumber . $strSuffix;
	
	}

}