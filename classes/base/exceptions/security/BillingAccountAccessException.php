<?
class BillingAccountAccessException extends PermissionsException {

	public function __construct() {
		parent::__construct('',
							'',
							'<p>You are trying to access this area using your church\'s billing username and password.</p>
							 <p>To offer greater flexibility with our applications, and to access this page, you can <a href="/products/powerMember/setup/password_member.php">click here to create a username and password</a>.</p>');
	}

}