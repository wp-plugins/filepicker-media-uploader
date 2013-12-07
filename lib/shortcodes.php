<?php

add_shortcode( 'filepicker', 'filepicker_show' );
function filepicker_show($atts) {
	global $post;

	extract(shortcode_atts(array(
		'post_id' 		=> $post->ID,
		'button_title'	=> __('Upload a File', 'filepicker'),
		'iframe_src' 	=> null,
	), $atts));

	if( !empty($_GLOBALS['filepicker']) ){
		$filepicker = $_GLOBALS['filepicker'];
	}
	else{
		$filepicker = new Filepicker();
	}

	print "<button class='fp-pick' data-postid='{$post_id}'>{$button_title}</button>";
	if( $filepicker->filepicker_options['container'] != 'window' && $filepicker->filepicker_options['container'] != 'modal' )
	{
		$src = $iframe_src ? $iframe_src : 'about:blank';
		print "<iframe id='".$filepicker->filepicker_options['container']."' src='{$src}'></iframe> ";
	}

}