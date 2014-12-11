<?php

/**
*
* Enqueue javascripts & CSS
*
* @since 2.1.0
*
**/

add_action('init', 'filepicker_scripts');
function filepicker_scripts()
{
	// Load a CSS in order to make the Filepicker modal pop up on the foreground of the Wordpress modal
	wp_register_style('filepicker-style', FILEPICKER_PLUGIN_URL . 'css/filepicker_style.css');
	wp_register_script('filepicker', FILEPICKER_PLUGIN_URL . 'lib/filepicker.js');
	wp_register_script('filepicker_debug', 'https://api.filepicker.io/v1/filepicker_debug.js', array('filepicker'));
	wp_register_script('filepicker_for_wordpress', FILEPICKER_PLUGIN_URL . 'lib/filepicker_for_wordpress.js', array('filepicker', 'jquery'));
	wp_register_script('cross_browser_ajax', FILEPICKER_PLUGIN_URL . 'lib/cross_browser_ajax.js', array('jquery'));

	if( !empty($_GLOBALS['filepicker']) ){
		$filepicker = $_GLOBALS['filepicker'];
	}
	else{
		$filepicker = new Filepicker();
	}

	if( is_numeric($filepicker->filepicker_options['media_owner']) ){
		$perms = $filepicker->filepicker_options['media_owner'];
	}
	elseif ( current_user_can( 'upload_files' ) ){
		$perms = get_current_user_id();
	}
	else{
		$perms = __("You don't have permission to upload files, please log in to continue", 'filepicker');
	}

	wp_localize_script( 'filepicker_for_wordpress', 'filepicker_ajax',
		array(
			'ajaxurl' 		=> admin_url( 'admin-ajax.php' ),
			'nonce'   		=> wp_create_nonce('filepicker-media'),
			'apikey'  		=> $filepicker->filepicker_options['api_key'],
			'maxsize'		=> $filepicker->filepicker_options['maxsize'],
			'debug'			=> (FILEPICKER_DEBUG ? 'true' : 'false'),
			'onSuccess'		=> stripslashes($filepicker->filepicker_options['onSuccess']),
			'services'		=> $filepicker->filepicker_options['services'],
			'container'		=> $filepicker->filepicker_options['container'],
			'mimetypes'		=> $filepicker->filepicker_options['mimetypes'],
			'cloud_storage' => $filepicker->filepicker_options['cloud_storage'],
			'cloud_folder' 	=> $filepicker->filepicker_options['cloud_folder'],
			'perms' 		=> $perms,
		)
	);

	add_action('wp_enqueue_scripts', function(){
		// Enqueue a CSS on the front end
		wp_enqueue_style('filepicker-style');
		wp_enqueue_script('filepicker');
		wp_enqueue_script('filepicker_for_wordpress');
		wp_enqueue_script('cross_browser_ajax');
		if( FILEPICKER_DEBUG ){ wp_enqueue_script('filepicker_debug'); }
	});

	add_action('admin_enqueue_scripts', function(){
		// Enqueue a CSS on admin pages
		wp_enqueue_style('filepicker-style');
		wp_enqueue_script('filepicker');
		wp_enqueue_script('filepicker_for_wordpress');
		wp_enqueue_script('cross_browser_ajax');
		if( FILEPICKER_DEBUG ){ wp_enqueue_script('filepicker_debug'); }
	});

}
