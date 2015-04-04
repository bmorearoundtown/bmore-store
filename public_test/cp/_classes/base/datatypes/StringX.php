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
		
		return preg_replace('/^(\d{3})(\d{3})(\d{4})(\d*)?/e', "'($1) $2-$3' . ('$4' ? ' x$4' : '')", implode($arrMatches[0]));
	
	}
	
	public static function toCamelCase($strOriginal) {
	
		$strOriginal = strtolower($strOriginal);
		
		return preg_replace_callback('/( |_)([a-z])/', function($arrMatches) {
			return strtoupper($arrMatches[2]);
		}, $strOriginal);
	
	}
	
	
	
	public function oxfordComma($arrWords, $strJoin = 'and') {
	
		if (!is_array($arrWords))
			return $arrWords;
			
		if (count($arrWords) == 2)
			return $arrWords[0] . ' ' . $strJoin . ' ' . $arrWords[1];
			
		$strPhrase = '';
		
		$strLast = array_pop($arrWords);
		
		return implode(', ', $arrWords) . ', ' . $strJoin . $strLast;
	
	}
	
	public function ucfirst($mxdWords) {
	
		if (!is_array($mxdWords))
			return ucfirst($mxdWords);
			
		for ($intI = 0; $intI < count($mxdWords); $intI++)
			$mxdWords[$intI] = ucfirst($mxdWords[$intI]);
			
		return $mxdWords;
	
	}
	
	public function ucwords($mxdWords) {
	
		if (!is_array($mxdWords))
			return ucwords($mxdWords);
			
		for ($intI = 0; $intI < count($mxdWords); $intI++)
			$mxdWords[$intI] = ucwords($mxdWords[$intI]);
			
		return $mxdWords;
	
	}
	
	
	
	public function getDigits($mxdValue) {
		return preg_replace('/\D/', '', $mxdValue);
	}

}