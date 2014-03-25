var Radium = new function ($) {
	"use strict";

	/**
	 * Initialise the ArkAdmin theme
	 * @param charts
	 */
	this.init = function (charts) {
		initControls();
		// initDatePickers();
		initMenus();
		initDataswitch();
		// //init code highlighter
		// if (typeof prettyPrint === "function"){
		// 	prettyPrint();
		// }
		// initCharts(charts);
		updateContentHeight();
		$('body').resize(function (){
			updateContentHeight();
		});
	};

	/**
	 * Update the height of the content to match the window size
	 */
	function updateContentHeight(){
		var windowHeight = $(window).height();
		var navHeight = $('.navbar-main').height();
		$('.content').css('min-height', (windowHeight - navHeight-1) + "px");
	}


	/**
	 * Init toggle open menu functionality
	 */
	function initMenus() {
		function toggleMenu($menu){
			$menu.toggleClass('open');
		}
		$(document).on('click', '.menu .menu-toggle', function (event){
			event.preventDefault();
			toggleMenu($(this).parents('.menu').first());
		});
	}

	/**
	 * Init the form controls and other input functionality
	 */
	function initControls() {
		// set up textarea autosize
		$('textarea').autosize();
		// set up tooltips
		$('[data-toggle="tooltip"]').tooltip();
		// set up checkbox/radiobox styles
		$("input:checkbox:visible, input:radio:visible").uniform();
		// set up select2
		$('select').select2();
	}

	function initDataswitch() {
		$('body').on('change', '[data-switch]', function(e){
			var name = $(this).attr('data-switch'),
				val = $(this).val(),
				target = name + '_' + val;

			$('[class*="' + name + '_"]').hide();
			if (this.value !== '') {
				$('div.'+target).show();
			}
		});
		$('[data-switch]').trigger('change');
	}

}(jQuery);

/**
 * Radium Init method
 */
jQuery(function () {
	"use strict";
	Radium.init();
});