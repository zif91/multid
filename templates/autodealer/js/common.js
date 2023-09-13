jQuery(function($){
	$(document).mouseup( function(e){
		var div = $( ".top-menu .catalog" );
		if ( !div.is(e.target)
		    && div.has(e.target).length === 0 ) {
			div.hide();
		}
	});
});

$(function(){
	const link = document.getElementById("float-button");

// добавляем слушатель события 'click'
link.addEventListener('click', (event) => {
  // предотвращаем переход по ссылке по умолчанию
  event.preventDefault();
  // вызываем функцию, которая показывает модальное окно
  $('#callphone').modal('show');
});
	$('.top-menu .menu-button').click(function() {
		$('.top-menu .catalog').show();
	});
	$('.mobile-menu .close').click(function() {
		$('.mobile-menu').removeClass('opened');
	});

	$('.mobile-header .menu-button').click(function() {
		$('.mobile-menu').addClass('opened');
	});
    $('.minus').click(function () {
        var $input = $(this).parent().find('input');
        var count = parseInt($input.val()) - 1;
        count = count < 1 ? 1 : count;
        $input.val(count);
        $input.change();
        return false;
    });
    $('.plus').click(function () {
        var $input = $(this).parent().find('input');
        $input.val(parseInt($input.val()) + 1);
        $input.change();
        return false;
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