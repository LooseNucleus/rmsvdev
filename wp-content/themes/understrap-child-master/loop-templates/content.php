<?php
/**
 * @package understrap
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( sprintf( '<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>

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






	<div class="post-excerpt">
		<?php the_excerpt(); ?>
	</div><!-- post-excerpt -->

</article><!-- #post-## -->
