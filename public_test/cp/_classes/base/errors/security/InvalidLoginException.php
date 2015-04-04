<?
class InvalidLoginException extends SecurityException {

	public function __construct() {
		parent::__construct('', 5);
	}

}