<?
class UnableToLoadChurchIdException extends SnafuException {

	public function __construct() {
		parent::__construct(array());
	}
	
	public function getDataToString($data = null) {
	
		return '';
	
	}

}