<?
class DiscountCode extends DatabaseObject {

	public function __construct($id = 0) {
	
		$this->_('id', 0);
		$this->_('eventId', 0);
		$this->_('code');
		$this->_('discountType');
		$this->_('discount', 0);
		$this->_('maxDiscounts', 0);
		$this->_('discountsUsed', 0);
		$this->_('dateCreated', 0, 'date');
		$this->_('dateLastUpdated', 0, 'datetime');
	
		parent::__construct('discount_codes', $id);
	
	}
	
	public function loadByDiscountCode($strDiscountCode, $mxdEventMatch) {
	
		$this->query("
			SELECT		dc.*
			FROM		{$this->tableName} AS dc
							INNER JOIN events As e ON dc.eventId = e.id
			WHERE		dc.code = '{$strDiscountCode}' AND
						" . (is_numeric($mxdEventMatch) ? " e.id = {$mxdEventMatch} " : " e.registrationCode = '{$mxdEventMatch}' ") . '
		');
		
		return $this->loadNext();
	
	}
	
	public function applyDiscount($dblAmount) {
	
		if ($this->discountType == 'fixed')
			return $dblAmount - $this->discount;
		elseif ($this->discountType == 'percentage')
			return $dblAmount * (1.0 - ($this->discount * .01));
		else
			return $dblAmount;
	
	}
	
	public function updateCounts() {
	
		$objRegistrationDiscount = new RegistrationDiscount();
		$objRegistrationDiscount->loadByDiscountId($this->id);
		
		$this->discountsUsed = count($objRegistrationDiscount);
		
		if (!$this->updateDatabase())
			throw new RecordUpdateException($this);
	
	}

}