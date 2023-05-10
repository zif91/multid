$(function(){
	$('.index-brands-block .bottom-link a').click(function() {
		$(this).parent().hide();
		$('.index-brands-block .row .col-3').show();
	});
	$('.slider-for').slick({
	  slidesToShow: 1,
	  slidesToScroll: 1,
	  arrows: false,
	  asNavFor: '.slider-nav'
	});
	$('.slider-nav').slick({
	  slidesToShow: 6,
	  slidesToScroll: 1,
	  asNavFor: '.slider-for',
	  arrows: false,
	  focusOnSelect: true,
	  responsive: [
	      {
	        breakpoint: 1200,
	        settings: {
	          slidesToShow: 5
	        }
	      },
	      {
	        breakpoint: 768,
	        settings: {
	          slidesToShow: 4
	        }
	      }
	    ]
	});
});