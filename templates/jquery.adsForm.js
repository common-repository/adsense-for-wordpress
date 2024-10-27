(function($) {
	
	$.fn.adsForm = function(options) {
		var settings = {
			callback: function() { }
		};
		
		if (options) $.extend(settings, options);
		
		var _this = $(this).parent();
		
		/**
		 * 显示面板
		 */
		var showPanel = function() {
			_this.addClass('adsForm_container');
			drawPanel();
		};
		
		/**
		 * 隐藏面板
		 */
		var hidePanel = function() {
			_this.find('.adsForm_panel').remove();
			_this.removeClass('adsForm_container');
		};
		
		/**
		 * 事件绑定
		 */
		var bindingEvent = function() {
			var _panel = _this.find('div.adsForm_panel');
			
			// 关闭按钮
			_this.find('a.btn-close').click(function() {
				// 如果有改动提示用户是否需要保存
				if ( $('input.modify').val() == '1' && confirm("You have unsaved settings, are you sure?\n\nClick OK to save, or click CANCEL to close without saving.") ) {
					_this.find('input.btn-submit').click();
					return false;
				}
				hidePanel();		
			});
			
			// 提交按钮
			_this.find('input.btn-submit').click(function() {
				var panel = _this.find('div.adsForm_panel');
				var hidden = _this.find('div.hidden');
				var dom = new Array('.ad-position', '.ad-alignment', '.ad-type', '.ad-format', '.ad-title', '.ad-custom-title', '.ad-title-hide', '.ad-margin', '.ad-google-id', '.ad-channel-id', '.ad-colors', '.ad-border-style', '.ad-number-post');
				var obj = {};
				
				// 将颜色的5个属性值存放在一个input域里
				var inputTags = _this.find('div.ad-color input');
				if ( inputTags.size() ) {
					for ( var i = 0; i < 5; i++ ) {
						inputTags.eq(5).val( inputTags.eq(5).val() + inputTags.eq(i).val() + ';' );
					}
				}
				
				// 循环查找每个dom组件，如果存在就获取其值，并且设置hidden域的值
				for ( var i = 0; i < dom.length; i++ ) {
					obj = panel.find( dom[i] );
					if ( obj.size() ) {
						value = _getSelectValue( obj );
						hidden.find( dom[i] ).val( value );
					}
				}
				hidePanel();
			});
			
			// 阻止任何地方的回车按键把整个表单都提交了
			_this.find('input').keydown(function(e) {
				if ( e.keyCode == 13 ) return false;
			});
			
			// 显示下拉菜单的默认值，一个被选中显示选中值，多个被选中则显示random
			_this.find('p.select span').each(function() {
				var __this = $(this);
				var parent = __this.parents('div:first');
				var value = parent.find('input.ad-select-value').val();
				
				if ( value.split(';').length == 1 ) {
					__this.text( _this.find('#k_' + value).next().text() );
				} else {
					__this.text('Random');
				}				
			});
			
			// 模拟下拉菜单
			_this.find('p.select').click(function(){
				var __this = $(this);
				var parent = __this.parent();
				var span = __this.find('span');
				
				// if exists, then hide it
				if ( span.hasClass('selected') ) {
					span.removeClass('selected');
					parent.find('ul').hide();
					return false;
				};
				
				// get the initial value
				var array = parent.find('input.ad-select-value').val().split(';');
				
				// 勾选checbox
				parent.find('ul input').removeAttr('checked');
				for ( var i = 0; i < array.length; i++ ) {
					parent.find('#k_' + array[i]).attr('checked', 'checked');
				}
				
				// show the panel
				span.addClass('selected');
				parent.find('ul').show();
			});
			
			// 关闭下拉菜单
			_this.find('li.close a').click(function() {
				var parent = $(this).parents('div:first');
				var span = parent.find('p.select span');
				var array = new Array();
				
				// get all the checkbox value, filter the checked value in an array
				parent.find('ul input').each(function() {
					if ( $(this).attr('checked') ) array.push($(this).val());
				});
				if ( !array.length ) {
					alert('You must select one or more format for this ad');
					return false;
				}
				
				// set the select value for display
				if ( array.length == 1 ) {
					span.text( _this.find('#k_' + array[0]).next().text() );
				} else {
					span.text('Random');
				}
				
				// join the array to a string
				parent.find('input.ad-select-value').val( array.join(';') );
				
				// hide the select panle
				span.removeClass('selected');
				parent.find('ul').hide();
				return false;
			});
			
			// 任何输入都会把input[@class=modify]设置为true
			var inputModify = _this.find('input.modify');
			_panel.find('input, select').change(function() {
				_changeModify(inputModify);
			});
			/*_panel.find('input').blur(function() {
				_changeModify(inputModify);
			});*/
			_panel.find('div.ad-color a').click(function() {
				_changeModify(inputModify);
			});
		};
		
		// 修改modify的值
		var _changeModify = function(inputModify) {
			inputModify.val('1');
		};
		
		var _getSelectValue = function(obj) {
			var value = 0;
			
			// INPUT
			if ( obj.get(0).nodeName == 'INPUT' ) {
				// text
				if ( obj.attr('type') == 'text' || obj.attr('type') == 'hidden' ) {
					value = obj.val();
				}
				
				// checkbox 
				if ( obj.attr('type') == 'checkbox' ) {
					value = new Number( obj.attr('checked') );
				}
				
				// radio
				if ( obj.attr('type') == 'radio' ) {
					obj.each(function() {
						if ( $(this).attr('checked') ) value = $(this).val();
					});
				}
			}
			
			// SELECT
			if ( obj.get(0).nodeName == 'SELECT' ) {
				obj.find('option').each(function() {
					if ( $(this).attr('selected') ) value = $(this).val();
				});
			}
			
			return value;
		};
		
		/**
		 * 生成loading动画
		 */
		var drawLoading = function() {
			var html = '<div class="adsForm_panel adsForm_load">';
			html += '<p>Loading...</p>';
			html += '</div>';
			return html;
		};
		
		/**
		 * 生成单个板块html
		 */
		var _unit_subject = function( param ) {
			var html = '';
			var subject = '';
			switch( param ) {
				case 'ad-top':
					subject = 'Header ad settings';
					break;
				case 'ad-bottom':
					subject = 'Footer ad settings';
					break;
				case 'ad-rand':
					subject = 'Post content ad settings';
					break;
				case 'ad-widget-text':
					subject = 'Sidebar ad settings';
					break;
				case 'ad-widget-link':
					subject = 'Sidebar link ad settings';
					break;
				case 'ad-widget-search':
					subject = 'Sidebar searchbox ad settings';
					break;
			}
			html += '<h3>' + subject + '</h3>';
			return html;
		};
		var _unit_position = function( param ) {
			var html = '';
			var obj = _this.find('input.ad-position');
			
			if ( obj.size() ) {
				var val = obj.val();
				
				switch ( param ) {
					case 'ad-top':
						html += '<div class="ad-pan ad-position-l L-1">';
						html += '<label class="label">Vertical position</label>';
						html += '<div class="item-panel">';
						html += '<select class="ad-position">';
						html += '<option value="1" ' + (val == 1 ? 'selected="selected"' : '')  + ' >Above Header</option>';
						html += '<option value="2" ' + (val == 2 ? 'selected="selected"' : '')  + ' >Below Header</option>';
						html += '</select>';
						html += '</div>';
						html += '<div class="clear"></div>';
						html += '</div>';
						break;
					
					case 'ad-bottom':
						html += '<div class="ad-pan ad-position-l L-1">';
						html += '<label class="label">Horizontal position</label>';
						html += '<div class="item-panel">';
						html += '<select class="ad-position">';
						html += '<option value="1" ' + (val == 1 ? 'selected="selected"' : '')  + ' >End of Page</option>';
						html += '<option value="2" ' + (val == 2 ? 'selected="selected"' : '')  + ' >Above Footer</option>';
						html += '<option value="3" ' + (val == 3 ? 'selected="selected"' : '')  + ' >Below Footer</option>';
						html += '</select>';
						html += '</div>';
						html += '<div class="clear"></div>';
						html += '</div>';
						break;
						
					case 'ad-rand':
						html += '<div class="ad-pan ad-position-l L-1 mn-select">';
						html += '<label class="label">Random position</label>';
						html += '<div class="item-panel">';
						html += '<p class="select"><span>&nbsp;</span></p>';
						html += '<ul class="select-list">';
						html += '<li><input type="checkbox" value="1" id="k_1" /><label for="k_1">Top Left</label></li>';
						html += '<li><input type="checkbox" value="2" id="k_2" /><label for="k_2">Top Center</label></li>';
						html += '<li><input type="checkbox" value="3" id="k_3" /><label for="k_3">Top Right</label></li>';
						html += '<li><input type="checkbox" value="4" id="k_4" /><label for="k_4">Center Left</label></li>';
						html += '<li><input type="checkbox" value="5" id="k_5" /><label for="k_5">Center</label></li>';
						html += '<li><input type="checkbox" value="6" id="k_6" /><label for="k_6">Center Right</label></li>';
						html += '<li><input type="checkbox" value="7" id="k_7" /><label for="k_7">Bottom Left</label></li>';
						html += '<li><input type="checkbox" value="8" id="k_8" /><label for="k_8">Bottom Center</label></li>';
						html += '<li><input type="checkbox" value="9" id="k_9" /><label for="k_9">Bottom Right</label></li>';
						html += '<li class="close"><a href="javascript:void(0);"></a></li>';
						html += '</ul>';
						html += '<input type="hidden" class="ad-position ad-select-value" value="' + val + '" />';
						html += '</div>';
						html += '<div class="clear"></div>';
						html += '</div>';
						break;
						
					default: 
						html = '';
						break;
				}
			}
			return html;
		};
		var _unit_alignment = function( param ) {
			var html = '';
			var obj = _this.find('input.ad-alignment');
			
			if ( param != 'ad-rand' ) {
				var val = obj.val();
				html += '<div class="ad-pan ad-alignment-l L-1">';
				html += '<label class="label">Horizontal position</label>';
				html += '<div class="item-panel">';
				html += '<select class="ad-alignment">';
				html += '<option value="1" ' + (val == 1 ? 'selected="selected"' : '')  + ' >Align Left</option>';
				html += '<option value="2" ' + (val == 2 ? 'selected="selected"' : '')  + ' >Center</option>';
				html += '<option value="3" ' + (val == 3 ? 'selected="selected"' : '')  + ' >Align Right</option>';
				html += '</select>';
				html += '</div>';
				html += '<div class="clear"></div>';
				html += '</div>';
			}
			
			return html;
		};
		var _unit_type = function( param ) {
			var html = '';
			var obj = _this.find('input.ad-type');
			
			if ( obj.size() && param != 'ad-widget-search' ) {
				var val = obj.val();
				html += '<div class="ad-pan ad-type-l L-1">';
				html += '<label class="label">Ad type</label>';
				html += '<div class="item-panel">';
				html += '<select class="ad-type">';
				html += '<option value="1" ' + (val == 1 ? 'selected="selected"' : '')  + ' >Text</option>';
				html += '<option value="2" ' + (val == 2 ? 'selected="selected"' : '')  + ' >Image</option>';
				html += '<option value="3" ' + (val == 3 ? 'selected="selected"' : '')  + ' >Text and Image</option>';
				html += '</select>';
				html += '</div>';
				html += '<div class="clear"></div>';
				html += '</div>';
			}
			return html;
		};
		var _unit_format = function( param ) {
			var html = '';
			var obj = _this.find('input.ad-format');
			
			if ( obj.size() && param != 'ad-widget-search' ) {
				var val = obj.val();
				html += '<div class="ad-pan ad-format-l L-1 mn-select">';
				html += '<label class="label">Ad format</label>';
				html += '<div class="item-panel">';
				html += '<p class="select"><span>&nbsp;</span></p>';
				html += '<ul class="select-list">';
				html += '<li><input type="checkbox" value="728_90" id="k_728_90" /><label for="k_728_90">728x90 Leaderboard</label></li>';
				html += '<li><input type="checkbox" value="468_60" id="k_468_60" /><label for="k_468_60">468x60 Banner</label></li>';
				html += '<li><input type="checkbox" value="234_60" id="k_234_60" /><label for="k_234_60">234x60 Half Banner</label></li>';
				
				html += '<li><input type="checkbox" value="120_600" id="k_120_600" /><label for="k_120_600">120x600 Skyscraper</label></li>';
				html += '<li><input type="checkbox" value="160_600" id="k_160_600" /><label for="k_160_600">160x600 Wide Skyscraper</label></li>';
				html += '<li><input type="checkbox" value="120_40" id="k_120_40" /><label for="k_120_40">120x240 Vertical Banner</label></li>';
				
				html += '<li><input type="checkbox" value="336_280" id="k_336_280" /><label for="k_336_280">336x280 Large Rectangle</label></li>';
				html += '<li><input type="checkbox" value="300_250" id="k_300_250" /><label for="k_300_250">300x250 Medium Rectangle</label></li>';
				html += '<li><input type="checkbox" value="250_250" id="k_250_250" /><label for="k_250_250">250x250 Square</label></li>';
				html += '<li><input type="checkbox" value="200_200" id="k_200_200" /><label for="k_200_200">200x200 Small Square</label></li>';
				html += '<li><input type="checkbox" value="180_150" id="k_180_150" /><label for="k_180_150">180x150 Small Rectangle</label></li>';
				html += '<li><input type="checkbox" value="125_125" id="k_125_125" /><label for="k_125_125">125x125 Button</label></li>';
				html += '<li class="close"><a href="javascript:void(0);"></a></li>';
				html += '</ul>';
				html += '<input type="hidden" class="ad-format ad-select-value" value="' + val + '" />';
				html += '</div>';
				html += '<div class="clear"></div>';
				html += '</div>';
			}
			return html;
		};
		var _unit_title = function(param) {
			var html = '';
			var obj = _this.find('input.ad-title');
			
			if ( obj.size() ) {
				val = obj.val();
				val_hide = _this.find('input.ad-title-hide').val();
				val_custom = _this.find('input.ad-custom-title').val();
				// 或者图片存放地址
				var img_url = $('#main-plugin input.wp-adsense-url').val() + '/templates/images/';
				switch( param ) {
					case 'ad-widget-search':
						html += '<div class="ad-pan ad-title-l ad-title-search L-3">';
						
						html += '<div class="google-white title-search-item">';
						html += '<span><input id=":google_white" type="radio" value="1" name="ad-custom-title" class="ad-custom-title" ' + (val_custom == '1' ? 'checked="checked"' : '') + ' /></span>';
						html += '<label for=":google_white"><img src="' + img_url + 'Logo_25wht.gif" alt="google white" /></label>';
						html += '</div>';
						
						html += '<div class="google-black title-search-item">';
						html += '<span><input id=":google_black" type="radio" value="2" name="ad-custom-title" class="ad-custom-title" ' + (val_custom == '2' ? 'checked="checked"' : '') + ' /></span>';
						html += '<label for=":google_black"><img src="' + img_url + 'Logo_25blk.gif" alt="google_png" /></label>';
						html += '</div>';
						
						html += '<div class="hide-title title-search-item">';
						html += '<span><input id=":hide_title" type="radio" value="4" name="ad-custom-title" class="ad-custom-title" ' + (val_custom == '4' ? 'checked="checked"' : '') + ' /></span>';
						html += '<label for=":hide_title">Hide</label>';
						html += '</div>';
						
						html += '<div class="custom-title">';
						html += '<input id=":custom_title" type="radio" value="3" name="ad-custom-title" class="ad-custom-title" ' + (val_custom == '3' ? 'checked="checked"' : '') + ' />';
						html += '<label for=":custom_title">Custom Title</label>';
						html += '<input id=":custom_title" type="text" class="ad-title" value="' + val + '" />';
						html += '</div>';
						html += '<div class="clear"></div>';
						
						html += '</div>';
						break;
					
					default:
						html += '<div class="ad-pan ad-title-l L-2">';
						html += '<label class="label">Title</label>';
						html += '<div class="item-panel">';
						html += '<input type="text" class="ad-title" value="' + val + '" />';
						html += '</div>';
						html += '<div class="clue">';
						html += '<input type="checkbox" id=":hide_title" class="ad-title-hide" value="1" ' + (val_hide == '1' ? 'checked="checked"' : '') + ' />';
						html += '<label for=":hide_title">Hide title</label>';
						html += '</div>';
						html += '<div class="clear"></div>';
						html += '</div>';
						break;
				}
			}
			html += '<div class="clear"></div>';
			return html;
		};
		var _unit_margin = function(param) {
			var html = '';
			var obj = _this.find('input.ad-margin');
			
			if ( obj.size() ) {
				var val = obj.val();
				html += '<div class="ad-pan ad-margin-l L-1">';
				html += '<label class="label" for=":margin">Margin</label>';
				html += '<div class="item-panel">';
				html += '<input type="text" class="ad-margin" id=":margin" value="' + val + '" />';
				html += '</div>';
				html += '<div class="clue">px</div>';
				html += '<div class="clear"></div>';
				html += '</div>';
				
			}
			return html;
		};
		var _unit_channel_id = function(param) {
			var html = '';
			var obj = _this.find('input.ad-channel-id');
			
			if ( obj.size() ) {
				var val = obj.val();
				html += '<div class="ad-pan ad-single-channel-id-l L-2">';
				html += '<label class="label" for=":chanel_id">Channel Number</label>';
				html += '<div class="item-panel">';
				html += '<input type="text" id=":chanel_id" class="ad-channel-id" value="' + val + '" />';
				html += '</div>';
				html += '<div class="clear"></div>';
				html += '</div>';
			}
			return html;
		};
		var _unit_google_id = function(param) {
			var html = '';
			var obj = _this.find('input.ad-google-id');
			
			if ( obj.size() ) {
				var val = obj.val();
				html += '<div class="ad-pan ad-single-google-id-l L-2">';
				html += '<label class="label" for=":google_id">Google Account</label>';
				html += '<div class="item-panel">';
				html += '<input type="text" id=":google_id" class="ad-google-id" value="' + val + '" />';
				html += '</div>';
				html += '<div class="clear"></div>';
				html += '</div>';
				if ( param != 'ad-widget-search' ) html += '</div><!-- /L-4 -->';
			}
			return html;
		}
		var _unit_color = function(param) {
			var html = '';
			var obj = _this.find('input.ad-colors');
			
			if ( obj.size() ) {
				var html = '';
				var val = obj.val();
				var colors = val.split(';');
				
				html += '<div class="clear"></div>';
				// 边框
				html += '<div class="ad-pan ad-color color-pick">';
				html += '<label for="f_color_border_single" class="t1">Color Border </label>';
				html += '<input type="text" class="t2" size="8" id="f_color_border_single" value="' + (colors[0] ? colors[0] : '') + '" />';
				html += '<a href="javascript:void(0);" class="t3 block_color_border" name="pick10" id="pick10" style="background: ' + (colors[0] ? colors[0] : '') + ';" onclick="tgt=document.getElementById(\'f_color_border_single\');colorSelect(tgt,\'pick10\');return false;"> </a>';
				
				// 标题
				html += '<label for="f_color_title_single" class="t1">Color Title</label>';
				html += '<input type="text" class="t2" size="8" id="f_color_title_single" value="' + (colors[1] ? colors[1] : '') + '" />';
				html += '<a href="javascript:void(0);" class="t3" name="pick11" id="pick11" style="background: ' + (colors[1] ? colors[1] : '') + ';" onclick="tgt=document.getElementById(\'f_color_title_single\');colorSelect(tgt,\'pick11\');return false;"> </a>';
				
				// 背景色
				html += '<label for="f_color_background_single" class="t1">Color Background </label>';
				html += '<input type="text" class="t2" size="8" id="f_color_background_single" value="' + (colors[2] ? colors[2] : '') + '" />';
				html += '<a href="javascript:void(0);" class="t3" name="pick12" id="pick12" style="background: ' + (colors[2] ? colors[2] : '') + ';" onclick="tgt=document.getElementById(\'f_color_background_single\');colorSelect(tgt,\'pick12\');return false;"> </a>';
				
				// 文字
				html += '<label for="f_color_text_single" class="t1">Color Text</label>';
				html += '<input type="text" class="t2" size="8" id="f_color_text_single" value="' + (colors[3] ? colors[3] : '') + '" />';
				html += '<a href="javascript:void(0);" class="t3" name="pick13" id="pick13" style="background: ' + (colors[3] ? colors[3] : '') + ';" onclick="tgt=document.getElementById(\'f_color_text_single\');colorSelect(tgt,\'pick13\');return false;"> </a>';
				
				// 链接
				html += '<label for="f_color_anchor_single" class="t1">Color Hyperlink </label>';
				html += '<input type="text" class="t2" size="8" id="f_color_anchor_single" value="' + (colors[4] ? colors[4] : '') + '" />';
				html += '<a href="javascript:void(0);" class="t3" name="pick14" id="pick14" style="background: ' + (colors[4] ? colors[4] : '') + ';" onclick="tgt=document.getElementById(\'f_color_anchor_single\');colorSelect(tgt,\'pick14\');return false;"> </a>';
				
				// 隐藏域
				html += '<input type="hidden" class="ad-colors" type="text" value="" />';
				html += '</div>';
			}
			return html;
		};
		var _unit_border = function(param) {
			var html = '';
			var obj = _this.find('input.ad-border-style');
			if ( obj.size() ) {
				var val = obj.val();
				if ( param != 'ad-widget-search' ) html += '<div class="L-4">';
				html += '<div class="ad-pan ad-border-style-l L-1">';
				html += '<label class="label">Border style</label>';
				html += '<div class="item-panel">';
				html += '<select class="ad-border-style">';
				html += '<option value="1" ' + (val == 1 ? 'selected="selected"' : '')  + ' >Square corners</option>';
				html += '<option value="2" ' + (val == 2 ? 'selected="selected"' : '')  + ' >Slightly rounded corners </option>';
				html += '<option value="3" ' + (val == 3 ? 'selected="selected"' : '')  + ' >Very rounded corners</option>';
				html += '</select>';
				html += '</div>';
				html += '<div class="clear"></div>';
				html += '</div>';
			}
			return html;
		};
		var _unit_number_post = function(param) {
			var html = '';
			var obj = _this.find('input.ad-number-post');
			
			if ( obj.size() && param == 'ad-rand' ) {
				var val = obj.val();
				html += '<div class="ad-pan ad-number-post-l L-2">';
				html += '<label class="label">Number of Ads to Show Per Post</label>';
				html += '<div class="item-panel">';
				html += '<select class="ad-number-post">';
				html += '<option value="0" ' + (val == 0 ? 'selected="selected"' : '')  + ' >0</option>';
				html += '<option value="1" ' + (val == 1 ? 'selected="selected"' : '')  + ' >1</option>';
				html += '<option value="2" ' + (val == 2 ? 'selected="selected"' : '')  + ' >2</option>';
				html += '<option value="3" ' + (val == 3 ? 'selected="selected"' : '')  + ' >3</option>';
				html += '</select>';
				html += '</div>';
				html += '<div class="clear"></div>';
				html += '</div>';
			}
			return html;
		};
		var _unit_submit = function(param) {
			var html = '';
			html += '<div class="ad-pan ad-unit-submit">';
			html += '<input type="button" value="Save" class="btn-submit button-secondary" />';
			html += '<input type="hidden" class="modify" value="0" />';
			html += '</div>';
			return html;
		};
		
		/**
		 * 生成表单html
		 */
		var drawHtml = function() {
			var html = '';
			var className = _this.find('input.btn-edit').attr('name');
			
			html += '<div class="adsForm_panel ' + className + '-block">';
			// 关闭按钮
			html += '<a class="btn-close" href="javascript:void(0);">Close</a>';
			
			// 位置
			var name = _this.find('input.btn-edit').attr('name');
			html += _unit_subject( name );
			html += _unit_title( name );
			html += _unit_position( name );
			html += _unit_alignment( name );
			html += _unit_type( name );
			html += _unit_format( name );
			html += _unit_color( name );
			html += _unit_border( name );
			html += _unit_margin( name );
			html += _unit_channel_id( name );
			html += _unit_google_id( name );
			html += _unit_number_post( name );
			html += _unit_submit( name );
			
			return html;
		};
		
		/**
		 * 生成面板
		 */
		var drawPanel = function() {
			html = drawLoading();
			_this.append(html);
			
			html += drawHtml();
			_this.append(html);
			_this.find('.adsForm_load').remove();
			
			bindingEvent();
		};
		
		_this.find('.adsForm_panel').size() ? hidePanel() : showPanel();		
	}
	
})(jQuery);