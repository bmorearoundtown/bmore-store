function deleteLocation(e, locationId) {

	Event.stop(e);

	if (confirm('Are you sure you want to delete this location?'))
		location.href = 'delete.php?location=' + locationId;

}