<?
/**
 *	Customized exception to provide more accurate exception catching and logging.
 *
 *	@abstract
 *	@author		Rob Erekson
 *	@package	base
 *	@subpackage	oop
 *	@version	1.0
 */
abstract class DatabaseObject extends DatabaseQuery implements Iterator, Griddable {

	/**
	 *	If the current object is valid.
	 *	@var	bool
	 */
	private $_valid = false;
	
	/**
	 *	The name of the table linked to the object.
	 *	@var	string
	 */
	protected $tableName = false;
	
	/**
	 *	The individual fields and values loaded from the database.
	 *
	 *	The $fields array is structured in the following format:
	 *
	 *	<code>
	 *	$fields['dateStarted'] => array('value' => 	 '1263323968',
	 *									'dbName' =>  'started_date',
	 *									'dbValue' => '2010-01-12',
	 *									'type' => 	 'date');
	 *	</code>
	 *
	 *	'value' contains the value after any conversions are done according to the type, 'dbName' contains the column name in the actual database table, 'dbValue' contains the original value in the database table before any conversions, 'type' is the data type of the field
	 *
	 *	@var	array
	 */
	private $_fields = array();
	
	/**
	 *	Collection of fields that have been changed.
	 *
	 *	An array containing any fields that have been changed since the data was originally loaded. Used to track changed fields for UPDATE queries.
	 *
	 *	@var	array
	 */
	private $_changedFields = array();
	
	/**
	 *	Create a new object.
	 *
	 *	If an ID is provided, the record will automatically be loaded.
	 *
	 *	@param	string	$tableName	The name of the database table to link the object to
	 *	@param	int		$id			The ID of the row to load
	 */
	public function __construct($tableName, $id = 0) {

		$this->tableName = $tableName;
		$this->id = $id;

		parent::__construct();

		// If a primary id isn't defined in the field list, a lot of the functions of this object won't work properly, so throw an exception
		if (!isset($this->_fields['id'])) throw new NoPrimaryIdException($this);

		// If an ID has been set and it's not an array (because of it's an array, then it's multiple rows of data), load the data into the object
		if ($this->id && !is_array($this->id)) {
			$this->loadFromDatabase();
			if ($this->isValid() && !$this->checkOwnership())
				throw new OwnershipException($this);
		}
	
	}
	
	/**
	 *	Load the current data array into the object.
	 *
	 *	@param	mixed	$fields		Either a field name or array of field names to load (if you want to load all fields, just leave this variable blank)
	 *	@param	bool	$query		If a query hasn't already been ran, pass true to run a query loading by the ID
	 *	@return	bool	True if the data was loaded, false otherwise
	 *
	 */
	public function loadFromDatabase($fields = '', $query = true) {

		if ($query)
			$this->query($this->scrubQueryString("SELECT * FROM $this->tableName WHERE " . $this->_fields['id']['dbName'] . " = '$this->id'"));
		elseif (!$this->getRowCount())
			return false;

		if ($row = $this->getNext(DatabaseQuery::_ROW_ASSOC_)) {

			$fields = $fields ? (is_array($fields) ? $fields : array($fields)) : $this->getClassVariables();
			
			if (is_array($fields)) {

				foreach ($fields as $f) {
				
					$this->_setDBValue($f, $row[$f]);
				
					// Override the type-casting for ID fields in order to allow loading of objects by ID arrays
					if ($this->_fields['id']['dbName'] && $f == $this->_fields['id']['dbName'])
						$this->$f = $row[$f];
					if ($this->_getVariableType($f) == 'serialized')
						$this->$f = unserialize($row[$f]);
					elseif (is_string($this->$f))
						$this->$f = stripslashes(trim($row[$f]));
					elseif (is_bool($this->$f)) {
						if ($this->_getVariableType($f) == 'yes/no' || $this->_getVariableType($f) == 'yes/no-full')
							$this->$f = strtolower($row[$f]) == 'y' || strtolower($row[$f]) == 'yes';
						elseif ($this->_getVariableType($f) == 'true/false')
							$this->$f = $row[$f] == 'true';
						else
							$this->$f = $row[$f] ? true : false;
					} elseif (is_numeric($this->$f)) {
						if ($this->_getVariableType($f) == 'date' || $this->_getVariableType($f) == 'datetime')
							$this->$f = $row[$f] == '0000-00-00' || $row[$f] == '0000-00-00 00:00:00' ? 0 : ($row[$f] && strtotime($row[$f]) ? strtotime($row[$f]) : 0);
						else
							$this->$f = $row[$f] == 'NULL' || is_null($row[$f]) || empty($row[$f]) ? 0 : $row[$f];
					} elseif (is_array($this->$f))
						$this->$f = explode(',', $row[$f]);
					else
						$this->$f = $row[$f];

				}

				$this->_valid = true;
				
			} else
				$this->_valid = false;
			
		} else
			$this->_valid = false;

		return $this->isValid();
	
	}
	
