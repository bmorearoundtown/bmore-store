<?
/**
 *	Object-based database query calls
 *
 *	This class provides object-based access to a querying a database through the {@link DatabaseLink} object.
 *
 *	Example usage:
 *
 *	<code>
 *	$db = new DatabaseLink('127.0.0.1', 'testuser', 'testpass', 'testdb');
 *	$query = new DatabaseQuery($db);
 *	$query->DatabaseQuery('SELECT * FROM testtable WHERE id = 5');
 *	</code>
 *
 *	@author		Rob Erekson
 *	@package	base
 *	@subpackage	database
 *	@version	1.0
 *	@see		DatabaseLink
 */
class DatabaseQuery implements Countable {

	const _ROW_ASSOC_ = 1;
	const _ROW_NUM_ = 2;
	const _ROW_BOTH_ = 3;
	const _ROW_OBJECT_ = 4;
	
	/**
	 *	The {@link DatabaseLink} object used by the object
	 *	@var	resource
	 */
	private $_parentDatabase = null;
	
	/**
	 *	The internal resource identifier used by the object
	 *	@var	resource
	 */
	private $_res = null;
	
	/**
	 *	The query string used by the object
	 *	@var	string		
	 */
	private $_queryString = '';
	
	/**
	 *	The number of rows in the current result set
	 *	@var	int
	 */
	private $_numRows = 0;
	
	/**
	 *	The index of the previous row in the current result set
	 *	@var	int
	 */
	private $_previousRow = 0;
	
	/**
	 *	The current row as an array
	 *	@var	array
	 */
	private $_currentRow = array();
	
	/**
	 *	The current index in the result set
	 *	@var	integer
	 */
	protected $index = -1;
	
	/**
	 *	Set the parent database and initalize the object.
	 *
	 *	@param	resource	$parentDatabase		The {@link DatabaseLink} object used by the object
	 */
	function __construct($parentDatabase = null) {

		// Set the parent database if it was provided
		$this->_parentDatabase = $parentDatabase;
		
		// If it wasn't provided, look for $GLOBALS['db'] and set it as the parent database
		if (!$this->_parentDatabase && $GLOBALS['db'])
			$this->_parentDatabase = $GLOBALS['db'];
		else
			throw new InvalidArgumentException();
		
		$this->_previousRow = -1;
	
	}
	
	/**
	 *	Get the first row of data from the result set. By default, data is returned in an associative array.
	 *
	 *	@return	array		The returned data
	 *	@param	int	$type	The type of data array to be returned
	 */
	public function getFirstRow($type = DatabaseQuery::_ROW_ASSOC_) {
		return $this->getRow(0, $type);
	}
	
	/**
	 *	Get the last row of data from the result set. By default, data is returned in an associative array.
	 *
	 *	@return	array		The returned data
	 *	@param	int	$type	The type of data array to be returned
	 */
	public function getLastRow($type = DatabaseQuery::_ROW_ASSOC_) {
		return $this->getRow($this->numRows - 1, $type);
	}
	
	/**
	 *	Get a particular row from the result set. By default, data is returned in an associative array.
	 *
	 *	@return	array		The returned data
	 *	@param	int	$index	The row to be returned
	 *	@param	int	$type	The type of data array to be returned
	 */
	public function getRow($index = 0, $type = DatabaseQuery::_ROW_ASSOC_) {
	
		// If the index is higher than the number of rows we have, throw an exception
		if ($index > count($this))
			throw new OutOfRangeException();
	
		$this->_previousRow = $index;

		switch ($type) {
		
			case DatabaseQuery::_ROW_ASSOC_:
				$row = mysql_fetch_assoc($this->_res);
				break;
				
			case DatabaseQuery::_ROW_NUM_:
				$row = mysql_fetch_row($this->_res);
				break;
				
			case DatabaseQuery::_ROW_BOTH_:
			default:
				$row = mysql_fetch_array($this->_res);
				break;
				
			case DatabaseQuery::_ROW_OBJECT_:
				$row = mysql_fetch_object($this->_res);
				break;
				
		}
		
		return $row;
		
	}
	
	/**
	 *	Reset the internal pointer of the object.
	 */
	public function reset() {
		$this->index = -1;
		$this->_previousRow = 1;
	}
	
	/**
	 *	Move the internal pointer to the next row.
	 */
	public function nextRow() {
		$this->index++;
	}
	
	/**
	 *	Move the internal pointer to the previous row.
	 */
	public function prevRow() {
		$this->index--;
	}
	
	/**
	 *	Fetch the next row of data from the result set. By default, data is returned in an associative array.
	 *
	 *	@param	int	$type	The type of data array to be returned
	 *	@return	array		The current row of data, or false if there are no more rows
	 */
	public function getNext($type = DatabaseQuery::_ROW_ASSOC_) {
	
		try {
		
			if ($this->_previousRow != -1)
				$this->nextRow();
			
			$this->_currentRow = $this->getRow($this->index, $type);
			
			return $this->_currentRow;
		
		} catch (OutOfRangeException $e) {
			return false;
		}
	
	}
	
	/**
	 *	Fetch the previous row of data from the result set. By default, data is returned in an associative array.
	 *
	 *	@param	int	$type	The type of data array to be returned
	 *	@return	array		The current row of data
	 */
	public function getPrevious($type = DatabaseQuery::_ROW_ASSOC_) {
		$this->prevRow();
		return $this->getRow($this->index, $type);
	}
	
