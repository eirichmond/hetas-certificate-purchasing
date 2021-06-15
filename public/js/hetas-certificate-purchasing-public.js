(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	 $(function() {

		var payByCreditCardPanel = document.getElementById('pay-by-credit-card');
		var payByCreditCardButton = document.querySelector('.pay-by-credit-card');

		if(payByCreditCardButton) {
			payByCreditCardButton.addEventListener('click', function(){
				payByCreditCardPanel.style.display = 'block';
			});
		}

		$('.coc-req').on('keyup',function () {
			var cocRequired = document.querySelectorAll('.coc-req');
			var paymentOptions = document.getElementById('payment-option');

			cocRequired.forEach(element => {

				var fieldValue = element.value;
				if(!fieldValue) {
					paymentOptions.style.display = 'none';
				} else {
					paymentOptions.style.display = 'block';
				}
			});
		});


		var ccpSuccessfulPayment = document.getElementById('ccp-successful-payment');
		if(ccpSuccessfulPayment) {
			var invoicenumber = ccpSuccessfulPayment.attributes.data_invoicenumber.nodeValue;
			var emailaddress = ccpSuccessfulPayment.attributes.data_emailaddress.nodeValue;
			var notificationid = ccpSuccessfulPayment.attributes.data_notificationid.nodeValue;

			async_update_ccp_notification(invoicenumber, emailaddress, notificationid);


		}
	
	 });

})( jQuery );

function async_update_ccp_notification(invoicenumber, emailaddress, notificationid) {
	
	console.log(invoicenumber, emailaddress, notificationid, async_object);

	jQuery.post(
		async_object.ajax_url,
		{
			// wp ajax action
			action : 'async_update_ccp_notification_with_users_emailaddress',
			invoicenumber : invoicenumber,
			emailaddress : emailaddress,
			notificationid : notificationid,
			nextNonce : async_object.nextNonce
		},
		
		function( response ) {
			console.log( response );
		}
	);

}

function send_js_error_logging(reference, data, actions) {
	var actions_obj = JSON.stringify(actions);
	jQuery.post(async_object.ajax_url, {
		// wp ajax action
		action: 'js_error_logging',
		reference: reference,
		data: data,
		actions: actions_obj,
		nextNonce: async_object.nextNonce
	}, function (response) {
		console.log(response);
	});
  
}
