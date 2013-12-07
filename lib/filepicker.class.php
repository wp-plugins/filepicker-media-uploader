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

	var $filepicker_options 	= array();
	var $filepicker_services	= array('COMPUTER', 'FACEBOOK', 'INSTAGRAM', 'FLICKR', 'GITHUB', 'BOX', 'GMAIL', 'GOOGLE_DRIVE', 'IMAGE_SEARCH', 'SKYDRIVE', 'PICASA', 'URL', 'WEBDAV', 'DROPBOX', 'EVERNOTE', 'WEBCAM', 'FTP');
	var $filepicker_storage		= array(
									'' 		    => 'None',
									'S3' 		=> 'Amazon s3 Storage',
									'dropbox' 	=> 'Dropbox',
									'rackspace' => 'Rackspace Cloudfiles',
									'azure' 	=> 'Azure',
								);
	var $filepicker_defaults    = array(
									'api_key' 				=> null,
									'media_owner' 			=> 1,
									'maxsize' 				=> 10240, // 10MB
									'onSuccess' 			=> '',
									'mimetypes' 			=> array('image/*'),
									'services' 				=> array('FACEBOOK', 'INSTAGRAM', 'FLICKR'),
									'cloud_storage'			=> '', /* (S3, rackspace, azure and dropbox) */
									'cloud_folder'			=> '',
									'container'				=> 'window', /* (window, modal, iframeID) */
								);

	function __construct() {
		$this->filepicker_options = get_option('filepicker_options');
		if( empty($this->filepicker_options) )
		{
			$this->filepicker_options = $this->filepicker_defaults;
		}
	}

	function store_local(){

		if( is_numeric($this->filepicker_options['media_owner']) ){
			$currentuser = $this->filepicker_options['media_owner'];
		}
		elseif ( current_user_can( 'upload_files' ) ){
			$currentuser = get_current_user_id();
		}
		else{
			return new WP_Error('not_allowed', __("You don't have permission to upload files, please log in to continue", 'filepicker'));
		}

		check_ajax_referer( 'filepicker-media' );

		$filename = pathinfo($_REQUEST['post_data']['inkblob']['url'], PATHINFO_FILENAME);
		$title = preg_replace('/[^\da-z\-]/i', ' ', $filename);

		$attachment = array(
		 'post_author' 		=> $currentuser,
		 'post_date' 		=> date('Y-m-d H:i:s'),
		 'post_type' 		=> 'attachment',
		 'post_title' 		=> $title,
		 'post_parent' 		=> (!empty($_REQUEST['post_id'])?$_REQUEST['post_id']:null),
		 'post_status' 		=> 'inherit',
		 'post_mime_type' 	=> $_REQUEST['post_data']['inkblob']['mimetype'],
		);

		$attachment_id = wp_insert_post( $attachment, true );

		add_post_meta($attachment_id, '_wp_attached_file', $_REQUEST['post_data']['inkblob']['url'], true );
		add_post_meta($attachment_id, '_wp_attachment_metadata', $_REQUEST['post_data']['metadata'], true );
		add_post_meta($attachment_id, 'filepicker_url', $_REQUEST['post_data']['inkblob']['url'], true );

	}

}
