(function($) {

	$.extend({
		loadScript : function(url) {
			var script = document.createElement('script');
			script.type = 'text/javascript';
			script.src = url;
			var head = document.getElementsByTagName('head')[0];
			head.appendChild(script);
		},
		loadStyle : function(url) {
			var link = document.createElement('link');
			link.rel = 'stylesheet';
			link.type = 'text/css';
			link.href = url;
			var head = document.getElementsByTagName('head')[0];
			head.appendChild(link);
		},
		configuredSkins : [ 'Ocean', 'Orange' ],
		defaultSkin : 'Ocean',
		_currentSkin : 'Ocean',
		setCurrentSkin : function(skin) {
			this._currentSkin = skin;
		},
		getCurrentSkin : function() {
			return this._currentSkin;
		}
	});
	
	//init skin
	var skins = $.configuredSkins;
	var defaultSkin = $.defaultSkin;
	var selectedSkin = '';

	var skin = $.cookie('DTBKSKINCOOKIE');
	if (typeof skin == 'string') {
		for ( var item in skins) {
			if (skin == skins[item]) {
				selectedSkin = skins[item];
				break;
			}
		}
	}
	if (selectedSkin == '') {
		selectedSkin = defaultSkin;
		$.cookie('DTBKSKINCOOKIE', defaultSkin);
	}
	$.setCurrentSkin(selectedSkin);
	$.loadStyle(baseUrl + '/js/mikoUI/skins/' + selectedSkin + '/css/mikoui-all.css');

})(jQuery);