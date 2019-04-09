<?php
function ecn_add_table_design_option( $data ) {
	?>
	<label><input type="radio" name="design" value="table"<?php checked( 'table', $data['design'] ) ?>> <?= __( 'Single Row With Button', 'event-calendar-newsletter' ) ?></label><br />
	<?php
}
add_action( 'ecn_designs', 'ecn_add_table_design_option', 10, 1 );

function ecn_output_format_table( $format, $event, $args, $previous_date ) {
	if ( isset( $args['design'] ) and 'table' == $args['design'] )
		$format = '<table style="width:100%">
<tr>
{if_event_image_url}
<td width="15%" style="text-align:right; padding-right:5px;"><img src="{event_image_url}" width="100%" /></td>
{/if_event_image_url}
{if_not_event_image_url}
<td width="15%">&nbsp;</td>
{/if_not_event_image_url}
<td width="70%" valign="top">
<strong><a href="{link_url}">{title}</a></strong>
{if_location_name}<br><em> at {location_name}</em>{/if_location_name}<br>
<div style="margin-top:5px;">{start_date} {if_not_all_day}@ {start_time}{if_end_time} to {end_time}{/if_end_time}{/if_not_all_day}</div>
</td>
<td width="15%"><a href="{link_url}" style="background-color:#0a0a0a;background-image:none;border-radius:3px;border:0;box-shadow:none;color:#efefef;cursor:pointer;display:inline-block;font-size:11px;font-weight:700;letter-spacing: 1px;line-height: normal;padding:6px 9px;text-align:center;text-decoration:none;text-transform:uppercase;vertical-align:middle;zoom:1;white-space:nowrap;">View</a></td>
</tr>
</table>';
	return $format;
}
add_filter( 'ecn_output_format', 'ecn_output_format_table', 10, 4 );
