<?php
/**
 * @package understrap
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> class="content-single-item">

	<header class="entry-header">

		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<?php if ( 'post' == get_post_type() ) : ?>

		<div class="post-details">
			<i class="fa fa-user"></i> <span class="vcard author"><span class="fn"><?php the_author(); ?></span></span>
			<i class="fa fa-clock-o"></i> <span class="date updated published"><time><?php the_date(); ?></time></span>

			<i class="fa fa-folder"></i> <?php the_category(', ') ?>
			<i class="fa fa-tags"></i> <?php the_tags(); ?>

			<?php edit_post_link( 'Edit', '<i class="fa fa-pencil"></i> ', ''  ); ?>
		</div><!-- post-details -->

		<?php endif; ?>

	</header><!-- .entry-header -->

	<?php
		$image = get_field('blog_image');
		$newBlogImage = get_field('new_blog_image');
		$size = 'blog_image';
		$value = $imageArray['id'];
		$imageUrl = $imageArray['url'];
		$imageAlt = $imageArray['original_image']['alt'];
		$imageCaption = $imageArray['original_image']['caption'];

	?>

			<div class="post-image">
				<?php if($image) {
					echo wp_get_attachment_image($image,$size);
				} else if ($newBlogImage) {
					echo wp_get_attachment_image($newBlogImage,$size);
				}
				?>
			</div>
			<?php if ($imageCaption) : ?> <p class="caption"><?php echo $imageCaption; ?></p>
			<?php endif; ?>

	<div class="entry-content">

		<?php the_content(); ?>

		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'understrap' ),
				'after'  => '</div>',
			) );
		?>

	</div><!-- .entry-content -->

</article><!-- #post-## -->

<!-- Modal -->
      <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h2 class="modal-title" id="myModalLabel">Subscribe for Special Offers</h2>
            </div>
            <div class="modal-body">
              <?php echo do_shortcode('[contact-form-7 id="2881" title="Brand Page Optin"]'); ?>
            </div>
          </div>
        </div>
      </div>
<!-- End Modal -->
