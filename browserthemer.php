<?php
/*
Plugin Name: BrowserThemer
Plugin URI: http://wordpress.org/#
Description: This plugin will let you the option to choose themes for your blog by the user browser
Version: 0.5
*/
//Function fot get the theme name by the user browser
function browserthemer_get_theme_name(){
	if(get_option('broser_themer_options')){
		//get options
		$options = get_option('broser_themer_options');
		$options = unserialize($options);
		if(strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 6') !== false) {
			$theme_name = $options['ie6'];
		}
		elseif(strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 7') !== false) {
			$theme_name = $options['ie7'];
		}
		elseif(strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 8') !== false) {
			$theme_name = $options['ie8'];
		}
		elseif(strpos($_SERVER['HTTP_USER_AGENT'],'Firefox/2') !== false) {
			$theme_name = $options['ff2'];
		}
		elseif(strpos($_SERVER['HTTP_USER_AGENT'],'Firefox/3') !== false) {
			$theme_name = $options['ff3'];
		}
		elseif(strpos($_SERVER['HTTP_USER_AGENT'],'Opera') !== false) {
			$theme_name = $options['opera'];
		}
		elseif(strpos($_SERVER['HTTP_USER_AGENT'],'Chrome') !== false) {
			$theme_name = $options['chrome'];
		}
		elseif(strpos($_SERVER['HTTP_USER_AGENT'],'Safari') !== false) {
			$theme_name = $options['safari'];
		}
		else{
			$theme_name = $options['others'];
		}
	}
	else{
		$theme_name = '';
	}
	return $theme_name;
}
/****Change Theme Functions Start***/
//Function for change the theme template using get_theme_name() function
function browserthemer_change_template($theme) {
	$name = browserthemer_get_theme_name();
	if($name == ''){
		$template = $theme;
	}
	else{
		$themes = get_themes();
		if(isset($themes[$name])){
			$template = $themes[$name]['Template'];
		}
		else{
			$template = 'classic';
		}
	}
	return $template;
}
//Function for change the theme stylesheet using get_theme_name() function
function browserthemer_change_stylesheet($stylesheet) {
	$name = browserthemer_get_theme_name();
	if($name == ''){
		$stylesheet = $stylesheet;
	}
	else{
		$themes = get_themes();
		if(isset($themes[$name])){
			$stylesheet = $themes[$name]['Stylesheet'];
		}
		else{
			$stylesheet = 'classic';
		}
	}
	return $stylesheet;
}
/****Change Theme Functions End***/
/****Plugin Control Panel Functions Start***/
function browserthemer_add_panel_link() {
	if(function_exists('add_options_page')) {
	  add_options_page('BrowserThemer', 'BrowserThemer', 10, __FILE__, 'browser_themer_panel');
	}
}
function browserthemer_select_box($browser,$theme){
	$themes = get_themes();
	$select_box = '<select name="'.$browser.'"><option value="">'._('Default Blog Template','browserthemer').'</otpion>';
	foreach($themes as $k=>$v){
		$select_box .= '<option value="'.$k.'"';
		if($theme == $k)$select_box .= ' selected="selected"';
		$select_box .= '>'.$k.'</option>';
	}
	$select_box .= '</select>';
	return $select_box;
}
function browser_themer_panel(){
	$succes_msg = '';
	if(get_option('broser_themer_options')){
		//get options
		$options = get_option('broser_themer_options');
		$options = unserialize($options);
	}
	else{
		//set default options
		$options = array();
		$options['ie6'] = null;
		$options['ie7'] = null;
		$options['ie8'] = null;
		$options['ff2'] = null;
		$options['ff3'] = null;
		$options['opera'] = null;
		$options['chrome'] = null;
		$options['safari'] = null;
		$options['others'] = null;
	}
	if(isset($_POST['browser_themer_submit'])){
		$options['ie6'] = $_POST['ie6'];
		$options['ie7'] = $_POST['ie7'];
		$options['ie8'] = $_POST['ie8'];
		$options['ff2'] = $_POST['ff2'];
		$options['ff3'] = $_POST['ff3'];
		$options['opera'] = $_POST['opera'];
		$options['chrome'] = $_POST['chrome'];
		$options['safari'] = $_POST['safari'];
		$options['others'] = $_POST['others'];
		$serialize_options = serialize($options);
		update_option('broser_themer_options',$serialize_options);
		$succes_msg = "The Settings Saved Successfully.";
	}
	?>
	<div class="wrap">
	<h2><?php _e("BrowserThemer Settings",'browserthemer'); ?></h2>
		<?php _e($succes_msg,'browserthemer'); ?>
		<form method="post" action="">
			<table>
				<tr><td><?php _e("IE6:",'browserthemer'); ?></td><td><?php echo browserthemer_select_box('ie6',$options['ie6']); ?></td></tr>
				<tr><td><?php _e("IE7:",'browserthemer'); ?></td><td><?php echo browserthemer_select_box('ie7',$options['ie7']); ?></td></tr>
				<tr><td><?php _e("IE8:",'browserthemer'); ?></td><td><?php echo browserthemer_select_box('ie8',$options['ie8']); ?></td></tr>
				<tr><td><?php _e("FF2:",'browserthemer'); ?></td><td><?php echo browserthemer_select_box('ff2',$options['ff2']); ?></td></tr>
				<tr><td><?php _e("FF3:",'browserthemer'); ?></td><td><?php echo browserthemer_select_box('ff3',$options['ff3']); ?></td></tr>
				<tr><td><?php _e("Opera:",'browserthemer'); ?></td><td><?php echo browserthemer_select_box('opera',$options['opera']); ?></td></tr>
				<tr><td><?php _e("Chrome:",'browserthemer'); ?></td><td><?php echo browserthemer_select_box('chrome',$options['chrome']); ?></td></tr>
				<tr><td><?php _e("Safari:",'browserthemer'); ?></td><td><?php echo browserthemer_select_box('safari',$options['safari']); ?></td></tr>
				<tr><td><?php _e("Others:",'browserthemer'); ?></td><td><?php echo browserthemer_select_box('others',$options['others']); ?></td></tr>
			</table>
			<input type="submit" name="browser_themer_submit" value="<?php _e('Save Settings','browserthemer') ?>" />
			<input type="reset" value="<?php _e('Reset','browserthemer') ?>" />
		</form>
	</div>
	<?php
}
/****Plugin Control Panel Functions End***/
/****Hooks & Filter Start****/
add_action('admin_menu','browserthemer_add_panel_link');
add_filter('template', 'browserthemer_change_template');
add_filter('stylesheet', 'browserthemer_change_stylesheet');
/****Hooks & Filter End****/
/***Lang Hooks***/
function browserthemer_translation_file() {
	load_plugin_textdomain( 'browserthemer','wp-content/plugins/browserthemer/');
}
add_action('init', 'browserthemer_translation_file');
?>