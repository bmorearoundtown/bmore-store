<?
class AccessLevelException extends PermissionsException {

	public function __construct() {
	
		$password = new CPPassword();
		$password->loadByMemberId($_SESSION['user_member_id']);
	
		$data = array('requiredLevels' => $GLOBALS['accessCheck'],
					  'accessLevels' => $password->getAccessLevels());

		parent::__construct($data);
		
	}
	
	public function getDataToString($data = null) {
	
		if (!$data) $data = $this->data;

		return '
			<dl>
				<dt>Required Levels:</dt>
				<dd>' . implode('<br />', $data['requiredLevels']) . '</dd>
				<dt>Access Levels:</dt>
				<dd>' . implode('<br />', $data['accessLevels']) . '</dd>
			</dl>
			
			<div class="clear"></div>';
		
	}

}