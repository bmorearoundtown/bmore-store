<?
class NoPrimaryIdException extends DataIntegrityException {

	public function __construct($obj) {
		parent::__construct(array('className' => get_class($obj), 'variables' => $obj->getVariableDump()), 2);
	}
	
	public function getDataToString($data = null) {
	
		if (!$data) $data = $this->data;

		return '
			<dl>
				<dt>Class Name:</dt>
				<dd>' . $data['className'] . '</dd>
				<dt>Access Levels:</dt>
				<dd>' . implode(', ', array_keys($data['variables']['vars'])) . '</dd>
			</dl>
			
			<div class="clear"></div>';
		
	}

}