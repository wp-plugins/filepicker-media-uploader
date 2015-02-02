
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

            /* attach this inkblob to the window object so we can access it (from the OnSuccess function et. al.) */
            window.inkblob = inkblob;

            /* create a new wordpress media post type and link to the filepicker.io upload */
            /* Switch to browse tab if in admin panel */
            if (jQuery("body").hasClass("wp-admin")) {
                if (typeof wpActiveEditor !== "undefined"){
                    var elem = jQuery(wp.media.editor.get(wpActiveEditor)
                                      .views._views[".media-frame-router"][0]
                                      ._views.browse.el)
                                      if (elem) {
                                          elem
                                          .triggerHandler("click");
                                      }
                }
            }

            FF_AJAX.make_ajax_request(window.filepicker_ajax.ajaxurl, filepicker_data, window.filepicker_ajax.onSuccess)

            // Refresh the media library after upload
            if (jQuery("body").hasClass("wp-admin")) {
                var elem;
                if (typeof wpActiveEditor !== "undefined"){
                    elem = wp.media.editor.get(wpActiveEditor)
                } else {
                    elem = wp.media.frame;
                }
                elem
                .views
                ._views[".media-frame-content"][0]
                .views
                ._views[""][3]
                .collection
                .props
                .set({ignore: (+(new Date()))});
            }
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

    filepicker.pick
    (
        options,
        function(InkBlob)
        {
            if( window.filepicker_ajax.cloud_storage ){

                filepicker.store
                (
                    InkBlob,
                    {
                        location: window.filepicker_ajax.cloud_storage,
                        container: window.filepicker_ajax.cloud_folder,
                        access: 'public',
                    },
                    function(InkBlob){
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

                           jQuery("body").on("click", ".fp-pick", function(e)
                                             {
                                                 e.preventDefault();
                                                 if( isNaN(window.filepicker_ajax.perms) ){
                                                     alert(window.filepicker_ajax.perms);
                                                 }
                                                 else{
                                                     fpforwp.pick( {postID : jQuery(this).attr('data-postid')} );
                                                 }
                                             });
                       });

