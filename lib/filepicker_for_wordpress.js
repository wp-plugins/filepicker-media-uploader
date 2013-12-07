
/**
*
* custom Ajax function for saving the media to wordpress after filepicker.io uploads it
*
**/
function create_wordpress_media( inkblob, postID )
{
	var stats = filepicker.stat
	(
		inkblob, {width: true, height: true, size: true, uploaded: true, filename: true, location: true, path: true, container: true, mimetype: true},
		function(metadata)
		{

			filepicker_data                 	= {};
			filepicker_data.action 				= "filepicker_store_local";
			filepicker_data._ajax_nonce 		= window.filepicker_ajax.nonce;
			filepicker_data.post_id 			= postID;
			filepicker_data.post_data           	= {};
			filepicker_data.post_data.metadata 		= metadata;
			filepicker_data.post_data.inkblob 		= inkblob;

			FF_AJAX.make_ajax_request(window.filepicker_ajax.ajaxurl, filepicker_data, window.filepicker_ajax.onSuccess)
		}
	);
}


/**
*
* Create the filepickerForWordpress local object
*
**/


filepickerForWordpress = {}
filepickerForWordpress.pick = function( filepicker_options )
{
	if( filepicker_options === undefined ){ filepicker_options = {} }

	var postID = filepicker_options.postID || null;
	var options = {
		container: window.filepicker_ajax.container,
		services: window.filepicker_ajax.services,
		mimetypes: window.filepicker_ajax.mimetypes,
		maxsize: window.filepicker_ajax.maxsize,
	};

	console.log('pick');
	filepicker.pick
	(
		options,
		function(InkBlob)
		{
			if( window.filepicker_ajax.cloud_storage ){

				console.log('store');
				filepicker.store
				(
					InkBlob,
					{
						location: window.filepicker_ajax.cloud_storage,
						container: window.filepicker_ajax.cloud_folder,
						access: 'public',
					},
					function(InkBlob){
						console.log(InkBlob);
						create_wordpress_media( InkBlob, postID );
					},
					function(FPError){
						console.log(FPError.toString());
					}
				);

			}
			else{
				create_wordpress_media( InkBlob, postID );
			}

		},
		function(FPError){
			console.log(FPError.toString());
		}
	);

}
window.fpforwp = filepickerForWordpress;



/**
*
* attach the filepicker.io popup to any button with class=fp-pick
*
**/


jQuery(document).ready(function($)
{

	/* set the filepicker.io API key */
	filepicker.setKey(window.filepicker_ajax.apikey);

	jQuery(".fp-pick").click(function(e)
	{
		e.preventDefault();
		if(  isNaN(window.filepicker_ajax.perms) ){
			alert(window.filepicker_ajax.perms);
		}
		else{
			fpforwp.pick( {postID : jQuery(this).attr('data-postid')} );
		}
	});
});

