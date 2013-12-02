<?php

/**
 * Ajax function for putting filepicker.io files into the media section
 *
 * @since 1.0.0
 */
function filepicker_store_local(){
	$filepicker = new Filepicker();
	$filepicker->store_local();
}
add_action( 'wp_ajax_filepicker_store_local', 'filepicker_store_local' );
add_action( 'wp_ajax_nopriv_filepicker_store_local', 'filepicker_store_local' );