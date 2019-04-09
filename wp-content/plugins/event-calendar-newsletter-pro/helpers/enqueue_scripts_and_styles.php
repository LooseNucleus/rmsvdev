<?php

function ecn_pro_enqueue_scripts( $hook ) {
	if ( 'toplevel_page_eventcalendarnewsletter' == $hook )
		wp_enqueue_script( 'ecn-pro-js', plugins_url( '../js/ecn-pro.js', __FILE__ ), array( 'jquery', 'jquery-ui-datepicker' ), ECN_PRO_VERSION, true );
	if ( 'event-calendar-newsletter_page_saved-ecn-templates' == $hook )
		wp_enqueue_script( 'ecn-saved-templates', plugins_url( '../js/saved-templates.js', __FILE__ ), array( 'jquery' ), ECN_PRO_VERSION, true );
	wp_enqueue_style( 'jquery-ui-core', plugins_url( '../css/jquery-ui/jquery-ui.css', __FILE__ ), ECN_PRO_VERSION );
	wp_enqueue_style( 'jquery-ui-theme', plugins_url( '../css/jquery-ui/jquery-ui.theme.min.css', __FILE__ ), array( 'jquery-ui-core' ), ECN_PRO_VERSION );
	wp_enqueue_style( 'ecn-pro-admin-css', plugins_url( '../css/admin.css', __FILE__ ), array(), ECN_PRO_VERSION );
}
add_action( 'admin_enqueue_scripts', 'ecn_pro_enqueue_scripts' );
