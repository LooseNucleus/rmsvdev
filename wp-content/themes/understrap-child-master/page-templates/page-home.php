<?php
/*

Template Name: Home Page

 */


get_header(); ?>

<div class="wrapper-home">

<?php

get_template_part('partials/section','hero'); ?>

<?php get_template_part('partials/section', 'carousel-notifications'); ?>

<?php get_template_part('partials/section', 'featured-products'); ?>

<?php get_template_part('partials/section','product-links'); ?>

<?php get_template_part('content','locations-new'); ?>

</div>

<?php get_footer(); ?>
