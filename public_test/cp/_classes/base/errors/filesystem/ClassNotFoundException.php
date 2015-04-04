<?
class ClassNotFoundException extends FileSystemException {

	public function __construct($className) {
		parent::__construct(array('className' => $className));
	}
	
	public function getDataToString($data = null) {
	
		if (!$data) $data = $this->data;

		return '
			<dl>
				<dt>Class Name:</dt>
				<dd>' . $data['className'] . '</dd>
			</dl>
			
			<div class="clear"></div>';
		
	}

}