$(document).ready(function() {

	$('*[rel=tooltip]').tooltip();



	if ($('#addNote')) {
	
		$('#add-note-form').validate({
		
			errorClass:		'alert-error',
			
			highlight:		function(element) {
				$(element).addClass('error');
			},
			
			rules:			{
				notes:	'required'
			},
			
			messages:		{
				notes:	'Enter some notes before saving'
			},
		
			submitHandler: function(objForm) {
			
				$('#add-note-button').button('loading');
		
				$.post('/_ajax/ajax.notes.php', $(objForm).serialize());
		
			}
		
		});



		$('#add-note-form').ajaxSuccess(function(event, xhr, settings) {
			
			var json = jQuery.parseJSON(xhr.getResponseHeader('X-JSON'));

			if ($('ul#notes')) {
			
				if ($('ul#notes > li.empty')) {
					$('ul#notes > li.empty').slideUp(function() {
						$(this).remove();
					});
				}
			
				$('ul#notes').prepend('<li style="display: none;"><a href="/users/view.php?user=' + json.createdByUserId + '"><strong>' + json.creatorName + '</strong></a> <em>&mdash; ' + json.createdDate + '</em><br />' + json.note + '</li>');
				
				$('ul#notes li:first-of-type').slideDown(function() {
					$(this).effect('highlight', 5000);
				});
				
				$('#addNote').modal('hide');
				
				setTimeout("$('#add-note-button').button('reset');$('#note').val('');", 1000);
			
			}
			
		});
	
		$('#add-note-form').ajaxError(function(event, xhr, settings, error) {
		
			var json = jQuery.parseJSON(xhr.getResponseHeader('X-JSON'));
	
			$('#note').before('<div class="alert alert-error" id="add-note-failed" style="display: none;"><strong>Unable to Save Note!</strong> There was an error saving the note. Please try again.</div>');
			
			$('#add-note-failed').slideDown();
		
			$('#add-note-button').button('reset');
		
		});
	
	}

});