<?
class SecurityException extends CPException {

	public function __construct($data, $severity = 0, $errorTitle = '', $message = '') {
		parent::__construct('security',
							$severity ? $severity : 3,
							$errorTitle ? $errorTitle : 'You are not authorized to view this page',
							$message ? $message : 'You have tried loading information that you don\'t have access to. Please contact your Connection Power Administrator at your church to request the appropriate access.</p><p>If the problem persists, please contact customer support at (866) 701-3373<em>*</em> or <a href="mailto:support@connectionpower.com">support@connectionpower.com</a>.</p><p><em>*</em> For non-US contact information, please <a href="http://www.connectionpower.com/contact/">visit our Contact Us page</a>.',
							$data);
	}

}