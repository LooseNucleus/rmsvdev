<?php
class RSSTest extends WP_UnitTestCase {

	private $feed;
	private $time;

	/**
	 * Test the feed with TEC
	 */
	function setUp() {
		update_option( 'timezone_string', 'America/New_York' );
		date_default_timezone_set( get_option( 'timezone_string' ) );

		require_once( dirname( __FILE__ ) . '/../../the-events-calendar/the-events-calendar.php' );
		Tribe__Events__Main::activate();
		do_action( 'init' );
		update_option( 'active_plugins', array( 'event-calendar-newsletter/event-calendar-newsletter.php', 'the-events-calendar/the-events-calendar.php' ) );

		// Load up The Events Calendar feed
		$this->feed = ECNCalendarFeedFactory::create( 'the-events-calendar' );

		// Load up some events for us to test with
		$this->createSampleEvents();
	}

	function createSampleEvents() {
		$this->time = time();
		$venue_id = tribe_create_venue( array(
			'post_status' => 'publish',
			'Venue' => 'The Pheasant Plucker',
			'Country' => 'CA',
			'Address' => '20 Augusta St',
			'City' => 'Hamilton',
			'Province' => 'Ontario',
			'Zip' => 'L8N 1P7',
			'Phone' => '(905) 529-9000',
		) );
		$organizer_id = tribe_create_organizer( array(
			'post_status' => 'publish',
			'Organizer' => 'Brian Hogg',
			'Email' => 'brian@brianhogg.com',
			'Website' => 'https://brianhogg.com',
			'Phone' => '905-555-2343',
		) );

		tribe_create_event( array(
			'post_status' => 'publish',
			'post_title' => 'Evening Event',
			'post_content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris hendrerit est eu est pellentesque posuere. Donec in nisi commodo, commodo velit a, convallis dui. Pellentesque ut est leo. Phasellus nec lobortis eros. Sed lacus mi, viverra in dolor quis, pharetra sollicitudin felis. Fusce neque massa, porttitor quis velit in, rutrum hendrerit massa. Mauris id diam a sem sollicitudin aliquet. Phasellus lobortis augue pulvinar efficitur sollicitudin. Duis imperdiet a urna a efficitur. Sed at augue ante. Vivamus ut lectus eros. Donec eget quam magna. Sed facilisis, lectus sit amet lacinia fringilla, leo felis congue purus, eget tristique massa risus at ipsum.

Vivamus accumsan lobortis nisl ac laoreet. Donec sem purus, tincidunt vel turpis quis, mattis semper nulla. Sed quis porta est. Aliquam ac tortor at felis elementum convallis. Nulla accumsan felis non dui tempus fermentum. Donec rhoncus, sem eu accumsan semper, ante tellus venenatis ipsum, nec porttitor justo ex non tellus. Vestibulum bibendum, urna mattis mattis lacinia, dui velit tristique erat, sollicitudin dictum risus mauris et dui. Nulla non elit sed tellus elementum dignissim. Nullam sollicitudin magna sed sapien finibus pharetra.

Nunc ut neque mi. Etiam consequat sollicitudin egestas. Nam elementum mollis nulla vitae faucibus. Maecenas eget sapien convallis, viverra felis ac, tristique metus. Nam venenatis erat nisi, vitae venenatis dui suscipit ut. Quisque eleifend ac ex nec elementum. Pellentesque diam augue, commodo ut nunc dapibus, commodo tristique ex. Suspendisse ullamcorper quam sit amet imperdiet ultrices. Aenean cursus metus ac ante auctor, sit amet tristique arcu laoreet. Morbi tempor enim magna, in feugiat sem consectetur vel. Vestibulum leo orci, interdum non maximus in, sagittis consectetur nibh. Sed cursus ex in ante sollicitudin, ac suscipit libero interdum. Curabitur nunc sem, porta a dui in, suscipit pellentesque turpis. Nunc maximus lacus id elit tempor, at gravida metus imperdiet. Sed auctor, mauris sit amet egestas laoreet, quam neque semper justo, nec congue nulla orci eget nunc. Fusce non diam sit amet velit posuere rhoncus quis a elit.',
			'EventStartDate' => date( 'Y-m-d', $this->time + ( 86400 * 2 ) ),
			'EventEndDate' => date( 'Y-m-d', $this->time + ( 86400 * 2 ) ),
			'EventAllDay' => false,
			'EventStartHour' => '06',
			'EventStartMinute' => '30',
			'EventStartMeridian' => 'pm',
			'EventEndHour' => '06',
			'EventEndMinute' => '30',
			'EventEndMeridian' => 'pm',
			'EventShowMapLink' => true,
			'EventShowMap' => true,
			'EventCost' => '50',
			'Venue' => array( 'VenueID' => $venue_id ),
			'Organizer' => array( 'OrganizerID' => $organizer_id ),
		) );

		tribe_create_event( array(
			'post_status' => 'publish',
			'post_title' => 'All day in 4 days',
			'post_content' => 'Nullam nec ex consequat, volutpat justo vel, ullamcorper eros. Aliquam aliquet purus metus, in convallis libero placerat eu. Maecenas molestie blandit libero nec lacinia. Aliquam ac dui eget elit auctor luctus. Proin eget dui eleifend, fringilla metus quis, vestibulum ligula. Phasellus eget lorem ut orci pharetra aliquam. Fusce malesuada dolor ac urna pulvinar lobortis. Curabitur ac leo facilisis, imperdiet purus a, luctus enim. Curabitur iaculis dapibus nunc, in sodales diam gravida sed. Proin et orci maximus, mattis magna quis, hendrerit elit. Nunc rhoncus leo nisi, scelerisque volutpat enim ornare at.',
			'EventStartDate' => date( 'Y-m-d', $this->time + ( 86400 * 4 ) ),
			'EventEndDate' => date( 'Y-m-d', $this->time + ( 86400 * 4 ) ),
			'EventAllDay' => true,
			'EventShowMapLink' => true,
			'EventShowMap' => true,
			'EventCost' => '0',
			'Venue' => array( 'VenueID' => $venue_id ),
			'Organizer' => array( 'OrganizerID' => $organizer_id ),
		) );

		// should not be included, hide from upcoming
		tribe_create_event( array(
			'post_status' => 'publish',
			'post_title' => 'Event to ignore',
			'post_content' => 'Sed egestas libero eu neque sagittis laoreet. Quisque sed tortor ac orci posuere dignissim rutrum sed purus. Curabitur in nisl volutpat, commodo erat vel, ultricies odio. Donec euismod nisi et tortor pretium, a porta tellus sodales. Etiam facilisis, metus vitae ultrices malesuada, lorem turpis ultrices elit, sit amet accumsan ex mi nec mi. Nunc ac elit fermentum ipsum ultricies luctus. Nam non mollis erat. Aliquam egestas sapien sapien, nec suscipit ante lacinia eu. Fusce mollis eu risus a commodo. Nunc pretium id dolor sed volutpat. Suspendisse nec est bibendum, gravida ligula ut, sagittis magna. Nunc quis mauris diam. Aliquam at nulla nec diam pellentesque viverra vel quis purus. Nunc et eros nunc. Suspendisse potenti.',
			'EventStartDate' => date( 'Y-m-d', $this->time + ( 86400 * 5 ) ),
			'EventEndDate' => date( 'Y-m-d', $this->time + ( 86400 * 5 ) ),
			'EventAllDay' => true,
			'EventHideFromUpcoming' => 'yes',
			'EventShowMapLink' => true,
			'EventShowMap' => true,
			'EventCost' => '0',
			'Venue' => array( 'VenueID' => $venue_id ),
			'Organizer' => array( 'OrganizerID' => $organizer_id ),
		) );
	}

