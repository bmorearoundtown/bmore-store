<?
	/**
	 *	This file creates custom exception and error handlers, allowing us to display user-friendly error messages and log the problems in the database, 
	 *	as well as an opportunity to get input from the user on what they were doing when the problem occured.
	 *
	 *	@author		Rob Erekson
	 *	@version	1.0
	 */

	/**
	 *	Catches exceptions thrown by PHP engine and handles them according to their settings. An exception can send an SMS message to an admin, send an
	 *	email to an admin, log details of the exception in the database, and display a user-friendly message to the user which allows them to provide
	 *	details on what they were doing when the problem occured.
	 *
	 *	@access		private
	 *	@param		AppException|Exception $e Preferably a AppException object, but will work with Exception and SPL Exceptions also
	 */
	function catch_exceptions($objException) {
die('<pre>' . print_r($objException, true) . '</pre>');
		// Create a local try/catch to prevent unhandled exceptions being thrown from within the exception handler itself
		try {
		
			// If the caught exception is a AppException, use the built-in functions to handle it
			if (is_a($objException, 'AppException'))
				$objException->handleActions();
				
			// Otherwise, handle general 'Exception' throws, as well as built-in SPL Exceptions
			else {
				
				// Set a default severity level, which only displays an error message to the user
				$intSeverity = 5;
				
				switch (get_class($objException)) {
				
					// Set a severity of 2 for the more important exceptions, causing them to be logged in the database, emailed to an admin, and an error displayed for the user
					case 'BadFunctionCallException':
					case 'BadMethodCallException':
					case 'DomainException':
					case 'InvalidArgumentException';
					case 'LogicException':
					case 'RuntimeException':
					case 'UnexpectedValueException':
					case 'ErrorException':
						$intSeverity = 2;
						break;
						
					// Set a severity of 3 for other known exceptions, causing them to be logged in the database and an error message displayed to the user
					case 'Exception':
					case 'LengthException':
					case 'OutOfBoundsException':
					case 'OutOfRangeException':
					case 'OverflowException':
					case 'RangeException':
					case 'UnderflowException':
						$intSeverity = 3;
						break;
						
					// For all unknown errors, log them in the database only
					default:
						$intSeverity = 5;
						break;
						
				}

				// If the severity level requires database logging, do it now (if there is a database connection established)
				if (in_array($intSeverity, array(1, 2, 3, 4)) && $GLOBALS['db'] && $GLOBALS['db']->isConnected()) {
					$objErrorLog = new DebugErrorLog();
					if ($GLOBALS['readOnly']) $objErrorLog->setParentDatabase($GLOBALS['writeDb']);
					$objErrorLog->addErrorToLog($objException, $intSeverity);
					$intErrorLogId = $objErrorLog->getId();
				}
		
				// Display the error message to the user
				$objDisplay = new UIErrorDisplay('There was an error loading this page', 'Don\'t worry, our technical team has been notified of this error and will research and fix the problem as soon as possible.', true, $intErrorLogId, $objException);
				echo $objDisplay->draw();
				
			}

		} catch (Exception $objException) {
		
			// If an exception is thrown from within the exception handler, an error message is displayed. We eventually need to email this or log it to the database
			echo('<h2>' . get_class($objException) . " thrown within the exception handler.</h2>\n\nMessage: " . $objException->getMessage() . ' in <strong>' . $objException->getFile() . '</strong> on line <strong>' . $objException->getLine() . "</strong><br /><br />\n\n" . nl2br($objException->getTraceAsString()) . '<br /><br /><pre>' . print_r($objException, true) . '</pre>');
			exit;
			
		}
	
	}
	
	/**
	 *	Accepts an error triggered by PHP, and throws an ErrorException for the exception handling to take care of it
	 *
	 *	@access		private
	 *	@param		int $errno The error number
	 *	@param		string $errstr The error as provided by the PHP engine
	 *	@param		string $errfile The filepath the error occured in
	 *	@param		string $errline	The line number of the file the error occured in
	 *	@link		http://us2.php.net/manual/en/errorfunc.constants.php PHP's predefined error constants
	 *	@see		catch_exceptions()
	 */
	function catch_errors($intErrorNo, $strError, $strErrorFile, $intErrorLine) {
		 throw new ErrorException($strError, 0, $intErrorNo, $strErrorFile, $intErrorLine);
	}

	// Set up our custom exception and error handlers
	set_exception_handler('catch_exceptions');
	set_error_handler('catch_errors', $GLOBALS['version'] > 1 ? E_ALL & ~E_NOTICE : E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);