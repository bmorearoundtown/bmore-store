<?
class RegistrationFieldOption extends DatabaseObject {

	public function __construct($intId = 0) {
	
		$this->_('id', 0);
		$this->_('fieldId', 0);
		$this->_('name');
	
		parent::__construct('registration_field_options', $intId);
		
	}
	
	public function loadByFieldId($intFieldId) {
		
		return $this->query("
			SELECT		*
			FROM		{$this->tableName}
			WHERE		fieldId = {$intFieldId}
			ORDER BY	id ASC
		");
		
	}

}