	function tearDown() {
		parent::tearDown();
		Tribe__Events__Main::deactivate( false );
	}

	function testFeedIsAvailableToECN() {
		$this->assertEquals( 'ECNCalendarFeedTheEventsCalendar', get_class( $this->feed ) );
	}

	/**
	 * Test that we get our two events only (3rd one should be ignored)
	 */
	function testOneMonthSavedTemplate() {
		global $ecn_saved_templates;
		$saved_template_id = $ecn_saved_templates->create_new_saved_template( array(
			'title' => 'Testing RSS Template',
			'data' => http_build_query( array(
				'event_calendar' => 'the-events-calendar',
				'events_future_in_days' => '30', // 1 Month selected in dropdown
				'custom_date_from' => date( 'Y-m-d', $this->time ),
				'custom_date_to' => date( 'Y-m-d', $this->time + ( 86400 * 4 ) ),
				// TODO: Test the category filtering
//				'tax_input' => array(
//					'tribe_events_cat' => array( 0 => '12' )
//				),
				'headertext' => '<p>Test header</p>',
				'footertext' => '<p>Test footer</p>',
				'group_events' => 'day',
				'format' => '<h3>{title}</h3>
<p>{start_date}{if_end_time} to {end_date}{/if_end_time} @ {location_name}</p>
<p>{description}</p>
<p><a href="{link_url}">More Information</a></p>
<p>&nbsp;</p>'
			) )
		) );

		// Ensure the template loads
		global $post;
		$post = get_post( $saved_template_id );
		$template = $ecn_saved_templates->override_template_with_rss( '' );
		$this->assertTrue( strpos($template, 'ecs-rss.php' ) !== 0 );

		// Basic test of the RSS output
		ob_start();
		include $template;
		$output = ob_get_clean();
		$xml = simplexml_load_string( $output );
		$this->assertEquals( 'SimpleXMLElement', get_class( $xml ), 'Ensure RSS output is valid RSS' );
		$this->assertEquals( 'Evening Event', $xml->channel->item[0]->title );
		$this->assertEquals( 'All day in 4 days', $xml->channel->item[1]->title );
		// Make sure the header appears after the <![CDATA[ block in the description and only once
		$this->assertTrue( strpos( $output, '<p>Test header</p>' ) !== false, 'Should have the header in the output' );
		$this->assertTrue( strpos( $output, '<p>Test header</p>', strpos( $output, '<p>Test header</p>' ) + 1 ) !== false, 'Should have header in the content:encoded section too' );
		$this->assertEquals( false, strpos( $output, '<p>Test header</p>', strpos( $output, '<p>Test header</p>', strpos( $output, '<p>Test header</p>' ) + 1 ) + 1 ), 'Should not have header again' );
		$this->assertEquals( 2, count( $xml->channel->item ), 'Should ignore the hidden last event' );

	}

