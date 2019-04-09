<div class="modal fade" tabindex="-1" role="dialog" id="rfq-modal-lg" aria-labelledby="myLargeModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="modal-header text-center">
<h3 class="modal-title" id="exampleModalLabel">Get A Quote</h3>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
<div class="modal-body">
<div class="container-fluid">
<div class="row grid-divider">
<div class="col-md-6">
<div class="col-padding">
<h4>Give Us a Call</h4>
<table class="table">
<thead>
	<tr>
		<th scope="col">Location</th>
		<th scope="col">Phone</th>
	</tr>
</thead>
<tbody>
  <?php $loop = new WP_Query( array( 'post_type' => 'tribe_venue', 'posts_per_page' => 4, 'orderby' => 'date', 'order' => 'ASC' ) ); ?>

    <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
	<tr>
		<th scope="row"><?php echo tribe_get_venue(); ?></th>
		<td nowrap><a href="tel:+1<?php echo tribe_get_phone(); ?>"><?php echo tribe_get_phone(); ?></a></td>
	</tr>
<?php endwhile;
wp_reset_query(); ?>

</tbody>
</table>
</div>
</div>
<div class="col-md-6">
<div class="col-padding">
<h4>Or Have Us Get In Touch</h4>
<?php
$title = "Product Quote Form";
$result = wpcf7_get_contact_form_by_title($title);

echo do_shortcode('[contact-form-7 id="' . $result->id . ' title="Product Quote Form"]'); ?>

<script>
document.addEventListener( 'wpcf7mailsent', function( event ) {
    ga('send', 'event','Form Submissions', 'submit', 'Product Quote Form Submitted');
}, false );
</script>

</div>
</div>

</div>

</div>
</div>
</div>
</div>

</div>
