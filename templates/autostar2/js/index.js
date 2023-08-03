//window.bootstrap = require('bootstrap');

//new window.bootstrap.Popover(document.querySelector('.map-marker'), {
//    trigger: 'focus',
//});

//document.querySelectorAll('.phone-imask').forEach((element) => {
//    IMask(element, {
//        mask: '+{7} (000) 000-00-00',
//    });
//});

document.querySelectorAll('.amount-imask').forEach((element) => {
    IMask(element, {
        mask: '₽ amount',
        blocks: {
            amount: {
                mask: Number,
                min: 0,
                max: 100000000,
                thousandsSeparator: ' ',
            },
        },
    });
});

document.querySelectorAll('.percent-imask').forEach((element) => {
    IMask(element, {
        mask: '% percent',
        blocks: {
            percent: {
                mask: Number,
                min: 0,
                max: 101,
            },
        },
    });
});

(() => {
    const slideItems = document.querySelectorAll('.slide-items .slide-item');

    const observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            const elementMutation = mutation.target;
            const { id } = elementMutation.dataset;

            slideItems.forEach((element) => {
                if (element.dataset.id == id) {
                    if (elementMutation.classList.contains('active')) {
                        element.classList.add('active');
                    } else {
                        element.classList.remove('active');
                    }
                }
            });
        });
    });

    document.querySelectorAll('.slide-cars .carousel-item').forEach((element) => {
        observer.observe(element, { attributes: true });
    });

    slideItems.forEach((element, index) => {
        element.addEventListener('click', () => {
            const carousel = window.bootstrap.Carousel.getInstance('.slide-cars');

            carousel.to(index);
        });
    });
})();
