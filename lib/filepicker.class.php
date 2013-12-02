<?php

/**
 *
 * Filepicker Class
 *
 * @package WordPress
 * @subpackage Filepicker.io Plugin
 *
**/
class Filepicker {

	var $filepicker_options = array();
	var $all_file_picker_services = array('COMPUTER', 'FACEBOOK', 'INSTAGRAM', 'FLICKR', 'GITHUB', 'BOX', 'GMAIL', 'GOOGLE_DRIVE', 'IMAGE_SEARCH', 'SKYDRIVE', 'PICASA', 'URL', 'WEBDAV', 'DROPBOX', 'EVERNOTE', 'WEBCAM', 'FTP');

	function __construct() {
		$this->filepicker_options = get_option('filepicker_options');
	}

	function store_local(){

		/* get plugin options or set default values */
		$default_user = (!empty($this->filepicker_options['default_user']) ? $this->filepicker_options['default_user'] : 1);
		$guest_access = (!empty($this->filepicker_options['guest_access']) ? $this->filepicker_options['guest_access'] : true);

		if( $guest_access )
		{
			$currentuser = $default_user;
		}
		elseif ( current_user_can( 'upload_files' ) )
		{
			$currentuser = get_current_user_id();
		}
		else
		{
			return new WP_Error('not_allowed', __("You don't have permission to upload files, please log in to continue", 'filepicker'));
		}

		check_ajax_referer( 'filepicker-media' );

		$filename = pathinfo($_REQUEST['post_data']['name'], PATHINFO_FILENAME);
		$title = preg_replace('/[^\da-z\-]/i', ' ', $filename);

		$attachment = array(
		 'post_author' 		=> $currentuser,
		 'post_date' 		=> date('Y-m-d H:i:s'),
		 'post_type' 		=> 'attachment',
		 'post_title' 		=> $title,
		 'post_parent' 		=> (!empty($_REQUEST['post_id'])?$_REQUEST['post_id']:null),
		 'post_status' 		=> 'inherit',
		 'post_mime_type' 	=> $_REQUEST['post_data']['type'],
		);

		$attachment_id = wp_insert_post( $attachment, true );

		add_post_meta($attachment_id, '_wp_attached_file', $_REQUEST['post_data']['file_url'], true );
		add_post_meta($attachment_id, '_wp_attachment_metadata', $_REQUEST['post_data'], true );
		add_post_meta($attachment_id, 'filepicker_url', $_REQUEST['post_data']['file_url'], true );

	}

}
