<?php

/**
 * Displays the filpicker uploader link in the admin area.
 *
 * @package WordPress
 * @subpackage Filepicker.io Plugin
 * @since 1.0.0
 */

add_action('post-upload-ui', 'filepicker_media_upload');
function filepicker_media_upload()
{

	if ( $post = get_post() )
		$browser_uploader .= '&amp;post_id=' . intval( $post->ID );
	elseif ( ! empty( $GLOBALS['post_ID'] ) )
		$browser_uploader .= '&amp;post_id=' . intval( $GLOBALS['post_ID'] );

	?>
	<p class="filepickerio_upload">
		<button class="fp-pick button-secondary" onclick="fpforwp.pick()"><?php print __( 'Filepicker.io uploader', 'filepicker'); ?></button>
	</p>
	<?php
}



/**
 * Fixes the attachment url (so it doesn't look in the local uploads directory)
 *
 * @package WordPress
 * @subpackage Filepicker.io Plugin
 * @since 1.0.0
 */

add_filter('wp_get_attachment_url', 'filepicker_get_attachment_url', 9, 2);
function filepicker_get_attachment_url($url, $postID)
{
	$filepicker_url = get_post_meta($postID, 'filepicker_url', true);

	if( !empty($filepicker_url) ){
		return $filepicker_url;
	}
	else{
		return $url;
	}
}



/**
 * Add the plugins settings page
 *
 * @package WordPress
 * @subpackage Filepicker.io Plugin
 * @since 1.0.0
 */

function filepicker_add_menu_page(){
	function filepicker_menu_page(){
		$options_page_url = FILEPICKER_PLUGIN_PATH . '/lib/admin-options.php';
		if(file_exists($options_page_url)){
			include_once($options_page_url);
		}
	};
	add_submenu_page( 'options-general.php', 'Filepicker', 'Filepicker', 'switch_themes', 'filepicker', 'filepicker_menu_page' );
};
add_action( 'admin_menu', 'filepicker_add_menu_page' );


/**
 * Add a link to the settings page in the plugins section
 *
 * @package WordPress
 * @subpackage Filepicker.io Plugin
 * @since 1.0.0
 */

function filepicker_plugin_settings_link($links) {
  $settings_link = __('<a href="options-general.php?page=filepicker">Settings</a>');
  //$settings_link = printf( __('<a href="options-general.php?page=filepicker"> %1$s </a>', 'filepicker'), 'Settings');
  array_unshift($links, $settings_link);
  return $links;
}
add_filter("plugin_action_links_" . FILEPICKER_PLUGIN_BASENAME, 'filepicker_plugin_settings_link' );
