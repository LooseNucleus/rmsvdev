<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package understrap
 */

 global $rmsvImageUrl;
?>



</div><!-- #page -->

<?php
echo "<style>";
echo "#hero-landing:";
echo "{background: url('";
echo $rmsvImageUrl;
echo "');}";
echo "</style>";
?>

<?php wp_footer(); ?>






</body>

</html>
