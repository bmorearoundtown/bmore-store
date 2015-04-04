<?
class RegistrationUpgradeX extends RegistrationUpgrade {

	public function __construct($intId = 0) {
	
		$this->_('upgradeName');
		$this->_('upgradePrice');
	
		parent::__construct($intId);
	
	}
	
	public function loadByRegistrationId($intRegistrationId) {
	
		return $this->query("
			SELECT		ru.*,
						u.name AS upgradeName,
						u.price AS upgradePrice
			FROM		{$this->tableName} AS ru
							INNER JOIN upgrades AS u ON ru.upgradeId = u.id
			WHERE		ru.registrationId = {$intRegistrationId} AND
						ru.quantity > 0
			ORDER BY	u.name ASC
		");
	
	}

}