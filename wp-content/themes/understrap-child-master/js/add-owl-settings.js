
    jQuery(document).ready(function($) {
        var owl = $('.owl-carousel');
        owl.owlCarousel({
            responsive: {
              0:{
                items:1.5,
              },
              768:{
                items:4,
              }
            },
            center: true,
            loop:true,
            autoplay:false,
            autoplayTimeout:5000,
            animateOut: 'fadeOut',
            animateIn: 'fadeIn',
            nav: true,
            navText: ["<i class='fa fa-chevron-left'></i>","<i class='fa fa-chevron-right'></i>"],
            dots: false,
            autoplayHoverPause:true,
            margin:20,
            startPosition:parseInt(simplur_startPosition),
            URLhashListener: true,
            responsiveClass:true
        });

        jQuery('.play').on('click',function(){
            owl.trigger('autoplay.play.owl',[1000])
        });
        jQuery('.stop').on('click',function(){
            owl.trigger('autoplay.stop.owl')
        });
    });
