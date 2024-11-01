<?php
	
	/*
		Plugin Name: Web Manifest
		Plugin URI: https://wordpress.org/plugins/web-manifest
		Description: Allows to create and configure a web-app manifest file (manifest.json).
		Author: Andrey Litvinov (FRO1D)
		Author URI: https://profiles.wordpress.org/fro1d
		Version: 1.1.0
		License: GNU General Public License v2.0 or later
		License URI: http://www.gnu.org/licenses/gpl-2.0.html
		Text Domain: web-manifest
		Domain Path: languages
	*/
	
	
	// Do not allow directly accessing this file
	if( !defined('ABSPATH') ) exit;
	
	
	/**
	 * Load plugin textdomain.
	 */
	function fwm_core_textdomain(){
		load_plugin_textdomain( 'web-manifest', false, dirname( plugin_basename(__FILE__) ) . '/languages' );
	}
	add_action('plugins_loaded', 'fwm_core_textdomain');
	
	
	/**
	 * Include existing files.
	 * 
	 * @param	string	$file	Relative path to the file.
	 * 
	 * @return	bool	False if file doesn't exists.
	 */
	function fwm_include($file){
		
		$filePath = wp_normalize_path( plugin_dir_path( __FILE__ ) . $file );
		
		if( file_exists($filePath) ){
			include_once $filePath;
		}
		else {
			return false;
		}
		
	}
	
	
	/**
	 * Get list of all site pages.
	 * 
	 * @return	array
	 */
	function fwm_all_pages(){
		
		$allLinks	=	array( __('Home', 'web-manifest') => array( trailingslashit(get_bloginfo('url')) ) );
		$optgroup	=	'';
		
		$the_query = new WP_Query(array(
			'post_type'			=>	'any',
			'post_status'		=>	'publish',
			'posts_per_page'	=>	-1,
			'orderby'			=>	'type',
			'order'				=>	'ASC'
		));
		while( $the_query -> have_posts() ){ $the_query -> the_post();
			
			$postType	=	get_post_type();
			$ptLabel	=	get_post_type_object($postType) -> label;
			$link		=	trailingslashit( get_permalink() );
			
			if( $ptLabel != $optgroup ){
				$allLinks[$ptLabel] = array();
				$optgroup = $ptLabel;
			}
			
			if( $link != trailingslashit(get_bloginfo('url')) ){
				array_push($allLinks[$ptLabel], $link);
			}
			
		}
		wp_reset_postdata();
		
		return $allLinks;
		
	}
	
	
	/**
	 * URL to components parse.
	 * 
	 * @param	string	$url	URL.
	 * 
	 * @return	array	URL components {
	 * 						base - main url part (without query args and fragment);
	 * 						query - query string;
	 * 						fragment - fragment (anchor).
	 * 					}
	 */
	function fwm_url_parse($url){
		
		$parsed		=	parse_url($url);
		
		$query		=	( array_key_exists('query', $parsed) )		?	trim($parsed['query'], '/')		:	'';
		$fragment	=	( array_key_exists('fragment', $parsed) )	?	trim($parsed['fragment'], '/')	:	'';
		
		$base = $url;
		if( !empty($fragment) ) $base = str_replace('#' . $fragment, '', $base);
		if( !empty($query) )	$base = str_replace('?' . $query, '', $base);
		$base = trailingslashit($base);
		
		return array(
			'base'		=>	$base,
			'query'		=>	$query,
			'fragment'	=>	$fragment
		);
		
	}
	
	
	/**
	 * Plugin options.
	 */
		
		// Default settings
		function fwm_defaults($key = null){
			
			$defaults = array(
				'name'				=>		get_bloginfo('name') . ' | ' . get_bloginfo('description'),
				'short_name'		=>		( mb_strstr(get_bloginfo('name'), ' ', true, 'utf-8') ) ? mb_strstr(get_bloginfo('name'), ' ', true, 'utf-8') : get_bloginfo('name'),
				'description'		=>		get_bloginfo('description'),
				'lang'				=>		get_bloginfo('language'),
				'dir'				=>		( is_rtl() ) ? 'rtl' : 'ltr',
				'start_url'			=>		trailingslashit( get_bloginfo('url') ),
				'display'			=>		'standalone',
				'orientation'		=>		'any',
				'background_color'	=>		'#efefef',
				'theme_color'		=>		'#008ec2',
			);
			
			return ( $key !== null && array_key_exists($key, $defaults) ) ? $defaults[$key] : $defaults;
			
		}
		
		
		// Register settings
		fwm_include('sanitize-functions.php');
		
		function fwm_register_settings(){
			
			if( current_user_can('manage_options') ){
				
				register_setting('fwebmanifest', 'fwebmanifest_name', array( 'default' => fwm_defaults('name'), 'sanitize_callback' => 'sanitize_text_field' ));
				register_setting('fwebmanifest', 'fwebmanifest_short_name', array( 'default' => fwm_defaults('short_name'), 'sanitize_callback' => 'sanitize_text_field' ));
				register_setting('fwebmanifest', 'fwebmanifest_description', array( 'default' => fwm_defaults('description'), 'sanitize_callback' => 'fwm_sanitize_description' ));
				register_setting('fwebmanifest', 'fwebmanifest_lang', array( 'default' => fwm_defaults('lang'), 'sanitize_callback' => 'sanitize_text_field' ));
				register_setting('fwebmanifest', 'fwebmanifest_dir', array( 'default' => fwm_defaults('dir'), 'sanitize_callback' => 'fwm_sanitize_dir' ));
				register_setting('fwebmanifest', 'fwebmanifest_start_url', array( 'default' => fwm_defaults('start_url'), 'sanitize_callback' => 'fwm_sanitize_start_url' ));
				register_setting('fwebmanifest', 'fwebmanifest_display', array( 'default' => fwm_defaults('display'), 'sanitize_callback' => 'fwm_sanitize_display' ));
				register_setting('fwebmanifest', 'fwebmanifest_orientation', array( 'default' => fwm_defaults('orientation'), 'sanitize_callback' => 'fwm_sanitize_orientation' ));
				register_setting('fwebmanifest', 'fwebmanifest_bgcolor', array( 'default' => fwm_defaults('background_color'), 'sanitize_callback' => 'sanitize_hex_color' ));
				register_setting('fwebmanifest', 'fwebmanifest_themecolor', array( 'default' => fwm_defaults('theme_color'), 'sanitize_callback' => 'sanitize_hex_color' ));
				
			}
			
		}
		add_action('admin_init', 'fwm_register_settings');
		
		
	/**
	 * Update options based on the site general settings.
	 */
		
		// Name, Description, Start URL
		function fwm_update_bloginfo($old_value, $value, $option){
			
			if( $option == 'blogname' && get_option('fwebmanifest_name') == $old_value ){
				update_option('fwebmanifest_name', $value);
			}
			
			if( $option == 'blogdescription' && get_option('fwebmanifest_description') == $old_value ){
				update_option('fwebmanifest_description', $value);
			}
			
			if( $option == 'home' ){
				update_option('fwebmanifest_start_url', $value);
			}
			
		}
		add_action('update_option_blogname', 'fwm_update_bloginfo', 10, 3);
		add_action('update_option_blogdescription', 'fwm_update_bloginfo', 10, 3);
		add_action('update_option_home', 'fwm_update_bloginfo', 10, 3);
		
		
		// Language, Text direction
		function fwm_update_lang(){
			
			if( current_user_can('manage_options') ){
				
				$wplang	=	get_option('WPLANG');
				$lang	=	( $wplang == '' ) ? 'en-US' : str_replace('_', '-', $wplang);
				$dir	=	( is_rtl() ) ? 'rtl' : 'ltr';
				
				if( get_option('fwebmanifest_lang') != $lang ) update_option('fwebmanifest_lang', $lang);
				if( get_option('fwebmanifest_dir') != $dir ) update_option('fwebmanifest_dir', $dir);
				
			}
			
		}
		add_action('admin_init', 'fwm_update_lang');
		
		
	/**
	 * Options page.
	 */
	fwm_include('options-page.php');
		
		
		// Hide admin bar in preview iframe
		function fwm_preview_hide_toolbar(){
			if( isset($_GET['fwm-preview']) ) add_filter('show_admin_bar', '__return_false');
		}
		add_action('init', 'fwm_preview_hide_toolbar');
		
		
	/**
	 * Update manifest.json
	 */
	function fwm_manifest_update(){
		
		if( current_user_can('manage_options') ){
			
			// manifest params
			$params = array(
				'name'				=>	get_option('fwebmanifest_name'),
				'short_name'		=>	get_option('fwebmanifest_short_name'),
				'description'		=>	get_option('fwebmanifest_description'),
				'lang'				=>	get_option('fwebmanifest_lang'),
				'dir'				=>	get_option('fwebmanifest_dir'),
				'start_url'			=>	get_option('fwebmanifest_start_url'),
				'display'			=>	get_option('fwebmanifest_display'),
				'orientation'		=>	get_option('fwebmanifest_orientation'),
				'background_color'	=>	get_option('fwebmanifest_bgcolor'),
				'theme_color'		=>	get_option('fwebmanifest_themecolor'),
			);
			
			// icons
			if( has_site_icon() ){
				
				$params['icons'] = array();
				
				$faviconID		=	get_option('site_icon');
				$faviconInfo	=	wp_get_attachment_metadata($faviconID);
				$faviconSizes	=	$faviconInfo['sizes'];
				
				foreach( $faviconSizes as $favicon ){
					
					$size	=	$favicon['width'];
					$mime	=	$favicon['mime-type'];
					
					array_push($params['icons'], array(
						'src'	=>	get_site_icon_url($size),
						'sizes'	=>	$size . 'x' . $size,
						'type'	=>	$mime
					));
					
				}
				
			}
			
			// manifest content
			$manifest = '';
			
			if( defined('JSON_UNESCAPED_UNICODE') ){
				
				$manifest	=	json_encode($params, JSON_UNESCAPED_UNICODE);
				
			}
			else {
				
				$manifest	=	preg_replace_callback('/\\\\u(\w{4})/', function($matches){
									return html_entity_decode('&#x' . $matches[1] . ';', ENT_COMPAT, 'UTF-8');
								}, json_encode($params));
				
			}
			
			// update file
			$filePath = wp_normalize_path( plugin_dir_path( __FILE__ ) . 'manifest.json' );
			file_put_contents($filePath, str_replace('\\/', '/', $manifest), LOCK_EX);
			
		}
		
	}
	add_action('admin_init', 'fwm_manifest_update');
	
	
	/**
	 * Include manifest.json
	 */
	function fwm_manifest_include(){
		
		$filePath	=	wp_normalize_path( plugin_dir_path( __FILE__ ) . 'manifest.json' );
		$fileURL	=	plugin_dir_url( __FILE__ ) . 'manifest.json';
		
		if( file_exists($filePath) ){
			echo "\n" . '<!-- Web Manifest -->' . "\n";
			echo '<link rel="manifest" href="' . $fileURL . '" />' . "\n\n";
		}
		
	}
	add_action('wp_head', 'fwm_manifest_include');
	
	
	/**
	 * Deactivate plugin.
	 */
	function fwm_deactivate(){
		$filePath = wp_normalize_path( plugin_dir_path( __FILE__ ) . 'manifest.json' );
		if( file_exists($filePath) ) unlink($filePath);
	}
	register_deactivation_hook(__FILE__, 'fwm_deactivate');
	
	
	/**
	 * Uninstall plugin.
	 */
	function fwm_uninstall(){
		unregister_setting('fwebmanifest', 'fwebmanifest_name');
		unregister_setting('fwebmanifest', 'fwebmanifest_short_name');
		unregister_setting('fwebmanifest', 'fwebmanifest_description');
		unregister_setting('fwebmanifest', 'fwebmanifest_lang');
		unregister_setting('fwebmanifest', 'fwebmanifest_dir');
		unregister_setting('fwebmanifest', 'fwebmanifest_start_url');
		unregister_setting('fwebmanifest', 'fwebmanifest_display');
		unregister_setting('fwebmanifest', 'fwebmanifest_orientation');
		unregister_setting('fwebmanifest', 'fwebmanifest_bgcolor');
		unregister_setting('fwebmanifest', 'fwebmanifest_themecolor');
	}
	register_uninstall_hook(__FILE__, 'fwm_uninstall');
	
?>