<?
	// prod include set_include_path(get_include_path() . PATH_SEPARATOR . '/home/users/web/b257/ipw.bmorearoundtown/public_html/cp/_includes/');

	$GLOBALS['devEmail'] = 'scottymitch88@gmail.com';
	$GLOBALS['env'] = 'development';

	set_include_path($_SERVER['DOCUMENT_ROOT'] . '/cp/_includes/');

	define('_CLASS_PATH_', $_SERVER['DOCUMENT_ROOT'] . '/cp/_classes');
	
	require_once('output_buffering.php');
	
	require_once('autoload.php');
	
	session_start();
	
	$GLOBALS['db'] = new ApplicationDatabaseLink();

	/*--- Set up exception handling ---*/
	require_once('error_handling.php');
		
	/*--- Set config options ---*/
	
	$GLOBALS['config'] = new AppConfig();
	
	$GLOBALS['config']->setParam('appName', 'BMORE Around Town | Administration Panel');
	
	function debug($mxdVar) {
		die('<pre>' . print_r($mxdVar, true) . '</pre>');
	}