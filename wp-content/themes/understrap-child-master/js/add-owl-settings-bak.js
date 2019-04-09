    jQuery(document).ready(function($) {
        var owl = jQuery('#brands');
        owl.owlCarousel( {
            loop:true,
            autoplay:false,
            center:true,
            mouseDrag:true,
            autoplayTimeout:5000,
            animateOut: 'fadeOut',
            animateIn: 'fadeIn',
            nav: true,
            dots: true,
            autoplayHoverPause:true,
            margin:25,
            startPosition:parseInt(simplur_startPosition),
            URLhashListener: true,
            responsiveClass:true,
            responsive: {
              0:{
                items:2,
              },
              768:{
                items:4,
              }
            }
        });

        jQuery('.play').on('click',function(){
            owl.trigger('autoplay.play.owl',[1000])
        });
        jQuery('.stop').on('click',function(){
            owl.trigger('autoplay.stop.owl')
        });
    });

  console.log(simplur_startPosition);
