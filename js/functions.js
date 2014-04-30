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

function statusDesc(status)
{
	switch (status.code) {
		case 0:
			return 'Новая игра!';
		break;
		
		case 1:
			return 'Сделайте ставку';
		break;
	
		case 2:
			return 'Сдаю карты';
		break;
	
		case 3:
			return 'Дилер раздаёт себе';
		break;
	
		case 4:
			return '';
		break;
	
		case 5:
			return '';
		break;
	
		case 6:
			return '';
		break;
	
		case 7:
			return '';
		break;
	}
}