<?
class RegistrationX extends Registration {

	public function __construct($intId = 0) {
	
		$this->_('eventName');
		$this->_('packageName');
		
		parent::__construct($intId);
	
	}
	
	public function loadRecentRegistrations($intLimit = 10) {
		
		$this->_('packageName');
		
		$this->query("
			SELECT		r.*, p.name as packageName, e.name AS eventName
			FROM		{$this->tableName} AS r
							INNER JOIN events AS e ON r.eventId = e.id
							INNER JOIN packages AS p ON r.packageId = p.id
			WHERE		r.isActive = 1 
						AND ipnResponse LIKE 'Completed'
						AND e.isActive = 1
			ORDER BY 	r.datePaid DESC
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
	
	public function loadOldCompleteByEventId($eventId) {
	
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
			WHERE		r.eventId ={$eventId} 
						AND ipnResponse LIKE 'Completed'
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
			WHERE		r.eventId ={$eventId} AND ( NOT ipnResponse LIKE 'Completed' OR datePaid = 0 )
			ORDER BY	firstName, lastName
		");
		
	}

	public function loadUniquePaypalUsers() {
	
		$this->query("
			SELECT r.*
			FROM registrations r
			INNER JOIN (
				SELECT DISTINCT paypalemailaddress, min( id ) AS id
				FROM registrations
				GROUP BY paypalemailaddress
			) AS r2 ON r.paypalemailaddress = r2.paypalemailaddress
			AND r.id = r2.id
		");
		
	}
	
}