<?php

add_shortcode( 'filepicker', 'filepicker_show' );
function filepicker_show($atts) {
	global $post;

	extract(shortcode_atts(array(
		'post_id' => $post->ID,
		'button_title' => __('Upload a File', 'filepicker')
	), $atts));

	print "<button class='fp-pick' data-postid='{$post_id}'>{$button_title}</button>";

}