<?
/**
 *	Object-based database connectivity
 *
 *	This class provides object-based access to a database located on a local or remote server.
 *
 *	Example usage:
 *	<code>
 *	$db = new DatabaseLink('127.0.0.1', 'testuser', 'testpass', 'testdb');
 *	</code>
 *
 *	@author		Rob Erekson
 *	@package	base
 *	@subpackage	database
 *	@version	1.0
 *	@see		DatabaseQuery
 */
class DatabaseLink {

	/**
	 *	The database identifier
	 *	@var	resource
	 */
	private $_dbLink = null;
	
	/**
	 *	The address of the database server
	 *	@var	string
	 */
	private $_server = '';
	
	/**
	 *	The username used to log in to the database
	 *	@var	string
	 */
	private $_username = '';
	
	/**
	 *	The password used to log in to the database
	 *	@var	string
	 */
	private $_password = '';
	
	/**
	 *	The database to be selected
	 *	@var	string
	 */
	private $_dbName = '';
	
	/**
	 *	If a persistent database connection is to be used
	 *	@var	bool
	 */
	private $_persistent = false;
	
	/**
	 *	Set the connection information and establish the database connection
	 *
	 *	@param	string	$server		The server URL or IP address
	 *	@param	string	$username	The username to connect with
	 *	@param	string	$password	The password to connect with
	 *	@param	string	$dbName		The database to select after connecting
	 */
	function __construct($server = '', $username = '', $password = '', $dbName = '') {

		$this->_server = $server;
		$this->_username = $username;
		$this->_password = $password;
		$this->_dbName = $dbName;
		
		// If connection details are provided, establish connection and select database
		if ($this->_server && $this->_username && $this->_password) {
			$this->connect();
			if ($this->_dbName)
				$this->selectDatabase();
			
		}
	
	}
	
	/**
	 *	Set the database to select
	 *
	 *	@param	string	$dbName		The database to select
	 */
	public function selectDatabase($dbName = '') {
	
		if (!empty($dbName))
			$this->_dbName = $dbName;
		elseif (!$this->_dbName)
			throw new InvalidArgumentException();

		if (!mysql_select_db($this->_dbName, $this->_dbLink))
			throw new DatabaseSelectDatabaseException(array('server' => $this->_server, 'database' => $this->_dbName));
	
	}
	
	/**
	 *	Establish the database connection
	 *
	 *	@return	bool	Whether connection was established successfully
	 */
	public function connect() {
	
		if ($this->_persistent)
			$this->_dbLink = @mysql_pconnect($this->_server, $this->_username, $this->_password);
		else
			$this->_dbLink = @mysql_connect($this->_server, $this->_username, $this->_password);
		
		if (!$this->_dbLink)
			throw new DatabaseConnectionException(array('server' => $this->_server, 'username' => $this->_username, 'password' => $this->_password));
		
		return $this->isConnected();
		
	}
	
	/**
	 *	Disconnect from the database
	 */
	public function disconnect() {
		@mysql_close($this->_dbLink);
		$this->_dbLink = 0;
	}
	
	/**
	 *	Set the internal database resource identifier
	 *
	 *	@param	resource	The database resource identifier to use
	 */
	public function setIdentifier($dbLink) {
		$this->_dbLink = $dbLink;
	}
	
	/**
	 *	Get the internal database resource identifier
	 *
	 *	@return	resource	The internal database resource identifier
	 */
	public function getIdentifier() {
		return $this->_dbLink;
	}
	
	/**
	 *	Get the last error message from the connection
	 *
	 *	@return	string		The error message
	 */
	public function getErrorMessage() {
		return @mysql_error($this->_dbLink);
	}
	
	/**
	 *	Check if the database is currently connected
	 *
	 *	@return	bool		Whether the database is connected
	 */
	public function isConnected() {
		return ($this->_dbLink != 0);
	}
	
	/**
	 *	Set the class to use a persistent database connection
	 */
	public function usePersistentConnection() {
		$this->_persistent = true;
	}
	
}