	/**
	 *	Insert the data from the current object into the database.
	 *
	 *	@param	bool	$autonumber		If set to true, skips the 'id' field in INSERT query
	 *	@param	bool	$debug 			When set to true, the query string will be echoed and script execution will halt
	 *	@param	bool	$skipOwnership	When set to true, the church ID of the object won't be checked against the churchId of the user
	 *	@return	int		If successful, the primary ID assigned by the insert is returned, otherwise false is returned
	 */
	public function insertIntoDatabase($autonumber = true, $debug = false, $skipOwnership = false) {
	
		if (!$skipOwnership && !$this->checkOwnership()) throw new OwnershipException($this);
	
		$fields = $this->getClassVariables();
		
		$queryString = "INSERT INTO $this->tableName (";

		foreach ($fields as $f) {
		
			if (preg_match('/^_.+$/', $f) || ($autonumber && $this->_fields['id']['dbName'] == $f))
				continue;
			
			$queryString .= $f . ', ';
		
		}
		
		$queryString = substr($queryString, 0, -2) . ') VALUES (';
		
		foreach ($fields as $f) {
		
			if (preg_match('/^_.+$/', $f) || ($autonumber && $this->_fields['id']['dbName'] == $f))
				continue;

			if (is_null($this->$f))
				$queryString .= 'NULL, ';
			elseif ($this->_getVariableType($f) == 'serialized')
				$queryString .= "'" . addslashes(serialize($this->$f)) . "', ";
			elseif (is_bool($this->$f)) {
				if ($this->_getVariableType($f) == 'yes/no')
					$queryString .= "'" . ($this->$f ? 'Y' : 'N') . "', ";
				elseif ($this->_getVariableType($f) == 'yes/no-full')
					$queryString .= "'" . ($this->$f ? 'yes' : 'no') . "', ";
				elseif ($this->_getVariableType($f) == 'true/false')
					$queryString .= "'" . ($this->$f ? 'true' : 'false') . "', ";
				else
					$queryString .= "'" . ($this->$f ? 1 : 0) . "', ";
			} elseif (is_numeric($this->$f)) {
				if ($this->_getVariableType($f) == 'date')
					$queryString .= "'" . ($this->$f ? date('Y-m-d', $this->$f) : '') . "', ";
				elseif ($this->_getVariableType($f) == 'datetime')
					$queryString .= "'" . ($this->$f ? date('Y-m-d G:i:s', $this->$f) : '') . "', ";
				else
					$queryString .= "'" . $this->$f . "', ";
			} elseif (is_string($this->$f)) {

				if ($this->_getVariableType($f) == 'md5')
					$queryString .= "MD5('" . addslashes($this->$f) . "'), ";
				else {
					$queryString .= "'" . addslashes(trim($this->$f)) . "', ";
				}
					
			} elseif (is_array($this->$f))
				$queryString .= "'" . addslashes(trim(implode(',', $this->$f))) . "', ";
			else
				$queryString .= "'" . $this->$f . "', ";
			
		}

		$queryString = substr($queryString, 0, -2) . ')';

		$q = new DatabaseQuery();
		$q->query($this->scrubQueryString($queryString), $debug);
		
		return $this->id = $q->getInsertId();
	
	}
	
