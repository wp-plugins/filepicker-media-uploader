<?php

require_once( FILEPICKER_PLUGIN_PATH . '/filepicker.php');

$filepicker = new Filepicker();

function filepicker_sanitize_admin_inputs($filepicker_options, $filepicker_options_post, $filepicker)
{
	foreach( $filepicker->filepicker_defaults as $field => $default){
		if( isset($filepicker_options_post[$field]) && $field == 'services' ){
			$services = array();
			foreach( $filepicker->filepicker_services as $service ){
				if( in_array($service, $filepicker_options_post['services']) ){ array_push($services, $service); }
			}
			$filepicker_options[$field] = $services;
		}
		elseif (isset($filepicker_options_post[$field]) && $field == 'mimetypes') {
				$filepicker_options['mimetypes'] = explode(',', $filepicker_options_post['mimetypes']);
		}
		elseif( isset($filepicker_options_post[$field]) ){
			$filepicker_options[$field] = sanitize_text_field( $filepicker_options_post[$field] );
		}
		elseif( empty($filepicker_options[$field]) ) {
			$filepicker_options[$field] = $default;
		}
	}
	return $filepicker_options;
}

// get the options (if any) stored in the DB
$filepicker_options = get_option('filepicker_options');

// get any updates to those options sent from the form
$filepicker_options_post = isset($_POST['filepicker_options']) ? $_POST['filepicker_options'] : false;

// overwrite options if needed - also set defaults for options that are not set
$filepicker_options = filepicker_sanitize_admin_inputs($filepicker_options, $filepicker_options_post, $filepicker);

// if the admin made changes, update the DB
if($filepicker_options_post){
	update_option('filepicker_options', $filepicker_options);
}

?>

<style>
	#filepicker-options-form td, #filepicker-options-form th {
		text-align: left;
		vertical-align: top;
		padding-bottom: 30px;
	}
	#filepicker-options-form td.col2 {
		padding: 6px 20px;
		color: #aaa;
	}
</style>

