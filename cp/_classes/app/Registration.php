<?
class Registration extends DatabaseObject {

	public function __construct($id = 0) {
	
		$this->_('id', 0);
		$this->_('eventId', 0);
		$this->_('packageId', 0);
		$this->_('firstName');
		$this->_('lastName');
		$this->_('address1');
		$this->_('address2');
		$this->_('city');
		$this->_('state');
		$this->_('zipCode');
		$this->_('emailAddress');
		$this->_('phoneNumber');
		$this->_('dateCreated', 0, 'datetime');
		$this->_('dateLastUpdated', 0, 'datetime');
		$this->_('isActive', false);
		$this->_('datePaid', 0, 'datetime');
		$this->_('isCanceled', false);
		$this->_('dateCanceled', 0, 'datetime');
		$this->_('confirmationNumber');
		$this->_('paypalEmailAddress');
		$this->_('ipnError');
		$this->_('ipnResponse');
		$this->_('numberOfTickets', 0);
		$this->_('amountPaid', 0.0);
		$this->_('registrationCode');
		$this->_('ticketId');
		$this->_('packageName');
		$this->_('eventName');
		
		parent::__construct('registrations', $id);
	
	}
	
	public function loadByEventId($intEventId) {
	
		return $this->query("
			SELECT		*
			FROM		{$this->tableName}
			WHERE		eventId = {$intEventId}
			ORDER BY	datePaid DESC
		");
	
	}

	public function loadByPackageId($intPackageId) {
	
		return $this->query("
			SELECT		*
			FROM		{$this->tableName}
			WHERE		packageId = {$intPackageId}
			ORDER BY	datePaid DESC
		");
	
	}

	public function loadByRegistrationCode($strRegistrationCode) {
	
		$this->query("
			SELECT		*
			FROM		{$this->tableName}
			WHERE		registrationCode = '{$strRegistrationCode}'
		");
		
		return $this->loadNext();
	
	}
	
	public function loadByConfirmationNumber($strConfirmationNumber) {
	
		$this->query("
			SELECT		*
			FROM		{$this->tableName}
			WHERE		confirmationNumber = '{$strConfirmationNumber}' AND
						datePaid <> '0000-00-00'
		");
		
		return $this->loadNext();
		
	}
	
	public function getRegistrationTitle() { 

		$objEvent = new Event( $this->eventId );
		$objPackage = new Package( $this->packageId );
		
		return $objEvent->getName() . '<small> (' . $objPackage->getName() . ') </small>';
		
	}
	
	public function createRegistrationCode() {
	
		$objEvent = new Event($this->eventId);
		$objPackage = new Package($this->packageId);
		
		do {
		
			$this->registrationCode = $objEvent->getRegistrationCode() . '-' . str_pad(rand(1000, 99999), 5, '0', STR_PAD_LEFT) . $objPackage->getRegistrationCode();
		
			$objRegistration = new Registration();
			$objRegistration->loadByRegistrationCode($this->registrationCode);
		
		} while ($objRegistration->isValid());
	
	}
	
	public function createTicketId() {
	
		$strSeeds = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		
		$this->ticketId = '';
		
		for ($intI = 0; $intI < 16; $intI++) {
			$strIdx = rand(0, strlen($strSeeds) - 1);
			$this->ticketId .= substr($strSeeds, $strIdx, 1);
		}
	
	}
	
	public function getNameDisplay() {
		return $this->firstName . ' ' . $this->lastName;
	}
	
	private function _scrubPhoneNumber($strPhoneNumber) {

		if (!trim($strPhoneNumber))
			return '';
	
		preg_match_all('/\d+/', trim($strPhoneNumber), $arrMatches);

		return implode($arrMatches[0]);
		
	}
	
	public function setPhoneNumber($strHomePhone) {
		$this->homePhone = $this->_scrubPhoneNumber($strHomePhone);
	}
	
	public function getPhoneNumberDisplay() {
		return StringX::formatPhoneNumber($this->homePhone);
	}

	public function getDatePaidDisplay() {
		return date('F jS, Y h:i:sA', $this->datePaid );
	}

	public function formatAsUSDollar( $value ) {
		//return money_format('$ %i', $value ); // non windows only
		return "$" . number_format( $value, 2 );
	}
	
	public function getAmountPaidDisplay() {
		return $this->formatAsUSDollar( $this->amountPaid );
	}
	
	public function getAddressDisplay($boolSingleLine = false) {
	
		$strSeparator = $boolSingleLine ? ', ' : '<br />';
	
		return $this->address1 . $strSeparator . ($this->address2 ? $this->address2 . $strSeparator : '') . $this->city . ', ' . $this->state . ' ' . $this->zipCode;
	
	}
	
	/*--- Registration Count Functions ---*/
	
	public function getTotalPaidRegistrationCount($intEventId) {
	
		$objQuery = new DatabaseQuery();
		
		$objQuery->query("
			SELECT		COUNT(id) AS registrationCount
			FROM		{$this->tableName}
			WHERE		eventId = {$intEventId} AND isActive = 1 AND ipnResponse = 'Completed'
		");
		
		return $objQuery->getField(0, 'registrationCount');
	
	}
	
	public function getTotalAmountPaidRegistrationDollar($intEventId){
		
		$objQuery = new DatabaseQuery();
		
		$objQuery->query("
			SELECT		SUM( amountPaid ) AS registrationTotalPaid
			FROM		{$this->tableName}
			WHERE		eventId = {$intEventId} AND isActive = 1 AND ipnResponse = 'Completed'
		");
		
		return $this->formatAsUSDollar( $objQuery->getField(0, 'registrationTotalPaid') );		
	}
	
	public function getTotalRegistrationCount($intEventId) {
	
		$objQuery = new DatabaseQuery();
		
		$objQuery->query("
			SELECT		COUNT(id) AS registrationCount
			FROM		{$this->tableName}
			WHERE		eventId = {$intEventId}
		");

		return $objQuery->getField(0, 'registrationCount');
	
	}
	
	public function getTotalPaidTicketsCount($intEventId) {
	
		$objQuery = new DatabaseQuery();
		
		$objQuery->query("
			SELECT		SUM(numberOfTickets) AS ticketCount
			FROM		{$this->tableName}
			WHERE		eventId = {$intEventId} AND isActive = 1 AND ipnResponse = 'Completed'
			GROUP BY	eventId
		");
		
		return $objQuery->getField(0, 'ticketCount');
	
	}
	
	public function getTotalTicketsCount($intEventId) {
	
		$objQuery = new DatabaseQuery();
		
		$objQuery->query("
			SELECT		SUM(numberOfTickets) AS ticketCount
			FROM		{$this->tableName}
			WHERE		eventId = {$intEventId}
			GROUP BY	eventId
		");

		return $objQuery->getField(0, 'ticketCount');
	
	}
	
	
	
	/*--- Pricing Functions ---*/
	
	public function getPackagePrice() {
	
		$objPackage = new Package($this->packageId);
		
		return $objPackage->getPrice() * $this->numberOfTickets;
	
	}
	
	
	
	/*--- Confirmation Email ---*/
	
	public function sendConfirmationEmail() {

		$objEvent = new Event($this->eventId);
		$objPackage = new Package($this->packageId);

		$arrTokens = array(
			'packageName'		=> $objPackage->getName(),
			'eventName'			=> $objEvent->getName(),
			'ticketId'			=> $this->ticketId,
			'registrationCode'	=> $this->registrationCode,
			'name'				=> $this->getNameDisplay(),
			'upgrades'			=> '',
			'amountPaid'		=> number_format($this->amountPaid, 2),
			'transactionId'		=> $this->transactionId
		);
		
		$objUpgrade = new RegistrationUpgradeX();
		$objUpgrade->loadByRegistrationId($this->id);
		
		if (count($objUpgrade)) {
		
			$arrTokens['upgrades'] = '<tr><td style="font-weight: bold;">Upgrades:</td><td>';
			
			while ($objUpgrade->loadNext())
				$arrTokens['upgrades'] .= $objUpgrade->getName() . '<br />';
			
			$arrTokens['upgrades'] .= '</td></tr>';
		
		}

		$resHtmlFile = fopen($_SERVER['DOCUMENT_ROOT'] . '/_includes/templates/confirmation_email_noticket.html', 'r');
		$resTextFile = fopen($_SERVER['DOCUMENT_ROOT'] . '/_includes/templates/confirmation_email.txt', 'r');
		
		$strHtml = '';
		$strText = '';
		
		while ($strLine = fgets($resHtmlFile))
			$strHtml .= $strLine;
		
		while ($strLine = fgets($resTextFile))
			$strText .= $strLine;
			
		$strHtml = $this->_replaceEmailTokens($strHtml, $arrTokens);
		$strText = $this->_replaceEmailTokens($strText, $arrTokens);

		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		
		mail( $this->emailAddress, 'Bmorearoundtown.com Confirmation Email', $strHtml , $headers );		
			
		return;
	}

	public function sendDevelopmentConfirmationEmail() {
	
		$objEvent = new Event($this->eventId);
		$objPackage = new Package($this->packageId);
	
		$arrTokens = array(
				'packageName'		=> $objPackage->getName(),
				'eventName'			=> $objEvent->getName(),
				'ticketId'			=> $this->ticketId,
				'registrationCode'	=> $this->registrationCode,
				'name'				=> $this->getNameDisplay(),
				'upgrades'			=> '',
				'amountPaid'		=> "$" . number_format($this->amountPaid, 2),
				'transactionId'		=> $this->transactionId
		);
	
		$objUpgrade = new RegistrationUpgradeX();
		$objUpgrade->loadByRegistrationId($this->id);
	
		if (count($objUpgrade)) {
	
			$arrTokens['upgrades'] = '<tr><td style="font-weight: bold;">Upgrades:</td><td>';
				
			while ($objUpgrade->loadNext())
				$arrTokens['upgrades'] .= $objUpgrade->getName() . '<br />';
				
			$arrTokens['upgrades'] .= '</td></tr>';
	
		}
	
		$resHtmlFile = fopen($_SERVER['DOCUMENT_ROOT'] . '/_includes/templates/confirmation_email_noticket.html', 'r');
		$resTextFile = fopen($_SERVER['DOCUMENT_ROOT'] . '/_includes/templates/confirmation_email.txt', 'r');
	
		$strHtml = '';
		$strText = '';
	
		while ($strLine = fgets($resHtmlFile))
			$strHtml .= $strLine;
	
		while ($strLine = fgets($resTextFile))
			$strText .= $strLine;
			
		$strHtml = $this->_replaceEmailTokens($strHtml, $arrTokens);
		$strText = $this->_replaceEmailTokens($strText, $arrTokens);
	
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
		mail( 'scottymitch88@gmail.com', 'Bmorearoundtown.com Confirmation Email ( DEVELOPMENT )', $strHtml , $headers );
			
		return;
	}
	
	protected function _replaceEmailTokens($strText, $arrTokens) {
	
		foreach ($arrTokens as $strKey => $strValue)
			$strText = str_replace('#{' . $strKey . '}', $strValue, $strText);
			
		return $strText;
	
	}

}