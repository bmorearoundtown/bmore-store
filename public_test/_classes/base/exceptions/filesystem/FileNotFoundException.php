<?
class FileNotFoundException extends FileSystemException {

	public function __construct($filename, $severity = 0) {
		parent::__construct(is_array($filename) ? $filename : array('filename' => $filename),
							$severity ? $severity : 3);
	}
	
	public function getDataToString($data = null) {
	
		if (!$data) $data = $this->data;

		return '
			<dl>
				<dt>Filename:</dt>
				<dd>' . $data['filename'] . '</dd>
			</dl>
			
			<div class="clear"></div>';
		
	}

}