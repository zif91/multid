// App.test = {
//     data() {
//         return {
//             test: ''
//         }
//     },

//     created() {
//         console.log('Vue ready')
//     }, 

//     methods: {

//     }
// }


$(function() {

    
    
    /* sliders */
    var homeSliderSwiper = new Swiper('.home-slider-swiper', {
    	loop: true,
    	speed: 600,
		navigation: {
			nextEl: '.home-slider .swiper-button-next',
			prevEl: '.home-slider .swiper-button-prev',
		},
		pagination: {
        	el: '.home-slider .swiper-pagination',
        	clickable: true,
      	}
    });

    var productsSlider = new Swiper('.products-slider', {
        slidesPerView: 1,
        speed: 600,
        pagination: {
            el: '.products-slider .swiper-pagination',
            clickable: true
        },
        breakpoints: {
            // when window width is >= 768px
            768: {
                slidesPerView: 2
            },
            // when window width is >= 992px
            992: {
                slidesPerView: 3
            }
        }
    });

    
});