<?
class Form {

	protected $_strFormName = '';
	
	protected $_objData = null;
	
	public function __construct($mxdFormName, $objData = null) {
	
		$this->_objData = $objData;
	
		if (is_array($mxdFormName)) {
		
		} else {
	
			$this->_strFormName = $mxdFormName;
			$this->_load();
			
		}
		
	}
	
	protected function _load() {

		$GLOBALS['objFormSession'] = new FormSession($this->_strFormName);

		if ($this->_objData && !$GLOBALS['objFormSession']->hasData()) 
			$GLOBALS['objFormSession']->setValues($this->_objData->getDataArray());
			
	}
	
	public function drawMessages($boolIncludeErrors = true) {
			
		$objUiForm = new UIFormFeedback($GLOBALS['objFormSession'], $boolIncludeErrors);
		
		return $objUiForm->draw();
	
	}
	
	
	
	public function drawElement(UserInterface $objElement, $strHelp = '') {
	
		if (is_a($objElement, FormTextInput) && !$objElement->getValue() && $GLOBALS['objFormSession']->hasData())
			$objElement->setValue($GLOBALS['objFormSession']->getValue($objElement->getName()));
			
		if (trim($strHelp))
			$objElement->addHelpBlock($strHelp);
			
		return $objElement->draw();
	}

}