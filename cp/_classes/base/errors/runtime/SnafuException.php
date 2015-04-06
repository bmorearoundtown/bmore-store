<?
class SnafuException extends AppException {

	public function __construct($data = '', $severity = 0, $errorTitle = '', $message = '') {
		parent::__construct('runtime',
							$severity ? $severity : 3,
							$errorTitle,
							$message,
							$data);
	}

}