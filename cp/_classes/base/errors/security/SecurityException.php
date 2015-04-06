<?
class SecurityException extends AppException {

	public function __construct($data, $severity = 0, $errorTitle = '', $message = '') {
		parent::__construct('security',
							$severity ? $severity : 3,
							$errorTitle ? $errorTitle : 'You are not authorized to view this page',
							$message ? $message : 'You have tried loading information that you don\'t have access to. Please contact your administrator to request the appropriate access.</p><p>If the problem persists, please contact customer support at <a href="mailto:info@bpos.projects.roberekson.com">info@bpos.projects.roberekson.com</a>.</p>.',
							$data);
	}

}