jQuery(document).ready(function($) {

	// Listen for a click event on our text input submit button
	$( '#searchBtn' ).click( function( e ) {

		// Stop the button from submitting and refreshing the page.
		e.preventDefault();
		
		// Now that a click has happened, let's run Ajax!!!!!!!!!
		// /*
		$.ajax({
			type: 'POST',
			contentType: "application/json; charset=utf-8",
			dataType: 'json',
			url: wcsf_ajax.ajaxurl,
			data: {
				'action' : 'wcsf_ajax',
				'data' : $( '#email' ).val(),
				'submission' : 'true',
				//'nonce' : $( '#wcsf-nonce' ).val(),
			},
			error: function(jqXHR, textStatus, errorThrown) {
                alert('An error occurred... Look at the console (F12 or Ctrl+Shift+I, Console tab) for more information!');
                console.log('jqXHR:');
                console.log(jqXHR);
                console.log('textStatus:');
                console.log(textStatus);
                console.log('errorThrown:');
                console.log(errorThrown);
            },
			complete: function( object ) {
				//$( '.entry-content' ).text( object.responseJSON.body );
				console.log("complete function");
				console.log($('#email').val());
			}
		});
		// */
		
		// Now that a click has happened, let's run Ajax!!!!!!!!!
		/*
		$.ajax({
			type: 'GET',
			dataType: 'json',
			contentType: "application/json",
			headers: {"Authorization": "Basic MFFBSy1WUzM2LUk2Qk8tQUVNWTpwYXNzd29yZA=="},
			url: "https://wideopencamps.wufoo.com/api/v3/forms/k16c3f9c0jwz7dm/entries.json?system=true",
			data: {
				//'system' : 'true'
				//'action' : 'wcsf_ajax',
				//'data' : $( '.wcsf-text-field' ).val(),
				//'submission' : $( '.wcsf-submitted' ).val(),
				//'nonce' : $( '#wcsf-nonce' ).val(),
			},
			complete: function( object ) {
				$( '.entry-content' ).text( object.responseJSON.body );
				console.log("complete function");
				console.log("here now bruh");
				console.log($( '.wcsf-text-field' ).val());
			}
		});
		*/
	});
});