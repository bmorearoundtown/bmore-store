<?
class Location extends DatabaseObject {

	public function __construct($id = 0) {
	
		$this->_('id', 0);
		$this->_('name');
		$this->_('description');
		$this->_('address1');
		$this->_('address2');
		$this->_('city');
		$this->_('state');
		$this->_('zipCode');
		$this->_('dateCreated', 0, 'datetime');
		$this->_('dateLastUpdated', 0, 'datetime');
		$this->_('dateRemoved', 0, 'datetime');
		$this->_('isActive', false);
	
		parent::__construct('locations', $id);
	
	}
	
	public function delete() {
	
		$this->isActive = false;
		$this->dateRemoved = time();
		
		return $this->updateDatabase();
	
	}
	
	public function loadByEventId($intEventId, $boolAutoload = true) {
	
		$this->query("
			SELECT		l.*
			FROM		{$this->tableName} AS l
							INNER JOIN event_locations AS el ON l.id = el.locationId
			WHERE		el.eventId = {$intEventId}
		");
		
		if ($boolAutoload)
			return $this->loadNext();
	
	}
	
	public function loadForForm() {
	
		$this->query("
			SELECT		*
			FROM		{$this->tableName}
			WHERE		isActive = 1 AND
						dateRemoved = 0
			ORDER BY	name ASC
		");
	
	}
	
	public function loadForAdmin() {
	
		$this->query("
			SELECT		*
			FROM		{$this->tableName}
			WHERE		dateRemoved = 0
			ORDER BY	name ASC
		");
	
	}
	
	public function getAddressDisplay($boolSingleLine = false) {
	
		$strSeparator = $boolSingleLine ? ', ' : '<br />';
	
		return $this->address1 . $strSeparator . ($this->address2 ? $this->address2 . $strSeparator : '') . $this->city . ', ' . $this->state . ' ' . $this->zipCode;
	
	}

}