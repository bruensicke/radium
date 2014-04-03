var uploader = uploader || {};
uploader.obj = null;
uploader.api = "/import";
uploader.button = '<div><i class="icon-upload-alt icon-white"></i> upload files</div>';
uploader.template = '<div class="qq-uploader">' +
					  '<pre class="qq-upload-drop-area"><span>{dragZoneText}</span></pre>' +
					  '<div class="qq-upload-button btn btn-success" style="width: auto;">{uploadButtonText}</div>' +
					  '<span class="qq-drop-processing"><span>{dropProcessingText}</span><span class="qq-drop-processing-spinner"></span></span>' +
					  '<ul class="qq-upload-list"></ul>' +
					'</div>';
uploader.msg = function(type, text, target) {
	target = typeof target !== 'undefined' ? target : '#uploadResult';
	$(target).html('<div class="alert alert-block block alert-'+type+'" data-alert="alert">'+text+'</div>');
};
uploader.message = function(message) {
	target = typeof target !== 'undefined' ? target : '#uploadResult';
	$(target).append('<div class="alert alert-error">' + message + '</div>');
};
uploader.failed = function(id, file, res) {
	console.log(id);
	console.log(file);
	console.log(res);
	if (res.url !== undefined) {
		$('ul.qq-upload-list li:eq('+id+') .qq-upload-status-text').html('<a href="'+res.url+'">'+res.message+'</a>');
	}
	if (res.error !== undefined) {
		uploader.msg('error', 'import failed: ' + res);
	}
	// if (res.url !== undefined) {
	// 	// message = uploader.importCompleted(res.result);
	// 	message = 'cool';
	// 	uploader.msg('success', 'import completed: ' + message );
	// }
	// console.log(res);
};
uploader.completed = function(id, file, res) {
	// console.log(id);
	// console.log(file);
	// console.log(res);
	if (res.url !== undefined) {
		$('ul.qq-upload-list li:eq('+id+') .qq-upload-status-text').html('<a href="'+res.url+'">'+res.message+'</a>');
	}
	if (res.error !== undefined) {
		uploader.msg('error', 'import failed: ' + res);
	}
	// if (res.url !== undefined) {
	// 	// message = uploader.importCompleted(res.result);
	// 	message = 'cool';
	// 	uploader.msg('success', 'import completed: ' + message );
	// }
	// console.log(res);
};
uploader.importCompleted = function(res) {
	console.log(res)
	var valid = [],
		invalid = [],
		errors = [],
		message = '';
	jQuery.each(res.results, function(index, val) {
		if (val) {
			valid.push(index);
		} else {
			invalid.push(index);
		}
	});
	if (invalid.length) {
		message += '<span class="error">Errors on <strong>'+invalid.length+'</strong> entities.</span>';
		jQuery.each(res.errors, function(index, val) {
			console.log(val);
		});
		console.log(res.errors);
	}
	message += '<span>Saved <strong>'+valid.length+'</strong> entities.</span>';
	return message;
};
uploader.create = function(elem) {
	elem = typeof elem !== 'undefined' ? elem : '#uploader';
	uploader.obj = new qq.FineUploader({
		element: $(elem)[0],
		request: { endpoint: uploader.api },
		text: { uploadButton: uploader.button },
		callbacks: { onComplete: uploader.completed, onError: uploader.failed },
		template: uploader.template,
		classes: {
			success: 'alert alert-success',
			fail: 'alert alert-error'
		},
		showMessage: uploader.message,
		failedUploadTextDisplay: {
			mode: 'custom',
			maxChars: 60,
			responseProperty: 'error',
			enableTooltip: true
		}
	});
};
uploader.init = function() {
	uploader.create();
};
