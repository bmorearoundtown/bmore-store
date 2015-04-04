<?
class FileSystemException extends CPException {

	public function __construct($data, $actions = 0) {
		parent::__construct('filesystem',
							$severity ? $severity : 2,
							'',
							'',
							$data);
	}

}