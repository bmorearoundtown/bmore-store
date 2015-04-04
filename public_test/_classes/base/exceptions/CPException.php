<?
/**
 *	Customized exception to provide more accurate exception catching and logging.
 *
 *	@author		Rob Erekson
 *	@package	cp
 *	@subpackage	debug
 *	@version	1.0
 *	@see		DebugError
 *	@see		DebugErrorLog
 *	@see		UIErrorDisplay
 */
class CPException extends Exception {
	
	protected $exceptionType = '';
	protected $severity = 0;
	protected $data = null;
	protected $errorTitle = '';

	/**
	 *	Set properties specific to CPException and call the Exception object constructor.
	 *
	 *	@param	string $exceptionType The exception type (database, runtime, security, data, filesystem)
	 *	@param	int $severity The severity of the exception, from 1 to 5
	 *	@param	string $errorTitle The title/headline to be displayed to the user
	 *	@param	string $message	The message to be displayed to the user
	 *	@param	mixed $data Exception-specific information to be serialized and stored for further analysis
	 */
	public function __construct($exceptionType, $severity, $errorTitle, $message, $data) {

		$this->exceptionType = $exceptionType;
		$this->severity = $severity;
		$this->data = $data;
		$this->errorTitle = $errorTitle;
		$this->message = $message;
		
		parent::__construct($message, $severity);
		
		// If the message and/or error title weren't supplied, provide defaults
		if (!$this->message)
			$this->message = 'Don\'t worry, our technical team has been notified of this error and will research and fix the problem as soon as possible.';
			
		if (!$this->errorTitle)
			$this->errorTitle = 'There was an error loading this page';

	}
	
	/**
	 *	Get the type of exception.
	 *
	 *	@return	string The exception type
	 */
	public function getExceptionType() {
		return $this->exceptionType;
	}
	
	/**
	 *	Get the data to be serialized.
	 *
	 *	@return	mixed The data to be serialized
	 */
	public function getData() {
		return $this->data;
	}
	
	/**
	 *	Get the exception severity level.
	 *
	 *	@return	int The severity level
	 */
	public function getSeverity() {
		return $this->severity;
	}
	
	/**
	 *	Handle the various actions depending on the severity level of the exception. Script execution is halted at the end of this function.
	 */
	public function handleActions() {

		// Log exception in database
		if (in_array($this->severity, array(1, 2, 3, 4)) && $GLOBALS['db']) {
			$errorLog = new DebugErrorLog();
			$errorLog->addErrorToLog($this);
			$errorLogId = $errorLog->getId();
		}
		
		// Display error message to user
		if (in_array($this->severity, array(1, 2, 3, 5))) {
			$display = new UIErrorDisplay($this->errorTitle, $this->message, !(is_a($this, 'PermissionsException') || is_a($this, 'SnafuException')), $errorLogId, $this);
			echo $display->draw();
		}
/*
		// Send email to admins
		if (in_array($this->severity, array(1, 2))) {
			$email = new DebugEmailNotice();
			$email->setException($this);
			$email->send(DebugEmailNotice::EXCEPTION_EMAIL);
		}
	
		// Send SMS to admins
		if (in_array($this->severity, array(1))) {
			$sms = new DebugSMSNotice();
			$sms->setException($this);
			$sms->send(DebugSMSNotice::EXCEPTION_SMS);
		}
*/
		exit;
	
	}
	
	public function getDataToString($data = null) {
		return print_r($data ? $data : $this->data, true);
	}

}