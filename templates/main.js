(function($) {
	$(document).ready(function() {
		
		var form = $('#main-plugin form.ads-setup');
		
		/**
		 * 另存模板
		 */
		$('#main-plugin input.btn-save-as').click(function() {
			var parent = $(this).parent();
			if ( parent.find('div.hide').size() ) {
				parent.find('div.hide').removeClass('hide');
			} else {
				parent.find('div.save-as').addClass('hide');
			}
		})
		// 保存
		form.find('input.save').click(function() {
			if ( $.trim(form.find('input.template-name').val()) == '' ) {
				alert('Preset name can not be empty');
				return false;
			}
			form.find('#btn-action').val('saveas');
			form.submit();
		});
		// 取消
		form.find('input.cancel').click(function() {
			form.find('div.save-as').addClass('hide');
		});
		
		// 阻止任何地方的回车按键把整个表单都提交了
		$('#main-plugin input').keydown(function(e) {
			if ( e.keyCode == 13 ) return false;
		});
		
		/**
		 * 按钮被按下
		 */
		// 删除模板
		form.find('input.ad-drop-template').click(function() {
			if ( !confirm('Are you sure you would like to delete this preset?') ) return false;				
			$('#btn-action').val('drop-template');			
		});
		// 清空数据
		form.find('input.ad-uninstall').click(function() {
			if ( !confirm('Are you sure you would like to clear all settings of this plugin from the database?') ) return false;
			$('#btn-action').val('ad-uninstall');			
		});
		
		
		/**
		 * 设置单个广告
		 */
		$('#main-plugin input.btn-edit').click(function() {
			var dom = form.find('div.adsForm_container');
			
			// if dom is self, delete it
			if ( $(this).parent().hasClass('adsForm_container') ) {
				dom.removeClass('adsForm_container');
				dom.find('div.adsForm_panel').remove();
				return false;
			}
			
			// delete the other pop
			if ( dom.size() ) {
				dom.removeClass('adsForm_container');
				dom.find('div.adsForm_panel').remove();
			}
			
			$(this).adsForm();
		});
		
		
		/**
		 * 模块选择
		 */
		$('#main-plugin select.select-template').change(function() {
			$('#btn-action').val( 'select' );
			form.submit();
		});
		
	});
})(jQuery);