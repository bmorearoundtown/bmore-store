<?
class FormTextInput extends FormInput {

	public function __construct($strLabel, $arrAttributes = array(), $strName = '', $strValue = '') {
	
		if (!$arrAttributes['type'])
			$arrAttributes['type'] = 'text';
			
		parent::__construct($strLabel, $arrAttributes, $strName, $strValue);
		
	}
	
	public function drawElementHtml() {
	
		$strHtml = '';
			
		
		
		// Check for prepend and append
		$strPrepend = '';
		$strAppend = '';
		
		if ($this->_arrAttributes['prepend']) {
			$strPrepend = $this->_arrAttributes['prepend'];
			unset($this->_arrAttributes['prepend']);
		}
		
		if ($this->_arrAttributes['append']) {
			$strAppend = $this->_arrAttributes['append'];
			unset($this->_arrAttributes['append']);
		}
			
			
			
				
		if ($strPrepend || $strAppend)
			$strHtml .= '
					<div class="' . ($strPrepend ? 'input-prepend' : '') . ($strAppend ? ' input-append' : '') . '">
						' . ($strPrepend ? '<span class="add-on">' . $strPrepend . '</span>' : '');
		
		$strHtml .= parent::drawElementHtml();
					
		if ($strPrepend || $strAppend)
			$strHtml .=	'
						' . ($strAppend ? '<span class="add-on">' . $strAppend . '</span>' : '') . '
					</div>';
					
					
			
		return $strHtml;
	
	}

}