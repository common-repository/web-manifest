(function($){
"use strict";
	
	// URL parts
	$('#start-url, #url-query, #url-fragment').on('change keyup', function(){
		
		var urlBase		=	$('#start-url').val(),
			urlQuery	=	( $('#url-query').val().trim() != '' ) ? '?' + $('#url-query').val().trim() : '',
			urlFragment	=	( $('#url-fragment').val().trim() != '' ) ? '#' + $('#url-fragment').val().trim() : '';
		
		var fullURL = urlBase + urlQuery + urlFragment;
		
		$('#fwebmanifest_start_url').val(fullURL);
		
	});
	
	
	// Color pickers
	$('#fwebmanifest_bgcolor, #fwebmanifest_themecolor').wpColorPicker({
		change	:	update_preview
	});
	
	
	// Reset to default settings
	$('#reset').on('click', function(){
		
		if( confirm(fwmResetMsg) ){
			
			var form = $(this).parents('form');
			
			for( var key in fwmDefaults ){
				var field = form.find('#' + key);
				if( field.length > 0 ) field.val( fwmDefaults[key] );
			}
			
			$('#submit').click();
			
		}
		
		return false;
		
	});
	
	
	// Live preview
		
		// Get options
		var fwmOptions;
		function get_options(){
			
			fwmOptions = {
				name			:	$('#fwebmanifest_name').val(),
				short_name		:	( $('#fwebmanifest_short_name').val().trim() != '' ) ? $('#fwebmanifest_short_name').val() : $('#fwebmanifest_name').val(),
				description		:	$('#fwebmanifest_description').val(),
				lang			:	$('#fwebmanifest_lang').val(),
				dir				:	$('#fwebmanifest_dir').val(),
				icon			:	( $('#fwebmanifest_icons img').length > 0 ) ? $('#fwebmanifest_icons img').attr('src') : null,
				start_url		:	$('#fwebmanifest_start_url').val(),
				display			:	$('#fwebmanifest_display').val(),
				orientation		:	$('#fwebmanifest_orientation').val(),
				bg_color		:	$('#fwebmanifest_bgcolor').val(),
				theme_color		:	$('#fwebmanifest_themecolor').val()
			}
			
		}
		
		// Update preview
		function update_preview(){
			
			get_options();
			
			var preview			=	$('#fwm-preview'),
				screen			=	preview.find('.window');
			
			var statusBar		=	preview.find('.status-bar'),
				navigationBar	=	preview.find('.navigation-bar'),
				browserBar		=	preview.find('.browser-bar');
			
			var bookmark		=	$('#bookmark'),
				bookmarkIcon	=	$('#bookmark .icon'),
				bookmarkName	=	$('#bookmark .name');
			
			// Clear mockups
			$('#fwm-preview .window').empty();
				
				// name
				screen.prepend('<div class="name">' + fwmOptions.name + '</div>');
				
				// icon
				if( fwmOptions.icon ) screen.prepend('<div class="icon"><img src="' + fwmOptions.icon + '" /></div>');
				
				// bg_color
				screen.css('backgroundColor', fwmOptions.bg_color);
				
				// theme_color
				statusBar.css('backgroundColor', fwmOptions.theme_color);
				
				// display
				switch( fwmOptions.display ){
					
					case 'fullscreen' :
						statusBar.hide();
						browserBar.hide();
						navigationBar.hide();
					break;
					
					case 'standalone' :
						statusBar.show();
						browserBar.hide();
						navigationBar.show();
					break;
					
					case 'minimal-ui' :
						statusBar.show();
						browserBar.show();
						navigationBar.show();
					break;
					
					case 'browser' :
						statusBar.show();
						browserBar.show();
						navigationBar.show();
					break;
					
				}
				
				// orientation
				( ['landscape', 'landscape-primary', 'landscape-secondary'].indexOf(fwmOptions.orientation) >= 0 ) ? preview.addClass('landscape') : preview.removeClass('landscape');
				( ['portrait-secondary', 'landscape-secondary'].indexOf(fwmOptions.orientation) >= 0 ) ? preview.addClass('secondary') : preview.removeClass('secondary');
				
				// start_url
				var urlFragment	=	( fwmOptions.start_url.split('#').length == 2 ) ? '#' + fwmOptions.start_url.split('#')[1] : '',
					urlPreview	=	fwmOptions.start_url.replace(/\?.*$|#.*$/, '') + '?fwm-preview' + urlFragment;
				
				preview.find('.preview-site .window').append('<div class="frame"><iframe src="' + urlPreview + '" scrolling="no"></iframe></div>');
				browserBar.children('.address-bar').text( fwmOptions.start_url.replace(/^http(s)?:\/\/(www\.)?/, '') );
				
			// Bookmark
			bookmarkIcon.empty();
			
			if( fwmOptions.icon ) bookmarkIcon.css('backgroundColor', 'transparent').append('<img src="' + fwmOptions.icon + '" />');
			else bookmarkIcon.css('backgroundColor', '#008ec2').text( fwmOptions.start_url.replace(/^http(s)?:\/\/(www\.)?/, '').slice(0, 1) );
			
			bookmarkName.text( fwmOptions.short_name.slice(0, 10).trim() );
			
			// Show preview
			if( !preview.is(':visible') ) preview.show();
			
		}
		
		update_preview();
		$('input, select').on('change keyup', update_preview);
		
		// Switch preview mockup
		$('#fwm-preview .tab').on('click', function(){
			
			var tab		=	$(this),
				target	=	$(this).data('for');
			
			if( !tab.hasClass('button-primary') ){
				tab.addClass('button-primary').siblings().removeClass('button-primary');
				$('#fwm-preview .mockup.' + target).show().siblings('.mockup').hide();
			}
			
			return false
			
		});
	
})(jQuery);