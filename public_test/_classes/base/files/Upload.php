<?
class Upload {

	protected $arrFile = '';
	
	protected $strFilepath = '';
	protected $strFilename = '';
	
	protected $boolValid = false;

	function __construct($arrFile = array()) {
	
		$this->arrFile = $arrFile;
		
		$this->setFullFilepath(dirname($arrFile['tmp_name']), basename($arrFile['tmp_name']));
		
		return $this->isValidUpload();
	
	}
	
	function isValidUpload() {
		$this->boolValid = is_uploaded_file($this->getFullFilepath());
		return $this->boolValid;
	}
	
	function isValid() {
		return $this->boolValid;
	}
	
	function moveTo($strFilepath) {
		
		if (!move_uploaded_file($this->getFullFilepath(), $strFilepath)) {
			return false;
		}
	
		$this->setFullFilepath($strFilepath);
		
		return true;
	
	}
	
	function setFullFilepath($strFilepath, $strFilename = '') {
	
		if ($strFilename) {
			$this->strFilepath = $strFilepath;
			$this->strFilename = $strFilename;
		} else {
			$this->strFilepath = dirname($strFilepath);
			$this->strFilename = basename($strFilepath);
		}
	
	}
	
	function getFullFilepath() {
		return $this->strFilepath . '/' . $this->strFilename;
	}
	
}