<?
class RegistrationX extends Registration {

	public function __construct($intId = 0) {
	
		$this->_('eventName');
	
		parent::__construct($intId);
	
	}
	
	public function loadRecentRegistrations($intLimit = 10) {
	
		$this->query("
			SELECT		r.*,
						e.name AS eventName
			FROM		{$this->tableName} AS r
							INNER JOIN events AS e ON r.eventId = e.id
			WHERE		r.isActive = 1 AND
						ipnResponse LIKE '%[payment_status] => Completed%' AND
						e.isActive = 1
			ORDER BY 	r.dateCreated DESC
			LIMIT		{$intLimit}
		");
	
	}
	
	public function loadByEventId($eventId) {
	
		$this->_('packageName');
	
		$this->query("
			SELECT		r.*,
						p.name AS packageName
			FROM		{$this->tableName} AS r
							INNER JOIN packages AS p ON r.packageId = p.id
			WHERE		r.eventId ={$eventId}
			ORDER BY	lastName, firstName
		");
		
	}
	
	public function loadCompleteByEventId($eventId) {
	
		$this->_('packageName');
	
		$this->query("
			SELECT		r.*,
						p.name AS packageName
			FROM		{$this->tableName} AS r
							INNER JOIN packages AS p ON r.packageId = p.id
			WHERE		r.eventId = {$eventId} AND
						isCanceled = 0
			ORDER BY	lastName, firstName
		");
		
	}
	
	public function loadIncompleteByEventId($eventId) {
	
		$this->_('packageName');
	
		$this->query("
			SELECT		r.*,
						p.name AS packageName
			FROM		{$this->tableName} AS r
							INNER JOIN packages AS p ON r.packageId = p.id
			WHERE		r.eventId ={$eventId} AND
						datePaid = 0
			ORDER BY	firstName, lastName
		");
		
	}

}