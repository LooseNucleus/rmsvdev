<?php

get_field('social_icon_links', 'option');
      $linkedinLink = get_sub_field('linkedin');

?>
<a class="mx-3 mb-3" href="<?php echo $linkedinLink; ?>" target="_blank"><i class="fa fa-linkedin fa-2x" aria-hidden="true"></i></a>
