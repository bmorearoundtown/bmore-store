<?
class Settings extends DatabaseObject {

	public function __construct($id = 0) {
	
		$this->_('id', 0);
		$this->_('accountId', 0);
		$this->_('defaultState');
	
		parent::__construct('settings', $id);
	
	}
	
	
	
	public function loadByAccountId($intAccountId) {
	
		$this->query("
			SELECT		*
			FROM		{$this->tableName}
			WHERE		accountId = {$intAccountId}
		");
		
		return $this->loadNext();
	
	}

}