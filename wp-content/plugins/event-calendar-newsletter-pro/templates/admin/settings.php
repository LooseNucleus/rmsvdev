<tr valign="top">
	<th scope="row"><?= esc_html( __( 'Group By Day Format', 'event-calendar-newsletter' ) ) ?></th>
	<td>
		<input type="text" name="group_by_day_format" value="<?= esc_attr( get_ecn_option( 'group_by_day_format', '' ) ) ?>" /><br />
		<?= sprintf( esc_html( __( 'Leave blank for default date format. %sDocumentation on date and time formatting%s' ) ), '<a target="_blank" href="https://codex.wordpress.org/Formatting_Date_and_Time">', '</a>' ) ?>
	</td>
</tr>
<tr valign="top">
	<th scope="row"><?= esc_html( __( 'Group By Month Format', 'event-calendar-newsletter' ) ) ?></th>
	<td>
		<input type="text" name="group_by_month_format" value="<?= esc_attr( get_ecn_option( 'group_by_month_format', '' ) ) ?>" /><br />
		<?= sprintf( esc_html( __( 'Leave blank for month name. %sDocumentation on date and time formatting%s' ) ), '<a target="_blank" href="https://codex.wordpress.org/Formatting_Date_and_Time">', '</a>' ) ?>
	</td>
</tr>
<tr valign="top">
	<th scope="row"><?= esc_html( __( 'Group By Tag Format', 'event-calendar-newsletter' ) ) ?></th>
	<td>
		<input type="text" style="width:50%;" name="group_by_tag_start" value="<?= esc_attr( get_ecn_option( 'group_by_tag_start', '<h3 class="group_event_title" style="padding-top:15px;">' ) ) ?>" />
		...
		<input type="text" name="group_by_tag_end" value="<?= esc_attr( get_ecn_option( 'group_by_tag_end', '</h3>' ) ) ?>" /><br />

	</td>
</tr>

<tr valign="top">
	<th scope="row"><?= esc_html( __( 'Publication Date in Feed', 'event-calendar-newsletter' ) ) ?></th>
	<td>
		<select name="feed_event_publication_date">
			<option value="today"<?= ( 'today' == get_ecn_option( 'feed_event_publication_date', 'today' ) ? ' SELECTED' : '' ) ?>>Always set to today's date so events appear new</option>
			<option value="start_date"<?= ( 'start_date' == get_ecn_option( 'feed_event_publication_date', 'today' ) ? ' SELECTED' : '' ) ?>>Set to the event's start date</option>
			<option value="published_date"<?= ( 'published_date' == get_ecn_option( 'feed_event_publication_date', 'today' ) ? ' SELECTED' : '' ) ?>>Set to the event's publication date</option>
		</select>
	</td>
</tr>
