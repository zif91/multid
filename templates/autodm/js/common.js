$(function() {
	// получаем ссылку по селектору
const link = document.getElementById("float-button");

// добавляем слушатель события 'click'
link.addEventListener('click', (event) => {
  // предотвращаем переход по ссылке по умолчанию
  event.preventDefault();
  // вызываем функцию, которая показывает модальное окно
  $('#callphone').modal('show');
});

	$('.footer nav p').click(function() {
		$(this).next('ul').toggleClass('opened');
	});
	$('.menubg').click(function() {
		$('.menubg').fadeOut();
		$('.header ul').removeClass('opened');
	});
	$('.header .menu-button').click(function() {
		$('.menubg').fadeIn();
		$('.header ul').addClass('opened');
	});
	$('.slider-for').slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		asNavFor: '.slider-nav',
		responsive: [{
			breakpoint: 576,
			settings: {
				arrows: false,
				dots: true
			}
		}]
	});
	$('.slider-nav').slick({
		slidesToShow: 4,
		slidesToScroll: 3,
		asNavFor: '.slider-for',
		arrows: true,
		focusOnSelect: true
	});
	/*if ($(window).width() < 576) {
		$('.catalog-block .list').slick({
			dots: true
		});
	}*/
	if ($(window).width() < 576) {
		$('.compilations-block .row').slick({
			arrows: false,
			dots: true
		});
	}
	if ($(window).width() < 576) {
		$('.services-block .row').slick({
			arrows: false,
			dots: true,
			adaptiveHeight: true
		});
	}
	$('.articles-block .list').slick({
		dots: true,
		arrows: false,
		speed: 300,
		slidesToShow: 3,
		slidesToScroll: 1,
		responsive: [{
				breakpoint: 1510,
				settings: {
					slidesToShow: 2
				}
			},
			{
				breakpoint: 992,
				settings: {
					slidesToShow: 1
				}
			}
		]
	});
});

$(document).ready(function() {
  var amountInput = $('#amount');
  var amount2Input = $('#amount2');
  var paymentSpan = $('#payment');
  var priceDiv = $('.price');
  var price = parseFloat(priceDiv.text().replace(/\s+/g, '').replace(/₽/, ''));

  // Add a slider for the car price using the .price element as the maximum value
  $( "#slider-range-max" ).slider({
    range: "max",
    min: price/40,
    max: price,
    value: 500000,
    slide: function( event, ui ) {
      $( "#amount" ).val( ui.value.toLocaleString('ru-RU') + " р.");
      updatePayment();
    }
  });
  $( "#amount" ).val( $( "#slider-range-max" ).slider( "value" ).toLocaleString('ru-RU') + " р." );

$( "#slider-range-max2" ).slider({
    range: "max",
    min: 12,
    max: 88,
    value: 88, // задаем значение по умолчанию
    slide: function( event, ui ) {
      $( "#amount2" ).val( ui.value + " месяцев");
      updatePayment();
    }
});

// устанавливаем значение по умолчанию в поле "Срок кредита"
function updatePayment() {
  var amountInput = $("#amount");
  if (amountInput.length > 0 && typeof amountInput.val() !== "undefined") {
    var amount = parseFloat(amountInput.val().replace(/\D/g, '')) || 0;
    var amount2Input = $("#amount2");
    var paymentSpan = $("#payment");
    var amount2 = parseFloat(amount2Input.val()) || 0;
    var coefficient = 1.24;
    
    // Добавьте проверку, чтобы избежать деления на ноль
    if (amount2 === 0) {
      paymentSpan.text('0 р/мес.');
    } else {
      var payment = (amount) / amount2 / 12 * coefficient;
      paymentSpan.text(payment.toLocaleString('ru-RU', { maximumFractionDigits: 0 }) + ' р/мес.');
    }
  }
}



  // Call the updatePayment function when the page loads and whenever the inputs change
  updatePayment();
  amountInput.on('input', updatePayment);
  amount2Input.on('change', updatePayment);
});


//SHORTENED CODE

