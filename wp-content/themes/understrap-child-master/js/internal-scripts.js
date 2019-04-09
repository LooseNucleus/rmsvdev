/* Scroll on buttons */
(function ($) {
$('.js--scroll-to-locations').click(function () {
	 $('html, body').animate({scrollTop: $('.js--section-locations').offset().top}, 1000);
});
}(jQuery));

jQuery(document).ready(function($) {
			$(".portfolio-block .valign-center-elem").css("position", "absolute")
					.css ("top", "50%")
					.css ("left", "50%")
					.css ("-webkit-transform", "translate(-50%, -50%)")
					.css ("transform", "translate(-50%, -50%)")
	}

);

function initMap() {
 var myLatLng = {lat: 34.0, lng: -117.5};

 var map = new google.maps.Map(document.getElementById('map'), {
	 zoom: 8,
	 center: myLatLng,
	 scrollwheel: false,
	 disableDefaultUI: true
 });


 var markers = [
		['Victorville Store', 34.471115,-117.309762, 'Victorville Store'],
		['Huntington Beach Store', 33.743665,-118.008939, 'Huntington Beach Store'],
		['Mission Viejo', 33.59382,-117.659408, 'Mission Viejo Store'],
		['Orange Store', 33.825945,-117.83558, 'Orange Store'],
		['Rancho Santa Margarita Store', 33.635265,-117.606735, 'Rancho Santa Margarita Store'],
		['Corona Store', 33.891752,-117.518369, 'Corona Store'],
		['Temecula Store', 33.51916,-117.155156, 'Temecula Store']
	];


	// Display multiple markers on a map
	var infowindow = new google.maps.InfoWindow(), marker, i;

	for (var i in markers)
			{
					var p = markers[i];
					var latlng = new google.maps.LatLng(p[1], p[2]);


					var marker = new google.maps.Marker({
							position: latlng,
							map: map,
							title: p[0]
					});




		google.maps.event.addListener(marker, 'click', (function(mm, tt) {
			return function() {
					infowindow.setContent(tt);
					infowindow.open(map, mm);
			}
		})(marker, p[3]));
	}


	}

  function sameHeights(selector) {
      var selector = selector || '[data-key="sameHeights"]',
          query = document.querySelectorAll(selector),
          elements = query.length,
          max = 0;
      if (elements) {
          while (elements--) {
              var element = query[elements];
              if (element.clientHeight > max) {
                  max = element.clientHeight;
              }
          }
          elements = query.length;
          while (elements--) {
              var element = query[elements];
              element.style.height = max + 'px';
          }
      }
  }

  if ('addEventListener' in window) {
        window.addEventListener('resize', function(){
            sameHeights();
        });
        window.addEventListener('load', function(){
            sameHeights();
        });
    }
