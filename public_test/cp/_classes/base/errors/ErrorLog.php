<?
/**
 *	Provides access to the error_logs table.
 *
 *	Provides object-oriented access to the debug_error_logs table where unique occurences of errors and exceptions are stored.
 *	@author		Rob Erekson
 *	@subpackage	debug
 *	@version	1.0
 *	@see		DebugError
 */
class ErrorLog extends DatabaseObject {

	/**
	 *	@param	int $id The primary ID of the database table row to load
	 */
	public function __construct($intId = 0) {
		
		$this->_('id', 0);
		$this->_('errorId', 0);
		$this->_('accountId', 0);
		$this->_('userId', 0);
		$this->_('errorDate', 0);
		$this->_('backtrace');
		$this->_('session', array(), 'serialized');
		$this->_('ipAddress');
		$this->_('browserName');
		$this->_('browserVersion');
		$this->_('userAgent');
		$this->_('data', array(), 'serialized');
		$this->_('url');
		$this->_('postData', array(), 'serialized');
		$this->_('userNotes');
		
		parent::__construct('error_logs', $intId);

	}
	
	public function loadByErrorId($intErrorId) {
		
		$this->query("
			SELECT		*
			FROM		{$this->tableName}
			WHERE		errorId = {$intErrorId}
			ORDER BY	errorDate DESC
		");
		
	}
	
	/**
	 *	Add an error occurence to the database.
	 *
	 *	@param	Exception|AppException $exception The exception object to log in the database
	 *	@param	int $severity The severity level of the exception (only used for Exception objects)
	 *	@see	Error::addError()
	 */
	public function addErrorToLog($objException, $intSeverity = 0) {
	
		require_once($_SERVER['DOCUMENT_ROOT'] . '/_includes/browser_compatibility.php');
	
		// Log the unique error to the database
		$objError = new Error();
		$objError->addError($objException, $intSeverity);
		
		// Set the information for the occurence to be logged
		$this->errorId = $objError->getId();
		$this->churchId = $GLOBALS['churchId'] ? $GLOBALS['churchId'] : 0;
		$this->memberId = $GLOBALS['userId'] ? $GLOBALS['userId'] : 0;
		$this->errorDate = time();
		$this->backtrace = $objException->getTraceAsString();
		$this->session = $_SESSION;
		$this->ipAddress = $_SERVER['REMOTE_ADDR'];
		$this->url = $_SERVER['REQUEST_URI'];
		$this->postData = $_POST;
		
		$this->userAgent = $_SERVER['HTTP_USER_AGENT'];
		$this->browserName = $GLOBALS['browserCheck']['shortName'];
		$this->browserVersion = $GLOBALS['browserCheck']['version'];
		
		// If the exception is a CPException, use the current data, otherwise store the Exception message and error code
		$this->data = is_a($objException, 'AppException') ? $objException->getData() : array('message' => $objException->getMessage(), 'code' => $objException->getCode());

		// Insert the occurence into the database
		$this->insertIntoDatabase(true, false, true);
	
	}
	
	public function getUrlDisplay($boolAsLink = true) {
		return ($boolAsLink ? '<a href="https://products.connectionpower.com' . $this->url . '" target="_blank">' : '') . 'https://products.connectionpower.com' . (strlen($this->url) > 59 ? substr_replace($this->url, '...', 44, -15) : $this->url) . ($boolAsLink ? '</a>' : '');
	}
	
	public function getBrowserNameDisplay() {
		return ($this->browserName == 'ie' ? 'Internet Explorer' : ucfirst($this->browserName)) . ' ' . $this->browserVersion;
	}
	
	public function getPostDataDisplay() {
	
		$strHtml = '
			<dl>';
			
		foreach ($this->postData as $strKey => $strVal)
			$html .= '
				<dt>' . $strKey . ':</dt>
				<dd>' . $strVal . '</dd>';
				
		$strHtml .= '
			</dl>
			
			<div class="clear"></div>';
			
		return $strHtml;
	
	}
	
	public function getBacktraceDisplay() {
		return '<ol><li>' . preg_replace('/#\d+\s+/', '', str_replace("\n", '</li><li>', $this->backtrace)) . '</li></ol>';
	}

}