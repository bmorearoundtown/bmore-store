<?
class RegistrationFieldValueX extends RegistrationFieldValue {

	public function __construct($intId = 0) {
		
		$this->_('fieldName');
		$this->_('fieldType');
		$this->_('optionName');
		
		parent::__construct($intId);
		
	}
	
	public function loadByRegistrationId($intRegistrationId) {
	
		return $this->query("
			SELECT		rfv.*,
						rf.name AS fieldName,
						rf.fieldType,
						rfo.name AS optionName
			FROM		{$this->tableName} AS rfv
							RIGHT JOIN registration_fields AS rf ON rfv.fieldId = rf.id
							LEFT JOIN registration_field_options AS rfo ON rf.fieldType = 'select' AND rfv.value = rfo.id
			WHERE		rfv.registrationId = {$intRegistrationId}
			ORDER BY	rf.name ASC
		");
	
	}
	
	public function getValueDisplay() {
	
		switch ($this->fieldType) {
		
			case 'text':
				return $this->value;
				break;
				
			case 'select':
				return $this->optionName;
				break;
				
			case 'checkbox':
				return $this->value ? 'Yes' : 'No';
				break;
		
		}
	
	}

}