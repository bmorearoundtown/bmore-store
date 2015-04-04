<?
class FormException extends AppException {

	public function __construct($objForm) {
		parent::__construct('data', 5, '', '', array('form' => $objForm));
	}

}