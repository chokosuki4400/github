$(function(){

	var Menu = {

		el: {
			ham: $('.menu_nav__icon'),
			menuTop: $('.menu__bar-top'),
			menuMiddle: $('.menu__bar-middle'),
			menuBottom: $('.menu__bar-bottom'),
			menuOverlay: $('.global_nav_overlay')
		},

		init: function() {
			Menu.bindUIactions();
		},

		bindUIactions: function() {
			Menu.el.ham.on('click',function(event) {
				Menu.activateMenu(event);
				event.preventDefault();
			});
		},

		activateMenu: function() {
			Menu.el.ham.toggleClass('close');
			Menu.el.menuTop.toggleClass('menu__bar-top-click');
			Menu.el.menuMiddle.toggleClass('menu__bar-middle-click');
			Menu.el.menuBottom.toggleClass('menu__bar-bottom-click');
			Menu.el.menuOverlay.toggleClass('active');
		}
	};

	Menu.init();

});


