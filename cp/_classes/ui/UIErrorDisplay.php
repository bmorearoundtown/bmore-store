<?
class UIErrorDisplay extends UserInterface {

	protected $_strErrorTitle = '';
	protected $_strMessage = '';
	protected $_boolIsError = false;
	protected $_intErrorLogId = 0;
	protected $_objException = null;

	public function __construct($strErrorTitle, $strMessage, $boolIsError, $intErrorLogId, $objException) {
		$this->_strErrorTitle = $strErrorTitle;
		$this->_strMessage = $strMessage;
		$this->_boolIsError = $boolIsError;
		$this->_intErrorLogId = $intErrorLogId;
		$this->_objException = $objException;
	}

	public function buildHtml() {
	
		$GLOBALS['error'] = true;
		
		$strHtml = '';

		if (0) {// && $GLOBALS['developmentMode'] || ini_get('display_errors')) {

			$strHtml .= '
				<link rel="stylesheet" type="text/css" href="/_css/screen.css" />
				<link rel="stylesheet" type="text/css" href="/_css/errors.css" />
			
				<div id="debug">
		
					<img src="/wide_images/closebox_black.png" alt="" class="closebox" id="debugClosebox" />
				
					<h2>There was an error on this page!</h2>
					
					<dl class="list">
						<dt>Type:</dt>
						<dd>' . (is_a($this->_objException, 'CPException') ? $this->_objException->getExceptionType() : 'General') . '</dd>
						<dt>Exception:</dt>
						<dd>' . get_class($this->_objException) . '</dd>
						<dt>Filename:</dt>
						<dd>' . $this->_objException->getFile() . ' [line ' . $this->_objException->getLine() . ']</dd>
					</dl>
					
					<div class="clear"></div>
					
					<dl class="flat">
						<dt><h2>Exception Data</h2></dt>
						<dd class="box">';
						
			if (is_a($this->_objException, 'AppException'))
				$strHtml .= '	
							' . $this->_objException->getDataToString();
			else
				$strHtml .= '
							<dl class="list">
								<dt>Error Message:</dt>
								<dd>' . $this->_objException->getMessage() . '</dd>
								<dt>Error Code:</dt>
								<dd>' . $this->_objException->getCode() . '</dd>
							</dl>
							
							<div class="clear"></div>';
							
			$strHtml .= '	
						</dd>
					</dl>
					
					<dl class="flat">
						<dt><h2>Backtrace:</h2></dt>
						<dd class="box">
							
							<ol>
								<li>
									' . preg_replace('/#\d+\s+/', '', str_replace("\n", '</li><li>', $this->_objException->getTraceAsString())) . '
								</li>
							</ol>
							
						</dd>
					</dl>';
					
			if (count($_GET)) {
				
				$strHtml .= '
					<dl class="flat">
						<dt><h2>GET Data</h2></dt>
						<dd class="box">
						
							<dl class="list">';
							
							
				foreach ($_GET as $strKey => $strVal)
					$strHtml .= '
								<dt>' . $strKey . ':</dt>
								<dd>' . $strVal . '</dd>';
								
				$strHtml .= '
							</dl>
							
							<div class="clear"></div>
						
						</dd>
					</dl>';
					
			}
			
			if (count($_POST)) {
				
				$strHtml .= '
					<dl class="flat">
						<dt><h2>POST Data</h2></dt>
						<dd class="box">
						
							<dl class="list">';
							
							
				foreach ($_POST as $strKey => $strVal)
					$strHtml .= '
								<dt>' . $strKey . ':</dt>
								<dd>' . $strVal . '</dd>';
								
				$strHtml .= '
							</dl>
							
							<div class="clear"></div>
						
						</dd>
					</dl>';
			
			}
			
			$strHtml .= '
				</div>';
			
		} else {
		
			ob_end_clean();

			ob_start();
			require($_SERVER['DOCUMENT_ROOT'] . '/_includes/header.php');
			$strHeaderHtml = ob_get_contents();
			ob_end_clean();

			$strHtml .= $strHeaderHtml;
	
			$strHtml .= '
				<div class="row" id="error-notice"' . ($this->_boolIsError ? '' : ' class="warning"') . '>
						
					<div class="span4">
						<i class="icon-exclamation-sign"></i>
					</div>
					
					<div class="span8">
					
						<h2>' . $this->_strErrorTitle . '</h2>
						
						<p>' . $this->_strMessage . '</p>';
		
			if ($this->_boolIsError && $this->_intErrorLogId)
				$strHtml .= '
						
						<div class="well">
						
							<div id="feedback-wrapper">
							
								<form action="/_ajax/ajax.error_feedback.php" method="post" id="feedback-form">
								
									<input type="hidden" name="errorLogId" value="' . $this->_intErrorLogId . '" />
								
									<label>To help us fix this error faster, please provide details of what you were doing when this error occurred:</label>
									<textarea name="userErrorNotes" id="userErrorNotes" rows="5" class="required"></textarea><br />
									
									<button type="submit" class="btn btn-primary" id="feedback-button"><i class="icon-ok"></i> Send Feedback</button>
									
								</form>
								
							</div>
							
							<div id="feedback-success" style="display: none;">
								<h3><strong>Thank you for submitting your feedback.</strong></h3>
								<p><small>To return to ' . $GLOBALS['config']->getParam('appName') . ', <a href="javascript:location.reload();">reload this page</a> or <a href="javascript:history.back();">return to the previous page</a>.</small></p>
							</div>
						
						</div>';
			else
				$strHtml .= '
						<p><small>To return to ' . $GLOBALS['config']->getParam('appName') . ', <a href="javascript:location.reload();">reload this page</a> or <a href="javascript:history.back();">return to the previous page</a>.</small></p>';
						
			$strHtml .= '
					</div>
						
				</div>';

			ob_start();
			require($_SERVER['DOCUMENT_ROOT'] . '/_includes/footer.php');
			$strFooterHtml = ob_get_contents();
			ob_end_clean();
	
			$strHtml .= preg_replace('/<\/div>\s+<\/body>/', '</div></div></body>', $strFooterHtml);
			
		}

		$this->strHtml = $strHtml;

	}
	
}