	/**
	 *	Get the number of results in the result set.
	 *
	 *	@return	int			The number of rows in the result set
	 */
	public function getRowCount() {

		if ($this->_res) {
			$this->_numRows = mysql_num_rows($this->_res);
		} else {
			$this->_numRows = 0;
		}
		
		return $this->_numRows;
	
	}
 	
 	/**
 	 *	Alias of {@link getRowCount}. Allows use of count() function on objects extending {@link DatabaseQuery}.
 	 *
 	 *	<code>
 	 *	$query = new DatabaseQuery();
 	 *	$query->query('SELECT * FROM testtable WHERE id < 10');
 	 *	
 	 *	if (count($query)) {
 	 *		echo 'We have results!';
 	 *	}
 	 *	</code>
 	 *
 	 *	@return	int			The number of rows in the result set
 	 */
	public function count() {
		return $this->getRowCount();
	}
	
	/**
	 *	Check to see if the internal pointer is on the last row in the result set.
	 *
	 *	@return	bool		True if the internal pointer is on the last row, false otherwise
	 */
	public function isLast() {
		return $this->index == $this->getRowCount();
	}
	
	/**
	 *	Check to see if the executed query returned any results.
	 *
	 *	@return	bool		True if the result set is empty, false otherwise
	 */
	public function isEmpty() {
		return !$this->getRowCount();
	}
	
	/**
	 *	Set the database query string without executing a query.
	 */
	public function setQuery($queryString) {
		$this->_queryString = $queryString;
	}
	
	/**
	 *	Get the current database query string.
	 *
	 *	@return	string		The current database query string
	 */
	public function getQuery() {
		return $this->_queryString;
	}
	
	/**
	 *	Perform a query on the database. If a query string is not provided as a parameter, the existing query string will be used (set via {@link setQuery}).
	 *
	 *	@param	string	$queryString	The database query string to be used
	 *	@param	bool	$debug 			When set to true, the query string will be echoed and script execution will halt
	 *	@return	resource				The resource identifier returned by the query
	 */
	public function query($queryString = '', $debug = false) {

		// If $queryString was provided, overwrite the local property
		if ($queryString)
			$this->_queryString = $queryString;

		// If $debug is true, dump the query and die
		if ($debug) die('Query Debug: ' . $this->_queryString);
		
		// If a query string wasn't provided, throw an exception
		if (!$this->_queryString) 
			throw new InvalidArgumentException();

		// Start the timer for debugging
		$start = microtime();

		// Query the database
		if (!$this->_res = mysql_query($this->_queryString, $this->_parentDatabase->getIdentifier())) $this->_throwQueryException();

		// If debugging is enabled, add the query to the debugger object, along with the query time
		if ($GLOBALS['debugger'])
			$GLOBALS['debugger']->addQuery($this->_queryString, microtime() - $start);
			
		// Reset the internal pointers
		$this->reset();

		// Return the resource identifier
		return $this->_res;
		
	}
	
	/**
	 *	Throw a query exception. Used by {@link query} to determine the query type and throw the appropriate exception.
	 */
	private function _throwQueryException() {
		
		preg_match('/^(delete|insert|select|update)/i', $this->_queryString, $regs);

		switch (strtolower($regs[1])) {
		
			case 'delete':
				throw new DatabaseDeleteException($this);
				break;
		
			case 'insert':
				throw new DatabaseInsertException($this);
				break;
		
			case 'select':
				throw new DatabaseSelectException($this);
				break;
		
			case 'update':
				throw new DatabaseUpdateException($this);
				break;
		
			default:
				throw new DatabaseQueryException($this);
				
		}
		
	}
	
	/**
	 *	A wrapper for {@link DatabaseLink}->{@link DatabaseLink:getErrorMessage}. Returns the error message from the database server.
	 *
	 *	@return	string		The error message
	 */
	public function getErrorMessage() {
		return $this->_parentDatabase->getErrorMessage();
	}
	
	/**
	 *	Set the parent {@link DatabaseLink} object.
	 *
	 *	@param	DatabaseLink	The {@link DatabaseLink} object to set as the parent database
	 */
	public function setParentDatabase($db) {
		$this->_parentDatabase = $db;
	}
/*
	public function getParent() {
		return $this->_parentDatabase;
	}
*/	

	/**
	 *	Get the autoincrement ID of the row created by the last query.
	 *
	 *	@return	int			The row ID
	 */
	public function getInsertId() {
		return mysql_insert_id($this->_parentDatabase->getIdentifier());
	}
	
	/**
	 *	Get the number of rows affected by the last query.
	 *
	 *	@return	int			The number of affected rows
	 */
	public function getAffectedRows() {
		return mysql_affected_rows($this->parentDatabase->getIdentifier());
	}
	
	/**
	 *	Get the value of a particular field in a particular row of the result set.
	 *
	 *	@param	$row	int		The row to fetch the field from
	 *	@param	$field	mixed	The field to fetch from the row
	 *	@return mixed			The field if it is found, otherwise false
	 */
	function getField($row, $field = 0) {

		if (count($this)) {
			return mysql_result($this->_res, $row, $field);
		} else {
			return false;
		}
	
	}

}