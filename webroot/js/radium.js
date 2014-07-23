var Radium = new function ($) {
	"use strict";

	/**
	 * Initialise the ArkAdmin theme
	 * @param charts
	 */
	this.init = function (charts) {
		initJsExtensions();
		initControls();
		// initDatePickers();
		initMenus();
		initDataswitch();
		initRTE();
		// //init code highlighter
		// if (typeof prettyPrint === "function"){
		// 	prettyPrint();
		// }
		// initCharts(charts);
		updateContentHeight();
		$( window ).resize(function (){
			updateContentHeight();
		});
		initDatetimes();
		initScrollEventHandler();
	};

	/**
	 * Update the height of the content to match the window size
	 */
	function updateContentHeight(){
		var windowHeight = $(window).height();
		var navHeight = $('.navbar-main').height();
		$('.content').css('min-height', (windowHeight) + "px");
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
		$('textarea').autoResize({
			maxHeight: 500
		});
		// set up tooltips
		$('[data-toggle="tooltip"]').tooltip();
		// set up checkbox/radiobox styles
		$("input:checkbox:visible, input:radio:visible").uniform();
		// set up select2
		$('select').select2({
			placeholder: "- choose one -",
			allowClear: true
		});

		$('input[name~="slug"]').slugify('input[name~="name"]');
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
	function initRTE() {
		$('.rte').trumbowyg();
	}

	function initDatetimes() {
		$("[data-datetime]").each(function(){
			var $this = $(this),
				date = $this.data('datetime'),
				valid = ((date != undefined && date > 0)
					|| (date != undefined && typeof(date)=='string' && date.length > 0) );
			if (!valid) {
				return;
			}
			var mom = 0;
			if (typeof(date)=='number') {
				mom = moment(date*1000);
			} else {
				mom = moment(date);
			}
			$this.html(mom.fromNow());
		});
	}

	function initJsExtensions() {
		String.prototype.allReplace = function(obj) {
			var retStr = this
			for (var x in obj) {
				retStr = retStr.replace(new RegExp(x, 'g'), obj[x])
			}
			return retStr;
		};
	}

	function initScrollEventHandler() {
		$(window).on('scroll', function(e) {
			if ($(this).scrollTop() > $('.navbar-static-top').height()) {
				$('.actions.btn-group').css({
					position: 'fixed',
					top: 0,
					right: 20,
					zIndex: 1
				});
			} else {
				$('.actions.btn-group').css({
					position: 'static'
				});
			}
		});
	}

}(jQuery);

/**
 * Radium Init method
 */
jQuery(function () {
	"use strict";
	Radium.init();
});
