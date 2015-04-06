<?
class DataIntegrityException extends AppException {

	public function __construct($data, $severity = 0) {
		parent::__construct('data', $severity ? $severity : 2, '', '', $data);
	}

}