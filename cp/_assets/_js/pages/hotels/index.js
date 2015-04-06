function deleteHotel(e, hotelId) {

	Event.stop(e);
	
	if (confirm('Are you sure you want to remove this hotel?'))
		location.href = 'delete.php?hotel=' + hotelId;

}