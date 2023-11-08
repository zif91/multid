$(function(){
	$('.item-page .complect .complect-name').click(function() {
		$(this).toggleClass('active');
		$(this).next('.complect-info').slideToggle();
	});
	$('.menubg').click(function() {
		$('.header .menu-button').removeClass('active');
		$('.menubg').fadeOut();
		$('.header ul').removeClass('opened');
	});
	$('.header .menu-button').click(function() {
		$('.header .menu-button').addClass('active');
		$('.menubg').fadeIn();
		$('.header ul').addClass('opened');
	});
	$('.index-catalog-block .list').slick({
	  speed: 300,
	  slidesToShow: 4,
	  slidesToScroll: 1,
	  responsive: [
	    {
	      breakpoint: 992,
	      settings: {
	        slidesToShow: 3
	      }
	    },
	    {
	      breakpoint: 768,
	      settings: {
	        slidesToShow: 2
	      }
	    },
	    {
	      breakpoint: 576,
	      settings: {
	        slidesToShow: 1
	      }
	    }
	  ]
	});
});
// $( function() {
// 	$( "#slider-range-max2" ).slider({
// 	  range: "max",
// 	  min: 1,
// 	  max: 5,
// 	  value: 2,
// 	  slide: function( event, ui ) {
// 		$( "#amount2" ).val( ui.value + " год(a)/лет");
// 	  }
// 	});
// 	$( "#amount2" ).val( $( "#slider-range-max2" ).slider( "value") + " год(a)/лет"  );
	
	
//   } );


//   $( function() {
// 	$( "#slider-range-max" ).slider({
// 	  range: "max",
// 	  min: 0,
// 	  max: 1000000,
// 	  value: 250000,
// 	  step: 1000,
// 	  slide: function( event, ui ) {
// 		$( "#amount" ).val( ui.value + " ₽");
// 	  }
// 	});
// 	$( "#amount" ).val( $( "#slider-range-max" ).slider( "value") + " ₽"  );
//   } );

