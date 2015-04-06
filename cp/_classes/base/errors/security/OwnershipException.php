<?
class OwnershipException extends SecurityException {

	public function __construct($obj) {
		parent::__construct(array(
				'className'		=> get_class($obj),
				'variables'		=> $obj->getVariableDump()
			),
			3
		);
	}
	
	public function getDataToString($arrData = null) {
	
		if (!$arrData)
			$arrData = $this->_arrData;
			
		die('DO AS STRING');
	
	}

}