<?
class InvalidRecordException extends SnafuException {

	public function __construct($strType, $intId) {
		parent::__construct(
			array(
				'type'	=> $strType,
				'id'	=> $intId
			),
			3,
			ucfirst($strType) . ' does not exist',
			'You\'ve tried accessing a ' . $strType . ' that doesn\'t exist in the system. Please return to the previous page and try again.'
		);
	}

}