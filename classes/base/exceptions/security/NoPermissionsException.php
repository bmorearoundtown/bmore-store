<?
class NoPermissionsException extends PermissionsException {

	public function __construct() {
	
		$data = array('memberId' => $_SESSION['user_member_id']);

		parent::__construct($data);
		
	}
	
	public function getDataToString($data = null) {
	
		if (!$data) $data = $this->data;

		return '
			<dl>
				<dt>Member ID:</dt>
				<dd>' . ($data['memberId'] ? $data['memberId'] : '0') . '</dd>
			</dl>
			
			<div class="clear"></div>';
		
	}

}