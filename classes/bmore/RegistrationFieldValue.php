<?
class RegistrationFieldValue extends DatabaseObject {

	public function __construct($id = 0) {
	
		$this->_('id', 0);
		$this->_('eventId', 0);
		$this->_('registrationId', 0);
		$this->_('fieldId', 0);
		$this->_('value');
	
		parent::__construct('registration_field_values', $id);
	
	}

}