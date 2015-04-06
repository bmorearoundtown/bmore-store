<?
class PackageX extends Package {

	public function __construct($intId = 0) {
		
		$this->_('eventName');
		$this->_('totalRegistrations');
		
		parent::__construct($intId);
	
	}

	public function loadByEventId($intEventId) {
	
		return $this->query("
				SELECT		p.*, e.name as eventName
				FROM		{$this->tableName} as p
							INNER JOIN events AS e ON p.eventId = e.id
				WHERE		p.eventId = {$intEventId} AND p.isHidden = 0
				ORDER BY	registrationDeadlineDate ASC, price DESC
				");
	
	}
	
	public function loadByRegistrationCode($strRegistrationCode) {
	
		$arrCodes = explode('-', $strRegistrationCode);
		
		$this->query("
			SELECT		p.*, COUNT( r.id ) AS totalRegistrations
			FROM		{ $this->tableName } AS p
							INNER JOIN events AS e ON p.eventId = e.id
							LEFT JOIN registrations AS r ON p.id = r.packageId
			WHERE		p.registrationCode = '{$arrCodes[1]}' AND
						e.registrationCode = '{$arrCodes[0]}'
		");
		
		return $this->loadNext();
	
	}
	
	public function isSoldOut() {
		return $this->maxParticipants <= 0;
//		return $this->maxParticipants > 0 && $this->totalRegistrations >= $this->maxParticipants;
	}
	
	public function getAvailableTickets() {
		return 1000;
//		return $this->maxParticipants < 0 ? 1000 : $this->maxParticipants;
//		return $this->maxParticipants <= 0 ? 1000 : $this->maxParticipants - $this->totalRegistrations;
	}

}