<?
class EventLocation extends DatabaseObject {

	public function __construct($id = 0) {
	
		$this->_('id', 0);
		$this->_('eventId', 0);
		$this->_('locationId', 0);
	
		parent::__construct('event_locations', $id);
	
	}
	
	public function loadByEventId($intEventId) {
		$this->query("
			SELECT	*
			FROM	{$this->tableName}
			WHERE	eventId = {$intEventId}
		");
	}

}