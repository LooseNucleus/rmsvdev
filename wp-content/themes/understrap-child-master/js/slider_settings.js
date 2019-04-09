
    jQuery(document).ready(function($) {
        var owl = $('#slides');
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
            autoplay:true,
            autoplayTimeout:5000,
            animateOut: 'fadeOut',
            animateIn: 'fadeIn',
            nav: true,
            navText: ["<i class='fa fa-chevron-left'></i>","<i class='fa fa-chevron-right'></i>"],
            dots: false,
            autoplayHoverPause:true,
            margin:40
        });

        jQuery('.play').on('click',function(){
            owl.trigger('autoplay.play.owl',[1000])
        });
        jQuery('.stop').on('click',function(){
            owl.trigger('autoplay.stop.owl')
        });
    });