	/**
	 *	Update the data from the current object in the database.
	 *
	 *	@param	mixed	$fields		A field name (or array of field names) to update. If omitted, all fields are updated
	 *	@param	bool	$debug 			When set to true, the query string will be echoed and script execution will halt
	 *	@param	bool	$skipOwnership	When set to true, the church ID of the object won't be checked against the churchId of the user
	 *	@return	bool	The results of the query (true if successful, false otherwise)
	 */
	public function updateDatabase($fields = '', $debug = false, $skipOwnership = false) {

		if (!$skipOwnership && !$this->checkOwnership()) return false;

		$fields = $fields ? (is_array($fields) ? $fields : array($fields)) : count(array_unique($this->_changedFields)) ? array_unique($this->_changedFields) : $this->getClassVariables(true);
		
		if (!count($fields)) return false;

		$queryString = "UPDATE $this->tableName SET ";
		
		foreach ($fields as $f) {
			
			$queryString .= $f . ' = ';
			
			if (is_null($this->$f))
				$queryString .= 'NULL, ';
			elseif (is_bool($this->$f)) {
				if ($this->_getVariableType($f) == 'yes/no')
					$queryString .= "'" . ($this->$f ? 'Y' : 'N') . "', ";
				elseif ($this->_getVariableType($f) == 'yes/no-full')
					$queryString .= "'" . ($this->$f ? 'yes' : 'no') . "', ";
				elseif ($this->_getVariableType($f) == 'true/false')
					$queryString .= "'" . ($this->$f ? 'true' : 'false') . "', ";
				else
					$queryString .= "'" . ($this->$f ? 1 : 0) . "', ";
			} elseif (is_numeric($this->$f)) {
				if ($this->_getVariableType($f) == 'date')
					$queryString .= "'" . ($this->$f ? date('Y-m-d', $this->$f) : '') . "', ";
				elseif ($this->_getVariableType($f) == 'datetime')
					$queryString .= "'" . ($this->$f ? date('Y-m-d G:i:s', $this->$f) : '') . "', ";
				else
					$queryString .= "'" . $this->$f . "', ";
			} elseif (is_string($this->$f)) {

				if ($this->_getVariableType($f) == 'md5')
					$queryString .= "MD5('" . addslashes($this->$f) . "'), ";
				else
					$queryString .= "'" . addslashes(trim($this->$f)) . "', ";
				
			} elseif (is_array($this->$f))
				$queryString .= "'" . addslashes(trim(implode(',', $this->$f))) . "', ";
			else
				$queryString .= "'" . $this->$f . "', ";
			
		}
		
		$queryString = substr($queryString, 0, -2) . ' WHERE ' . $this->_fields['id']['dbName'] . " = '$this->id'";

		$q = new DatabaseQuery();

		return $q->query($this->scrubQueryString($queryString), $debug);
	
	}
	
	/**
	 *	Removes data from the database for the current ID.
	 *
	 *	@param	bool	$ignoreBlankChurchId	When set to true, a blank Church ID won't throw an exception (used for legacy data)
	 *	@return	bool	The results of the query (true if successful, false otherwise)
	 */
	public function removeFromDatabase() {
	
		if (!$this->checkOwnership()) return false;

		$q = new DatabaseQuery();
		
		return $q->query($this->scrubQueryString("DELETE FROM $this->tableName WHERE " . $this->_fields['id']['dbName'] . " = '$this->id'"));
		
	}
	
	/**
	 *	Alias of {@link DatabaseObject::removeFromDatabase()}.
	 *
	 *	@param	bool	$ignoreBlankChurchId	When set to true, a blank Church ID won't throw an exception (used for legacy data)
	 *	@return	bool	The results of the query (true if successful, false otherwise)
	 */
	public function delete($ignoreBlankChurchId = false) {
		return $this->removeFromDatabase($ignoreBlankChurchId);
	}
	
	/**
	 *	Get an array of fields (database column names) that have changed since the object was loaded from the database.
	 *
	 *	@return	array	Array containing the changed fields
	 */
	public function getChangedFields() {
		return $this->_changedFields;
	}
	
	/**
	 *	Calls {@link DatabaseQuery::query()} after sending query to {@link DatabaseObject::scrubQueryString()} for string replacements.
	 *
	 *	@param	string	$queryString	The database query string to be used
	 *	@param	bool	$debug 			When set to true, the query string will be echoed and script execution will halt
	 *	@return	resource				The resource identifier returned by the query
	 */
	public function query($queryString, $debug = false) {
		return parent::query($this->scrubQueryString($queryString), $debug);
	}
	
	/**
	 *	Parses the supplied query string for database column name replacements.
	 *
	 *	To take advantage of this feature, when writing a query, use an '@' symbol in front of the variable name used in the class (NOT the actual database column name). This function will then automatically replace
	 *	those variable names with their proper database column names when {@link DatabaseObject::query()) is called.
	 *
	 *	@param	string	$queryString	The database query string to be parsed
	 *	@return	string	The query string with database column names inserted
	 */
	protected function scrubQueryString($queryString) {
	
		$fields = array($this->getClassVariables(false));
		
		if (preg_match_all('/\@([\w\d]+)/', $queryString, $regs))		
			foreach ($regs[1] as $varName)
				$queryString = preg_replace('/(\s|\.|\()@' . $varName . '/', '$1' . $this->_fields[$varName]['dbName'], $queryString);

		return $queryString;
	
	}
	
