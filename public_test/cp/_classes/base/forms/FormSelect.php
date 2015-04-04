<?
class FormSelect extends FormElement {

	protected $_arrOptions = array();

	public function __construct($strLabel, $arrOptions = array(), $arrAttributes = array(), $strName = '', $strValue = '') {
		
		$this->_arrOptions = $arrOptions;
	
		parent::__construct($strLabel, $arrAttributes, $strName, $strValue);
		
	}
	
	public function drawElementHtml() {
	
		$strHtml = '
					<select name="' . $this->_strName . '" id="' . $this->_strName . '" ' . $this->_stringifyAttributes() . '>';	
		
		foreach ($this->_arrOptions as $mxdOption) {

			if (is_string($mxdOption))
				$objOption = new FormSelectOption($mxdOption);
			elseif (is_object($mxdOption) && is_a($mxdOption, 'FormSelectOption'))
				$objOption = $mxdOption;
			else
				throw new InvalidArgumentException('Unexpected value. Expected string or FormSelectOption element');

			if ($this->_strValue == $objOption->getValue())
				$objOption->setIsSelected();

			$strHtml .= '
				' . $objOption->draw();
		
		}
		
		
					
		$strHtml .= '
					</select>';
					
		return $strHtml;
	
	}

}