<?
/**
 *	Provides access to the debug_errors table.
 *
 *	Provides object-oriented access to the errors table where unique errors and exceptions are stored
 *	@author		Rob Erekson
 *	@subpackage	debug
 *	@version	1.0
 *	@see		ErrorLog
 */
class Error extends DatabaseObject {

	/**
	 *	@param	int $id The primary ID of the database table row to load
	 */
	public function __construct($intId = 0) {
		
		$this->_('id', 0);
		$this->_('searchIndex');
		$this->_('type');
		$this->_('exception');
		$this->_('severity', 0);
		$this->_('filename');
		$this->_('lineNumber', 0);
		
		parent::__construct('errors', $intId);

	}
	
	/**
	 *	Check to see if an error occurs in the database, if adds it if it doesn't currently exist.
	 *
	 *	@param	Exception|CPException $exception The exception object to log in the database
	 *	@param	int $severity The severity level of the exception (only used for Exception objects)
	 *	@see	DebugErrorLog::addErrorToLog()
	 */
	public function addError($objException, $intSeverity = 0) {

		// Set the information for the error to be logged
		$this->setType(is_a($objException, 'AppException') ? $objException->getExceptionType() : 'general');
		$this->setException(get_class($objException));
		$this->setSeverity(is_a($objException, 'AppException') ? $objException->getSeverity() : $intSeverity);
		$this->setFilename($this->_getRelativeFilePath($objException->getFile()));
		$this->setLineNumber($objException->getLine());
		
		// Generate and store the search index
		$this->setSearchIndex($this->_generateSearchIndex());
		
		// Check if error already exists and add it to the database if it doesn't
		if (!$this->_errorExists())
			$this->insertIntoDatabase('', false, true);
	
	}
	
	/**
	 *	Checks if an error already exists in the database by the generated searchIndex.
	 *
	 *	@return	bool True if the unique error already exists, false otherwise
	 */
	private function _errorExists() {
	
		$this->query("
			SELECT	*
			FROM	$this->tableName
			WHERE	searchIndex = '{$this->searchIndex}'
		");
		
		return $this->loadNext();
		
	}
	
	/**
	 *	Generates a unique searchIndex consisting of the exception type, the exception name, the filename, and the line number.
	 *
	 *	@return	string The generated searchIndex
	 */
	private function _generateSearchIndex() {
		return strtolower($this->type) . ':' . strtolower($this->_getBaseExceptionName()) . ':' . strtolower($this->filename) . '[' . $this->lineNumber . ']';
	}
	
	/**
	 *	Parses out the name of the exception without the actual 'Exception' appended to the end.
	 *
	 *	@return	string The exception name minus the 'Exception' at the end
	 */
	private function _getBaseExceptionName() {
		preg_match('/^(\w+)Exception$/', $this->exception, $arrRegs);
		return $arrRegs[1];
	}
	
	/**
	 *	Parses out the filepath of the filename relative to the document root.
	 *
	 *	@return string The relative filepath
	 */
	private function _getRelativeFilePath($strFilename) {
		return str_replace($_SERVER['DOCUMENT_ROOT'], '/', $strFilename);
	}

}