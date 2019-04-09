<?php
/**
 * @var array $filters All registered filters
 */
?>
<div class="tribe-admin-box-right">
	<h4><?php esc_html_e( 'Active Filters', 'tribe-events-filter-view' ); ?></h4>
	<ul id="active_filters">
		<?php
		$i = 0;
		foreach ( $filters as $slug => $filter ) {
			?>
			<li id="tribe_events_active_filter_<?php echo esc_attr( $slug ); ?>" class="tribe-arrangeable-item">
				<div class="ui-state-default tribe-arrangeable-item-top widget-top">
					<span class="active-sort"></span>
					<?php echo $filters[ $slug ]['name']; ?>
					<a href="" class="tribe-arrangeable-action hide-if-no-js"><span class="tribe-drop-indicator"></span></a>
				</div>
				<div class="tribe-arrangeable-child">
					<?php if ( ! empty( $filters[ $slug ]['admin_form'] ) ) { ?>
						<div id="tribe_events_active_filter_form_<?php echo esc_attr( $slug ); ?>" class="active-filters-form" method="POST">
							<?php echo $filters[ $slug ]['admin_form']; ?>
							<input type="hidden" name="tribe_filter_options[<?php echo esc_attr( $slug ); ?>][priority]" class="tribe-filter-priority" value="<?php echo esc_attr( ++ $i ); ?>"/>
						</div>
					<?php } ?>
				</div>
			</li>
			<?php
		}
		?>
	</ul>
</div>