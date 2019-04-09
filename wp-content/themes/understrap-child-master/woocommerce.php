<?php
/**
 * The template for displaying all woocommerce pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package understrap
 */

get_header();

$container   = get_theme_mod( 'understrap_container_type' );

remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );


?>

<div class="wrapper" id="woocommerce-wrapper">

	<div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">
		<div class="row">
      <div class="col-md-8">
				<?php echo $pageType; ?>
        <?php get_product_search_form(); ?>
      </div>
			<div class="col-md-4">
				<?php if ( is_active_sidebar( 'category-sidebar' ) ) : ?>
				    <?php dynamic_sidebar( 'category-sidebar' ); ?>
				<?php endif; ?>
			</div>
		</div>
		<div class="row">
			<div class="col mx-auto">
			<?php woocommerce_breadcrumb(); ?>
		</div>
		</div>

    <div class="row">

      <div class="col-md-12">
				<?php
				global $post;
				$pageType = get_page_template_slug($post);
				echo $pageType;


				?>


			<main class="site-main" id="main">

			<?php
				$template_name = '\archive-product.php';
				$args = array();
				$template_path = '';
				$default_path = untrailingslashit( plugin_dir_path(__FILE__) ) . '\woocommerce';

					if ( is_singular( 'product' ) ) {

						woocommerce_content();

			//For ANY product archive, Product taxonomy, product search or /shop landing page etc Fetch the template override;
				} 	elseif ( file_exists( $default_path . $template_name ) )
					{
					wc_get_template( $template_name, $args, $template_path, $default_path );

			//If no archive-product.php template exists, default to catchall;
				}	else  {
					woocommerce_content( );
				}

			;?>

			</main><!-- #main -->

    </div><!-- end of col-md-12 -->

		</div><!-- #primary -->

	</div><!-- .row -->

</div><!-- Container end -->

</div><!-- Wrapper end -->


<?php get_footer(); ?>
