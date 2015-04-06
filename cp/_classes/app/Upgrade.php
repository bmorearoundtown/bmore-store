<?
class Upgrade extends DatabaseObject {

	public function __construct($id = 0) {
	
		$this->_('id', 0);
		$this->_('eventId', 0);
		$this->_('name');
		$this->_('description');
		$this->_('price', 0.0);
		$this->_('maxPerRegistrant', 0);
		$this->_('dateCreated', 0, 'datetime');
		$this->_('dateLastUpdated', 0, 'datetime');
	
		parent::__construct('upgrades', $id);
	
	}
	
	public function loadByEventId($intEventId) {
	
		$this->query("
			SELECT		*
			FROM		{$this->tableName}
			WHERE		eventId = {$intEventId}
			ORDER BY	name ASC
		");
	
	}

}