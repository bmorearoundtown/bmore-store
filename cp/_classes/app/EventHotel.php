<?
class EventHotel extends DatabaseObject {

	public function __construct($id = 0) {
	
		$this->_('id', 0);
		$this->_('eventId', 0);
		$this->_('hotelId', 0);
	
		parent::__construct('event_hotels', $id);
	
	}

}