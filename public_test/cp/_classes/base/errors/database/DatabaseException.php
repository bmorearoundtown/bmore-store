<?
class DatabaseException extends AppException {

	public function __construct($data, $severity = 0) {
		parent::__construct('database', $severity ? $severity : 2, '', '', $data);
	}

}