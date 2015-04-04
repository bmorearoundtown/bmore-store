<?
class FormInput extends FormElement {

	public function __construct($strLabel, $arrAttributes = array(), $strName = '', $strValue = '') {
		parent::__construct($strLabel, $arrAttributes, $strName, $strValue);
	}
	
	public function drawElementHtml() {
	
		$strHtml = '
					<input name="' . $this->_strName . '" id="' . $this->_strName . '" value="' . $this->_strValue . '" ' . $this->_stringifyAttributes() . ' />';
					
		return $strHtml;
	
	}

}