<?
class PermissionsException extends SecurityException {

	public function __construct($data, $errorTitle = '', $message = '') {
		parent::__construct($data,
							5,
							$errorTitle,
							$message);
	}

}