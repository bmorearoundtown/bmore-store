<?
class Category extends DatabaseObject {

	public function __construct($id = 0) {
	
		$this->_('id', 0);
		$this->_('name');
		$this->_('isActive', false);
		$this->_('dateCreated', 0, 'datetime');
		$this->_('dateLastUpdated', 0, 'datetime');
	
		parent::__construct('categories', $id);
	
	}
	
	public function loadCurrentCategories($boolIncludeCurrentEvents = true) {
	
		$this->query("
			SELECT		c.*
			FROM		{$this->tableName} AS c
							INNER JOIN events AS e ON c.id = e.categoryId
			WHERE		c.isActive = 1 AND
						e.isActive = 1 AND
						e.isPublished = 1 AND
						" . ($boolIncludeCurrentEvents ? 'e.endDate' : 'e.startDate') . " >= NOW()
			GROUP BY	c.id
			ORDER BY	c.name ASC
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
	
	public function loadForAdmin() {
	
		$this->query("
			SELECT		*
			FROM		{$this->tableName}
			WHERE		isActive = 1
			ORDER BY	name ASC
		");
	
	}

}