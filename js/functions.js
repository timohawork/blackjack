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
	switch (parseInt(status.code)) {
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
			return 'Дилер раздаёт вам';
		break;
	
		case 5:
			return '<a id="getCard" href="#">Ещё</a> или <a id="enough" href="#">хватит</a>?';
		break;
	
		case 6:
			return 'Дилер думает';
		break;
	
		case 7:
			return 'Дилер выиграл!';
		break;
	
		case 8:
			return 'Вы виграли!<a id="next" href="#">Играть дальше</a>';
		break;
	}
}