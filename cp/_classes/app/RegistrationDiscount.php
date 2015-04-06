<?
class RegistrationDiscount extends DatabaseObject {

	public function __construct($id = 0) {
	
		$this->_('id', 0);
		$this->_('eventId', 0);
		$this->_('registrationId', 0);
		$this->_('discountId', 0);
		$this->_('discountAmount', 0.0);
	
		parent::__construct('registration_discounts', $id);
	
	}
	
	public function loadByDiscountId($intDiscountId) {
	
		return $this->query("
			SELECT		*
			FROM		{$this->tableName}
			WHERE		discountId = {$intDiscountId}
		");
	
	}

}