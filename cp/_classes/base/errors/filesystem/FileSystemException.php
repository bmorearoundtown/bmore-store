<?
class FileSystemException extends AppException {

	public function __construct($data, $actions = 0) {
		parent::__construct('filesystem',
							$severity ? $severity : 2,
							'',
							'',
							$data);
	}

}