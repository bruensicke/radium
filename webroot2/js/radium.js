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
		initTableSelect();
		initMenus();
		initDataswitch();
		initConfirm();
		initAjaxLinks();
		initRTE();
		// //init code highlighter
		// if (typeof prettyPrint === "function"){
		// 	prettyPrint();
		// }
		// initCharts(charts);
		initSearchForm();
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
	function initTableSelect() {
		function toggleSelectedRow($row){
			$row.toggleClass('info');
		}
		$(document).on('click', '.table-selectable tr[data-id]', function (e){
			if (e.target.nodeName.toLowerCase() == 'a') {
				return true;
			}
			e.preventDefault();
			toggleSelectedRow($(this));
		});
	}

	/**
	 * Init toggle open menu functionality
	 */
	function initMenus() {
		function toggleMenu($menu){
			$menu.toggleClass('open');
		}
		$(document).on('click', '.menu .menu-toggle', function (e){
			e.preventDefault();
			toggleMenu($(this).parents('.menu').first());
		});
	}

	/**
	 * Init confirm functionality
	 */
	function initConfirm(options) {
		$('[data-confirm]').jBox('Confirm', options || {});
	}

	/**
	 * Init confirm functionality
	 */
	function initAjaxLinks() {
		$('.btn-ajax,[data-ajax]').jBox('Modal', {
			getTitle: 'data-title',
			ajax: {
				getData: 'data-ajax',
			},
			preventDefault: true,
			onOpen: function() {
				this.options.ajax.url = this.source.attr('href');
			}
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

	/**
	 * Init the collapsable search form in index-pages for scaffold-views
	 */
	function initSearchForm(){
		$('.search').on('click', '.btn-search', function(e) {
			e.preventDefault();
			$(this).parents('.search').toggleClass('active');
		});
	}

	function initRTE() {
		$.trumbowyg.svgPath = '/radium/trumbowyg/ui/icons.svg';
		// $('div.rte').trumbowyg();
		var ed = $('div.rte').trumbowyg({
			autogrow: true,
    		btns: [['bold', 'italic'], ['link']]
		});
		ed.on('tbwblur', function(e){
			var div = $(e.target);
			$(div.data('for')).val(div.trumbowyg('html'));
		});
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
