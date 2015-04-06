<?
class UnableToLoadFamilyLastNameException extends SnafuException {

	public function __construct($familyId) {
		parent::__construct(array('familyId' => $familyId));
	}
	
	public function getDataToString($data = null) {
	
		if (!$data) $data = $this->data;

		return '
			<dl>
				<dt>Family ID:</dt>
				<dd>' . $data['familyId'] . '</dd>
			</dl>
			
			<div class="clear"></div>';
	
	}

}