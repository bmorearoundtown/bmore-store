<?
class ChurchIdDoesNotMatchException extends SecurityException {

	public function __construct($obj) {
		parent::__construct(array('className' => get_class($obj), 'variables' => $obj->getVariableDump()),
							3);
	}
	
	public function getDataToString($data = null) {
	
		if (!$data) $data = $this->data;

		$html = '
			<dl>
				<dt>Class Name:</dt>
				<dd>' . $data['className'] . '</dd>
				<dt>Variables:</dt>
				<dd>
					<dl class="superwide">';
					
		foreach ($data['variables']['vars'] as $key => $val)
			$html .= '
						<dt>' . $key . ':</dt>
						<dd>' . ($val['value'] !== '' ? $val['value'] . ' (' . $val['dbValue'] . ')' : '&nbsp;') . '</dd>';
						
		$html .= '
					</dl>
				</dd>
			</dl>
			
			<div class="clear"></div>';
			
		return $html;
		
	}

}