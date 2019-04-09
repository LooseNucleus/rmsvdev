<div class="wrap">
	<h1><?php echo esc_html( __( 'Saved Event Calendar Templates', 'event-calendar-newsletter' ) ) ?> <a href="<?= admin_url( 'admin.php?page=eventcalendarnewsletter' ) ?>" class="page-title-action"><?php echo __( 'Add New' ) ?></a></h1>

	<form id="posts-filter" method="get">
		<?php wp_nonce_field( 'ecn_admin', 'wp_ecn_admin_nonce' ); ?>
		<table class="wp-list-table widefat fixed striped posts">
			<thead>
			<tr>
				<th scope="col" id="title" class="manage-column column-title">
					<span><?php echo esc_html( __( 'Title', 'event-calendar-newsletter' ) ) ?></span>
				</th>
				<th scope="col" id="feed" class="manage-column column-feed">
					<span><?php echo esc_html( __( 'Feed URL', 'event-calendar-newsletter' ) ) ?></span>
				</th>
				<th scope="col" id="feed" class="manage-column column-feed">
					<span><?php echo esc_html( __( 'MailChimp Merge Tags', 'event-calendar-newsletter' ) ) ?></span>
				</th>
				<?php if ( class_exists( 'WYSIJA_object' ) or defined( 'MAILPOET_VERSION' ) ): ?>
					<th scope="col" id="mailpoet" class="manage-column column-mailpoet">
						<span><?php echo esc_html( __( 'MailPoet Shortcode', 'event-calendar-newsletter' ) ) ?></span>
					</th>
				<?php endif; ?>
                <?php if ( defined( 'NEWSLETTER_VERSION' ) ): ?>
                    <th scope="col" id="newsletter" class="manage-column column-newsletter">
                        <span><?php echo esc_html( __( 'Newsletter Tag', 'event-calendar-newsletter' ) ) ?></span>
                    </th>
                <?php endif; ?>
				<th scope="col" id="feed_event_publication_date" class="manage-column column-feed-pub-date">
					<span><?php echo esc_html( __( 'Feed Event Date', 'event-calendar-newsletter' ) ) ?></span>
				</th>
				<th scope="col" id="published" class="manage-column column-published">
					<span><?php echo esc_html( __( 'Published', 'event-calendar-newsletter' ) ) ?></span>
				</th>
			</tr>
			</thead>
			<tbody id="the-list">
			<?php $query = new WP_Query( 'post_type=ecn&post_status=publish&posts_per_page=-1' ); ?>
			<?php while ( $query->have_posts() ): $query->the_post(); ?>
				<tr id="post-<?php the_ID() ?>" class="iedit author-self level-0 post-<?php the_ID() ?> type-calendar status-publish hentry">
					<td class="title column-title has-row-actions column-primary page-title" data-colname="Title">
						<strong><a href="<?= admin_url( 'admin.php?page=eventcalendarnewsletter&saved_template_id=' . get_the_ID() ) ?>" title="<?php echo __( 'View' ) ?>" rel="permalink"><?php the_title(); ?></a></strong>
						<div class="row-actions">
							<span class="view"><a href="<?= admin_url( 'admin.php?page=eventcalendarnewsletter&saved_template_id=' . get_the_ID() ) ?>" title="<?php echo __( 'View' ) ?>" rel="permalink">View</a> | </span>
							<span style="display:none;" class="clear_cache"><a href="<?php echo wp_nonce_url( "admin.php?page=saved-ecn-templates&action=clear_cache&amp;post=" . get_the_ID(), 'ecn_clear_cache_' . get_the_ID() ) ?>"><?php echo esc_html( __( 'Clear Event Cache', 'event-calendar-newsletter' ) ) ?></a> | </span>
							<span class="trash"><a class="submitdelete" title="<?php echo __( 'Move this item to the Trash' ) ?>" href="<?php echo wp_nonce_url( "post.php?action=trash&amp;post=" . get_the_ID(), 'trash-post_' . get_the_ID() ) ?>"><?php echo __( 'Delete' ) ?></a></span>
						</div>
					</td>
					<td class="feed-url column-feed-url" data-colname="Feed URL">
						<input readonly="readonly" class="ecn-feed-url" onclick="this.select();" value="<?php echo esc_attr( the_permalink() ); ?>" />
					</td>
					<td class="feed-url column-feed-url" data-colname="MailChimp Merge Tags">
						<input readonly="readonly" class="ecn-mailchimp-merge-tags" onclick="this.select();" value="*|FEEDBLOCK:<?php echo esc_attr( the_permalink() ); ?>|**|FEEDITEMS:[$count=100]|**|FEEDITEM:CONTENT_FULL|**|END:FEEDITEMS|**|END:FEEDBLOCK|*" />
                        <input readonly="readonly" class="ecn-mailchimp-merge-tags" onclick="this.select();" value="*|RSSITEMS:|**|RSSITEM:CONTENT_FULL|**|END:RSSITEMS|*" />
					</td>
					<?php if ( class_exists( 'WYSIJA_object' ) or defined( 'MAILPOET_VERSION' ) ): ?>
						<td class="mailpoet-shortcode column-feed-url" data-colname="MailPoet Shortcode">
							<input readonly="readonly" class="ecn-mailpoet-shortcode" onclick="this.select();" value="[custom:ecn-<?= get_the_ID() ?>]" />
						</td>
					<?php endif; ?>
					<?php if ( defined( 'NEWSLETTER_VERSION' ) ): ?>
                        <td class="newsletter-tag column-feed-url" data-colname="Newsletter Tag">
                            <input readonly="readonly" class="ecn-newsletter-tag" onclick="this.select();" value="{ecn-<?= get_the_ID() ?>}" />
                        </td>
                    <?php endif; ?>
                    <td class="pub-date">
						<select class="feed_event_publication_date" data-id="<?= get_the_ID() ?>">
							<option value="">Default</option>
							<option value="today"<?= ( 'today' == ecn_get_saved_template_value( get_the_ID(), 'feed_event_publication_date' ) ? ' SELECTED' : '' ) ?>>Today's date</option>
							<option value="start_date"<?= ( 'start_date' == ecn_get_saved_template_value( get_the_ID(), 'feed_event_publication_date' ) ? ' SELECTED' : '' ) ?>>Event start date</option>
							<option value="published_date"<?= ( 'published_date' == ecn_get_saved_template_value( get_the_ID(), 'feed_event_publication_date' ) ? ' SELECTED' : '' ) ?>>Event published date</option>
						</select>
						<span id="feed_event_publication_date_message-<?= get_the_ID() ?>"></span>
						<span id="spinner-<?= get_the_ID() ?>" class="spinner"></span>
					</td>
					<td class="date column-date" data-colname="Date">
						<?php the_time( get_option( 'date_format' ) ); ?>
					</td>
				</tr>
			<?php endwhile; ?>
			</tbody>
		</table>
	</form>

	<br class="clear">

	<h2>Feed URL</h2>

	<p>This is the feed URL you would use in a MailChimp, Active Campaign or other mailing program's RSS campaign or for adding events within an existing campaign.</p>
	<p>You can create as many as you'd like - for example, you could just make one for all your events, and/or you could make a template for each of your event categories so subscribers can pick and choose which events they are interested in using MailChimp Groups!</p>

	<?php if ( class_exists( 'WYSIJA_object' ) ): ?>
		<h2>MailPoet Shortcode</h2>

		<p>If you are using MailPoet to send your mailings, just copy and paste the shortcode into your MailPoet campaign.</p>
	<?php endif; ?>

	<h2>MailChimp Merge Tags</h2>

	<p>You can copy and paste the content of this box into a text area in a MailChimp campaign to pull in your events!</p>
	
	<h2>Feed Event Date</h2>

	<p>The feed event date determines how the mailings are handled in MailChimp.</p>
	<p>If set to <strong>Default</strong>, it will use the <a href="<?= admin_url( 'admin.php?page=ecn-settings' ) ?>">global Event Calendar Newsletter setting</a>.</p>
	<p>If set to <strong>Today's date</strong>, MailChimp will always see events as 'new' and could send multiple times depending on your mailing schedule and what events you are fetching in the template.</p>
	<p>If set to <strong>Event start date</strong>, MailChimp will only send the event once for an RSS campaign, unless you change an event's start date.</p>
	<p>If set to <strong>Event published date</strong>, MailChimp will only send the event once for an RSS campaign, unless you change an event's published date.  Note that depending on the frequency of your RSS campaign, the published date needs to fall within 24 hours, 7 days for weekly, or 30 days for monthly.  See <a href="http://kb.mailchimp.com/campaigns/rss-in-campaigns/troubleshooting-rss-in-campaigns#I-updated-my-feed,-but-my-RSS-Campaign-didn" target="_blank">troubleshooting tips and more information here</a>.</p>
	<p>For example, by using these settings you can could have one campaign that sends new events as they're added, and another that will send the upcoming events for the next couple weeks regardless of whether events are new or not.  This way people are always reminded of your events!</p>
</div>