$(function(){
	$('.about-block .item .name').click(function() {
		$(this).toggleClass('active');
		$(this).next('.text').slideToggle();
	});
	$('.item-page .faq-item .name').click(function() {
		$(this).toggleClass('active');
		$(this).next('.text').slideToggle();
	});
	$('.menubg, .mobile-menu .close').click(function() {
		$('.menubg').fadeOut();
		$('.mobile-menu').removeClass('opened');
	});
	$('.header .menu-button').click(function() {
		$('.menubg').fadeIn();
		$('.mobile-menu').addClass('opened');
	});
	$('.index-brands-block .bottom-link').click(function() {
		$(this).hide();
		$('.index-brands-block .list .list:first-child').show();
	});
	$('.index-services-block .list').slick({
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
	      breakpoint: 768,
	      settings: {
	        slidesToShow: 1
	      }
	    },
	    {
	      breakpoint: 576,
	      settings: {
	        variableWidth: true
	      }
	    }
	  ]
	});
	$('.index-catalog-block .list').slick({
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
	      breakpoint: 768,
	      settings: {
	        slidesToShow: 1
	      }
	    },
	    {
	      breakpoint: 576,
	      settings: {
	        variableWidth: true
	      }
	    }
	  ]
	});
	$('.index-catalog-block2 .list').slick({
	  arrows: false,
	  speed: 300,
	  slidesToShow: 3,
	  slidesToScroll: 1,
	  responsive: [
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
	    },
	    {
	      breakpoint: 576,
	      settings: {
	        variableWidth: true
	      }
	    }
	  ]
	});
	$('.advantages-block .list').slick({
	  arrows: false,
	  variableWidth: true
	});
	$('.slider-for').slick({
	  slidesToShow: 1,
	  slidesToScroll: 1,
	  arrows: false,
	  asNavFor: '.slider-nav',
	  responsive: [
	    {
	      breakpoint: 576,
	      settings: {
	        variableWidth: true
	      }
	    }
	  ]
	});
	$('.slider-nav').slick({
	  slidesToShow: 3,
	  slidesToScroll: 1,
	  asNavFor: '.slider-for',
	  arrows: false,
	  focusOnSelect: true
	});
	$('.index-catalog-block .list2').slick({
	  speed: 300,
	  arrows: false,
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
	        variableWidth: true
	      }
	    }
	  ]
	});
	if ($(window).width() < 576) {
		$('.item-page .banks .slider').slick({
			arrows: false,
			variableWidth: true
		});
	}
	$('.about-block .list').slick({
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
	      breakpoint: 768,
	      settings: {
	        slidesToShow: 1
	      }
	    },
	    {
	      breakpoint: 576,
	      settings: {
	        variableWidth: true
	      }
	    }
	  ]
	});
});