	function testCategoryFilter() {
		$this->markTestIncomplete();
	}

	/**
	 * Test custom date range option and that the RSS contains the correct entries
	 */
	function testCustomDateRangeRSS() {
		global $ecn_saved_templates;
		$saved_template_id = $ecn_saved_templates->create_new_saved_template( array(
			'title' => 'Testing RSS Template',
			'data' => http_build_query( array(
				'event_calendar' => 'the-events-calendar',
				'events_future_in_days' => ECN_CUSTOM_DATE_RANGE_DAYS, // Custom date range selected in dropdown
				'custom_date_from' => date( 'Y-m-d', $this->time ),
				'custom_date_to' => date( 'Y-m-d', $this->time + ( 86400 * 2 ) ),
				'headertext' => '<p>Test header</p>',
				'footertext' => '<p>Test footer</p>',
				'group_events' => 'day',
				'format' => '<h3>{title}</h3>
<p>{start_date}{if_end_time} to {end_date}{/if_end_time} @ {location_name}</p>
<p>{description}</p>
<p><a href="{link_url}">More Information</a></p>
<p>&nbsp;</p>'
			) )
		) );

		global $post;
		$post = get_post( $saved_template_id );
		$template = $ecn_saved_templates->override_template_with_rss( '' );

		// Basic test of the RSS output
		ob_start();
		include $template;
		$output = ob_get_clean();
		$xml = simplexml_load_string( $output );
		$this->assertEquals( 'SimpleXMLElement', get_class( $xml ), 'Ensure RSS output is valid RSS' );
		$this->assertEquals( 'Evening Event', $xml->channel->item[0]->title, 'Event in feed should be the earliest event' );
		$this->assertEquals( mysql2date( 'D, d M Y H:i:s +0000', date( 'Y-m-d' ) . ' 00:00:00', false ), $xml->channel->item[0]->pubDate, 'Event pub date should be the default "Today" value' );
		$this->assertEquals( 1, count( $xml->channel->item ), 'Custom date range should only have 1 event' );
	}

