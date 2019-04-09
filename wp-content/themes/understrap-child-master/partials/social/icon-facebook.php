<?php

get_field('social_icon_links', 'option');
      $facebookLink = get_sub_field('facebook');

?>
<a class="mx-3 mb-3" href="<?php echo $facebookLink; ?>" target="_blank"><i class="fa fa-facebook-official fa-2x" aria-hidden="true"></i></a>
