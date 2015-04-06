<?
/**
 *	Customized exception to provide more accurate exception catching and logging.
 *
 *	@author		Rob Erekson
 *	@subpackage	debug
 *	@version	1.1
 *	@see		Error
 *	@see		ErrorLog
 *	@see		UIErrorDisplay
 */
class AppException extends Exception {
	
	protected $_strExceptionType	= '';
	protected $_intSeverity			= 0;
	protected $_mxdData				= null;
	protected $_strErrorTitle		= '';
	protected $_strMessage			= '';

	/**
	 *	Set properties specific to AppException and call the Exception object constructor.
	 *
	 *	@param	string $exceptionType The exception type (database, runtime, security, data, filesystem)
	 *	@param	int $severity The severity of the exception, from 1 to 5
	 *	@param	string $errorTitle The title/headline to be displayed to the user
	 *	@param	string $message	The message to be displayed to the user
	 *	@param	mixed $data Exception-specific information to be serialized and stored for further analysis
	 */
	public function __construct($strExceptionType, $intSeverity, $strErrorTitle, $strMessage, $mxdData) {

		$this->_strExceptionType	= $strExceptionType;
		$this->_intSeverity			= $intSeverity;
		$this->_mxdData				= $mxdData;
		$this->errorTitle			= $strErrorTitle;
		$this->message				= $strMessage;
		
		parent::__construct($strMessage, $intSeverity);
		
		// If the message and/or error title weren't supplied, provide defaults
		if (!$this->_strMessage)
			$this->_strMessage = 'Don\'t worry, our technical team has been notified of this error and will research and fix the problem as soon as possible.';
			
		if (!$this->_strErrorTitle)
			$this->_strErrorTitle = 'There was an error loading this page';

	}
	
	/**
	 *	Get the type of exception.
	 *
	 *	@return	string The exception type
	 */
	public function getExceptionType() {
		return $this->_strExceptionType;
	}
	
	/**
	 *	Get the data to be serialized.
	 *
	 *	@return	mixed The data to be serialized
	 */
	public function getData() {
		return $this->_mxdData;
	}
	
	/**
	 *	Get the exception severity level.
	 *
	 *	@return	int The severity level
	 */
	public function getSeverity() {
		return $this->_intSeverity;
	}
	
	/**
	 *	Handle the various actions depending on the severity level of the exception. Script execution is halted at the end of this function.
	 */
	public function handleActions() {

		// Log exception in database
		if (in_array($this->_intSeverity, array(1, 2, 3, 4)) && $GLOBALS['db']) {
			$objErrorLog = new ErrorLog();
			$objErrorLog->addErrorToLog($this);
			$intErrorLogId = $objErrorLog->getId();
		}
		
		// Display error message to user
		if (in_array($this->_intSeverity, array(1, 2, 3, 5))) {
			$objDisplay = new UIErrorDisplay($this->_strErrorTitle, $this->_strMessage, !(is_a($this, 'PermissionsException') || is_a($this, 'SnafuException')), $intErrorLogId, $this);
			echo $objDisplay->draw();
		}
		
		exit;
	
	}
	
	public function getDataToString($mxdData = null) {
		return print_r($mxdData ? $mxdData : $this->data, true);
	}

}