	public function checkOwnership() {
	
		if ($GLOBALS['ignoreOwnership']) return true;

		return !isset($this->_fields['accountId']) || (isset($this->_fields['accountId']) && $this->accountId == $GLOBALS['session']->getAccountId());
		
	}
	
	public function loadAllRecords($args = '') {
		$this->query($this->scrubQueryString("SELECT * FROM $this->tableName " . $args));
	}
	
	public function loadByIds($ids) {
		$this->loadAllRecords(' WHERE @id IN (' . implode(',', $ids) . ')');
	}
	
	public function loadNext($fields = '') {
		return $this->loadFromDatabase($fields, false);
	}
	
	public function isValid() {
		return $this->_valid;
	}
	
	public function getTableName() {
		return $this->tableName;
	}
	
	public function getIdArray($queryString = '') {
	
		if ($queryString)
			$this->query($this->scrubQueryString($queryString));
		
		$ids = array();
		
		while ($row = $this->getNext(_ROW_NUM_))
			$ids[] = $row[0];
		
		return $ids;
		
	}
	
	protected function getClassVariables($dbName = true) {
		
		foreach ($this->_fields as $key => $val)
			$a[] = $dbName ? $val['dbName'] : $key;

		return $a;
	
	}
	
	public function getVariableDump() {
		return array('className' => get_class($this),
					 'vars' => $this->_fields);
	}
	
	public function _($varName, $dbName = '', $value = '', $type = '') {
		$this->addClassVariable($varName, $dbName, $value, $type);
	}
	
	public function addClassVariable($varName, $dbName = '', $value = '', $type = '') {

		if (!is_string($dbName)) {
			$type = $value;
			$value = $dbName;
			$dbName = $varName;
		}
		
		if (property_exists($this, $varName)) return;
		
		$this->_fields[$varName] = array('value' => $value,
								   'dbName' => $dbName ? $dbName : $varName,
								   'dbValue' => '');
		
		$type = strtolower($type);
						   
		switch ($type) {
		
			case 'date':
			case 'datetime':
				$this->_fields[$varName]['type'] = $type;
				$this->_fields[$varName]['value'] = $value ? strtotime($value) : 0;
				break;
				
			case 'md5':
			case 'yes/no':
			case 'yes/no-full':

				$this->_fields[$varName]['type'] = $type;
				$this->_fields[$varName]['value'] = $value;
				break;
				
			case 'serialized':
				$this->_fields[$varName]['type'] = $type;
				$this->_fields[$varName]['value'] = is_string($value) ? unserialize($value) : $value;
				break;
		
		}
		
	}
	
	public function clearClassVariables() {
		$this->_fields = array();
	}
	
	public function removeClassVariable($varName) {
		unset($this->_fields[$varName]);
	}
	
	private function _getVariableType($varName) {
	
		if (isset($this->_fields[$varName]))
			return $this->_fields[$varName]['type'];
		else
			foreach ($this->_fields as $key => $val)
				if ($val['dbName'] == $varName)
					return $val['type'];
	
	}
	
	protected function setVariableType($varName, $type) {
	
		if (isset($this->_fields[$varName]))
			$this->_fields[$varName]['type'] = $type;
		else
			foreach ($this->_fields as $key => $val)
				if ($val['dbName'] == $varName)
					$this->_fields['key']['type'] = $type;
	}
	
	protected function getDBValue($varName) {
	
		if (isset($this->_fields[$varName]))
			return $this->_fields[$varName]['dbValue'];
		else
			foreach ($this->_fields as $key => $val)
				if ($val['dbName'] == $varName)
					return $val['dbValue'];
	
	}
	
	public function getDBName($var) {
		return $this->_fields[$var]['dbName'];
	}
	
	private function _setDBValue($varName, $dbValue) {

		if (isset($this->_fields[$varName]))
			$this->_fields[$varName]['dbValue'] = $dbValue;
		else
			foreach ($this->_fields as $key => $val)
				if ($val['dbName'] == $varName)
					$this->_fields[$key]['dbValue'] = $dbValue;
	
	}
	
	public function debug($die = true) {
		$GLOBALS['debugger']->debugObject($this);
		if ($die) die;
	}
	
	public function getDebugVars() {
	
		$vars = array();
	
		foreach (get_object_vars($this) as $key => $val)
			$vars[$key] = $val;
		
		return $vars;
	
	}
	
