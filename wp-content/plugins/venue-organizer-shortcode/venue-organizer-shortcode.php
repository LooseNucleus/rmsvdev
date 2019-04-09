<?php
/**
 * Plugin name: The Events Calendar: Venue/Organizer Shortcodes
 * Description: Adds shortcodes to help list venues and organizers
 * Author:      Modern Tribe, Inc
 * Author URI:  http://theeventscalendar.com
 * Version:     1.0
 * License:     GPL v3 - see http://www.gnu.org/licenses/gpl.html
 *
 *     The Events Calendar: Venue/Organizer Shortcodes
 *     Copyright (C) 2015 Modern Tribe, Inc
 *
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


class TEC_VenueOrganizer_List {
	protected $atts = array();
	protected $query;
	protected $output = '';


	public function __construct( $atts ) {
		$this->atts = shortcode_atts( array(
			'post_type' => self::get_type( 'VENUE_POST_TYPE' ),
			'limit'     => -1
		), $atts );
	}

	public function __toString() {
		$this->query();
		$this->format();
		return $this->output;
	}

	protected function query() {
		$args = array(
			'post_type'      => $this->atts['post_type'],
			'posts_per_page' => $this->atts['limit']
		);

		$this->query = new WP_Query( apply_filters( __CLASS__ . '.args', $args, $this->atts ) );
	}

	protected function format() {
		$opening_tag  = '<ul class="tec list ' . $this->atts['post_type'] . '">';
		$this->output = apply_filters( __CLASS__ . '.list.open', $opening_tag, $this->atts );

		while ( $this->query->have_posts() ) {
			$this->query->the_post();
			$link = '<a href="' . get_the_permalink() . '">' . get_the_title() . '</a>';
			$item = '<li class="tec list ' . $this->atts['post_type'] . '">' . $link . '</li>';
			$this->output .= apply_filters( __CLASS__ . '.list.item', $item, $this->atts );
		}

		$this->output .= apply_filters( __CLASS__ . '.list.close', '</ul>', $this->atts );
		wp_reset_postdata();
	}

	public static function get_type( $type_const ) {
		if ( class_exists( 'Tribe__Events__Events' ) ) $class = 'Tribe__Events__Events';
		elseif ( class_exists( 'TribeEvents' ) )       $class = 'TribeEvents';
		else return false;

		$class = new ReflectionClass( $class );
		return $class->getConstant( $type_const );
	}
}


function tec_do_venue_shortcode( $atts ) {
	$type = TEC_VenueOrganizer_List::get_type( 'VENUE_POST_TYPE' );
	if ( ! $type  ) return '';

	$atts['post_type'] = $type;
	return new TEC_VenueOrganizer_List( $atts );
}

function tec_do_organizer_shortcode( $atts ) {
	$type = TEC_VenueOrganizer_List::get_type( 'ORGANIZER_POST_TYPE' );
	if ( ! $type  ) return '';

	$atts['post_type'] = $type;
	return new TEC_VenueOrganizer_List( $atts );
}


add_shortcode( 'list_venues', 'tec_do_venue_shortcode' );
add_shortcode( 'list_organizers', 'tec_do_organizer_shortcode' );