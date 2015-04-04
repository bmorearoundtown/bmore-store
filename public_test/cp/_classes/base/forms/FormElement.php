<?
abstract class FormElement extends UserInterface {

	protected $_strLabel		= '';
	protected $_arrAttributes	= array();
	protected $_strName			= '';
	protected $_strValue		= '';
	
	protected $_arrHelpBlocks	= array();

	public function __construct($strLabel, $arrAttributes = array(), $strName = '', $strValue = '') {

		$this->_strLabel		= $strLabel;
		$this->_arrAttributes	= $arrAttributes;
		$this->_strName			= $strName;
		$this->_strValue		= $strValue;
			
		if (!$GLOBALS['objFormSession'])
			throw new SnafuException('No FormSession object detected.');

		if (!trim($this->_strName))
			$this->_strName = StringX::toCamelCase($strLabel);
			
		if (!$this->_strValue && $GLOBALS['objFormSession']->getValue($this->_strName) && !is_null($GLOBALS['objFormSession']->getValue($this->_strName)))
			$this->_strValue = $GLOBALS['objFormSession']->getValue($this->_strName);

	}
	
	public function buildHtml() {
	
		$strHtml = '';
		
		if (!is_a($this, 'FormHiddenInput'))
			$strHtml .= '
			<div class="control-group' . ($GLOBALS['objFormSession']->isError($this->_strName) ? ' error' : '') . '">
				<label class="control-label">' . $this->_strLabel . '</label>
				<div class="controls">';
				
		$strHtml .= $this->drawElementHtml();
		
		if (count($this->_arrHelpBlocks))
			foreach ($this->_arrHelpBlocks as $strHelpBlock)
				$strHtml .= '
					<span class="help-block">' . $strHelpBlock . '</span>';
				
		if (!is_a($this, 'FormHiddenInput'))
			$strHtml .= '
				</div>
			</div>';
			
		$this->strHtml = $strHtml;
	
	}
	
	
	
	public function addHelpBlock($strHelpBlock) {
		if (trim($strHelpBlock))
			$this->_arrHelpBlocks[] = trim($strHelpBlock);
	}
	
	
	
	protected function _stringifyAttributes() {
	
		$arrAttributesHtml = array();
		
		foreach ($this->_arrAttributes as $strAttributeName => $strAttributeValue)
			$arrAttributesHtml[] = $strAttributeName . (trim($strAttributeValue) ? '="' . $strAttributeValue . '"' : '');
	
		return implode(' ', $arrAttributesHtml);
	
	}
	
	
	
	abstract public function drawElementHtml();
	
	
	
	public function getValue() {
		return $this->_strValue;
	}
	
	public function setValue($strValue) {
		$this->_strValue = $strValue;
	}
	
	public function getName() {
		return $this->_strName;
	}
	
	
	
	

}