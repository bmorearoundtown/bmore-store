<?
abstract class UserInterface {

	protected $strHtml;

	abstract public function buildHtml();
	
	public function draw() {
		return $this->getHtml();
	}
	
	public function getHtml() {
		if (!$this->strHtml) $this->buildHtml();
		return $this->strHtml;
	}

}