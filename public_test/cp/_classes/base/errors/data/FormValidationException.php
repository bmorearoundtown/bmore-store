<?
class FormValidationException extends FormException {

	public function __construct($arrData) {
		parent::__construct($arrData);
	}

}