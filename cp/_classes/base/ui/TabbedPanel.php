<?
class TabbedPanel {

	protected $panelId = '';
	protected $tabs = array();
	
	public function __construct($panelId, $tabs = array()) {
		$this->panelId = $panelId;
		$this->tabs = $tabs;
	}
	
	public function add($tabTitle, $tabContent, $current = false) {
		return $this->addTab($tabTitle, $tabContent, $current);
	}
	
	public function addTab($tabTitle, $tabContent, $current = false) {
	
		$this->tabs[] = array('title' => $tabTitle,
							  'content' => $tabContent,
							  'current' => $current);
	
	}
	
	protected function setDefaultTab() {
	
		for ($i = 0; $i < count($this->tabs); $i++) {
			if ($this->tabs[$i]['current']) return;
		}
		
		$this->tabs[0]['current'] = true;
	
	}
	
	public function draw() {
	
		$this->setDefaultTab();
	
		$html .= '
			<div id="' . $this->panelId . '" class="tabs">
				
				<ul class="tabs">';
				
		foreach ($this->tabs as $tab) {
			
			$id = preg_replace('/[^A-Za-z0-9]/', '', $tab['title']);
			$id[0] = strtolower($id[0]);
			
			$html .= '
					<li class="tab' . ($tab['current'] ? ' current': '') . '" id="' . ((string)$id) . 'Tab">' . $tab['title'] . '</li>';
			
		}
				
		$html .= '
				</ul>
					
				<div class="tabbody"><div>';
				
		foreach ($this->tabs as $tab) {
		
			$id = preg_replace('/[^A-Za-z0-9]/', '', $tab['title']);
			$id[0] = strtolower($id[0]);
		
			$html .= '
					<div id="' . ((string)$id) . 'TabContent" class="tabcontent"' . ($tab['current'] ? '' : ' style="display: none;"') . '>
						' . $tab['content'] . '
						
						<div class="clear"></div>
						
					</div>';
					
		}
		
		$html .= '
		
					<div class="clear"></div>
					
				</div></div>
				
				<div class="clear"></div>
				
			</div>';
			
		return $html;
	
	}

}
?>