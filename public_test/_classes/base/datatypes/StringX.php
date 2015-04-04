<?
class StringX {

	const ALPHANUMERIC = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	const ALPHANUMERIC_LOWER = 'abcdefghijklmnopqrstuvwxyz0123456789';
	const ALPHANUMERIC_UPPER = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	const ALPHA = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	const ALPHA_LOWER = 'abcdefghijklmnopqrstuvwxyz';
	const ALPHA_UPPER = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	const NUMERIC = '0123456789';

	public static function randomize($intStringLength, $strCharset = self::ALPHANUMERIC) {
	
		$strRandom = '';
		
		for ($intI = 1; $intI <= $intStringLength; $intI++)
			$strRandom .= $strCharset[rand(0, strlen($strCharset) - 1)];
			
		return $strRandom;
	
	}
	
	public static function isEmailAddress($strTest) {
		return preg_match('/^[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i', $strTest);
	}

	public static function truncate($strOriginal, $intLength = 75, $intTail = 6, $strTailJoin = '...') {
	
		if (strlen($strOriginal) + $intTail + $strTailJoin <= $intLength)
			return $strOriginal;
		else {
	
			$strStub = substr($strOriginal, 0, strrpos($strOriginal, ' ', (strlen($strOriginal) - $intLength) * -1));
			
			return $strStub . $strTailJoin . ($intTail ? substr($strOriginal, $intTail * -1) : '');

		}
	
		return substr($strOriginal, 0, $intLength - $intTail - strlen($strTailJoin)) . (strlen($strOriginal) > $intLength + strlen($strTailJoin) ? $strTailJoin . substr($strOriginal, $intTail * -1) : '');

	}
	
	public static function formatPhoneNumber($strPhoneNumber) {
	
		preg_match_all('/\d+/', $strPhoneNumber, $arrMatches);
		
		return preg_replace('/^(\d{3})(\d{3})(\d{4})$/', '($1) $2-$3', implode($arrMatches[0]));
	
	}

}