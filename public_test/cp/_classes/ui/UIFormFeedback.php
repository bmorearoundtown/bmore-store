<?
class UIFormFeedback extends UserInterface {

	protected $_objForm =			null;
	
	protected $_boolIncludeErrors = false;
	
	public function __construct($objForm, $boolIncludeErrors = true) {
		$this->_objForm				= $objForm;
		$this->_boolIncludeErrors	= $boolIncludeErrors;
	}
	
	public function buildHtml() {

		if (!$this->_objForm->isLoaded())
			return '';
	
		$strHtml = '';

		if ($this->_objForm->isSuccessful() && $this->_objForm->getSuccessMessage()) {

			$strHtml .= '
	<div class="alert alert-success">
		<a class="close" data-dismiss="alert">x</a>
		' . $this->_objForm->getSuccessMessage() . '
	</div>';
	
			$this->_objForm->delete();
			
		} elseif ($this->_boolIncludeErrors && ($this->_objForm->isFailed() || $this->_objForm->hasValidationFailures())) {

			$strHtml .= '
	<div class="alert alert-error">';
	
			if ($this->_objForm->hasValidationFailures()) {
				$strHtml .= '
		<h4 class="alert-heading">There were errors with the information you entered</h4>
			
			<ul>';
		
				foreach ($this->_objForm->getValidationFailures() as $mxdMessage) {
				
					if (is_array($mxdMessage))
						$strHtml .= '
				<li>' . implode('</li><li>', $mxdMessage) . '</li>';
					else
						$strHtml .= '
				<li>' . $strMessage . '</li>';
				
				}
			
				$strHtml .= '
			</ul>';
	
			} else
				$strHtml .= '
		<h4 class="alert-heading">Oops, something went wrong!</h4>
		' . ($this->_objForm->getErrorMessage() ? $this->_objForm->getErrorMessage() : 'There was an error completing the action. Please try again.');
		
			$strHtml .= '
	</div>';
	
		}
	
		$this->strHtml = $strHtml;
	
	}

}