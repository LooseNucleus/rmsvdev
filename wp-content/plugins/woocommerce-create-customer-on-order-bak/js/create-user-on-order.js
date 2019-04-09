jQuery( function($){
	$( document ).ready( function() {
		
		// Topggle Settings
		var expiration_row = jQuery('#cxccoo_user_role_heirarchy').closest('tr');
		var expiration_checked = jQuery("#cxccoo_user_role_selection:checked").length;

		var expiration_opt_in = jQuery("#cxccoo_user_role_selection");

		if (expiration_checked == 0) {
			expiration_row.hide();
		}

		jQuery('#cxccoo_user_role_selection').click(function() {
			expiration_row.toggle();
		});
		
		// Move the Save to Address checkboxes theirr correct places
		var heading = jQuery('.order_data_column .load_customer_billing').parents('.order_data_column').find('h4');
		$('.save-billing-address').insertAfter( heading );
		
		var heading = jQuery('.order_data_column .billing-same-as-shipping').parents('.order_data_column').find('h4');
		$('.save-shipping-address').insertAfter( heading );
		
		// Only show 'Save To User' checkboxes when 'Edit Address' is clicked
		$( document ).on( 'click', 'a.edit_address', function() {
			var column_holder = $( this ).parents( '.order_data_column' );
			column_holder.find( '.sac-order-save-actions' ).show();
			//column_holder.find( 'input[type="checkbox"]' ).attr('checked', true);
		});

		// Animate opening of Create Customer UI
		$('button.create_user_form').click(function() {

			$(".toggle-create-user").slideDown(200);
			$(".button.create_user_form").fadeOut(200);

			return false;
		});
		
		// Debug: Auto Open Form.
		//$('button.create_user_form').click();

		// Main 'Create Customer' action
		$(".button.submit_user_form").click(function() {

			var email_address = $.trim($("#create_user_email_address").val());
			var first_name = $.trim($("#create_user_first_name").val());
			var last_name = $.trim($("#create_user_last_name").val());
			var username = $.trim($("#create_user_username").val());
			var user_role = $.trim($("#create_user_role").val());
			var disable_email = ( $("#create_user_disable_email").is(':checked') ) ? 'true' : 'false';

			$(".create_user.form-field").block({
				message: null,
				overlayCSS: {
					background: '#fff url( ' + woocommerce_create_customer_order_params.plugin_url + '/assets/images/select2-spinner.gif ) no-repeat center',
					opacity: 0.6
				}
			});

			$.post(
				ajaxurl,
				{
					action: 		'woocommerce_order_create_user',
					email_address: 	email_address,
					first_name: 	first_name,
					last_name: 		last_name,
					username:       username,
					user_role: 		user_role,
					disable_email:  disable_email,
					security: 		woocommerce_create_customer_order_params.create_customer_nonce
				},
				function( response ) {
					
					// First remove all form errors, to avoid duplicates.
					$('.create-customer-form-error').remove();
					
					// Validation.
					if ( 'email_empty' == response.error_message ) {
						
						$el = $( '<div class="inline error create-customer-form-error"><p><strong>'+ woocommerce_create_customer_order_params.msg_error +'</strong>: '+ woocommerce_create_customer_order_params.msg_email_empty +'.</p></div>' );
						$el.insertBefore( $("#create_user_email_address") );
						$( ".create_user.form-field" ).unblock();
					}
					else if ( 'email_invalid' == response.error_message ) {
						
						$el = $( '<div class="inline error create-customer-form-error"><p><strong>'+ woocommerce_create_customer_order_params.msg_error +'</strong>: '+ woocommerce_create_customer_order_params.msg_email_invalid +'.</p></div>' );
						$el.insertBefore( $("#create_user_email_address") );
						$( ".create_user.form-field" ).unblock();
					}
					else if ( 'email_exists' == response.error_message ) {
						
						$el = $( '<div class="inline error create-customer-form-error"><p><strong>'+ woocommerce_create_customer_order_params.msg_error +'</strong>: '+ woocommerce_create_customer_order_params.msg_email_exists +'.</p></div>' );
						$el.insertBefore( $("#create_user_email_address") );
						$( ".create_user.form-field" ).unblock();
					}
					else if ( 'username_invalid' == response.error_message ) {
						
						$el = $( '<div class="inline error create-customer-form-error"><p><strong>'+ woocommerce_create_customer_order_params.msg_error +'</strong>: '+ woocommerce_create_customer_order_params.msg_username_invalid +'.</p></div>' );
						$el.insertBefore( $("#create_user_username") );
						$( ".create_user.form-field" ).unblock();
					}
					else if ( 'username_exists' == response.error_message ) {
						
						$el = $( '<div class="inline error create-customer-form-error"><p><strong>'+ woocommerce_create_customer_order_params.msg_error +'</strong>: '+ woocommerce_create_customer_order_params.msg_email_exists_username +'.</p></div>' );
						$el.insertBefore( $("#create_user_email_address") );
						$( ".create_user.form-field" ).unblock();
					}
					else if ( 'role_unable' == response.error_message ) {
						
						$el = $( '<div class="inline error create-customer-form-error"><p><strong>'+ woocommerce_create_customer_order_params.msg_error +'</strong>: '+ woocommerce_create_customer_order_params.msg_role +'.</p></div>' );
						$el.insertBefore( $("#create_user_role") );
						$( ".create_user.form-field" ).unblock();
					}
					else {

						// Success...
						var user_id = response.user_id;
						var username = response.username;

						// Select2 (after WC2.3)
						if( 0 !== $('.wc-customer-search.select2-container').length ){
							$(".wc-customer-search").select2({
								data: [{ id: user_id, text: username }]
							});
							$(".wc-customer-search").val( user_id ).trigger("change");

							// Debug
							//console.log('Select2!');
						}

						// Choozen (before WC2.3)
						if( 0 !== $('#customer_user.ajax_chosen_select_customer').length ){
							$('select.ajax_chosen_select_customer').append(
								$('<option></option>')
								.val(user_id)
								.html(username)
								.attr("selected", "selected")
							);
							$('select.ajax_chosen_select_customer').trigger("liszt:updated").trigger("chosen:updated");

							// Debug
							//console.log('Chosen!');
						}

						// Reset our interface.
						$(".create_user.form-field").unblock();
						$("#create_user_email_address").val("");
						$("#create_user_first_name").val("");
						$("#create_user_last_name").val("");

						// Auto check the 'Save to Customer' checkboxes so details are saved.
						$("#save-billing-address-input").attr("checked","checked");
						$("#save-shipping-address-input").attr("checked","checked");

						// Show a success notification, then remove a few seconds later.
						$('<div id="message" class="updated fade"><p><strong>'+ woocommerce_create_customer_order_params.msg_successful +'</strong>: '+ woocommerce_create_customer_order_params.msg_success +'.</p></div>').insertAfter($(".button.create_user_form").parents("p:eq(0)"));
						setTimeout(function(){
							$('.create_user.form-field').find(".updated.fade").fadeOut().remove();
						}, 8000);

						// Close our interface.
						$(".button.submit_user_form_cancel").trigger("click");

						// Show the original button again.
						$(".button.create_user_form").fadeIn(200);
					}
				},
				"json"
			);

			return false;
		});

		// Cancel 'Create Customer' action
		$(".button.submit_user_form_cancel").click(function() {

			$(".toggle-create-user").slideUp();
			$(".button.create_user_form").fadeIn(200);

			return false;
		});
	});

});