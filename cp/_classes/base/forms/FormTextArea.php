<?
class FormTextArea extends FormElement {

	public function __construct($strLabel, $arrAttributes = array(), $strName = '', $strValue = '') {
		parent::__construct($strLabel, $arrAttributes, $strName, $strValue);
	}
	
	public function drawElementHtml() {
	
		$strHtml = '
					<textarea name="' . $this->_strName . '" id="' . $this->_strName . '" ' . $this->_stringifyAttributes() . '>' . $this->_strValue . '</textarea>';
					
		return $strHtml;
	
	}

}