<div class="wrap">

	<div class="icon32" id="icon-options-general"><br></div><h2>Filepicker.io Settings</h2>

	<p style="text-align: left;">
		<h3>Upload & Store any file - from anywhere on the Internet:  <a target="_blank" href="https://www.inkfilepicker.com/">Filepicker.io</a></h3>
	</p>

	<div id="filepicker-options-form">

		<?php if(!$filepicker_options['api_key']): ?>
			<div class="updated" id="message"><p><strong>Alert!</strong> You must get an API Key from Filepicker.io to start<br />If you don't already have an account, you can <a target="_blank" href="https://www.inkfilepicker.com/">sign up for one here</a></p></div>
		<?php endif; ?>

		<form action="" id="filepicker-form" method="post">
			<table class="filepicker-table">
				<tbody>
				<tr>
					<th><label for="category_base">API Key</label></th>
					<td class="col1">
						<input type="text" class="regular-text code" value="<?php echo $filepicker_options['api_key']; ?>" id="filepicker-api_key" name="filepicker_options[api_key]">
					</td>
					<td class="col2">
						Required
					</td>
				</tr>
				<tr>
					<th><label for="category_base">Services</label></th>
					<td class="col1">
						<select id="filepicker-services" name="filepicker_options[services][]" class="large-text" style="width:300px;height:90px" multiple>
						<?php foreach( $filepicker->filepicker_services as $service ): ?>
							<option value="<?php print $service ?>" <?php if( in_array($service, $filepicker_options['services']) ){print "selected='selected'";} ?>><?php print $service ?></option>
						<?php endforeach; ?>
						</select>
					</td>
					<td class="col2">
						These are the services you are allowing people to upload files from
					</td>
				</tr>
				<tr>
					<th><label for="category_base">Mimetypes</label></th>
					<td class="col1">
						<input type="text" class="regular-text code" value="<?php echo join(',', $filepicker_options['mimetypes']); ?>"
					id="filepicker-mimetypes" name="filepicker_options[mimetypes]">
					</td>
					<td class="col2">
						Mimetypes allow to specify the file extensions that can be uploaded.<br>
						NOTE: The coma at the end of the mimetype list must be removed e.g mimetype1,mimetype2
					</td>
				</tr>
				<tr>
					<th><label for="category_base">Upload Container</label></th>
					<td class="col1">
						<input type="text" class="regular-text code" value="<?php echo $filepicker_options['container']; ?>" id="filepicker-container" name="filepicker_options[container]">
					</td>
					<td class="col2">
						Where the filepicker.io uploader should be - default is a popup window<br/>
						(window, modal, iframeID)
					</td>
				</tr>
				<tr>
					<th><label for="category_base">Media Owner</label></th>
					<td class="col1">
						<?php $users = get_users( array('orderby' => 'id', 'order' => 'DESC') ); ?>
						<select id="filepicker-media_owner" name="filepicker_options[media_owner]" class="large-text" style="width:300px">
							<option value="logged_in" <?php if( 'logged_in' == $filepicker_options['media_owner'] ){print "selected='selected'";} ?>>User must be logged in to wordpress to upload files</option>
						<?php foreach( $users as $user ): ?>
							<option value="<?php print $user->ID ?>" <?php if( $user->ID == $filepicker_options['media_owner'] ){print "selected='selected'";} ?>><?php print $user->data->display_name ?></option>
						<?php endforeach; ?>
						</select>
					</td>
					<td class="col2">
						Define what wordpress user will own the files that are uploaded.<br/>
					</td>
				</tr>
				<tr>
					<th><label for="category_base">Cloud Storage<br/><small>(optional)</small></label></th>
					<td class="col1">
						<select id="filepicker-cloud_storage" name="filepicker_options[cloud_storage]" class="large-text" style="width:300px">
						<?php foreach( $filepicker->filepicker_storage as $type => $desc ): ?>
							<option value="<?php print $type ?>" <?php if( $type == $filepicker_options['cloud_storage'] ){print "selected='selected'";} ?>><?php print $desc ?></option>
						<?php endforeach; ?>
						</select>
					</td>
					<td class="col2">
						Copy uploads to your own S3, Dropbox, etc.<br/>
						Requires additional setup & costs at Filepicker.io
					</td>
				</tr>
				<tr>
					<th><label for="category_base">Cloud Folder<br/><small>(optional)</small></label></th>
					<td class="col1">
						<input type="text" class="regular-text code" value="<?php echo $filepicker_options['cloud_folder']; ?>" id="filepicker-cloud_folder" name="filepicker_options[cloud_folder]">
					</td>
					<td class="col2">
						Bucket/Container to use inside of your cloud storage
					</td>
				</tr>
				<tr>
					<th><label for="category_base">OnSuccess Function<br/><small>(optional)</small></label></th>
					<td class="col1">
						<textarea class="regular-text code" id="filepicker-onSuccess" style="width:300px;height:80px" name="filepicker_options[onSuccess]"><?php echo stripslashes($filepicker_options['onSuccess']); ?></textarea>
					</td>
					<td class="col2">
						Javascript to be performed after the filepicker popup closes.<br/>
						This will not fire when filepicker.io is used in the admin, only when it's used via the shortcode.
					</td>
				</tr>
				<tr>
					<th>&nbsp;</th>
					<td class="col1">
						<input type="submit" value="Save Settings" class="button-primary"/>
					</td>
					<td class="col2">
					</td>
				</tr>
				</tbody>
			</table>
		</form>

		<p style="text-align: left;">
			<h3>Shortcode Examples</h3>
		</p>

		<table class="filepicker-notes">
			<tbody>
			<tr>
				<td class="col1">
					[filepicker]
				</td>
				<td class="col2">
					Drop this in a page or post to allow users the ability to attach images to that page or post
				</td>
			</tr>
			<tr>
				<td class="col1">
					[filepicker<br/> button_title='Upload a Photo'<br/> post_id=1]
				</td>
				<td class="col2">
					In this example, we customize the button title and define a different post to attach the uploaded images to
				</td>
			</tr>
			<tr>
				<td class="col1">
					[filepicker<br/> iframe_src=/upload]
				</td>
				<td class="col2">
					In this example, we are creating an iframe that includes the /upload page.<br/>
					Be sure to set the Upload Container to the iframe ID
				</td>
			</tr>
			</tbody>
		</table>


	</div><!-- filepicker-options-form -->

</div>