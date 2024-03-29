<?php
/**
 * Left sidebar check.
 *
 * @package understrap
 */

?>

<?php
$sidebar_pos = get_theme_mod( 'understrap_sidebar_position' );
?>

<?php if ( 'left' === $sidebar_pos || 'both' === $sidebar_pos ) : ?>
	<?php get_sidebar( 'left' ); ?>
<?php endif; ?>

<?php
	$html = '';
	if ( !tribe_is_month() &&!tribe_is_week() && !tribe_is_list_view() && 'right' === $sidebar_pos || 'left' === $sidebar_pos ) {
		$html = '<div class="';
		if ( ( is_active_sidebar( 'right-sidebar' ) && 'right' === $sidebar_pos ) || ( is_active_sidebar( 'left-sidebar' ) && 'left' === $sidebar_pos ) ) {
			$html .= 'col-md-8 content-area" id="primary">';
		} else {
			$html .= 'col-md-12 content-area" id="primary">';
		}
		echo $html; // WPCS: XSS OK.
	} elseif ( 'both' === $sidebar_pos ) {
		$html = '<div class="';
		if ( is_active_sidebar( 'right-sidebar' ) && is_active_sidebar( 'left-sidebar' ) ) {
			$html .= 'col-md-6 content-area" id="primary">';
		} elseif ( is_active_sidebar( 'right-sidebar' ) || is_active_sidebar( 'left-sidebar' ) ) {
			$html .= 'col-md-8 content-area" id="primary">';
		} else {
			$html .= 'col-md-12 content-area" id="primary">';
		}
		echo $html; // WPCS: XSS OK.
	} else {
	    echo '<div class="col-md-12 content-area" id="primary">';
	}
