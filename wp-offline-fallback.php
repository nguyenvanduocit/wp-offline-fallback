<?php

/*
Plugin Name: WP Offline Fallback
Plugin URI: http://laptrinh.senviet.org
Description: Make your website valuable even when offline.
Version: 1.0.0
Author: nguyenvanduocit
Author URI: http://senviet.org
License: GPL2
*/

/**
 * Show admin notice
 */
function wpof_admin_notices(){
	$scheme = parse_url(get_option( 'siteurl' ), PHP_URL_SCHEME);
	if($scheme == 'http'){
		wpof_render_notice(__( 'WP Offline Fallback: Site of you need to have SSL, otherwise the plugin will not work.', 'wpof' ));
		return;
	}

	$page = get_page_by_path('/offline-fallback', OBJECT, 'page');
	if(!$page){
		wpof_render_notice(sprintf(__( 'WP Offline Fallback: You need to create a public page with the following url: <a href="%1$s/offline-fallback">%1$s/offline-fallback</a>', 'wpof' ),get_option( 'siteurl' )));
		return;
	}
	$screen = get_current_screen();
	if(($screen->parent_file == 'edit.php?post_type=page') && isset($_GET['post'])){
		$page = get_post($_GET['post']);
		if($page && ($page->post_name == 'offline-fallback')){
			wpof_render_notice(__( 'WP Offline Fallback: To see the changes, you must test in incognito mode, or close off the tab and reopen.', 'wpof' ));
			return;
		}
	}
}
add_action( 'admin_notices', 'wpof_admin_notices' );

/**
 * Render admin notice
 * @param $message
 */
function wpof_render_notice($message) {
	$class = 'notice notice-error';
	printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
}

/**
 * Add rewrite rules to make sw.js on the top of domain
 */
function wpof_add_rewrite_rules()
{
	add_rewrite_rule('sw.js', '/wp-admin/admin-ajax.php?action=wpof-sw-file', 'top');
}

/**
 * Render sw file, replace cache version with page modified
 */
function wpof_render_sw_file(){
	ob_clean();
	header("Content-Type: application/javascript");
	$wsContent = file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'sw.js');
	$page = get_page_by_path('/offline-fallback', OBJECT, 'page');
	if($page){
		$wsContent = str_replace('__VERSION__', md5($page->post_modified), $wsContent);
	}
	echo $wsContent;
	die;
}
add_action('wp_ajax_wpof-sw-file', 'wpof_render_sw_file');
add_action('wp_ajax_nopriv_wpof-sw-file', 'wpof_render_sw_file');

/**
 * On activated
 */
function wpof_activate(){
	wpof_add_rewrite_rules();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'wpof_activate');
register_deactivation_hook( __FILE__, 'flush_rewrite_rules');

function wpof_enqueue_scripts(){
	wp_enqueue_script('wpof-sw-register', plugin_dir_url(__FILE__).DIRECTORY_SEPARATOR.'sw-register.js', [], '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'wpof_enqueue_scripts');

/**
 * Change page template for offline-fallback page.
 *
 * @param $template
 *
 * @return string
 */
function wpof_template_include($template){

	if(!is_page('offline-fallback')){
		return $template;
	}

	$file = plugin_dir_path(__FILE__).DIRECTORY_SEPARATOR.'page-templates/offline-fallback.php';

	if ( file_exists( $file ) ) {
		return $file;
	}

	return $template;
}
add_filter( 'template_include','wpof_template_include' );
