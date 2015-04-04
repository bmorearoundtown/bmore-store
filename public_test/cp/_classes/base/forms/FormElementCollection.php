<?
class FormElementCollection extends UserInterface {

	protected $_strLabel	= '';
	protected $_arrElements	= array();
	
	protected $_arrHelpBlocks	= array();

	public function __construct($strLabel, $arrElements) {
		$this->_strLabel		= $strLabel;
		$this->_arrElements		= $arrElements;
	}
	
	public function buildHtml() {
	
		$strHtml = '';
		
		$strHtml .= '
			<div class="control-group' . ($this->_isError() ? ' error' : '') . '">
				<label class="control-label">' . $this->_strLabel . '</label>
				<div class="controls">';
				
		foreach ($this->_arrElements as $mxdElement) {
		
			if (is_string($mxdElement))
				$strHtml .= '
					' . $mxdElement;
			else
				$strHtml .= '
					' . $mxdElement->drawElementHtml();
					
		}
		
		if (count($this->_arrHelpBlocks))
			foreach ($this->_arrHelpBlocks as $strHelpBlock)
				$strHtml .= '
					<span class="help-block">' . $strHelpBlock . '</span>';
				
		$strHtml .= '
				</div>
			</div>';
			
		$this->strHtml = $strHtml;
	
	}
	
	protected function _isError() {
	
		foreach ($this->_arrElements as $objElement)
			if (is_a($objElement, 'FormElement') && $GLOBALS['objFormSession']->isError($objElement->getName()))
				return true;
	
	}
	
	
	
	public function addHelpBlock($strHelpBlock) {
		if (trim($strHelpBlock))
			$this->_arrHelpBlocks[] = trim($strHelpBlock);
	}

}