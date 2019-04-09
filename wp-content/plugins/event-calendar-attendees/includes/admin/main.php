<div class="wrap">
    <h2><?= esc_html( apply_filters( 'eca_settings_title', _x( 'Event Calendar Attendees', 'Settings title', 'event-calendar-attendees' ) ) ) ?></h2>
	<?php do_action( 'eca_after_settings_title' ); ?>



    <?php if ( ! $data['available_plugins'] ): ?>
	    <div id="no-supported-calendars">
	        <h1><?= esc_html( __( 'No supported event calendar plugins available.', 'event-calendar-attendees' ) ) ?></h1>
		    <p>
			    <?= esc_html( __( 'Event Calendar Attendees takes the details of your upcoming events to put inside your newsletter from one of the supported WordPress event calendar plugins.', 'event-calendar-attendees' ) ); ?>
		    </p>
	        <p>
	            <strong><?= esc_html( __( 'Install one of the supported calendars, which include:', 'event-calendar-attendees' ) ); ?></strong>
	            <ul>
	                <li><a href="<?= admin_url( 'plugin-install.php?tab=search&type=term&s=the+events+calendar' ); ?>">The Events Calendar by Modern Tribe, Inc</a></li>
	                <li><a href="<?= admin_url( 'plugin-install.php?tab=search&s=simple+calendar+google' ); ?>">Simple Calendar - Google Calendar Events</a></li>
	                <li><a href="<?= admin_url( 'plugin-install.php?tab=search&type=term&s=all+in+one+event+calendar+time.ly' ); ?>">All-in-One Event Calendar by time.ly</a></li>
	            </ul>
                <div><?= sprintf( esc_html( __( 'Note that certain calendars like %sEvent Espresso%s are only supported %sin the PRO version of Event Calendar Attendees%s', 'event-calendar-attendees' ) ), '<a href="https://eventcalendarattendees.com/features/#calendars?utm_source=plugin&utm_campaign=pro-cal-support-ee" target="_blank">', '</a>', '<a href="https://eventcalendarattendees.com/?utm_source=plugin&utm_campaign=pro-cal-support" target="_blank">', '</a>' ); ?></div>
		    </p>
		    <p><?= sprintf( esc_html( __( "Have another events calendar you'd like supported?  %sLet us know%s!", 'event-calendar-attendees' ) ), '<a href="mailto:info@eventcalendarattendees.com">', '</a>' ); ?></p>
		    <p>
			    <?= sprintf( esc_html( __( 'Still need help?  View %sfull instructions for setting up a supported calendar%s' ) ), '<a target="_blank" href="https://eventcalendarattendees.com/docs/set-event-calendar-wordpress-site/">', '</a>' ); ?>
		    </p>
		    <h1><?php echo esc_html__( 'Preview of Event Calendar Attendees', 'event-calendar-attendees' ); ?></h1>
		    <iframe width="560" height="315" src="https://www.youtube.com/embed/rTwus0wTzX4" frameborder="0" allowfullscreen></iframe>
	    </div>
    <?php else: ?>
        <div id="eca-admin">
            <?php wp_nonce_field( 'eca_admin', 'wp_eca_admin_nonce' ); ?>
            <div class="leftcol">
                <form>
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <th scope="row"><?= esc_html( __( 'Event Calendar:', 'event-calendar-attendees' ) ) ?></th>
                            <td>
                                <select name="event_calendar">
                                    <?php foreach ( $data['available_plugins'] as $plugin => $description ): ?>
                                        <option value="<?php echo esc_attr( $plugin ); ?>"<?php echo ( $plugin == $data['event_calendar'] ? ' SELECTED' : '' ); ?>><?php echo esc_html( $description ); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div>
                                    <em><?= sprintf( esc_html( __( "Can't find the calendar with your events that you'd like to use?  %sLet us know%s!", 'event-calendar-attendees' ) ), '<a href="mailto:info@eventcalendarattendees.com">', '</a>' ); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php echo esc_html( __( 'Future Events to Use:', 'event-calendar-attendees' ) ) ?></th>
                            <td>
                                <select id="events_future_in_days" name="events_future_in_days">
                                    <?php do_action( 'eca_events_future_in_days_before', $data['events_future_in_days'] ); ?>
                                    <?php for ( $i = 1; $i < 4; $i++ ): ?>
                                        <option value="<?php echo ($i * 7); ?>"<?php echo ( $i * 7 == $data['events_future_in_days'] ? ' SELECTED' : '' ); ?>><?php echo sprintf( _n( '%d week', '%d weeks', $i, 'event-calendar-attendees' ), $i ); ?></option>
                                    <?php endfor; ?>
                                    <?php for ( $i = 1; $i <= 12; $i++ ): ?>
                                        <option value="<?php echo ($i * 30); ?>"<?php echo ( $i * 30 == $data['events_future_in_days'] ? ' SELECTED' : '' ); ?>><?php echo sprintf( _n( '%d month', '%d months', $i, 'event-calendar-attendees' ), $i ); ?></option>
                                    <?php endfor; ?>
                                    <?php do_action( 'eca_events_future_in_days_after', $data['events_future_in_days'] ); ?>
                                </select>
	                            <?php do_action( 'eca_events_future_in_days_after_select', $data ); ?>
                            </td>
                        </tr>
                        </tbody>
                        <tbody id="additional_filters">
                            <?php
                            $current_plugin = $data['event_calendar'];
                            if ( ! $current_plugin ) {
                                $all_plugins = array_keys( $data['available_plugins'] );
                                $current_plugin = $all_plugins[0];
                            }
                            do_action( 'eca_additional_filters_settings_html-' . $current_plugin, $data );
                            do_action( 'eca_additional_filters_settings_html', $current_plugin, $data );
                            ?>
                        </tbody>
                        <tbody>
                        <tr>
	                        <th scope="row"><?php echo esc_html( __( 'Group events:', 'event-calendar-attendees' ) ) ?></th>
	                        <td>
		                        <div>
			                        <select id="group_events" name="group_events">
				                        <option value="normal"><?php echo esc_html( __( 'None (Show events in order)', 'event-calendar-attendees' ) ) ?></option>
				                        <?php do_action( 'eca_additional_group_events_values', $data['group_events'] ); ?>
			                        </select>
		                        </div>
		                        <div>
			                        <em>
				                        <?php echo esc_html( __( 'If you have lots of events, you can group them together by day or month with a header for each group', 'event-calendar-attendees' ) ) ?>
				                        <?php if ( 'valid' != get_option( 'eca_pro_license_status' ) ): ?>
					                        <?php echo sprintf( esc_html( __( 'with the %sPro version%s', 'event-calendar-attendees' ) ), '<a target="_blank" href="https://eventcalendarattendees.com/pro/?utm_source=wordpress.org&utm_medium=link&utm_campaign=event-cal-plugin&utm_content=groupevents">', '</a>' ); ?>
				                        <?php endif; ?>
									</em>
		                        </div>
	                        </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php echo esc_html( __( 'Format/Design:', 'event-calendar-attendees' ) ) ?></th>
                            <td>
	                            <div class="leftcol">
		                            <fieldset>
			                            <label><input type="radio" name="design" value="default"<?php if ( 'default' == $data['design'] or false === $data['design'] ) checked( true ); ?>> Default</label><br />
			                            <label><input type="radio" name="design" value="compact"<?php checked( 'compact', $data['design'] ) ?>> Minimal/Compact</label><br />
			                            <?php do_action( 'eca_designs', $data ); ?>
			                            <label><input type="radio" name="design" value="custom"<?php checked( 'custom', $data['design'] ) ?>> Custom</label><br />
			                        </fieldset>
	                            </div>
	                            <div class="right">
		                            <a target="_blank" href="https://eventcalendarattendees.com/designs?utm_source=wordpress.org&utm_medium=link&utm_campaign=event-cal-plugin&utm_content=design-link">See all designs</a>
	                            </div>

                                <div class="format_editor clearfix" style="display:none;">
                                    <select id="placeholder">
                                        <?php foreach ( ECACalendarEvent::get_available_format_tags( $data['event_calendar'] ) as $tag => $description ): ?>
                                            <option value="<?php echo esc_attr( $tag ); ?>"><?php echo esc_html( $description ); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input id="insert_placeholder" type="submit" value="<?= esc_attr( __( 'Insert', 'event-calendar-attendees' ) ) ?>" class="button" />
	                                &nbsp; <a target="_blank" href="https://eventcalendarattendees.com/docs/tags/">View documentation on available tags</a>
                                </div>
                                <div class="format_editor">
	                                <?php wp_editor( $data['format'], 'format', array( 'textarea_rows' => 8, 'wpautop' => false, 'media_buttons' => false ) ); ?>
                                </div>
	                            <?php do_action( 'eca_end_settings_page', $current_plugin, $data ) ?>
                            </td>
                        </tr>
	                    </tbody>
                    </table>
                </form>

                <form action="maindata.php" method="post">
                  <button type="submit" class="button button-primary">Get Attendees</button>
                </form>

                <div id="generate">
                    <input id="fetch_events" type="submit" value="<?= esc_attr( apply_filters( 'eca_generate_button_text', __( 'Generate Newsletter Formatted Events', 'event-calendar-attendees' ) ) ) ?>" class="button button-primary" />
                    <?php do_action( 'eca_settings_after_fetch_events' ); ?>
	                <span class="spinner"></span>
                </div>


                <div class="result">
	                <?php do_action( 'eca_main_before_results' ); ?>

                    <div id="copy_paste_info"><?php echo sprintf( esc_html__( 'Copy and paste the result into your MailChimp, ActiveCampaign, MailPoet or other newsletter sending service.  You will likely want to use the "Results (HTML)" version. %sView a Quick Demo%s', 'event-calendar-attendees' ), '<a target="_blank" href="http://www.youtube.com/watch?v=4oSIlU541Bo">', '</a>' ); ?></div>

                    <h2 class="nav-tab-wrapper">
                        <a id="results_tab" class="nav-tab nav-tab-active"><?= esc_html( __( 'Result', 'event-calendar-attendees' ) ) ?></a>
                        <a id="results_html_tab" class="nav-tab"><?= esc_html( __( 'Result (HTML)', 'event-calendar-attendees' ) ) ?></a>
                    </h2>

                    <div id="results" class="tab_container">
                        <span id="output"></span>
                    </div>
                    <div id="results_html" class="tab_container">
                        <p><button id="select_html_results" class="btn"><?= esc_html( __( 'Select All Text', 'event-calendar-attendees' ) ) ?></button></p>
                        <textarea id="output_html" rows="10" cols="80"></textarea>
                    </div>

	                <?php do_action( 'eca_main_after_results' ); ?>

                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'maindata.php'; ?>
