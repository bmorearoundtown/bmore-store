<?
	
	// Custom handling of output buffering
	function handle_buffer($strBuffer, $intFlag) {

		if ($intFlag & PHP_OUTPUT_HANDLER_END) {
			return $GLOBALS['bufferedHtml'] . $strBuffer;
		} else {
		
			$GLOBALS['bufferedHtml'] .= $strBuffer;
			
			if (strlen($GLOBALS['bufferedHtml']) > 2500000) {
				$strHtml = $GLOBALS['bufferedHtml'];
				$GLOBALS['bufferedHtml'] = '';
				return $strHtml;
			} else
				return '';
				
		}
		
	}
	
	ob_start('handle_buffer', 1);