	/**
	 * Test the feed publication date setting, which determines whether RSS readers see the content as new or not
	 */
	function testFeedPublicationDateStartDateOverride() {
		global $ecn_saved_templates;
		$saved_template_id = $ecn_saved_templates->create_new_saved_template( array(
			'title' => 'Testing RSS Template',
			'data' => http_build_query( array(
				'event_calendar' => 'the-events-calendar',
				'events_future_in_days' => '30', // Custom date range selected in dropdown
				'headertext' => '<p>Test header</p>',
				'footertext' => '<p>Test footer</p>',
				'group_events' => 'day',
				'format' => '<h3>{title}</h3>
<p>{start_date}{if_end_time} to {end_date}{/if_end_time} @ {location_name}</p>
<p>{description}</p>
<p><a href="{link_url}">More Information</a></p>
<p>&nbsp;</p>',
				
				// Override the feed publication date with start_date
				'feed_event_publication_date' => 'start_date'
			) )
		) );

		global $post;
		$post = get_post( $saved_template_id );
		$template = $ecn_saved_templates->override_template_with_rss( '' );

		ob_start();
		include $template;
		$output = ob_get_clean();
		$xml = simplexml_load_string( $output );
		$this->assertEquals( mysql2date( 'D, d M Y', date( 'Y-m-d H:i:s', $this->time + ( 86400 * 2 ) ), false ) . ' 18:30:00 ' . current_time( 'O' ), $xml->channel->item[0]->pubDate, 'Should be the event start date not today' );
	}

	/**
	 * Test the global publication date setting
	 */
	function testFeedPublicationDateStartDateGlobalOverride() {
		global $ecn_saved_templates;
		$saved_template_id = $ecn_saved_templates->create_new_saved_template( array(
			'title' => 'Testing RSS Template',
			'data' => http_build_query( array(
				'event_calendar' => 'the-events-calendar',
				'events_future_in_days' => '30', // Custom date range selected in dropdown
				'headertext' => '<p>Test header</p>',
				'footertext' => '<p>Test footer</p>',
				'group_events' => 'day',
				'format' => '<h3>{title}</h3>
<p>{start_date}{if_end_time} to {end_date}{/if_end_time} @ {location_name}</p>
<p>{description}</p>
<p><a href="{link_url}">More Information</a></p>
<p>&nbsp;</p>',
				// Leave feed publication date default
			) )
		) );

		// Set the global option to event start date (so does not look 'new' more than once)
		save_ecn_option( 'feed_event_publication_date', 'start_date' );

		global $post;
		$post = get_post( $saved_template_id );
		$template = $ecn_saved_templates->override_template_with_rss( '' );

		ob_start();
		include $template;
		$output = ob_get_clean();
		$xml = simplexml_load_string( $output );
		$this->assertEquals( mysql2date( 'D, d M Y', date( 'Y-m-d H:i:s', $this->time + ( 86400 * 2 ) ), false ) . ' 18:30:00 ' . current_time( 'O' ), $xml->channel->item[0]->pubDate, 'Should be the event start date not today' );
	}


	/**
	 * Test that when columns enabled, a) only one item should be present and b) the date should be today
	 */
	function testOnlyOneItemForColumnsTodayDate() {
		global $ecn_saved_templates;

		//include( trailingslashit( dirname( __FILE__ ) ) . '../../event-calendar-newsletter-pro-columns/event-calendar-newsletter-pro-columns.php' );
		// Set the license data to be >= 5, which activates the columns feature, in bootstrap

		$saved_template_id = $ecn_saved_templates->create_new_saved_template( array(
			'title' => 'Testing RSS Template',
			'data' => http_build_query( array(
				'enable_columns' => 1,
				'column_count' => 2,
				'event_calendar' => 'the-events-calendar',
				'events_future_in_days' => '30', // Custom date range selected in dropdown
				'headertext' => '<p>Test header</p>',
				'footertext' => '<p>Test footer</p>',
				'group_events' => 'day',
				'format' => '<h3>{title}</h3>
<p>{start_date}{if_end_time} to {end_date}{/if_end_time} @ {location_name}</p>
<p>{description}</p>
<p><a href="{link_url}">More Information</a></p>
<p>&nbsp;</p>',

				// Override the feed publication date with start_date
				'feed_event_publication_date' => 'start_date'
			) )
		) );

		global $post;
		$post = get_post( $saved_template_id );
		$template = $ecn_saved_templates->override_template_with_rss( '' );

		ob_start();
		include $template;
		$output = ob_get_clean();
		$xml = simplexml_load_string( $output );
		$this->assertEquals( mysql2date( 'D, d M Y H:i:s +0000', date( 'Y-m-d' ) . ' 00:00:00', false ), $xml->channel->item[0]->pubDate, 'Event pub date should be the default "Today" value' );
		$this->assertTrue( 1 == count( $xml->channel->item ), 'Should only have one item, with all events lumped in' );
	}


	function testSavedTemplateWithCategory() {
		$this->markTestIncomplete();
	}

}