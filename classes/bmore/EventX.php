<?
class EventX extends Event {

	public function __construct($intId = 0) {
	
		$this->_('locationName');
		$this->_('categoryName');
		$this->_('totalRegistrations');
		$this->_('totalPaidRegistrations');
		$this->_('totalTickets');
		$this->_('totalPaidTickets');
		$this->_('lowestPrice');
		$this->_('highestPrice');
	
		parent::__construct($intId);
		
	}

	public function loadFromDatabase($arrFields = '', $boolQuery = true) {
	
		if (count($this)) {
			return parent::loadFromDatabase($arrFields, $boolQuery);
		}
		
		$this->query("
			SELECT		e.*,
						l.name AS locationName,
						c.name AS categoryName,
						MIN(p.price) AS lowestPrice,
						MAX(p.price) AS highestPrice
			FROM		{$this->tableName} AS e
							LEFT JOIN event_locations AS el ON e.id = el.eventId
							LEFT JOIN locations AS l ON l.id = el.locationId
							INNER JOIN categories AS c ON e.categoryId = c.id
							LEFT JOIN packages AS p ON e.id = p.eventId
			WHERE		e.id = {$this->id}
			GROUP BY	e.id
		");
									
		return parent::loadFromDatabase($fields, false);
	
	}
	
	public function loadForAdmin() {

		return $this->query("
			SELECT		e.*,
						l.name AS locationName,
						c.name AS categoryName
#						COUNT(r1.id) AS totalPaidRegistrations,
#						COUNT(r2.id) AS totalRegistrations,
#						SUM(r2.numberOfTickets) AS totalTickets,
#						SUM(r1.numberOfTickets) AS totalPaidTickets,
#						MIN(p.price) AS lowestPrice,
#						MAX(p.price) AS highestPrice
			FROM		{$this->tableName} AS e
							LEFT JOIN event_locations AS el ON e.id = el.eventId
							LEFT JOIN locations AS l ON l.id = el.locationId
							INNER JOIN categories AS c ON e.categoryId = c.id
#							LEFT JOIN packages AS p ON e.id = p.eventId
#							LEFT JOIN registrations AS r1 ON (e.id = r1.eventId AND ( (r1.isActive = 1 AND r1.datePaid <> '0000-00-00') OR (r1.isActive IS NULL AND r1.datePaid IS NULL) ) )
#							LEFT JOIN registrations AS r2 ON e.id = r2.eventId
			WHERE		e.isActive = 1 AND
						" . ($boolIncludeCurrentEvents ? 'endDate' : 'startDate') . " >= NOW()
						" . ($intCategoryId ? " AND e.categoryId = {$intCategoryId} " : '') . "
			GROUP BY	e.id
			ORDER BY	startDate ASC
			" . ($intLimit ? " LIMIT		{$intLimit} " : '') . '
		');
	
	}

	public function loadByRegistrationCode($strRegistrationCode) {
		
		$this->query("
			SELECT		e.*,
						l.name AS locationName,
						c.name AS categoryName,
#						COUNT(r.id) AS totalRegistrations,
#						SUM(r.numberOfTickets) AS totalTickets,
						MIN(p.price) AS lowestPrice,
						MAX(p.price) AS highestPrice
			FROM		{$this->tableName} AS e
							INNER JOIN event_locations AS el ON e.id = el.eventId
							INNER JOIN locations AS l ON l.id = el.locationId
							INNER JOIN categories AS c ON e.categoryId = c.id
							LEFT JOIN packages AS p ON e.id = p.eventId
#							LEFT JOIN registrations AS r ON e.id = r.eventId
			WHERE		e.registrationCode = '{$strRegistrationCode}'
			GROUP BY	e.id
		");

		return $this->loadNext();
	
	}

	public function loadUpcomingEvents($boolIncludeCurrentEvents = true, $intLimit = 10, $intCategoryId = 0) {

		return $this->query("
			SELECT		e.*,
						l.name AS locationName,
						c.name AS categoryName,
						MIN(p.price) AS lowestPrice,
						MAX(p.price) AS highestPrice
			FROM		{$this->tableName} AS e
							INNER JOIN event_locations AS el ON e.id = el.eventId
							INNER JOIN locations AS l ON l.id = el.locationId
							INNER JOIN categories AS c ON e.categoryId = c.id
							LEFT JOIN packages AS p ON e.id = p.eventId
			WHERE		e.isActive = 1 AND e.isPublished = 1 AND
						e.datePublished < NOW() AND
						" . ($boolIncludeCurrentEvents ? 'endDate' : 'startDate') . " >= NOW()
						" . ($intCategoryId ? " AND e.categoryId = {$intCategoryId} " : '') . "
			GROUP BY	e.id
			ORDER BY	startDate ASC
			" . ($intLimit ? " LIMIT		{$intLimit} " : '') . '
		');
	
	}
	
	public function getTotalPaidRegistrations() {
		
		$objRegistration = new Registration();
		
		return $objRegistration->getTotalPaidRegistrationCount($this->id);
		
	}
	
	public function getTotalRegistrations() {
		
		$objRegistration = new Registration();
		
		return $objRegistration->getTotalRegistrationCount($this->id);
		
	}
	
	public function getTotalPaidTickets() {
		
		$objRegistration = new Registration();
		
		return $objRegistration->getTotalPaidTicketsCount($this->id);
		
	}
	
	public function getTotalTickets() {
		
		$objRegistration = new Registration();
		
		return $objRegistration->getTotalTicketsCount($this->id);
		
	}
	
	public function getPriceRangeDisplay() {
		return "$" . sprintf('%01.2f', $this->lowestPrice ) . " - $" . sprintf('%01.2f', $this->highestPrice );
	}
		
	public function isSoldOut() {
		return $this->maxParticipants < 0;
//		return $this->maxParticipants > 0 && $this->totalPaidRegistrations >= $this->maxParticipants;
	}

}