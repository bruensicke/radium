
jQuery(function () {
	"use strict";
	nestable();
	addNumber();

	var widgetList = $('#list1').html();

	removeNameAttr();
	setEvents();


	$('.refreshWidget').on('click',function() {
		$('#list1').html(widgetList);
		removeNameAttr();
		setEvents();
	});


	function setEvents() {
		$('.config').off('click').on('click', function() {
			$(this).parent().next('.configForm').toggle("slow");
		});
	}

	function removeNameAttr() {
		$.each($('#list1').find(":input"), function(i, val) {
			$(val).data('name',val.name).attr('name','').val('');

		});
	}

	function addNameAttr() {
		$.each($('#list2').find(":input"), function(i, val) {
			$(val).attr('name',$(val).data('name'));
		});
	}

	function addNumber(){
		$.each($('#list2').find("li"), function(i, val) {
				$.each($(val).find(":input"), function(j, input) {
					$(input).attr('name',$(input).attr('name').replace(/[0-9]+/,i));
				});
		});
	}

	/*Nestable Lists*/
	function nestable() {
		$('.dd').nestable();
		//Watch for list changes and show serialized output
		function update_out(selector, sel2){
			var out = $(selector).nestable('serialize');
			$(sel2).html(window.JSON.stringify(out));
		}

		update_out('#list2',"#out2");

		$('#list2').on('change', function() {
			update_out('#list2',"#out2");
			addNameAttr();
			addNumber();
			removeNameAttr();
		});
	};//End of Nestable Lists
});
