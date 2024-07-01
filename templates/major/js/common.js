$(function(){
	/*$('.item-page .item .link').click(function() {
		$(this).toggleClass('active');
		$(this).parent().toggleClass('opened');
		$(this).parent().find('.top-block').toggle();
		$(this).parent().find('.hidden-block').toggleClass('opened');
	});*/
	$('.brands-block .bottom-link a').click(function() {
		$('.brands-block .list').show();
	});
	$('.dealers-block .button button').click(function() {
		$('.dealers-block .flex .list').show();
	});
	$('.menubg').click(function() {
		$('.menubg').fadeOut();
		// $('.header ul').removeClass('opened');
		// $('.header ul').removeClass('opened');
	});
	$('.header .menu-button').click(function() {
		$('.menubg').fadeIn();
		// $('.header ul').addClass('opened');
	});
	$('.index-page-slider').slick({
		arrows: false,
		dots: true
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
	  focusOnSelect: true
	});
});