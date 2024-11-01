<?php
	
	/**
	 * Options' sanitize functions.
	 * 
	 * @package		WordPress
	 * @subpackage	Web Manifest
	 * @since		1.0.0
	 * @version		1.0.0
	 */
	
	
	// Do not allow directly accessing this file
	if( !defined('ABSPATH') ) exit;
	
	
	/**
	 * Sanitizes a description value.
	 * 
	 * @param	string	$text	Description text.
	 * 
	 * @return	string	Sanitized description text.
	 */
	function fwm_sanitize_description($text){
		
		$output = sanitize_textarea_field($text);
		if( mb_strlen($output, 'utf-8') > 1024 ) $output = mb_substr($output, 0, mb_strrpos(mb_substr($output, 0, 1024, 'utf-8'), ' ', 'utf-8'), 'utf-8');
		
		return $output;
		
	}
	
	
	/**
	 * Sanitizes a direction value.
	 * 
	 * @param	string	$dir	Direction value - "ltr" or "rtl".
	 * 
	 * @return	string	Sanitized string - "ltr" or "rtl".
	 */
	function fwm_sanitize_dir($dir){
		return ( $dir == 'rtl' ) ? 'rtl' : 'ltr';
	}
	
	
	/**
	 * Sanitizes a site icon value.
	 * 
	 * @param	int		$id		Attachment id.
	 * 
	 * @return	int		Attachment id if exists or 0.
	 */
	function fwm_sanitize_favicon($id){
		return ( wp_attachment_is_image($id) ) ? $id : 0;
	}
	
	
	/**
	 * Sanitizes a start url value.
	 * 
	 * @param	string	$value	Selected value.
	 * 
	 * @return	string	Sanitized value.
	 */
	function fwm_sanitize_start_url($value){
		
		$allLinks	=	fwm_all_pages();
		$allURLs	=	array();
		
		foreach( $allLinks as $type => $links ){
		 for( $i = 0; $i < sizeof($links); $i++ ){
			
			array_push($allURLs, $links[$i]);
			
		 }
		}
		
		$urlFull	=	( function_exists('fwm_url_parse') ) ? fwm_url_parse($value) : '';
		$urlBase	=	( !empty($urlFull) ) ? trailingslashit($urlFull['base']) : trailingslashit($value);
		
		$link = ( in_array($urlBase, $allURLs) ) ? $value : $allURLs[0];
		
		return esc_url_raw($link);
		
	}
	
	
	/**
	 * Sanitizes a display value.
	 * 
	 * @param	string	$value	Selected value.
	 * 
	 * @return	string	Sanitized value.
	 */
	function fwm_sanitize_display($value){
		$options = array('fullscreen', 'standalone', 'minimal-ui', 'browser');
		return ( in_array($value, $options) ) ? $value : 'standalone';
	}
	
	
	/**
	 * Sanitizes an orientation value.
	 * 
	 * @param	string	$value	Selected value.
	 * 
	 * @return	string	Sanitized value.
	 */
	function fwm_sanitize_orientation($value){
		$options = array('any', 'natural', 'landscape', 'landscape-primary', 'landscape-secondary', 'portrait', 'portrait-primary', 'portrait-secondary');
		return ( in_array($value, $options) ) ? $value : 'any';
	}
	
?>