function unset_time_predefined() {
	$('#time_predefined').attr('selectedIndex', 0);
}

function set_user_prefs(page) {
	if ($('#m-content')) {
		$('#m-content').addClass('transparent_class');
	}

	$.post('/ajax/profile/postPrefs', $('#user_prefs').serialize(true), function(data) {
		$('#m-content').html(data);
		loadContent(page);
	});
}

function loadContent(page) {
	$.get(page, function(data) {
		$('#m-content').html(data);
	});
}

function loadView(view,container) {
	$.get(view, function(data) {
		$('#' + container).html(data);
	});
}

$(document).ready(function() {
	$.fn.insertAtCaret = function(myValue) {
		return this.each(function() {
			var me = this;
			if (document.selection) { // IE
				me.focus();
				sel = document.selection.createRange();
				sel.text = myValue;
				me.focus();
			} else if (me.selectionStart || me.selectionStart == '0') { // Real browsers
				var startPos = me.selectionStart,
					endPos = me.selectionEnd,
					scrollTop = me.scrollTop;
				me.value = me.value.substring(0, startPos) + myValue + me.value.substring(endPos, me.value.length);
				me.focus();
				me.selectionStart = startPos + myValue.length;
				me.selectionEnd = startPos + myValue.length;
				me.scrollTop = scrollTop;
			} else {
				me.value += myValue;
				me.focus();
			}
		});
	};
});

function colorizeReportTd(obj,always_neg) {
	if((typeof always_neg !== 'undefined') && (always_neg)){
		if($(obj).text().length > 0) {
			$(obj).addClass('report_neg');
			return;
		}
	}

	if($(obj).text().length > 0) {
		if($(obj).text().indexOf('(') > -1) {
			$(obj).addClass('report_neg');
		}
		else if($(obj).text().indexOf('-') > -1) {
			$(obj).addClass('report_neg');
		}
		else {
			$(obj).addClass('report_pos');
		}
	}
}

function getURLParameter(name) {
    return decodeURI(
        (RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[null])[1]
    );
}