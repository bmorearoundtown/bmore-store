<?
class NoChurchIdException extends ChurchIdDoesNotMatchException {

	public function __construct($obj) {
		parent::__construct($obj);
	}

}