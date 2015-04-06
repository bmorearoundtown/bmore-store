<?
class RegistrationField extends DatabaseObject {

	public function __construct($id = 0) {
	
		$this->_('id', 0);
		$this->_('eventId', 0);
		$this->_('name');
		$this->_('fieldType');
		$this->_('isRequired', false);
		$this->_('dateCreated', 0, 'datetime');
		$this->_('dateLastUpdated', 0, 'datetime');
	
		parent::__construct('registration_fields', $id);
	
	}
	
	public function loadByEventId($intEventId) {
		
		return $this->query("
			SELECT		*
			FROM		{$this->tableName}
			WHERE		eventId = {$intEventId}
			ORDER BY	name
		");
		
	}
	
	public function getFieldHtml($mxdValue = '') {
		
		$strHtml = '';
		
		switch ($this->fieldType) {
		
			case 'text':
				$strHtml = '
					<input type="text" name="fields[' . $this->id . ']" id="field_' . $this->id . '" value="' . str_replace('"', '\"', $mxdValue) . '" size="35" maxlength="255" class="uiText' . ($this->isRequired ? ' required' : '') . '" />';
				break;
				
			case 'select':
				
				$strHtml = '
					<select name="fields[' . $this->id . ']" id="field_' . $this->id . '" class="uiSelect' . ($this->isRequired ? ' required validate-selection' : '') . '">
						<option value=""> - Select an Option - </option>';
					
				$objOption = new RegistrationFieldOption();
				$objOption->loadByFieldId($this->id);
				
				while ($objOption->loadNext())
					$strHtml .= '
						<option value="' . $objOption->getId() . '"' . ($mxdValue == $objOption->getId() ? 'selected="selected"' : '') . '>' . $objOption->getName() . '</option>';
				
				$strHtml .= '
					</select>';
				
				break;
				
			case 'checkbox':
				$strHtml = '
					<input type="checkbox" name="fields[' . $this->id . ']" id="field_' . $this->id . '" value="1" ' . ($this->isRequired ? ' class="required"' : '') . ($mxdValue ? ' checked="checked"' : '') . ' />';
				break;
		
		}
		
		if ($this->isRequired)
			$strHtml .= '
					<div class="error" id="advice-field_' . $this->id . '" style="display: none;">This is a required field</div>';
		
		return $strHtml;
		
	}

}