	public function __get($n) {
		if (isset($this->_fields[$n]))
			return $this->_fields[$n]['value'];
		else
			foreach ($this->_fields as $key => $val)
				if ($val['dbName'] == $n)
					return $this->_fields[$key]['value'];
	}
	
	public function __set($n, $value) {

		if (array_key_exists($n, get_object_vars($this))) {
			$this->$n = $value;
			return;
		}

		if (isset($this->_fields[$n])) {
			$this->_fields[$n]['value'] = $value;
			return true;
		} else
			foreach ($this->_fields as $key => $val)
				if ($val['dbName'] == $n)
					$this->_fields[$key]['value'] = $value;


	}
	
	public function __call($m, $a) {

		if (!is_array($a)) $a = array($a);

		if (preg_match('/^get(.+)$/', $m, $regs)) {

			$varName = strtolower(substr($regs[1], 0, 1)) . substr($regs[1], 1);
			$varNameLower = strtolower($varName);

			if (isset($this->_fields[$varName]))
				return $this->$varName;
			elseif (isset($this->_fields[$varNameLower]))
				return $this->$varNameLower;
			else
				throw new BadMethodCallException($m);
		
		} elseif (preg_match('/^(is(.+))$/', $m, $regs)) {

			$varName = strtolower(substr($regs[2], 0, 1)) . substr($regs[2], 1);
			$varNameLower = strtolower($varName);

			if (isset($this->_fields[$regs[1]]))
				return $this->$regs[1];
			elseif (isset($this->_fields[$varName]))
				return $this->$varName;
			elseif (isset($this->_fields[$varNameLower]))
				return $this->$varNameLower;
			else 
				throw new BadMethodCallException($m);
		
		} elseif (preg_match('/^set(.+)$/', $m, $regs)) {

			$varName = strtolower(substr($regs[1], 0, 1)) . substr($regs[1], 1);
			$varNameLower = strtolower($varName);

			if (isset($this->_fields[$varName])) {

				if (is_null($a[0]))
					$this->$varName = NULL;
				elseif (is_string($this->_fields[$varName]['value']))
					$this->$varName = stripslashes(trim($a[0]));
				elseif (is_bool($this->_fields[$varName]['value']))
					$this->$varName = $a[0] ? true : false;
				elseif (is_numeric($this->_fields[$varName]['value']))
					$this->$varName = $a[0] == 'NULL' || is_null($a[0]) || empty($a[0]) ? 0 : $a[0];
				elseif (is_array($this->_fields[$varName]['value']))
					$this->$varName = explode(',', $a[0]);
				else
					$this->$varName = $a[0];

			} elseif (isset($this->_fields[$varNameLower])) {

				if (is_string($this->_fields[$varNameLower]))
					$this->$varNameLower = stripslashes(trim($a[0]));
				elseif (is_bool($this->_fields[$varNameLower]))
					$this->$varNameLower = $a[0] ? true : false;
				elseif (is_numeric($this->_fields[$varNameLower]))
					$this->$varNameLower = $a[0] == 'NULL' || is_null($a[0]) || empty($a[0]) ? 0 : $a[0];
				elseif (is_array($this->_fields[$varNameLower]))
					$this->$varNameLower = explode(',', $a[0]);
				else
					$this->$varNameLower = $a[0];
				
			} else
				throw new BadMethodCallException($m);
			
			$this->_changedFields[] = $this->_fields[$varName]['dbName'];
			
		} else 
			throw new BadMethodCallException($m);
	
	}
	
	public function getDataArray() {
	
		$a = array();
	
		foreach ($this->getClassVariables() as $var)
			$a[$var] = $this->$var;

		return $a;
	
	}
	
	public function getDataForGrid($fields = array(), $limit = -1) {
	
		$count = 0;
		$data = array();
		
		while ($this->loadNext()) {
			
			$newArray = array();
		
			foreach ($fields as $f)
				$newArray[$f] = $this->$f;
			
			$data[] = $newArray;
		
		}
		
		return $data;
	
	}
	
	public function isEven() {
		return $this->index % 2;
	}
	
	/*
	 * Iterator functions
	 */
	 
	public function next() {
		return $this->loadNext();
	}
	 
	public function current() {
		if (!$this->isValid()) $this->loadNext();
		return $this;
	}
	
	public function key() {
		return $this->index;
	}
	
	public function valid() {
		return (count($this) && $this->index < count($this));
	}
	
	public function rewind() {
		return $this->reset();
	}

	public function isLast() {
		return $this->index == $this->getRowCount() - 1;
	}

}