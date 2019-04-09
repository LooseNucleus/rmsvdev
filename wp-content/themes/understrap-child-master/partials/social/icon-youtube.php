<?php

get_field('social_icon_links', 'option');
      $youtubeLink = get_sub_field('youtube');

?>
<a class="mx-3 mb-3" href="<?php echo $youtubeLink; ?>" target="_blank"><i class="fa fa-youtube fa-2x" aria-hidden="true"></i></a>
