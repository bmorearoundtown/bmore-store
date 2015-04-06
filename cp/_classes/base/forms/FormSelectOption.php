<?
class FormSelectOption extends UserInterface {

	protected $_strLabel		= '';
	protected $_strValue		= '';
	protected $_boolIsSelected	= false;

	public function __construct($strLabel, $mxdValue = '', $boolIsSelected = false) {
		
		$this->_strLabel = $strLabel;
		
		if ($mxdValue === true)
			$this->_boolIsSelected = true;
		else {
			$this->_strValue = $mxdValue;
			$this->_boolIsSelected = $boolIsSelected;
		}
		
		if (!is_null($this->_strValue) && !trim($this->_strValue))
			$this->_strValue = $this->_strLabel;
		
	}
	
	public function buildHtml() {
		$this->strHtml = '<option value="' . (is_null($this->_strValue) ? '' : $this->_strValue) . '"' . ($this->_boolIsSelected ? ' selected' : '') . '>' . $this->_strLabel . '</option>';
	}
	
	
	
	public function getValue() {
		return $this->_strValue;
	}
	
	
	
	public function setIsSelected($boolSelected = true) {
		$this->_boolIsSelected = $boolSelected;
	}

}