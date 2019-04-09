<?php if ( is_active_sidebar( 'brand' ) ): ?>

    <!-- ******************* The Hero Widget Area ******************* -->

    <div class="wrapper" id="wrapper-hero">

        <div class="owl-carousel brand-carousel" id="brands">

            <?php dynamic_sidebar( 'brand' ); ?>

        </div><!-- ,owk-carousel -->

    </div><!-- #wrapper-hero -->

<?php endif; ?>
