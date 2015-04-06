<?
class CategoryX extends Category {

	public function __construct($intId = 0) {
	
		$this->_('eventCount', 0);
		$this->_('upcomingEventCount', 0);
		
		parent::__construct($intId);
	
	}
	
	public function loadForAdmin() {
	
		$this->query("
			SELECT		c.*,
						COUNT(e.id) AS upcomingEventCount
			FROM		{$this->tableName} AS c
							LEFT JOIN events AS e ON (c.id = e.categoryId AND e.isActive = 1 AND e.endDate > NOW())
			GROUP BY	c.id
			ORDER BY	c.name
							
		");
	
	}

}