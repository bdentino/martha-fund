<?php
/**
 * Theme info page
 *
 * @package Martha
 */

//Add the theme page
add_action('admin_menu', 'martha_add_theme_info');
function martha_add_theme_info(){
	$theme_info = add_theme_page( __('Martha Info','martha'), __('Martha Info','martha'), 'manage_options', 'martha-info.php', 'martha_info_page' );
    add_action( 'load-' . $theme_info, 'martha_info_hook_styles' );
}

//Callback
function martha_info_page() {
?>
	<div class="info-container">
		<h2 class="info-title"><?php _e('Martha Info','martha'); ?></h2>
		<div class="info-block"><div class="dashicons dashicons-desktop info-icon"></div><p class="info-text"><a href="http://demo.athemes.com/martha" target="_blank"><?php _e('Theme demo','martha'); ?></a></p></div>
		<div class="info-block"><div class="dashicons dashicons-book-alt info-icon"></div><p class="info-text"><a href="http://athemes.com/documentation/martha" target="_blank"><?php _e('Documentation','martha'); ?></a></p></div>
		<div class="info-block"><div class="dashicons dashicons-sos info-icon"></div><p class="info-text"><a href="http://athemes.com/forums" target="_blank"><?php _e('Support','martha'); ?></a></p></div>
		<div class="info-block"><div class="dashicons dashicons-smiley info-icon"></div><p class="info-text"><a href="http://athemes.com/theme/martha-pro" target="_blank"><?php _e('Pro version','martha'); ?></a></p></div>
	</div>
<?php
}

//Styles
function martha_info_hook_styles(){
   	add_action( 'admin_enqueue_scripts', 'martha_info_page_styles' );
}
function martha_info_page_styles() {
	wp_enqueue_style( 'martha-info-style', get_template_directory_uri() . '/css/info-page.css', array(), true );
}
