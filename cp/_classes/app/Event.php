<?
class Event extends DatabaseObject {

	public function __construct($id = 0) {
	
		$this->_('id', 0);
		$this->_('name');
		$this->_('description');
		$this->_('categoryId', 0);
		$this->_('isActive', false);
		$this->_('isPublished', false);
		$this->_('hasLogo', false);
		$this->_('maxParticipants', 0);
		$this->_('startDate', 0, 'datetime');
		$this->_('endDate', 0, 'datetime');
		$this->_('isMultiDay', false);
		$this->_('registrationCode');
		$this->_('dateLastUpdated', 0, 'datetime');
		$this->_('dateCreated', 0, 'datetime');
		$this->_('dateCanceled', 0, 'datetime');
		$this->_('datePublished', 0, 'datetime');
		$this->_('maxTicketsPerRegistration', 0);
		$this->_('registrationDeadlineDate', 0, 'datetime');
	
		parent::__construct('events', $id);
	
	}
	
	public function loadByRegistrationCode($strRegistrationCode) {
		$this->query("
			SELECT		*
			FROM		{$this->tableName}
			WHERE		registrationCode = '{$strRegistrationCode}'
		");
	}
	
	public function loadUpcomingEvents($boolIncludeCurrentEvents = true, $intLimit = 10) {
	
		$this->query("
			SELECT		*
			FROM		{$this->tableName}
			WHERE		isActive = 1 AND
						isPublished = 1 AND
						" . ($boolIncludeCurrentEvents ? 'endDate' : 'startDate') . " >= NOW()
			ORDER BY	startDate ASC
			LIMIT		{$intLimit}
		");
	
	}

	public function loadForForm() {
	
		$this->query("
			SELECT		id, name
			FROM		{$this->tableName}
			ORDER BY	name ASC
		");
	
	}
	
	public function loadAdminEvents() {
	
		$this->query("
			SELECT		*
			FROM		{$this->tableName}
			WHERE		1 = 1 AND
						" . ($boolIncludeCurrentEvents ? 'endDate' : 'startDate') . " >= '" . date('Y-m-d', time() - 60*60*24*2) . "'
			ORDER BY	startDate ASC
		");
	
	}
	
	public function getAvailableTickets() {
	
		$objRegistration = new Registration();
		$objRegistration->loadByEventId($this->id);
		
//		return $this->maxParticipants <= 0 ? 1000 : $this->maxParticipants - count($objRegistration);
		return 1000;
	
	}
	
	public function getDescription($intLimit = 0) {
	
		if ($intLimit)
			return StringX::truncate($this->description, $intLimit, 0, '...');
		else
			return $this->description;
	
	}
	
	public function getDatesDisplay() {
	
		if (date('Ymd', $this->startDate) == date('Ymd', $this->endDate))
			return date('l, F j, Y', $this->startDate) . ' from ' . date('g:i A', $this->startDate) . ' to ' . date('g:i A', $this->endDate) . ' (ET)';
		else
			return date('l, F j, Y', $this->startDate) . ' at ' . date('g:i A', $this->startDate) . ' to ' . date('l, F j, Y', $this->endDate) . ' at ' . date('g:i A', $this->endDate) . ' (ET)';
	
	}
	
	public function getLogoImageUrl() {
		return '/_assets/_images/logos/events/' . $this->getRegistrationCode() . '.png';
	}
	
	public function generateRegistrationCode() {
	
		$strLetters = 'BCDFGHJKLMNPQRSTVWXYZ';
		
		do {
		
			$strRegistrationCode = $strLetters[rand(0, strlen($strLetters) - 1)] . $strLetters[rand(0, strlen($strLetters) - 1)] . $strLetters[rand(0, strlen($strLetters) - 1)];
			
			$objEvent = new Event();
			$objEvent->loadByRegistrationCode($strRegistrationCode);
		
		} while (count($objEvent));
		
		$this->registrationCode = $strRegistrationCode;
	
	}

}