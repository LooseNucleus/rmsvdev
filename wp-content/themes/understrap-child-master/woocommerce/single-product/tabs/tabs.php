<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );

global $product;



$financePrice = $product->get_price();
$bundled_items = '';
if (function_exists('get_bundled_items')) {
$bundled_items = $product->get_bundled_data_items();
}



if ( ! empty( $tabs ) ) : ?>

<style>
.sticky:before,
.sticky:after {
    content: '';
    display: table;
}
</style>

<div class="section" id="product-tabs">
	<!-- REMOVED WC-TABS CLASS to use bootstrap javascript -->

		<div class="card">
			<div class="card-body">
				<div class="row">
		<div class="col-md-3">
			<div id="sticky" class="sticky" style="top: 100px;">

		<ul class="nav nav-pills flex-column bg-light" id="pills-tab" role="tablist">
			<?php
			$count = 0;
			foreach ( $tabs as $key => $tab ) :
				$count++; ?>
				<li class="<?php echo esc_attr( $key ); ?>_tab nav-item" id="tab-title-<?php echo esc_attr( $key ); ?>" role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
					<a class="nav-link <?php if ($count==1) {
						echo "active";
					} ?>" data-toggle="pill" href="#tab-<?php echo esc_attr( $key ); ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?></a>
				</li>
			<?php endforeach; ?>
			<?php if ($financePrice > 300): ?>
				<li class="nav-item" role="tab">
					<a class="nav-link" data-toggle="pill" href="#tab-finance">Financing Available!</a>
				</li>
			<?php endif; ?>
			<?php if (!empty($bundled_items)): ?>
				<li class="nav-item" role="tab">
					<a class="nav-link" data-toggle="pill" href="#tab-bundled">See Included Products</a>
				</li>
			<?php endif; ?>
</ul>




</div>
</div>
<div class="col-md-9">

	<div class="tab-content">
		<?php
		$count=0;
		foreach ( $tabs as $key => $tab ) :
			$count++; ?>
			<div class="tab-pane <?php if ($count==1) {
				echo "show active ";
			} ?>fade woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?>" id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
				<?php if ( isset( $tab['callback'] ) ) { call_user_func( $tab['callback'], $key, $tab ); } ?>
			</div>
		<?php endforeach; ?>
		<?php if ($financePrice > 300): ?>
			<div class="tab-pane fade" role="tab-panel" id="tab-finance">
				<div class="container">
					<div class="row">
						<div class="col-md-6">
				<h3>0% Interest for Up to 60 Months</h3>
				<p>On purchases of $300 or more made with your Sewing & More credit card. Terms of 6 to 60 months available.</p>
				<p>Apply for your <a href="https://etail.mysynchrony.com/eapply/eapply.action?uniqueId=D2895D9FF3CADB4EDE01B7CCC4D6E13E81F7C10362A49DC2&client=Sewing & More">
Sewing & More Credit Card</a> now!</p>
</div>
<div class="col-md-6">

				<a href="https://etail.mysynchrony.com/eapply/eapply.action?uniqueId=D2895D9FF3CADB4EDE01B7CCC4D6E13E81F7C10362A49DC2&client=Sewing & More"><img src="<?php echo get_site_url(); ?>/wp-content/uploads/2018/10/sewing-n-more-credit-card-art-2.jpg" alt="Moore's Sewing Credit Card from Synchrony Financial"></a>
			</div>
			</div>
		</div>
		</div>
		<?php endif; ?>


		<?php if (!empty($bundled_items)): ?>
			<div class="tab-pane fade" role="tab-panel" id="tab-bundled">
			<form method="post" enctype="multipart/form-data" class="cart cart_group bundle_form layout_tabular group_mode_parent">

			<?php

	do_action( 'woocommerce_before_bundled_items', $product );

				foreach ( $bundled_items as $bundled_item ) {

					/**
					 * 'woocommerce_bundled_item_details' action.
					 *
					 * @hooked wc_pb_template_bundled_item_details_wrapper_open  -   0
					 * @hooked wc_pb_template_bundled_item_thumbnail             -   5
					 * @hooked wc_pb_template_bundled_item_details_open          -  10
					 * @hooked wc_pb_template_bundled_item_title                 -  15
					 * @hooked wc_pb_template_bundled_item_description           -  20
					 * @hooked wc_pb_template_bundled_item_product_details       -  25
					 * @hooked wc_pb_template_bundled_item_details_close         -  30
					 * @hooked wc_pb_template_bundled_item_details_wrapper_close - 100
					 */
					do_action( 'woocommerce_bundled_item_details', $bundled_item, $product );
				}

					/**
					 * 'woocommerce_after_bundled_items' action.
					 *
					 * @param  WC_Product_Bundle  $product
					 */
					do_action( 'woocommerce_after_bundled_items', $product );

					/**
					 * 'woocommerce_bundles_add_to_cart_wrap' action.
					 *
					 * @since  5.5.0
					 *
					 * @param  WC_Product_Bundle  $product
					 */
					do_action( 'woocommerce_bundles_add_to_cart_wrap', $product );
				?>

			</form>


		</div>
	<?php endif;
	?>


	</div>
</div>
</div>
</div>
</div>
</div>
</div>

</div>

<?php endif; ?>
