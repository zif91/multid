$(function(){
	$('.faq-block .name').click(function() {
		$(this).toggleClass('active');
		$(this).next('.text').slideToggle();
	});
	$('.mobile-menu .close').click(function() {
		$('.mobile-menu').removeClass('opened');
	});
	$('.header .menu-button').click(function() {
		$('.mobile-menu').addClass('opened');
	});
	$('.index-actions-block .list').slick({
	  dots: true,
	  arrows: false,
	  speed: 300,
	  slidesToShow: 3,
	  slidesToScroll: 1,
	  responsive: [
	    {
	      breakpoint: 992,
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
	$('.slider-for').slick({
	  slidesToShow: 1,
	  slidesToScroll: 1,
	  arrows: false,
	  asNavFor: '.slider-nav'
	});
	$('.slider-nav').slick({
	  slidesToShow: 4,
	  slidesToScroll: 1,
	  asNavFor: '.slider-for',
	  arrows: false,
	  focusOnSelect: true
	});
});
$('.partners-block .list').slick({
    speed: 300,
    slidesToShow: 6,
    slidesToScroll: 1,
    responsive: [
        {
            breakpoint: 1200,
            settings: {
                slidesToShow: 5
            }
        },
        {
            breakpoint: 992,
            settings: {
                slidesToShow: 4
            }
        },
        {
            breakpoint: 768,
            settings: {
                slidesToShow: 3
            }
        },
        {
            breakpoint: 576,
            settings: {
                slidesToShow: 2
            }
        }
    ]
});
$('.other-actions-block .list').slick({
    arrows: false,
    variableWidth: true
});
$(window).on('load', function() {

	$('#brand').on('change', function() {
		var selected = $(this).val();
		$("#model option").each(function(item) {
			console.log(selected);
			var element = $(this);
			console.log(element.data("tag"));
			if (element.data("tag") != selected) {
				element.hide();
			} else {
				element.show();
			}
		});

		$("#model").val($("#model option:visible:first").val());

	});
	// code here
});
