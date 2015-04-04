<?
class Date {
	
	public static function daysInMonth($intMonth, $intYear) {
	
		$arrDaysInMonth = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	
		if ($intMonth == 2 && $intYear % 4 === 0)
			return 29;
			
		return $arrDaysInMonth[$intMonth - 1];
	
	}

	public static function diffAsString($mxdStartDate, $mxdEndDate) {
	
		$arrDiff = self::diff($mxdStartDate, $mxdEndDate);
		
		$arrDiffString = array();
		
		if ($arrDiff['d'])
			$arrDiffString[] = $arrDiff['d'] . ' day' . ($arrDiff['d'] == 1 ? '' : 's');
		
		if ($arrDiff['h'])
			$arrDiffString[] = $arrDiff['h'] . ' hour' . ($arrDiff['h'] == 1 ? '' : 's');
		
		if ($arrDiff['m'])
			$arrDiffString[] = $arrDiff['m'] . ' minute' . ($arrDiff['m'] == 1 ? '' : 's');
			
		return implode(', ', $arrDiffString);
	
	}

	public static function diff($mxdStartDate, $mxdEndDate) {

		$intStartDate = is_string($mxdStartDate) ? strtotime($mxdStartDate) : $mxdStartDate;
		$intEndDate = is_string($mxdEndDate) ? strtotime($mxdEndDate) : $mxdEndDate;

		if ($intStartDate > $intEndDate) {
			$intTempEndDate = $intEndDate;
			$intEndDate = $intStartDate;
			$intStartDate = $intTempEndDate;
			unset($intTempEndDate);
		}
	
		$arrStartDate = getdate($intStartDate);
		$arrEndDate = getdate($intEndDate);
		
		$arrDiff = array(
			'y' => $arrEndDate['year'] - $arrStartDate['year'],
			'm' => $arrEndDate['mon'] - $arrStartDate['mon'] + (($arrEndDate['year'] - $arrStartDate['year']) * 12),
			'd' => 0,
			'w' => floor(($intEndDate / (60*60*24) - $intStartDate / (60*60*24)) / 7)
		);

		for ($intYear = $arrEndDate['year']; $intYear >= $arrStartDate['year']; $intYear--) {

			$arrTempStartDate = getdate($arrStartDate['year'] == $intYear ? $intStartDate : mktime(0, 0, 0, 1, 1, $intYear));
			$arrTempEndDate = getdate($arrEndDate['year'] == $intYear ? $intEndDate : mktime(0, 0, 0, 12, 31, $intYear));

			if ($arrTempStartDate['mon'] == $arrTempEndDate['mon']) {
				$arrDiff['d'] += $arrTempEndDate['mday'] - $arrTempStartDate['mday'];
			} else {

				$arrDiff['d'] += $arrTempEndDate['mday'] + (self::daysInMonth($arrTempStartDate['mon'], $intYear) - $arrTempStartDate['mday']);

				for ($intMonth = $arrTempEndDate['mon'] - 1; $intMonth > $arrTempStartDate['mon']; $intMonth--)
					$arrDiff['d'] += self::daysInMonth($intMonth, $intYear);
				
			}
			
		}
		
		if ($arrEndDate['hours'] - $arrStartDate['hours'] < 0) {
			$arrDiff['d']--;
			$arrDiff['h'] = $arrEndDate['hours'] - $arrStartDate['hours'] + 24;
		} else
			$arrDiff['h'] = $arrEndDate['hours'] - $arrStartDate['hours'];
		
		if ($arrEndDate['minutes'] - $arrStartDate['minutes'] < 0) {
			$arrDiff['h']--;
			$arrDiff['m'] = $arrEndDate['minutes'] - $arrStartDate['minutes'] + 60;
		} else
			$arrDiff['m'] = $arrEndDate['minutes'] - $arrStartDate['minutes'];
		
		if ($arrEndDate['seconds'] - $arrStartDate['seconds'] < 0) {
			$arrDiff['m']--;
			$arrDiff['s'] = $arrEndDate['seconds'] - $arrStartDate['seconds'] + 60;
		} else
			$arrDiff['s'] = $arrEndDate['seconds'] - $arrStartDate['seconds'];
		
		return $arrDiff;
	
	}

}