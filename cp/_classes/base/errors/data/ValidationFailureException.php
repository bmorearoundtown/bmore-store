<?
class ValidationFailureException extends FormValidationException {

	public function __construct($objForm) {
		parent::__construct($objForm);
	}

}