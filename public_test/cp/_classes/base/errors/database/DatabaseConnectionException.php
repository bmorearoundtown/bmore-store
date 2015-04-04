<?
class DatabaseConnectionException extends DatabaseException {

	public function __construct($obj) {
		parent::__construct(serialize($obj), 1);
	}

}