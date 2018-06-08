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
				'submission' : $( '.search-submitted' ).val(),
				'nonce' : $( '#search_email' ).val(),
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
				//console.log(object);
				//console.log(object.responseJSON);
				createTable(object.responseJSON);
			}
		});
	});

	// Listen for a click event on our text input submit button
	$( '.payBtn' ).click( function( e ) {
		console.log("clicked pay button");
		// Stop the button from submitting and refreshing the page.
		e.preventDefault();
	});

	$(document).on('click', '.payBtn', function(e) {

			// Stop the button from submitting and refreshing the page.
			e.preventDefault();

			console.log("clicked pay button");
	    // Do stuff with the formid
			console.log(e);
			var buttonID = $(e.target).attr('id');
			console.log(buttonID);
			var buttonIdSplit = buttonID.split("_");
			var id = buttonIdSplit[1];
			console.log(id);
			var balanceInfoForm = document.getElementById("balanceInfo_" + id);
			console.log(balanceInfoForm);
			balanceInfoForm.submit();

	    return false;
	});

// function to create results table
function createTable(balances){
	console.log(balances);
	var balanceHiddenForms = [];
	var table = document.createElement('table');
	var tableRow = document.createElement('tr');
	var campersTableHeading = document.createElement('th');
	var campsTableHeading = document.createElement('th');
	var registrationDateHeading = document.createElement('th');
	var balanceHeading = document.createElement('th');
	var payButtonHeading = document.createElement('th');

	var campersText = document.createTextNode('Camper(s)');
	var campsText = document.createTextNode('Camp(s)');
	var registrationDateText = document.createTextNode('Registration Date');
	var balanceText = document.createTextNode('Balance');

	campersTableHeading.appendChild(campersText);
	campsTableHeading.appendChild(campsText);
	registrationDateHeading.appendChild(registrationDateText);
	balanceHeading.appendChild(balanceText);
	tableRow.appendChild(campersTableHeading);
	tableRow.appendChild(campsTableHeading);
	tableRow.appendChild(registrationDateHeading);
	tableRow.appendChild(balanceHeading);
	tableRow.appendChild(payButtonHeading);
	table.appendChild(tableRow);

	for(var i in balances){
		var balance = balances[i];
		console.log(balance);
		// add table row
		tableRow = document.createElement('tr');
		var campersTableData = document.createElement('td');
		var campsTableData = document.createElement('td');
		var registrationDateData = document.createElement('td');
		var balanceData = document.createElement('td');
		var payButtonData = document.createElement('td');
		var name = "";
		var camp = "";
		var campers = balance.campers;
		for(var j in campers){
			var camper = campers[j];
			if(j != 0){
				campersTableData.appendChild(document.createElement('hr'));
				campsTableData.appendChild(document.createElement('hr'));
			}
			name = camper.firstName + " " + camper.lastName;
			camp = camper.campOne + " " + camper.campOneType + "\n" + camper.campTwo + " " + camper.campTwoType;
			campersTableData.appendChild(document.createTextNode(name));
			campsTableData.appendChild(document.createTextNode(camp));
		}
		var registrationDate = new Date(balance.date);
		var registrationDateText = document.createTextNode(registrationDate.toLocaleString());
		var balanceDataText = document.createTextNode(balance.remainingBalance.toFixed(2));
		//<form id="pay_balance_btn_form" class="form" action="#">
		// <input id="payBtn" type="submit" value="Pay Now" />
		// </form>
		var payBalanceButtonForm = document.createElement('form');
		payBalanceButtonForm.className = "form";
		payBalanceButtonForm.id = "pay_balance_btn_form";
		var payBalanceButtonFormInput = document.createElement('input');
		payBalanceButtonFormInput.id = "payBtn" + "_" + i;
		payBalanceButtonFormInput.className = "payBtn";
		payBalanceButtonFormInput.type = "submit";
		payBalanceButtonFormInput.value = "Pay Now";

		payBalanceButtonForm.appendChild(payBalanceButtonFormInput);
		registrationDateData.appendChild(registrationDateText);
		balanceData.appendChild(balanceDataText);
		payButtonData.appendChild(payBalanceButtonForm);
		tableRow.appendChild(campersTableData);
		tableRow.appendChild(campsTableData);
		tableRow.appendChild(registrationDateData);
		tableRow.appendChild(balanceData);
		tableRow.appendChild(payButtonData);
		table.appendChild(tableRow);
		// TODO add necessary hidden data
		//action="/action_page.php" method="post"
		var balanceInfoForm = document.createElement('form');
		balanceInfoForm.className = "form";
		balanceInfoForm.id = "balanceInfo" + "_" + i;
		balanceInfoForm.method = "post";
		balanceInfoForm.action = "/pay-my-balance"
		//balanceInfoForm.action = "/pay-my-balance/?preview_id=1477&preview_nonce=1bf602509e&_thumbnail_id=-1&preview=true"
		// email hidden Field
		var emailFormInput = document.createElement('input');
		emailFormInput.id = "emailFormInput" + "_" + i;
		emailFormInput.name = "email";
		emailFormInput.type = "hidden";
		emailFormInput.value = balance.email;
		balanceInfoForm.appendChild(emailFormInput);
		// first name hidden field (use first camper)
		var firstNameFormInput = document.createElement('input');
		firstNameFormInput.id = "firstNameFormInput" + "_" + i;
		firstNameFormInput.name = "firstName";
		firstNameFormInput.type = "hidden";
		firstNameFormInput.value = balance.email;
		balanceInfoForm.appendChild(firstNameFormInput);
		// last name hidden field (use first camper)
		var lastNameFormInput = document.createElement('input');
		lastNameFormInput.id = "lastNameFormInput" + "_" + i;
		lastNameFormInput.name = "lastName";
		lastNameFormInput.type = "hidden";
		lastNameFormInput.value = balance.email;
		balanceInfoForm.appendChild(lastNameFormInput);
		// selected camps hidden field
		var midJulyCampFormInput = document.createElement('input');
		midJulyCampFormInput.id = "midJulyCampFormInput" + "_" + i;
		midJulyCampFormInput.name = "midJulyCamp";
		midJulyCampFormInput.type = "hidden";
		midJulyCampFormInput.value = balance.email;
		balanceInfoForm.appendChild(midJulyCampFormInput);
		var lateJulyCampFormInput = document.createElement('input');
		lateJulyCampFormInput.id = "lateJulyCampFormInput" + "_" + i;
		lateJulyCampFormInput.name = "lateJulyCamp";
		lateJulyCampFormInput.type = "hidden";
		lateJulyCampFormInput.value = balance.email;
		balanceInfoForm.appendChild(lateJulyCampFormInput);
		var middleSchoolCampFormInput = document.createElement('input');
		middleSchoolCampFormInput.id = "middleSchoolCampFormInput" + "_" + i;
		middleSchoolCampFormInput.name = "middleSchoolCamp";
		middleSchoolCampFormInput.type = "hidden";
		middleSchoolCampFormInput.value = balance.email;
		balanceInfoForm.appendChild(middleSchoolCampFormInput);
		// amount hidden field
		var remainingBalanceFormInput = document.createElement('input');
		remainingBalanceFormInput.id = "remainingBalanceFormInput" + "_" + i;
		remainingBalanceFormInput.name = "remainingBalance";
		remainingBalanceFormInput.type = "hidden";
		remainingBalanceFormInput.value = balance.remainingBalance.toFixed(2);
		balanceInfoForm.appendChild(remainingBalanceFormInput);
		balanceHiddenForms.push(balanceInfoForm);
	}
	var searchResultsDiv = document.getElementById("searchResults");
	searchResultsDiv.innerHTML = "";
	searchResultsDiv.appendChild(table);
	for(var k in balanceHiddenForms){
		searchResultsDiv.appendChild(balanceHiddenForms[k]);
	}

}

});
