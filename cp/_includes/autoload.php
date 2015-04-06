<?

	function __autoload($strClassName) {
	
		switch ($strClassName){
	
			case 'Services_JSON':
				require_once(_CLASS_PATH_ . '/base/ajax/JSON.php');
				break;
				
			case 'Csv':
				require_once(_CLASS_PATH_ . '/base/data/' . $strClassName . '.php');
				break;

			case 'DatabaseLink':
			case 'DatabaseQuery':
				require_once(_CLASS_PATH_ . '/base/database/' . $strClassName . '.php');
				break;
				
			case 'ArrayX':
			case 'NumberX':
			case 'StringX':
				require_once(_CLASS_PATH_ . '/base/datatypes/' . $strClassName . '.php');
				break;
				
			case 'Date':
				require_once(_CLASS_PATH_ . '/base/dates/' . $strClassName . '.php');
				break;
				
			case 'htmlMimeMail5':
				require_once(_CLASS_PATH_ . '/base/email/' . $strClassName . '.php');
				break;
				
			case 'Encryption':
				require_once(_CLASS_PATH_ . '/base/encryption/' . $strClassName . '.php');
				break;
			
			case 'Form':
			case 'FormElement':
			case 'FormElementCollection':
			case 'FormHiddenInput':
			case 'FormInput':
			case 'FormSelect':
			case 'FormSelectOption':
			case 'FormSession':
			case 'FormTextArea':
			case 'FormTextInput':
			case 'FormValidation':
				require_once(_CLASS_PATH_ . '/base/forms/' . $strClassName . '.php');
				break;
				
			case 'Upload':
			case 'File':
			case 'FileDirectory':
				require_once(_CLASS_PATH_ . '/base/files/' . $strClassName . '.php');
				break;
				
			case 'Barcode':
			case 'Image':
				require_once(_CLASS_PATH_ . '/base/images/' . $strClassName . '.php');
				break;
				
			case 'Griddable':
				require_once(_CLASS_PATH_ . '/base/interfaces/' . $strClassName . '.php');
				break;
				
			case 'Math':
			case 'Number':
				require_once(_CLASS_PATH_ . '/base/math/' . $strClassName . '.php');
				break;
				
			case 'DatabaseObject':
				require_once(_CLASS_PATH_ . '/base/oop/' . $strClassName . '.php');
				break;
				
			case 'ButtonTabs':
			case 'DataGrid':
			case 'TabbedPanel':
			case 'UserInterface':
			case 'Calendar':
			case 'CalendarGroup':
			case 'Select': 
			case 'ColumnListing':
				require_once(_CLASS_PATH_ . '/base/ui/' . $strClassName . '.php');
				break;
				
			case 'AppException':
			case 'Error':
			case 'ErrorLog':
				require_once(_CLASS_PATH_ . '/base/errors/' . $strClassName . '.php');
				break;
			
			default:
				
				if (preg_match('/^UI/', $strClassName)) {
				
					if (file_exists(_CLASS_PATH_ . '/ui/fields/' . $strClassName . '.php'))
						require_once(_CLASS_PATH_ . '/ui/fields/' . $strClassName . '.php');
					else
						require_once(_CLASS_PATH_ . '/ui/' . $strClassName . '.php');
						
				} elseif (preg_match('/Exception$/', $strClassName)) {

					if (file_exists(_CLASS_PATH_ . '/base/errors/' . $strClassName . '.php'))
						require_once(_CLASS_PATH_ . '/base/errors/' . $strClassName . '.php');
					elseif (file_exists(_CLASS_PATH_ . '/base/errors/runtime/' . $strClassName . '.php'))
						require_once(_CLASS_PATH_ . '/base/errors/runtime/' . $strClassName . '.php');
					elseif (file_exists(_CLASS_PATH_ . '/base/errors/data/' . $strClassName . '.php'))
						require_once(_CLASS_PATH_ . '/base/errors/data/' . $strClassName . '.php');
					elseif (file_exists(_CLASS_PATH_ . '/base/errors/security/' . $strClassName . '.php'))
						require_once(_CLASS_PATH_ . '/base/errors/security/' . $strClassName . '.php');
					elseif (file_exists(_CLASS_PATH_ . '/base/errors/database/' . $strClassName . '.php'))
						require_once(_CLASS_PATH_ . '/base/errors/database/' . $strClassName . '.php');
					elseif (file_exists(_CLASS_PATH_ . '/base/errors/filesystem/' . $strClassName . '.php'))
						require_once(_CLASS_PATH_ . '/base/errors/filesystem/' . $strClassName . '.php');
						
				} elseif (file_exists(_CLASS_PATH_ . '/email/' . $strClassName . '.php'))
					require_once(_CLASS_PATH_ . '/email/' . $strClassName . '.php');
				elseif (file_exists(_CLASS_PATH_ . '/app/' . $strClassName . '.php'))
					require_once(_CLASS_PATH_ . '/app/' . $strClassName . '.php');
				else {
					require_once(_CLASS_PATH_ . '/base/errors/filesystem/ClassNotFoundException.php');
					throw new ClassNotFoundException($strClassName);
				}

				break;
				
		}
	
	}
