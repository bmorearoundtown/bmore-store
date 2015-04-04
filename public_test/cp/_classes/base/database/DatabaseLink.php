<?
class DatabaseLink {

	private 	$dbLink = null; 	// Database connection identifier
	private 	$res = null; 		// Current resource identifier
	
	private 	$server = '';		// Server name
	private 	$username = '';		// Database username
	private 	$password = '';		// Database password
	private 	$dbName = '';		// Name of current working database
	
	private 	$persistent = false;
	
	private 	$errors;
	
	function __construct($server = '', $username = '', $password = '', $dbName = '') {

		$this->server = $server;
		$this->username = $username;
		$this->password = $password;
		$this->dbName = $dbName;
		
		if ($this->server && $this->username && $this->password) {
			$this->connect();
			if ($this->dbName)
				$this->selectDatabase();
			
		}
	
	}
	
	public function selectDatabase($dbName = '') {
	
		if (!empty($dbName))
			$this->dbName = $dbName;
		elseif (!$this->dbName)
			return false;

		if (!mysqli_select_db($this->dbLink, $this->dbName))
			throw new DatabaseSelectDatabaseException(array('server' => $this->server, 'database' => $this->dbName));
	
	}
	
	public function connect() {

		$this->dbLink = mysqli_connect($this->server, $this->username, $this->password);

		if (!$this->dbLink)
			throw new DatabaseConnectionException(array('server' => $this->server, 'username' => $this->username, 'password' => $this->password));
		
		return $this->isConnected();
		
	}
	
	public function disconnect() {
		@mysqli_close($this->dbLink);
		$this->dbLink = 0;
	}
	
	public function setIdentifier($dbLink) {
		$this->dbLink = $dbLink;
	}
	
	public function getIdentifier() {
		return $this->dbLink;
	}
	
	public function getErrorMessage() {
		return @mysqli_error($this->dbLink);
	}
	
	public function isConnected() {
		return ($this->dbLink != 0);
	}
	
	public function usePersistentConnection() {
		$this->persistent = true;
	}
	
	
	
	public function getServer() {
		return $this->server;
	}
	
	public function getUsername() {
		return $this->username;
	}
	
	public function getPassword() {
		return $this->password;
	}
	
	public function getDbName() {
		return $this->dbName;
	}
	
}