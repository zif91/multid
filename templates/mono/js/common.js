$(function() {
	$('.item-data-block2 .item .name').click(function() {
		$(this).toggleClass('active');
		$(this).next('.text').slideToggle();
	});
	$('.index-page-slider').slick({
		arrows: false,
		dots: true
	});
	$('.choise-block .images').slick({
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
		slidesToShow: 3,
		slidesToScroll: 1,
		asNavFor: '.slider-for',
		arrows: false,
		focusOnSelect: true
	});
});
$(document).ready(function() {
  if ($('form.form-calculate').length) { // check if form is present
    var handle1 = $("#custom-handle");
    var min1 = $('div[data-min]').data('min');
    var max1 = $('div[data-max]').data('max');
    var $slider1 = $("#slider");
    var $slider2 = $("#slider2");
    var handle2 = $("#custom-handle2");

    $slider1.slider({
      min: min1,
      max: max1,
      create: function() {
        handle1.text($(this).slider("value") + " руб.");
      },
      slide: function(event, ui) {
        handle1.text(ui.value + " руб.");
        var newAmount = Math.round((max1 - ui.value) / $slider2.slider("value"));
        $('.amount').text(newAmount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") + " руб.");
      }
    });

    $slider2.slider({
      min: 12,
      max: 64,
      create: function() {
        handle2.text($(this).slider("value") + " мес.");
      },
      slide: function(event, ui) {
        handle2.text(ui.value + " мес.");
        var newAmount = Math.round((max1 - $slider1.slider("value")) / ui.value);
        $('.amount').text(newAmount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") + " руб.");
      }
    });
  }
});


