$(function() {
	$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
		$('.index-catalog-block .list').slick('setPosition');
	})
	$('.index-calc-block .bottom-link').click(function() {
		$(this).addClass('hide');
		$('.index-calc-block .row .col-lg-3').show();
	});
	$('.menubg').click(function() {
		$('.menubg').fadeOut();
		$('.header ul').removeClass('opened');
	});
	$('.header .menu-button').click(function() {
		$('.menubg').fadeIn();
		$('.header ul').addClass('opened');
	});
	$('.footer .totop').bind("click", function(e) {
		var anchor = $(this);
		$('html, body').stop().animate({
			scrollTop: $(anchor.attr('href')).offset().top
		}, 1000);
		e.preventDefault();
	});

	$('.index-catalog-block .tabs-nav li a').click(function() {
		$('.index-catalog-block .tabs-nav li a').removeClass('active');
	});
	$('.index-catalog-block .list').slick({
		arrows: false,
		speed: 300,
		slidesToShow: 3,
		slidesToScroll: 1,
		responsive: [{
				breakpoint: 1200,
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
	$('.slider-for').slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		arrows: false,
		asNavFor: '.slider-nav'
	});
	$('.slider-nav').slick({
		variableWidth: true,
		slidesToScroll: 1,
		asNavFor: '.slider-for',
		focusOnSelect: true
	});
});
$(function() {
	var $myDom = $('#monthes');
	if ($myDom.length > 0) {
		var firstpaymentInput = document.getElementById('firstpayment');
		var monthesInput = document.getElementById('monthes');
		var monthlyInput = document.getElementById('monthly');
		var carPriceInput = document.getElementById('car-price');

		// Set default value for the firstpayment input
		if (carPriceInput) {
			var carPrice = parseFloat(carPriceInput.textContent.replace(/[^\d\.]+/g, ''));
			var defaultFirstPayment = (carPrice / 15).toFixed(0);
			firstpaymentInput.value = formatNumberWithSpaces(defaultFirstPayment) + ' ₽';
		}

		// Set default value for the monthes input
		monthesInput.value = '12 месяцев';

		// Add event listeners to the input elements
		firstpaymentInput.addEventListener('input', handleFirstpaymentInput);
		firstpaymentInput.addEventListener('blur', handleFirstpaymentBlur);
		monthesInput.addEventListener('input', calculateMonthlyWithDelay);

		// Function to format a number with spaces between digits
		function formatNumberWithSpaces(number) {
			return number.toLocaleString('ru-RU', { useGrouping: true }).replace(',', '.');
		}

		// Function to handle the firstpayment input
		function handleFirstpaymentInput() {
			if (firstpaymentInput.value.match(/₽/)) {
				firstpaymentInput.value = '';
			}
		}

		// Function to handle the firstpayment input blur event
		function handleFirstpaymentBlur() {
			var firstpayment = parseFloat(firstpaymentInput.value.replace(/[^0-9.,\s]+/g, '').replace(',', '.'));
			firstpaymentInput.value = formatNumberWithSpaces(firstpayment) + ' ₽';
			calculateMonthly();
		}

		// Function to calculate the monthly value with a delay
		var timeoutId;

		function calculateMonthlyWithDelay() {
			clearTimeout(timeoutId);
			timeoutId = setTimeout(calculateMonthly, 200);
		}

		// Function to calculate the monthly value
		function calculateMonthly() {
			// Get the values from the input elements
			var firstpayment = parseFloat(firstpaymentInput.value.replace(/[^0-9.,\s]+/g, '').replace(',', '.'));
			var monthes = parseInt(monthesInput.value.replace(/[^\d]+/g, ''));
			var carPrice = carPriceInput ? parseFloat(carPriceInput.textContent.replace(/[^\d\.]+/g, '')) : 0;

			// Calculate the monthly value
			var monthly = ((carPrice - firstpayment) / monthes).toFixed(0);

			// Update the monthly input element
			monthlyInput.value = formatNumberWithSpaces(monthly) + ' ₽';
		}
	} else {
		console.log('nothing');
	}
});
/*
const tradeinButton = document.getElementById('tradeinbutton');
const tradeinModal = new bootstrap.Modal(document.getElementById('tradein'));

tradeinButton.addEventListener('click', function() {
  tradeinModal.show();
});
*/
// Get references to the input elements
$(window).on('load', function() {
	$(".bottom-page-line .close").click(function() {
		$(".bottom-page-line").hide();
	});
	$(".bottom-page-line").click(function() {
		$("#exampleModal").modal("show");
	});

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

function openModal() {
	$('#exampleModal').modal('show');
}
