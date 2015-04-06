<?
class DataGrid {

	protected $gridName = '';

	protected $fields = array();
	protected $data = array();
	
	protected $caption = '';
	protected $emptyMessage = '';
	
	protected $minRows = 0;
	protected $alternateRows = true;
	protected $colorRows = true;
	protected $sortable = false;
	protected $clickable = false;

	protected $th = true;

	public function __construct($gridName = '', $fields = array(), $data = array(), $caption = '', $emptyMessage = '') {
	
		$this->gridName = $gridName;
	
		$this->fields = $this->initFields($fields);
		$this->data = $data;
		
		$this->caption = $caption;
		$this->emptyMessage = $emptyMessage;
	
	}
	
	public function draw() {
	
		$html = '
			<table class="table table-bordered table-condensed table-striped" id="' . $this->gridName . 'Grid">';
			
		if ($this->caption) {
			$html .= '
			<caption>
				' . $this->caption . '
			</caption>';
		}

		if (!$this->data || !count($this->data)) {
			$html .= $this->drawTableHead();
			$html .= '
			<tbody>
				<tr class="empty">
					<td colspan="' . count($this->fields) . '">' . $this->emptyMessage . '</td>
				</tr>';
		} else {
		
			$html .= $this->drawTableHead();
			
			$html .= '
				<tbody>';
			
			$colskip = 0;
			$rowCount = 0;
			
			foreach ($this->data as $id => $vals) {

				$rowCount++;
			
				$html .= '
				<tr class="' . ($vals['css'] ? ' ' . $vals['css'] : '') . '"' . ($vals['data-href'] ? ' data-href="' . $vals['data-href'] . '"' : '') . ($vals['hide'] ? ' style="display: none;"' : '') . ' id="row' . $id . '">';
				
				foreach ($this->fields as $key => $val) {
					
					if ($key == 'css') continue;
					
					if ($vals[$key] === false)
						continue;
					
					if ($colskip - 1 > 0) {
						$colskip--;
						continue;
					}
					
					if (is_array($vals[$key])) {
						
						$html .= '
						<td class="' . $key . 'Label' . ($vals[$key]['css'] ? ' ' . $vals[$key]['css'] : '') . '"' . ($vals[$key]['rowspan'] ? ' rowspan="' . $vals[$key]['rowspan'] . '"' : '');
						
						if ($vals[$key]['colspan']) {
							$html .= ' colspan="' . $vals[$key]['colspan'] . '"';
							$colskip = $vals[$key]['colspan'];
						}
						
						$html .= '>' . $vals[$key]['value'] . '</td>';
						
					} else
						$html .= '
						<td class="' . $key . 'Label">' . $vals[$key] . '</td>';
				}
				
				$html .= '
				</tr>';
				
			}

			while (++$rowCount <= $this->minRows) {

				$html .= '
				<tr class="nohover">';

				foreach ($this->fields as $key => $val) {
					$html .= '
					<td class="' . $key . 'Label">&nbsp;</td>';
				}

				$html .= '
				</tr>';
				
			}
			
		}
			
		$html .= '
				</tbody>
			</table>';
			
		return $html;
	
	}
	
	protected function drawTableHead() {

		if (!$this->th) return '';
	
		$html = '
			<thead>
				<tr id="' . $this->gridName . 'Grid_th">';
			
		foreach ($this->fields as $key => $label) {
			$html .= '
					<th class="' . $key . 'Label' . (is_array($label) && $label['type'] ? ' ' . $label['type'] : ' nosort') . (is_array($label) && $label['sort'] ? ' sort' . $label['sort'] : '') . '">' . (is_array($label) ? $label['value'] : $label) . '</th>';
		}
		
		$html .= '
				</tr>
			</thead>';
			
		return $html;
	
	}

	public function initFields($fields) {

		$new_fields = array();

		if (preg_match('/^\d+$/', implode('', array_keys($fields)))) {

			foreach ($fields as $f) {
				$new_fields[$f] = '&nbsp;';
			}

		} else return $fields;

		return $new_fields;
	
	}

	public function th($th = true) {
		$this->th = $th;
	}
	
	public function setCaption($caption) {
		$this->caption = $caption;
	}
	
	public function setEmptyMessage($message) {
		$this->emptyMessage = $message;
	}
	
	public function setMinRows($minRows) {
		$this->minRows = $minRows;
	}
	
	public function sortable($sortable = true) {
		$this->sortable = $sortable;
	}
	
	public function colorRows($colorRows = true) {
		$this->colorRows = $colorRows;
	}
	
	public function clickable($clickable = true) {
		$this->clickable = $clickable;
	}

}
?>