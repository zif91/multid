
$(document).ready(function() {

  const scrollY = document.body.style.top;
  document.body.style.position = '';
  document.body.style.top = '';
  window.scrollTo(0, parseInt(scrollY || '0') * -1);

	let popup = {};
	
	popup.class = {
		overlay: '.overlay',
		show: '_show'
	};
	
	popup.getScrollbarWidth = function() {
		if ($(document).height() <= $(window).height()) {
			return 0;
		}
		
		let outer = document.createElement('div');
		let inner = document.createElement('div');
		let widthNoScroll;
		let widthWithScroll;
		
		outer.style.visibility = 'hidden';
		outer.style.width = '100px';
		document.body.appendChild(outer);
		widthNoScroll = outer.offsetWidth;
		outer.style.overflow = 'scroll';
		inner.style.width = '100%';
		outer.appendChild(inner);
		widthWithScroll = inner.offsetWidth;
		outer.parentNode.removeChild(outer);
		
		return widthNoScroll - widthWithScroll;
	};
	
	popup.lockScreen = function() {	
		let body = $('body');
		let lockedClass = 'is-locked';
		let paddingRight;
		
		if (!body.hasClass(lockedClass)) {
			body = $(document.body);
			paddingRight = parseInt(body.css('padding-right'), 10) + popup.getScrollbarWidth();
			body.css('padding-right', paddingRight + 'px');
			body.addClass(lockedClass);
		}
	};
	
	popup.unlockScreen = function() {
		let body = $('body');
		let lockedClass = 'is-locked';
		let paddingRight;
		
		if (body.hasClass(lockedClass)) {
			body = $(document.body);
			paddingRight = parseInt(body.css('padding-right'), 10) - popup.getScrollbarWidth();
			body.css('padding-right', paddingRight + 'px');
			body.removeClass(lockedClass);
		}
	};
	
	popup.show = function(id) {
		let current = $('[data-popup-id="' + id + '"]');
		if (!current.hasClass(popup.class.show)) {
			popup.lockScreen();
	
			$('[data-popup-id]').removeClass(popup.class.show);
			$(popup.class.overlay).fadeIn(300);
			
			setTimeout(function(){
				current.addClass(popup.class.show);
			}, 200);
		}
	};
	
	popup.hide = function(id) {
		$('[data-popup-id="' + id + '"]').removeClass(popup.class.show);
		$(popup.class.overlay).fadeOut(200);
		popup.unlockScreen();
	};
	
	
		$(document).on('click', '[data-popup-target]', function(e) {
      e.preventDefault();
			popup.show($(this).data('popup-target'));
			return false;
		});
	
		$(document).on('click', '[data-popup-close]', function() {
			popup.hide($('.popup.' + popup.class.show).data('popup-id'));
			return false;
		});
	
		$(document).on('click', popup.class.overlay, function() {
			popup.hide($('.popup.' + popup.class.show).data('popup-id'));
			return false;
		});
	
	});