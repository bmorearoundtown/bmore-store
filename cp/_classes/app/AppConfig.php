<?
class AppConfig {

	private $_arrParams		= array();
	private $_arrJavascript	= array();
	private $_arrCSS		= array();
	
	public function __construct() {}
	
	public function setParam($strParamName, $mxdParamValue) {
		$this->_arrParams[$strParamName] = $mxdParamValue;
	}
	
	public function getParam($strParamName) {
		return $this->_arrParams[$strParamName];
	}
	
	public function getPageTitle() {
	
		if ($this->getParam('pageTitle'))
			return $this->getParam('pageTitle') . ' :: ' . $this->getParam('appName');
		else
			return $this->getParam('appName');
	
	}
	
	public function isCurrentSection($strSectionName) {
		if ($strSectionName == 'home')
			return $_SERVER['PHP_SELF'] == '/index.php';
		else
			return preg_match('/^\/' . $strSectionName . '\//i', $_SERVER['SCRIPT_NAME']);
	}
	
	public function isCurrentPage($strPath) {
		
		if (preg_match('/index\.php$/i', $_SERVER['SCRIPT_NAME']) && !strstr($strPath, 'index.php'))
			return str_replace('index.php', '', $_SERVER['SCRIPT_NAME']) == $strPath;
		else
			return $_SERVER['SCRIPT_NAME'] == $strPath;
		
	}

	public function addPageJS() {
		$this->_arrJavascript[] = '/_assets/_js/pages' . str_replace('.php', '.js', $_SERVER['PHP_SELF']);
	}

	public function addPageJSFullPath( $jsFullPath ) {
		$this->_arrJavascript[] = $jsFullPath;
	}
	
	public function getAdditionalJS() {
		return $this->_arrJavascript;
	}

	public function addPageCSS() {
		$this->_arrCSS[] = '/cp/_assets/_css/pages' . str_replace('.php', '.css', $_SERVER['PHP_SELF']);
	}

	public function addPageCSSManual( $cssPagePath ) {
		$this->_arrCSS[] = '/cp/_assets/_css/pages/' . $cssPagePath;
	}

	public function addPageCSSFullPath( $cssFullPath ) {
		$this->_arrCSS[] = $cssFullPath;
	}
	
	public function getAdditionalCSS() {
		return $this->_arrCSS;
	}
	
}