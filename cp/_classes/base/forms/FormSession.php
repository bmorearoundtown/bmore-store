<?
class FormSession {

	const SESSION_KEY = 'formSessions';

	private $_strFormName			= '';
	
	private $_intTimeout			= 600;
	
	private $_boolStatus			= null;
	
	public function __construct($strFormName = '') {
	
		if ($strFormName) {
			$this->_strFormName = $strFormName;	
			$this->_init();
		}
		
		if ($_GET['f'] == 1)
			$this->reset();
	
	}
	
	public function loadForms($arrFormNames) {
	
		if (!is_array($arrFormNames))
			$arrFormNames = array($arrFormNames);
			
		foreach ($arrFormNames as $strFormName) {
		
			if ($this->_formDataExists($strFormName)) {
				$this->_strFormName = $strFormName;
				$this->_init();
				break;
			}
		
		}
	
	}
	
	public function isLoaded() {
		return $this->_strFormName;
	}
	
	public function setValue($strKey, $mxdValue) {
		$_SESSION[self::SESSION_KEY][$this->_strFormName][$strKey] = $mxdValue;
	}
	
	public function setValues($arrValues) {
	
		$arrArgs = func_get_args();
		
		if (count($arrArgs) == 1) {
		
			foreach ($arrValues as $strKey => $mxdValue)
				$this->setValue($strKey, $mxdValue);
		
		} else {
		
			for ($intI = 1; $intI < count($arrArgs); $intI++)
				$this->setValue($arrArgs[$intI], $arrValues[$arrArgs[$intI]]);
		
		}
	
	}
	
	public function getValue($strKey) {
		return isset($_SESSION[self::SESSION_KEY][$this->_strFormName][$strKey]) ? $_SESSION[self::SESSION_KEY][$this->_strFormName][$strKey] : '';
	}
	
	public function reset() {
		$_SESSION[self::SESSION_KEY][$this->_strFormName] = array();
		$this->_setTimeout();
	}
	
	public function delete() {
		unset($_SESSION[self::SESSION_KEY][$this->_strFormName]);
	}
	
	public function setStatus($boolStatus) {
		$this->setValue('_formStatus', $boolStatus);
	}
	
	public function setSuccessMessage($strMessage) {
		$this->setValue('_successMessage', $strMessage);
		$this->setStatus(true);
	}
	
	public function getSuccessMessage() {
		return $this->getValue('_successMessage');
	}
	
	public function setErrorMessage($strMessage) {
		$this->setValue('_errorMessage', $strMessage);
		$this->setStatus(false);
	}
	
	public function getErrorMessage() {
		return $this->getValue('_errorMessage') ? $this->getValue('_error') : '';
	}
	
	public function isSuccessful() {
		return $this->getValue('_formStatus') === true;
	}
	
	public function isFailed() {
		return $this->getValue('_formStatus') === false;
	}
	
	private function _init() {

		if (!is_array($_SESSION[self::SESSION_KEY][$this->_strFormName]) || $this->_isTimedOut())
			$this->reset();

		$this->_setTimeout();
	
	}
	
	private function _setTimeout() {
		$this->setValue('_timeout', time() + $this->_intTimeout);
	}
	
	private function _isTimedOut() {
		return $this->getValue('_timeout') < time();
	}
	
	public function debug() {
		die('<pre>' . print_r($_SESSION[self::SESSION_KEY][$this->_strFormName], true) . '</pre>');
	}
	
	public function hasData() {
		return $this->_formDataExists($this->_strFormName);
	}
	
	private function _formDataExists($strFormName) {
		return isset($_SESSION[self::SESSION_KEY][$strFormName]) && (count($_SESSION[self::SESSION_KEY][$strFormName]) > 2 || !array_key_exists('_timeout', $_SESSION[self::SESSION_KEY][$strFormName]));
	}
	
	
	
	/*--- Validation ---*/
	
	public function validate($mxdFieldName, $mxdFieldLabel, $intRules) {
	
		$arrValidation = array();
			
		$arrArguments = func_get_args();

		if (count($arrArguments) % 3)
			throw new InvalidArgumentException('Irregular number of parameters. Parameters must be passed in \'label, field name, rule\' sets.');
		
		for ($intI = 0; $intI < count($arrArguments); $intI += 3)
			$arrValidation[] = array(
				'name'		=> $arrArguments[$intI + 1],
				'label'		=> $arrArguments[$intI],
				'rules'		=> $arrArguments[$intI + 2]
			);
		

		
		foreach ($arrValidation as $arrSettings) {
			
			$mxdValue = '';
			
			if (is_array($arrSettings['name']))
				foreach ($arrSettings['name'] as $strName)
					$mxdValue[] = $_POST[$strName];
			else
				$mxdValue = $_POST[$arrSettings['name']];

			$intResult = FormValidation::validate($mxdValue, $arrSettings['rules']);
			
			if ($intResult)
				$this->_addValidationFailure($arrSettings['name'], $arrSettings['label'], $intResult);
				
		}
		
		$this->_cleanupValidationFailures();
		
		if ($this->hasValidationFailures())
			throw new ValidationFailureException($_SESSION[self::SESSION_KEY][$this->_strFormName]['validation']['messages']);
	
	}
	
	public function validateConditional($strConditionalName, $intConditionalRule, $mxdFieldName, $mxdFieldLabel, $intRules) {

		if (FormValidation::validate($_POST[$strConditionalName], $intConditionalRule) != 0) {

			$arrArguments = func_get_args();
			array_shift($arrArguments);
			array_shift($arrArguments);
			$arrArguments = array_values($arrArguments);
		
			call_user_func_array(array($this, 'validate'), $arrArguments);
			
		}
	
	}
	
	
	
	private function _addValidationFailure($mxdName, $mxdLabel, $intResult) {
	
		if (!is_array($mxdName))
			$mxdName = array($mxdName);
		
		if (!is_array($mxdLabel))
			$mxdLabel = array($mxdLabel);

		if (count($mxdName) != count($mxdLabel))
			throw new InvalidArgumentException('The number of items in $mxdName and $mxdLabel must be the same');

		for ($intI = 0; $intI < count($mxdName); $intI++) {

			$arrMessages = FormValidation::stringify($mxdLabel[$intI], $intResult, $mxdLabel);

			foreach ($arrMessages as $strMessage)
				$_SESSION[self::SESSION_KEY][$this->_strFormName]['validation']['messages'][$mxdName[$intI]][] = $strMessage;
				
		}
			
	}
	
	protected function _cleanupValidationFailures() {
		$_SESSION[self::SESSION_KEY][$this->_strFormName]['validation']['messages'] = array_unique($_SESSION[self::SESSION_KEY][$this->_strFormName]['validation']['messages']);
	}
	
	public function hasValidationFailures() {
		return count($_SESSION[self::SESSION_KEY][$this->_strFormName]['validation']['messages']);
	}
	
	public function getValidationFailures() {
		return $_SESSION[self::SESSION_KEY][$this->_strFormName]['validation']['messages'];
	}
	
	public function isError($strKey) {
		return is_array($_SESSION[self::SESSION_KEY][$this->_strFormName]['validation']['messages']) && array_key_exists($strKey, $_SESSION[self::SESSION_KEY][$this->_strFormName]['validation']['messages']);
	}

}