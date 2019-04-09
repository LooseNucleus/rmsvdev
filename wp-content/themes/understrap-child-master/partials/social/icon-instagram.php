<?php

get_field('social_icon_links', 'option');
      $instagramLink = get_sub_field('instagram');

?>
<a class="mx-3 mb-3" href="<?php echo $instagramLink; ?>" target="_blank"><i class="fa fa-instagram fa-2x" aria-hidden="true"></i></a>
