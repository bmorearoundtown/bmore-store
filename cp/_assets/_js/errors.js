$(document).ready(function() {

	if ($('#error-notice')) {

		$('#feedback-form').validate({
		
			errorClass:		'alert-error',
			
			highlight:		function(element) {
				$(element).addClass('error');
			},
			
			rules:			{
				userErrorNotes:	'required'
			},
			
			messages:		{
				userErrorNotes:	'Please enter some notes on the error'
			},
		
			submitHandler: function(objForm) {
			
//				$('#feedback-button').button('loading');
		
				$.post('/_ajax/ajax.error_feedback.php', $(objForm).serialize());
		
			}
		
		});



		$('#feedback-form').ajaxSuccess(function(event, xhr, settings) {

			$('#feedback-wrapper').slideUp(function() {
				$('#feedback-success').slideDown();
			});

		});
	
		$('#feedback-form').ajaxError(function(event, xhr, settings, error) {
		
			var json = jQuery.parseJSON(xhr.getResponseHeader('X-JSON'));
	
			$('#note').before('<div class="alert alert-error" id="add-feedback-failed" style="display: none;"><strong>Unable to Save Feedback!</strong> There was an error saving your feedback. Please try again.</div>');
			
			$('#add-feedback-failed').slideDown();
		
			$('#feedback-button').button('reset');
		
		});
		
		
	}
	
});