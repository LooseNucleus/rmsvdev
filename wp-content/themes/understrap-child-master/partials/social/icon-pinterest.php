<?php

get_field('social_icon_links', 'option');
      $pinterestLink = get_sub_field('pinterest');

?>
<a class="mx-3 mb-3" href="<?php echo $pinterestLink; ?>" target="_blank"><i class="fa fa-pinterest fa-2x" aria-hidden="true"></i></a>
