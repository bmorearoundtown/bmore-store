Event.observe(document, 'dom:loaded', function() {

	$('startDate').observe('blur', function() {

		if (!$F('endDate'))
			$('endDate').setValue($F('startDate'));
	
	});

});