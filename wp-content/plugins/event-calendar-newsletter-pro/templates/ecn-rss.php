<?php
/**
 * RSS2 Feed Template for displaying RSS2 Posts feed.
 *
 * @package WordPress
 */

if ( ! defined( 'WP_TESTS_TABLE_PREFIX' ) )
	header('Content-Type: ' . feed_content_type('rss2') . '; charset=' . get_option('blog_charset'), true);
$more = 1;

echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>';

/**
 * Fires between the xml and rss tags in a feed.
 *
 * @param string $context Type of feed. Possible values include 'rss2', 'rss2-comments',
 *                        'rdf', 'atom', and 'atom-comments'.
 */
do_action( 'ecn_rss_tag_pre', 'rss2' );
?>
<rss version="2.0"
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:wfw="http://wellformedweb.org/CommentAPI/"
     xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:atom="http://www.w3.org/2005/Atom"
     xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
     xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
	<?php
	/**
	 * Fires at the end of the RSS root to add namespaces.
	 */
	do_action( 'ecn_rss2_ns' );
	?>
	>

	<channel>
		<title><?php wp_title_rss(); ?></title>
		<atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
		<link><?php bloginfo_rss('url') ?></link>
		<description><?php bloginfo_rss("description") ?></description>
		<lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', date('Y-m-d H:i:s'), false); ?></lastBuildDate>
		<language><?php bloginfo_rss( 'language' ); ?></language>
		<sy:updatePeriod><?php
			$duration = 'hourly';

			/**
			 * Filter how often to update the RSS feed.
			 *
			 * @param string $duration The update period. Accepts 'hourly', 'daily', 'weekly', 'monthly',
			 *                         'yearly'. Default 'hourly'.
			 */
			echo apply_filters( 'ecn_rss_update_period', $duration );
			?></sy:updatePeriod>
		<sy:updateFrequency><?php
			$frequency = '1';

			/**
			 * Filter the RSS update frequency.
			 *
			 * @param string $frequency An integer passed as a string representing the frequency
			 *                          of RSS updates within the update period. Default '1'.
			 */
			echo apply_filters( 'ecn_rss_update_frequency', $frequency );
			?></sy:updateFrequency>
		<?php
		/**
		 * Fires at the end of the RSS2 Feed Header.
		 *
		 * @since 2.0.0
		 */
		do_action( 'ecn_rss2_head' );

		global $ecn_admin_class;
		$data = ecn_get_saved_template_data( get_the_ID() );
		$data['feed_event_publication_date'] = ( isset( $data['feed_event_publication_date'] ) ? $data['feed_event_publication_date'] : get_ecn_option( 'feed_event_publication_date', 'today' ) );
		echo $ecn_admin_class->get_output_from_data( wp_parse_args( array( 'is_rss' => true ), $data ) );
		?>
	</channel>
</rss>
