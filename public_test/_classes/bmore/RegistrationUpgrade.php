<?
class RegistrationUpgrade extends DatabaseObject {

	public function __construct($id = 0) {
	
		$this->_('id', 0);
		$this->_('eventId', 0);
		$this->_('registrationId', 0);
		$this->_('upgradeId', 0);
		$this->_('quantity', 0);
	
		parent::__construct('registration_upgrades', $id);
	
	}
	
	public function loadByRegistrationId($intRegistrationId) {
	
		$this->query("
			SELECT		ru.*
			FROM		{$this->tableName} AS ru
							INNER JOIN upgrades AS u ON ru.upgradeId = u.id
			WHERE		registrationId = {$intRegistrationId}
			ORDER BY	u.name ASC
		");
	
	}

}