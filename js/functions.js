var ajaxSender = new function() {
	this.request = null;
	
	this.ajax = function(options) {
		var settings = $.extend({}, options);
		if ("object" === typeof options) {
			options.beforeSend = function() {
				if ('function' === typeof settings.beforeSend) {
					settings.beforeSend();
				}
			};
			options.success = function(response) {
				if ('function' === typeof settings.success) {
					settings.success(response);
				}
			};
			null != this.request ? this.request.abort() : '';
			this.request = $.ajax(options);
		}
	}
};