<?
class PageNotFound404Exception extends FileNotFoundException {

	public function __construct() {
		parent::__construct(array('url' => $_SERVER['REQUEST_URI'], 'referrer' => $_SERVER['HTTP_REFERER']),
								  2,
								  'Page Not Found');
	}
	
	public function getDataToString($data = null) {
	
		if (!$data) $data = $this->data;

		return '
			<dl>
				<dt>Requested URL:</dt>
				<dd>' . $data['url'] . '</dd>
				<dt>Referring URL:</dt>
				<dd>' . ($data['referrer'] ? $data['referrer'] : '<span class="inactive">None</span>') . '</dd>
			</dl>
			
			<div class="clear"></div>';
		
	}

}