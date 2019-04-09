<?php

class ECNProSettings {
	function __construct() {
		add_action( 'ecn_additional_settings_page_rows_before', array( &$this, 'settings_page' ) );
		add_filter( 'ecn_pro_group_by_day_format', array( &$this, 'group_by_day_format' ) );
		add_filter( 'ecn_pro_group_by_month_format', array( &$this, 'group_by_month_format' ) );
		add_action( 'ecn_settings_save', array( &$this, 'settings_save' ) );
	}

	function settings_page( $data ) {
		include( dirname( __FILE__ ) . '/../templates/admin/settings.php' );
	}
	
	function settings_save( $data ) {
		if ( isset( $data['group_by_day_format'] ) )
			save_ecn_option( 'group_by_day_format', wp_kses_post( trim( $data['group_by_day_format'] ) ) );

		if ( isset( $data['group_by_month_format'] ) )
			save_ecn_option( 'group_by_month_format', wp_kses_post( trim( $data['group_by_month_format'] ) ) );

		if ( isset( $data['group_by_tag_start'] ) )
			save_ecn_option( 'group_by_tag_start', wp_kses_post( trim( $data['group_by_tag_start'] ) ) );

		if ( isset( $data['group_by_tag_end'] ) )
			save_ecn_option( 'group_by_tag_end', wp_kses_post( trim( $data['group_by_tag_end'] ) ) );

		if ( isset( $data['feed_event_publication_date'] ) )
			save_ecn_option( 'feed_event_publication_date', sanitize_text_field( $data['feed_event_publication_date'] ) );
	}

	function group_by_day_format( $format ) {
		if ( trim( get_ecn_option( 'group_by_day_format', '' ) ) )
			$format = get_ecn_option( 'group_by_day_format', '' );
		return $format;
	}

	function group_by_month_format( $format ) {
		if ( trim( get_ecn_option( 'group_by_month_format', '' ) ) )
			$format = get_ecn_option( 'group_by_month_format', '' );
		return $format;
	}
}

$GLOBALS['ecn_pro_settings'] = new ECNProSettings();