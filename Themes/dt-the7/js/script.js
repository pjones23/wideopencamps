jQuery(document).ready(function($) {

	// Listen for a click event on our text input submit button
	$( '#searchBtn' ).click( function( e ) {

		// Stop the button from submitting and refreshing the page.
		e.preventDefault();
		
		// Now that a click has happened, let's run Ajax!!!!!!!!!
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: balancesAjax.ajaxurl,
			data:{
				'action' : 'getBalances',
				'data' : $( '#email' ).val(),
				'submission' : 'true',
				//'nonce' : $( '#wcsf-nonce' ).val(),
			},
			error: function(jqXHR, textStatus, errorThrown) {
                //alert('An error occurred... Look at the console (F12 or Ctrl+Shift+I, Console tab) for more information!');
                console.log('jqXHR:');
                console.log(jqXHR);
                console.log('textStatus:');
                console.log(textStatus);
                console.log('errorThrown:');
                console.log(errorThrown);
            },
			complete: function( object ) {
				//$( '.entry-content' ).text( object.responseJSON.body );
				console.log(object.responseJSON.body)
				console.log("complete function");
				console.log($('#email').val());
			}
		});
	});
});