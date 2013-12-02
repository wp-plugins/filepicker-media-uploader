
/**
*
* custom Ajax function for saving the media to wordpress after filepicker.io uploads it
*
**/
function create_wordpress_media( inkblob, postID )
{
	var stats = filepicker.stat
	(
		inkblob, {width: true, height: true},
		function(metadata)
		{

			filepicker_data                 = {};
			filepicker_data.action 			= "filepicker_store_local";
			filepicker_data._ajax_nonce 	= window.filepicker_ajax.nonce;
			filepicker_data.post_id 		= postID;

			filepicker_data.post_data               = {};
			filepicker_data.post_data.name 			= inkblob.filename;
			filepicker_data.post_data.file_url		= inkblob.url;
			filepicker_data.post_data.type 			= inkblob.mimetype;
			filepicker_data.post_data.width 		= metadata.width;
			filepicker_data.post_data.height 		= metadata.height;
			filepicker_data.post_data.size			= inkblob.size;
			filepicker_data.post_data.isWriteable 	= inkblob.isWriteable;

			ajaxurl = window.filepicker_ajax.ajaxurl;

			FF_AJAX.make_ajax_request(ajaxurl, filepicker_data, window.filepicker_ajax.onSuccess)
		}
	);
}



/**
*
* Filepicker.io functions are below
*
**/


/* PICK function */
jQuery(document).ready(function($)
{

	/* set the filepicker.io API key */
	filepicker.setKey(window.filepicker_ajax.apikey);

	jQuery(".fp-pick").click(function(e)
	{
		e.preventDefault();
		var postID = $(this).attr('data-postid');
		filepicker.pick
		(
			{
				container: 'window',
				services: window.filepicker_ajax.services,
				mimetypes: window.filepicker_ajax.mimetypes,
				maxsize: window.filepicker_ajax.maxsize,
				//debug: window.filepicker_ajax.debug,
			},
			function(InkBlob){
				console.log(InkBlob);
				create_wordpress_media( InkBlob, postID );
			},
			function(FPError){
				console.log(FPError.toString());
			}
		);
	});
});

