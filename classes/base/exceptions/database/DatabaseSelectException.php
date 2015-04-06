<?
class DatabaseSelectException extends DatabaseQueryException {

	public function __construct($obj, $severity = 0) {
		parent::__construct($obj, $severity ? $severity : 2);
	}

}