<?
class DatabaseQueryException extends DatabaseException {

	public function __construct($obj, $severity = 0) {
	
		$data = array('query' => '', 'error' => '');
		
		if (is_string($obj)) {
			$data['query'] = $obj;
			$data['error'] = mysql_error();
		} else {
			$data['query'] = $obj->getQuery();
			$data['error'] = $obj->getErrorMessage();
		}
	
		parent::__construct($data, $severity ? $severity : 2);
		
	}
	
	public function getDataToString($data = null) {
		
		if (!$data) $data = $this->data;
		
		return '
			<dl>
				<dt>Error:</dt>
				<dd>' . $data['error'] . '</dd>
				<dt>Query:</dt>
				<dd>' . $data['query'] . '</dd>
			</dl>
			
			<div class="clear"></div>';
		
	}

}