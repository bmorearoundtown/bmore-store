<?
class FormHiddenInput extends FormInput {

	public function __construct($strName, $strValue) {
	
		$arrAttributes = array(
			'type' => 'hidden'
		);
		
		parent::__construct('', $arrAttributes, $strName, $strValue);
		
	}

}