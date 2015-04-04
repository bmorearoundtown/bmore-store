<?
class Hotel extends DatabaseObject {

	public function __construct($id = 0) {
	
		$this->_('id', 0);
		$this->_('isActive', false);
		$this->_('name');
		$this->_('address1');
		$this->_('address2');
		$this->_('city');
		$this->_('state');
		$this->_('zipCode');
		$this->_('phone1');
		$this->_('phone2');
		$this->_('url');
		$this->_('dateCreated', 0, 'datetime');
		$this->_('dateRemoved', 0, 'datetime');
		$this->_('dateLastUpdated', 0, 'datetime');
	
		parent::__construct('hotels', $id);
	
	}
	
	public function delete() {
	
		$this->isActive = false;
		$this->dateRemoved = time();
		
		return $this->updateDatabase();
	
	}
	
	public function loadForAdmin() {
	
		$this->query("
			SELECT		*
			FROM		{$this->tableName}
			WHERE		dateRemoved = 0
			ORDER BY	name ASC
		");
	
	}
	
	public function loadForForm() {
	
		$this->query("
			SELECT		*
			FROM		{$this->tableName}
			WHERE		isActive = 1
			ORDER BY	name ASC
		");
	
	}
	
	public function loadByEventId($intEventId) {
		
		$this->query("
			SELECT		h.*
			FROM		{$this->tableName} AS h
							INNER JOIN event_hotels AS eh ON h.id = eh.hotelId
			WHERE		eh.eventId = {$intEventId} AND
						isActive = 1
			ORDER BY	h.name
		");
		
	}
	
	public function getAddressDisplay($boolSingleLine = false) {
	
		$strSeparator = $boolSingleLine ? ', ' : '<br />';
	
		return $this->address1 . $strSeparator . ($this->address2 ? $this->address2 . $strSeparator : '') . $this->city . ', ' . $this->state . ' ' . $this->zipCode;
	
	}
	
	public function getUrlDisplay() {
	
		return '<a href="' . $this->url . '" class="uiLink" rel="popup=yes">' . $this->url . '</a>';
	
	}

}