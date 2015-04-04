<?
class Package extends DatabaseObject {

	public function __construct($id = 0) {
	
		$this->_('id', 0);
		$this->_('eventId', 0);
		$this->_('name');
		$this->_('description');
		$this->_('price');
		$this->_('registrationStartDate', 0, 'datetime');
		$this->_('registrationDeadlineDate', 0, 'datetime');
		$this->_('atDoor', false);
		$this->_('isHidden', false);
		$this->_('registrationCode');
		$this->_('maxParticipants', 0);
		$this->_('ticketsPerPackage', 0);
		$this->_('dateCreated', 0, 'datetime');
		$this->_('dateLastUpdated', 0, 'datetime');
		
		parent::__construct('packages', $id);
	
	}
	
	public function loadByEventId($intEventId) {
	
		return $this->query("
			SELECT		*
			FROM		{$this->tableName}
			WHERE		eventId = {$intEventId} AND isHidden = 0
			ORDER BY	maxParticipants DESC, registrationDeadlineDate ASC, price DESC
		");
	
	}

	public function loadByEventRegistrationCode($strEventRegistrationCode) {
	
		return $this->query("
				
				SELECT		p.*
				FROM		{$this->tableName} AS p
							INNER JOIN events AS e ON p.eventId = e.id
				WHERE		e.registrationCode = '{$strEventRegistrationCode}' AND p.isHidden = 0
				
				");
	
	
	}

	public function loadAvailablePackagesByEventRegistrationCode($strEventRegistrationCode) {
	
		return $this->query("
	
				SELECT		p.*
				FROM		{$this->tableName} AS p
				INNER JOIN events AS e ON p.eventId = e.id
				WHERE		e.registrationCode = '{$strEventRegistrationCode}'
							AND p.isHidden = 0
							AND p.maxParticipants > 0 AND p.registrationDeadlineDate >=  NOW()
				");
	
	
	}

	public function loadSoldOutPackagesByEventRegistrationCode($strEventRegistrationCode) {
	
		return $this->query("
	
				SELECT		p.*
				FROM		{$this->tableName} AS p
				INNER JOIN events AS e ON p.eventId = e.id
				WHERE		e.registrationCode = '{$strEventRegistrationCode}'
				AND p.isHidden = 0
				AND p.maxParticipants = 0
				");
	
	
	}

	public function loadExpiredPackagesByEventRegistrationCode($strEventRegistrationCode) {
	
		return $this->query("
	
				SELECT		p.*
				FROM		{$this->tableName} AS p
				INNER JOIN events AS e ON p.eventId = e.id
				WHERE		e.registrationCode = '{$strEventRegistrationCode}'
				AND p.isHidden = 0
				AND p.registrationDeadlineDate <  NOW()
				");
	
	
	}
	
	public function loadByRegistrationCode($strRegistrationCode) {
	
		$arrCodes = explode('-', $strRegistrationCode);
		
		$this->query("
			SELECT		p.*
			FROM		{$this->tableName} AS p
							INNER JOIN events AS e ON p.eventId = e.id
			WHERE		p.registrationCode = '{$arrCodes[1]}' AND
						e.registrationCode = '{$arrCodes[0]}'
		");
		
		return $this->loadNext();
	
	}

	public function getDescriptionWithLimit($intLimit = 0) {
	
		if ($intLimit)
			return StringX::truncate($this->description, $intLimit, 0, '...');
		else
			return $this->description;
	
	}
	
	public function getStartDateDisplay() {
		return date('l, F j, Y', $this->registrationStartDate);
	}
	
	public function getEndDateDisplay() {
		return date('l, F j, Y', $this->registrationDeadlineDate);
	}
	
	public function isSoldOut() {
		return $this->maxParticipants < 1;
	}
	
	public function isDeadlinePassed() {
		return $this->registrationDeadlineDate < time();
	}
	
	public function lessThanDayLeft() {
		return $this->registrationDeadlineDate - time() < 60*60*24;
	}
	
	public function getRegistrationTimeLeft() {
		return Date::diffAsString(time(), $this->registrationDeadlineDate);
	}

}