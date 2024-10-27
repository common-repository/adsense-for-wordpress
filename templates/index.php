<!-- LOAD CSS -->
<link href="<?php echo $this -> pluginUrl . 'templates/main.css' ?>" rel="stylesheet" type="text/css" />

<!-- LOAD JS -->
<?php echo '<script src="' . includes_url() . 'js/colorpicker.js' . '" type="text/javascript"></script>'; ?>
<?php echo '<script src="' . $this -> pluginUrl . 'templates/color.js' . '" type="text/javascript"></script>'; ?>
<?php echo '<script src="' . $this -> pluginUrl . 'templates/jquery.adsForm.js' . '" type="text/javascript"></script>'; ?>
<?php echo '<script src="' . $this -> pluginUrl . 'templates/wz_tooltip.js' . '" type="text/javascript"></script>'; ?>
<?php echo '<script src="' . $this -> pluginUrl . 'templates/main.js' . '" type="text/javascript"></script>'; ?>

<div id="main-plugin" class="wrap">
	<h2>Adsense for Wordpress</h2>
	<?php if ( $_SESSION['xcer_error'] ) : ?>
		<div class="warn error"><p><strong><?php echo $_SESSION['xcer_error'] ?></strong></p></div>
		<?php unset ( $_SESSION['xcer_error'] ) ?>
	<?php endif; ?>
	
	<?php if ( $_SESSION['xcer_update'] ) : ?>
		<div class="warn updated"><p><strong><?php echo $_SESSION['xcer_update'] ?></strong></p></div>
		<?php unset ( $_SESSION['xcer_update'] ) ?>
	<?php endif; ?>
  
  <!-- 当前使用模板 -->
  <div class="item theme-current">
  	<label class="item-title">Currently active ad preset:</label>
    <span><?php echo str_replace(" ", "_", $current_template_name) ?></span>
  </div>
	
  <form action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method="post" class="ads-setup" name="ads-setup">
  <!-- 模板选择 -->
  <div class="item template-select">
  	<label class="item-title">Choose ad preset:</label>
  	<?php $theme = str_replace(" ", "_", $theme); ?>
    <select name="template-select" class="select-template">
    	<?php foreach ( $theme_list as $key => $value ) : ?>
    	<option 
      	value="<?php echo $key ?>" <?php if ( $theme == $key ) echo 'selected="selected"' ?>>
				<?php echo $value ?>
      </option>
      <?php endforeach; ?>
    </select>
    <input type="button" name="template-select-save" class="btn-save-as button-secondary" value="Save current settings as a new preset" />
    <span 
      	class="tool"
      	onmouseover="TagToTip('tool_1', ABOVE, true, WIDTH, 420, PADDING, 8, TEXTALIGN, 'justify');"
      	onmouseout="UnTip();"
      	>
    	<img src="<?php echo $this -> pluginUrl . 'templates/images/qm.gif' ?>" alt="What's this?" />
    </span>
    <div id="tool_1" style="display: none;">
    	<p>* You can save your current settings including ad color as a template and when you switch themes you can easily apply the template with matching ad colors.</p>
			<p>* To display a template, simply select the template and click the "Save and set as default" button. You can also modify a template without setting it to default by just clicking the "Save" button.</p>
    </div>
    <div class="save-as hide">
    	<input type="text" name="new-template-name" class="template-name input" size="10" />
      <input type="button" class="save button-secondary" value="Save" />
      <input type="button" class="cancel button-secondary" value="Cancel" />
    </div>
  </div>
  
  <!-- 全局Google帐号 -->
  <div class="item global-google-id">
  	<label for="f_google_id" class="item-title">Google Account #:</label>
    <span>
	    <input type="text" name="google-id" size="10" id="f_google_id" class="input-google-id"
      	value="<?php echo $options['google-id'] ?>" />
    </span>
    <span 
      	class="tool"
      	onmouseover="TagToTip('tool_2', ABOVE, true, WIDTH, 420, PADDING, 8, TEXTALIGN, 'justify');"
      	onmouseout="UnTip();"
      	>
    	<img src="<?php echo $this -> pluginUrl . 'templates/images/qm.gif' ?>" alt="What's this?" />
    </span>
    <div id="tool_2" style="display: none;">
    	<p>* Your google adsense account number can be found on the top right-hand corner after you login to your adsense account. It should look like "ca-pub-xxxxxxxxxxxxxxxxx", the part marked by "x" is your account number. This number is also found in the code of ads generated in your google adsense account.</p>
			<p>* The adsense account number entered here will be used as default for all places unless another account number is specified in individual placements.</p>
			<p>* Adsense ad code will be automatically generated for you, you do not have to create the ad in your adsense account, this is fully compliant with adsense policy.</p>
    </div>
  </div>
  
  <!-- 广告设置 -->
  <fieldset class="item ad-main-options">
  	<legend>Ad specific settings</legend>
  	
  	<!-- 头部 -->
    <div class="list">
    	<input class="btn-edit button-secondary" type="button" value="Change ad settings" name="ad-top" />
      <p class="ops-edit">Header ad</p>
      <p class="ops-forbid">
     		<input 
      		type="checkbox" name="ad-active-top" class="input-forbid-top" id="f_active_top"
     			value="1" <?php echo $options['ad-active-top'] ? 'checked="checked"' : '' ?>
     			><label for="f_active_top">Disable</label>
     	</p>
      <div class="hidden">
      	<input type="hidden" name="ad-position-top" class="ad-position" 
          value="<?php echo $options['ad-position-top'] ?>" />
        <input type="hidden" name="ad-alignment-top" class="ad-alignment" 
          value="<?php echo $options['ad-alignment-top'] ?>" />
        <input type="hidden" name="ad-type-top" class="ad-type" 
          value="<?php echo $options['ad-type-top'] ?>" />
        <input type="hidden" name="ad-format-top" class="ad-format" 
          value="<?php echo $options['ad-format-top'] ?>" />
        <input type="hidden" name="ad-margin-top" class="ad-margin" 
        	value="<?php echo $options['ad-margin-top'] ?>" />
        <input type="hidden" name="ad-channel-id-top" class="ad-channel-id" 
        	value="<?php echo $options['ad-channel-id-top'] ?>" />
        <input type="hidden" name="ad-google-id-top" class="ad-google-id" 
        	value="<?php echo $options['ad-google-id-top'] ?>" />
       	<input type="hidden" name="ad-color-top" class="ad-colors" 
        	value="<?php echo $options['ad-color-top'] ?>" />
       	<input type="hidden" name="ad-border-style-top" class="ad-border-style"
        	value="<?php echo $options['ad-border-style-top'] ?>" />
      </div>
    </div>
    
    <!-- 随机 -->
    <div class="list">
    	<input class="btn-edit button-secondary" type="button" value="Change ad settings" name="ad-rand" />
      <p class="ops-edit">Post content ad</p>
      <p class="ops-forbid">
     		<input type="checkbox" name="ad-active-rand" class="input-forbid-rand" id="f_active_rand"
     			value="1" <?php echo $options['ad-active-rand'] ? 'checked="checked"' : '' ?>
     			><label for="f_active_rand">Disable</label>
     	</p>
      <div class="hidden">
      	<input type="hidden" name="ad-position-rand" class="ad-position" 
          value="<?php echo $options['ad-position-rand'] ?>" />
        <input type="hidden" name="ad-type-rand" class="ad-type" 
          value="<?php echo $options['ad-type-rand'] ?>" />
        <input type="hidden" name="ad-format-rand" class="ad-format" 
          value="<?php echo $options['ad-format-rand'] ?>" />
        <input type="hidden" name="ad-margin-rand" class="ad-margin" 
        	value="<?php echo $options['ad-margin-rand'] ?>" />
        <input type="hidden" name="ad-channel-id-rand" class="ad-channel-id" 
        	value="<?php echo $options['ad-channel-id-rand'] ?>" />
        <input type="hidden" name="ad-google-id-rand" class="ad-google-id" 
        	value="<?php echo $options['ad-google-id-rand'] ?>" />
        <input type="hidden" name="ad-color-rand" class="ad-colors" 
        	value="<?php echo $options['ad-color-rand'] ?>" />
        <input type="hidden" name="ad-border-style-rand" class="ad-border-style"
        	value="<?php echo $options['ad-border-style-rand'] ?>" />
        <input type="hidden" name="ad-number-post-rand" class="ad-number-post"
        	value="<?php echo $options['ad-number-post-rand'] ?>" />
      </div>
    </div>
    
    <!-- 底部 -->
    <div class="list">
    	<input class="btn-edit button-secondary" type="button" value="Change ad settings" name="ad-bottom" />
      <p class="ops-edit">Footer ad</p>
      <p class="ops-forbid">
     		<input type="checkbox" name="ad-active-bottom" class="input-forbid-bottom" id="f_active_bottom" 
     			value="1" <?php echo $options['ad-active-bottom'] ? 'checked="checked"' : '' ?>
     			><label for="f_active_bottom">Disable</label>
     	</p>
      <div class="hidden">
      	<input type="hidden" name="ad-position-bottom" class="ad-position" 
          value="<?php echo $options['ad-position-bottom'] ?>" />
        <input type="hidden" name="ad-alignment-bottom" class="ad-alignment" 
          value="<?php echo $options['ad-alignment-bottom'] ?>" />
        <input type="hidden" name="ad-type-bottom" class="ad-type" 
          value="<?php echo $options['ad-type-bottom'] ?>" />
        <input type="hidden" name="ad-format-bottom" class="ad-format" 
          value="<?php echo $options['ad-format-bottom'] ?>" />
        <input type="hidden" name="ad-margin-bottom" class="ad-margin" 
        	value="<?php echo $options['ad-margin-bottom'] ?>" />
        <input type="hidden" name="ad-channel-id-bottom" class="ad-channel-id" 
        	value="<?php echo $options['ad-channel-id-bottom'] ?>" />
        <input type="hidden" name="ad-google-id-bottom" class="ad-google-id" 
        	value="<?php echo $options['ad-google-id-bottom'] ?>" />
        <input type="hidden" name="ad-color-bottom" class="ad-colors" 
        	value="<?php echo $options['ad-color-bottom'] ?>" />
        <input type="hidden" name="ad-border-style-bottom" class="ad-border-style"
        	value="<?php echo $options['ad-border-style-bottom'] ?>" />
      </div>
    </div>
    
    <!-- widget-text  -->
    <div class="list">
    	<input class="btn-edit button-secondary" type="button" value="Change ad settings" name="ad-widget-text" />
      <p class="ops-edit">Sidebar ad</p>
      <p class="ops-forbid">
     		<input type="checkbox" name="ad-active-widget-text" id="f_active_widget_text" 
     			value="1" <?php echo $options['ad-active-widget-text'] ? 'checked="checked"' : '' ?>
     			><label for="f_active_widget_text">Disable</label>
     	</p>
     	<p class="ops-anchor">
     		<a href="widgets.php" title="Go widget page">Add to widget page</a>
     	</p>
      <div class="hidden">
        <input type="hidden" name="ad-alignment-widget-text" class="ad-alignment" 
          value="<?php echo $options['ad-alignment-widget-text'] ?>" />
        <input type="hidden" name="ad-type-widget-text" class="ad-type" 
          value="<?php echo $options['ad-type-widget-text'] ?>" />
        <input type="hidden" name="ad-format-widget-text" class="ad-format" 
          value="<?php echo $options['ad-format-widget-text'] ?>" />
        <input type="hidden" name="ad-title-widget-text" class="ad-title" 
          value="<?php echo $options['ad-title-widget-text'] ?>" />
        <input type="hidden" name="ad-title-hide-widget-text" class="ad-title-hide" 
          value="<?php echo $options['ad-title-hide-widget-text'] ?>" />
        <input type="hidden" name="ad-margin-widget-text" class="ad-margin" 
          value="<?php echo $options['ad-margin-widget-text'] ?>" />
        <input type="hidden" name="ad-channel-id-widget-text" class="ad-channel-id" 
          value="<?php echo $options['ad-channel-id-widget-text'] ?>" />
        <input type="hidden" name="ad-google-id-widget-text" class="ad-google-id" 
        	value="<?php echo $options['ad-google-id-widget-text'] ?>" />
        <input type="hidden" name="ad-color-widget-text" class="ad-colors" 
        	value="<?php echo $options['ad-color-widget-text'] ?>" />
        <input type="hidden" name="ad-border-style-widget-text" class="ad-border-style"
        	value="<?php echo $options['ad-border-style-widget-text'] ?>" />
      </div>
    </div>
    
    <!-- widget-link -->
    <div class="list">
    	<input class="btn-edit button-secondary" type="button" value="Change ad settings" name="ad-widget-link" />
      <p class="ops-edit">Sidebar link ad</p>
      <p class="ops-forbid">
	  		<input type="checkbox" name="ad-active-widget-link" id="f_active_widget_link" 
	  			value="1" <?php echo $options['ad-active-widget-link'] ? 'checked="checked"' : '' ?>
	  			><label for="f_active_widget_link">Disable</label>
     	</p>
     	<p class="ops-anchor">
     		<a href="widgets.php" title="Go widget page">Add to widget page</a>
     	</p>
      <div class="hidden">
        <input type="hidden" name="ad-alignment-widget-link" class="ad-alignment" 
          value="<?php echo $options['ad-alignment-widget-link'] ?>" />
        <input type="hidden" 	name="ad-type-widget-link" class="ad-type" 
          value="<?php echo $options['ad-type-widget-link'] ?>" />
        <input type="hidden" name="ad-format-widget-link" class="ad-format" 
          value="<?php echo $options['ad-format-widget-link'] ?>" />
        <input type="hidden" name="ad-title-widget-link" class="ad-title" 
          value="<?php echo $options['ad-title-widget-link'] ?>" />
        <input type="hidden" name="ad-title-hide-widget-link" class="ad-title-hide" 
          value="<?php echo $options['ad-title-hide-widget-link'] ?>" />
        <input type="hidden" name="ad-margin-widget-link" class="ad-margin" 
          value="<?php echo $options['ad-margin-widget-link'] ?>" />
        <input type="hidden" name="ad-channel-id-widget-link" class="ad-channel-id" 
          value="<?php echo $options['ad-channel-id-widget-link'] ?>" />
        <input type="hidden" name="ad-google-id-widget-link" class="ad-google-id" 
        	value="<?php echo $options['ad-google-id-widget-link'] ?>" />
        <input type="hidden" name="ad-color-widget-link" class="ad-colors" 
        	value="<?php echo $options['ad-color-widget-link'] ?>" />
        <input type="hidden" name="ad-border-style-widget-link" class="ad-border-style"
        	value="<?php echo $options['ad-border-style-widget-link'] ?>" />
      </div>
    </div>
    
    <!-- widget-search  -->
    <div class="list">
    	<input class="btn-edit button-secondary" type="button" value="Change ad settings" name="ad-widget-search" />
      <p class="ops-edit">Sidebar searchbox ad</p>
      <p class="ops-forbid">
     		<input type="checkbox" name="ad-active-widget-search" id="f_active_widget_search" 
      		value="1" <?php echo $options['ad-active-widget-search'] ? 'checked="checked"' : '' ?>
      		><label for="f_active_widget_search">Disable</label>
     	</p>
     	<p class="ops-anchor">
     		<a href="widgets.php" title="Go widget page">Add to widget page</a>
     	</p>
      <div class="hidden">
        <input type="hidden" name="ad-alignment-widget-search" class="ad-alignment" 
          value="<?php echo $options['ad-alignment-widget-search'] ?>" />
        <input type="hidden" name="ad-type-widget-search" class="ad-type" 
          value="<?php echo $options['ad-type-widget-search'] ?>" />
        <input type="hidden" name="ad-format-widget-search" class="ad-format" 
          value="<?php echo $options['ad-format-widget-search'] ?>" />
        <input type="hidden" name="ad-custom-title-widget-search" class="ad-custom-title" 
          value="<?php echo $options['ad-custom-title-widget-search'] ?>" />
        <input type="hidden" name="ad-title-widget-search" class="ad-title" 
          value="<?php echo $options['ad-title-widget-search'] ?>" />
        <input type="hidden" name="ad-margin-widget-search" class="ad-margin" 
          value="<?php echo $options['ad-margin-widget-search'] ?>" />
        <input type="hidden" name="ad-channel-id-widget-search" class="ad-channel-id" 
          value="<?php echo $options['ad-channel-id-widget-search'] ?>" />
        <input type="hidden" name="ad-google-id-widget-search" class="ad-google-id" 
        	value="<?php echo $options['ad-google-id-widget-search'] ?>" />
      </div>
    </div>
  </fieldset>
 
   <!-- 赞助 -->
  <div class="item ad-supprot">
  	<label class="item-title" for="f_support">Support this plugin's development by optionally donating a percentage of your ad revenue. Put 0 to disable donation, default on. </label>
    <input type="text" name="ad-support-rate" size="1" id="f_support" class="input-ad-support-rate"  
      value="" />%
  </div>
  
  <!-- 广告边框颜色设置 -->
  <div class="item ad-border-color">
  	<label class="item-title">Ad color settings:</label>
    <div class="colorArea">
    	<div class="list color-pick">
      	<label for="f_color_border" class="t1">Color Border </label>
        <input type="text" name="color-border" class="t2" size="8" id="f_color_border" 
          value="<?php echo $options['color-border'] ?>" />
        <a href="javascript:void(0);" style="background: <?php echo $options['color-border'] ?>" 
          class="t3 block_color_border" name="pick1" id="pick1" 
        	onclick="tgt=document.getElementById('f_color_border');colorSelect(tgt,'pick1');return false;"> </a>
      </div>
      
      <div class="list color-pick">
      	<label for="f_color_title" class="t1">Color Title</label>
        <input type="text" name="color-title" class="t2" size="8" id="f_color_title" 
          value="<?php echo $options['color-title'] ?>" />
        <a href="javascript:void(0);" class="t3 block_color_title" name="pick2" id="pick2" style="background: <?php echo $options['color-title'] ?>"
        	onclick="tgt=document.getElementById('f_color_title');colorSelect(tgt,'pick2');return false;"> </a>
      </div>
      
      <div class="list color-pick">
      	<label for="f_color_background" class="t1">Color Background </label>
        <input type="text" name="color-background" class="t2" size="8" id="f_color_background" 
          value="<?php echo $options['color-background'] ?>" />
        <a href="javascript:void(0);" class="t3 block_color_background" name="pick3" id="pick3" style="background: <?php echo $options['color-background'] ?>"
        	onclick="tgt=document.getElementById('f_color_background');colorSelect(tgt,'pick3');return false;"> </a>
      </div>
      
      <div class="list color-pick">
      	<label for="f_color_text" class="t1">Color Text </label>
        <input type="text" name="color-text" class="t2" size="8" id="f_color_text" 
          value="<?php echo $options['color-text'] ?>" />
        <a href="javascript:void(0);" class="t3 block_color_text" name="pick4" id="pick4" style="background: <?php echo $options['color-text'] ?>"
        	onclick="tgt=document.getElementById('f_color_text');colorSelect(tgt,'pick4');return false;"> </a>
      </div>
      
      <div class="list color-pick">
      	<label for="f_color_anchor" class="t1">Color Hyperlink </label>
        <input type="text" name="color-anchor" class="t2" size="8" id="f_color_anchor" 
          value="<?php echo $options['color-anchor'] ?>" />
        <a href="javascript:void(0);" class="t3 block_color_anchor" name="pick5" id="pick5" style="background: <?php echo $options['color-anchor'] ?>"
        	onclick="tgt=document.getElementById('f_color_anchor');colorSelect(tgt,'pick5');return false;"> </a>
      </div>
      
    </div>
  </div>
  
  <div id="colorPickerDiv" style="z-index:100;background:#eee;border:1px solid #ccc;position:absolute;visibility:hidden;"> </div>
  
  <!-- 广告数量 -->
  <?php if ( !isset($options['ad-number']) ) $default = true; ?>
  <div class="item ad-number">
  	<label class="item-title">Total ads per page:(Google ToS limits total adsense ad per page to 3)</label>
    
    <input type="radio" name="ad-number" value="0" id="f_ad_number_0" 
      <?php echo ($options['ad-number'] == 0) ? 'checked="checked"' : ''; echo $default ? 'checked="checked"' : ''; ?> 
      /><label for="f_ad_number_0">0</label>
    
    <input type="radio" name="ad-number" value="1" id="f_ad_number_1" 
      <?php echo ($options['ad-number'] == 1 ) ? 'checked="checked"' : ''; ?> 
      /><label for="f_ad_number_1">1</label>
    
    <input type="radio" name="ad-number" value="2" id="f_ad_number_2"
      <?php echo ($options['ad-number'] == 2) ? 'checked="checked"' : '' ?> 
      /><label for="f_ad_number_2">2</label>
    
    <input type="radio" name="ad-number" value="3" id="f_ad_number_3"
      <?php echo ($options['ad-number'] == 3) ? 'checked="checked"' : '' ?> 
      /><label for="f_ad_number_3">3</label>
    
    <!--input type="radio" name="ad-number" value="99" id="f_ad_number_n"
      <?php echo ($options['ad-number'] == 99) ? 'checked="checked"' : '' ?> />
    <label for="f_ad_number_n">无限个(自己承担风险)</label-->
  </div>
  <!--
  <div class="item ad-number">
  	<label class="item-title">Number of Ads to Show Per Page</label>
  	<select name="ad-number">
  		<option value="0" <?php echo ($options['ad-number'] == 0) ? 'selected="selected"' : ''; ?>>0</option>
  		<option value="1" <?php echo ($options['ad-number'] == 1) ? 'selected="selected"' : ''; ?>>1</option>
  		<option value="2" <?php echo ($options['ad-number'] == 2) ? 'selected="selected"' : ''; ?>>2</option>
  		<option value="3" <?php echo ($options['ad-number'] == 3) ? 'selected="selected"' : ''; ?>>3</option>
  	</select>
  </div>
  
  <div class="item ad-number">
  	<label class="item-title">Number of Ads to Show Per Post</label>
  	<select name="ad-number-post">
  		<option value="1" <?php echo ($options['ad-number-post'] == 1) ? 'selected="selected"' : ''; ?>>1</option>
  		<option value="2" <?php echo ($options['ad-number-post'] == 2) ? 'selected="selected"' : ''; ?>>2</option>
  		<option value="3" <?php echo ($options['ad-number-post'] == 3) ? 'selected="selected"' : ''; ?>>3</option>
  	</select>
  </div>
  -->
  
  <!-- 不允许广告出现页面 -->
  <div class="item ad-invisible-page">
  	<label class="item-title">Disable ad display on these pages (when checked):</label><br />
    
    <input type="checkbox" name="ad-invisible-page-home" value="1" id="f_invisible_page_home"
      <?php echo $options['ad-invisible-page-home'] ? 'checked="checked"' : '' ?> 
      /><label for="f_invisible_page_home">Homepage</label><br />
    
    <input type="checkbox" name="ad-invisible-page-front" value="1" id="f_invisible_page_front"
      <?php echo $options['ad-invisible-page-front'] ? 'checked="checked"' : '' ?> 
      /><label for="f_invisible_page_front">Frontpage</label><br />
    
    <input type="checkbox" name="ad-invisible-page-category" value="1" id="f_invisible_page_category"
      <?php echo $options['ad-invisible-page-category'] ? 'checked="checked"' : '' ?> 
      /><label for="f_invisible_page_category">Categories page</label><br />
    
    <input type="checkbox" name="ad-invisible-page-archive" value="1" id="f_invisible_page_archive"
      <?php echo $options['ad-invisible-page-archive'] ? 'checked="checked"' : '' ?> 
      /><label for="f_invisible_page_archive">Archive page</label><br />
    
    <input type="checkbox" name="ad-invisible-page-single" value="1" id="f_invisible_page_single"
      <?php echo $options['ad-invisible-page-single'] ? 'checked="checked"' : '' ?> 
      /><label for="f_invisible_page_single">Pages</label><br />
    
    <input type="checkbox" name="ad-invisible-page-tag" value="1" id="f_invisible_page_tag"
      <?php echo $options['ad-invisible-page-tag'] ? 'checked="checked"' : '' ?> 
      /><label for="f_invisible_page_tag">Tag page</label><br />
    
    <input type="checkbox" name="ad-invisible-page-post" value="1" id="f_invisible_page_post"
      <?php echo $options['ad-invisible-page-post'] ? 'checked="checked"' : '' ?> 
      /><label for="f_invisible_page_post">Posts</label><br />
    
  </div>
  
  <!-- 其他选项 -->
  <div class="item ad-other-option">
  	<label class="item-title">Additional options:</label><br />
    <input type="checkbox" name="ohter-option-sidebar" value="1" id="f_other_option_sidebar"
      <?php echo $options['ohter-option-sidebar'] ? 'checked="checked"' : '' ?> 
      /><label for="f_other_option_sidebar">Display sidebar ads before other ads when limit is reached</label><br />
    
    <input type="checkbox" name="ohter-option-short" value="1" id="f_other_option_short"
      <?php echo $options['ohter-option-short'] ? 'checked="checked"' : '' ?> 
      /><label for="f_other_option_short">Display post content ads even if post content is small</label>
  </div>
  

  
  <!-- 按钮 -->
  <div class="item ad-btn">
  	<input type="hidden" name="action" value="save" id="btn-action" />
  	<input type="submit" name="ad-save" class="button-secondary" value="Save settings and use as default" />
    <input type="submit" name="ad-update" class="button-secondary" value="Save settings" />
    <input type="submit" name="ad-drop-template" class="ad-drop-template button-secondary" value="Delete this preset" />
    <input type="submit" name="ad-uninstall" class="ad-uninstall button-secondary" value="Delete all plugin settings from database" />
    <input type="hidden" name="wp-adsense-url" class="wp-adsense-url" value="<?php echo $this -> pluginUrl; ?>" />
  </div>
  
  </form>
  
  <div class="note">
		<h3>Instructions:</h3>
		
		<p>Your settings can be saved as a "preset", and you can have multiple presets with different position and colors, so you can test out your settings with different themes, without redo the settings when you switch themes.</p>
		<p>&nbsp;</p>
		<p>To choose which presets to use, just choose the preset at "choose ad preset" and click on "save settings and use as default" button on the bottom of the page.</p>
		<p>&nbsp;</p>
		<p>Under the "Ad specific settings" section, each ad setting is independent. If no setting was set, it just uses the global settings from the ad preset.</p>
		<p>&nbsp;</p>
		<p>Remember to uncheck the "disable" option when you are ready to show your ads.</p>
		<p>&nbsp;</p>
		<p>Remember to save your settings after any change is made to any option. You can use the "save settings and use as default" to immediately start using the current settings, or just use the "save settings" button to save your changes but not immediately use them as default</p>
		<p>&nbsp;</p>
		<p>You do not need to generate any ad code in your google account, the correct adsense code is automatically generated in this plugin.</p>
		<p>&nbsp;</p>											
		<p>To disable ad display in any post or page, just put &lt;!&#45;-noadsense&#45;-&gt; within the content, in html mode.</p>
		<p>&nbsp;</p>
		<p>If you need to limit ad display to a specific area of the content, under html mode, put &lt;!&#45;-adsensestart&#45;-&gt; also use &lt;!&#45;-adsenseend&#45;-&gt; to end </p>
  </div>
</div>