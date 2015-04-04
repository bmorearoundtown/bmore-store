<?
class AccessAreaException extends PermissionsException {

	public function __construct($module = '') {
	
		$password = new CPPassword();
		$password->loadByMemberId($_SESSION['user_member_id']);
	
		$data = array('module' => $module ? 'Unhandled module: ' . $module : $GLOBALS['module'],
					  'accessModules' => $password->getAccessModules());

		parent::__construct($data);
		
	}
	
	public function getDataToString($data = null) {
	
		if (!$data) $data = $this->data;

		return '
			<dl>
				<dt>Required Module:</dt>
				<dd>' . $data['module'] . '</dd>
				<dt>Access Modules:</dt>
				<dd>' . implode(', ', array_keys($data['accessModules'])) . '</dd>
			</dl>
			
			<div class="clear"></div>';
		
	}

}