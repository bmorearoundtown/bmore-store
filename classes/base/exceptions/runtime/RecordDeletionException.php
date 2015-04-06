<?
class RecordDeletionException extends SnafuException {

	public function __construct($query) {
		parent::__construct(array('query' => $obj->getQuery(),
								  'objectName' => get_class($obj)));
	}
	
	public function getDataToString($data = null) {
	
		if (!$data) $data = $this->data;

		return '
			<dl>
				<dt>Object:</dt>
				<dd>' . $data['objectName'] . '</dd>
				<dt>Query:</dt>
				<dd>' . $data['query'] . '</dd>
			</dl>
			
			<div class="clear"></div>';
		
	}

}