<?php

require_once( FILEPICKER_PLUGIN_PATH . '/filepicker.php');

$filepicker = new Filepicker();

function filepicker_sanitize_admin_inputs($filepicker_options, $filepicker_options_post, $filepicker)
{
	$filepicker_defaults = array(
		'api_key' 				=> null,
		'default_user' 			=> 1,
		'guest_access' 			=> 1,
		'maxsize' 				=> 10*1024,
		'onSuccess' 			=> '',
		'mimetypes' 			=> array('image/*'),
		'services' 				=> array('FACEBOOK', 'INSTAGRAM', 'FLICKR'),
	);
	foreach( $filepicker_defaults as $field => $default){
		if( isset($filepicker_options_post[$field]) && $field == 'services' ){
			$services = array();
			foreach( $filepicker->all_file_picker_services as $service ){
				if( in_array($service, $filepicker_options_post['services']) ){ array_push($services, $service); }
			}
			$filepicker_options[$field] = $services;
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

<div class="wrap">

	<div class="icon32" id="icon-options-general"><br></div><h2>Filepicker.io Settings</h2>

	<p style="text-align: left;">
		<h3>Connect, Store, and Process any file from anywhere on the Internet:  <a target="_blank" href="https://www.inkfilepicker.com/">Filepicker.io</a></h3>
	</p>

	<div id="filepicker-options-form">

		<?php if(!$filepicker_options['api_key']): ?>
			<div class="updated" id="message"><p><strong>Alert!</strong> You must get an API Key from Filepicker.io to start<br />If you don't already have an account, you can <a target="_blank" href="https://www.inkfilepicker.com/">sign up for one here</a></p></div>
		<?php endif; ?>

		<form action="" id="filepicker-form" method="post">
			<table class="filepicker-table">
				<tbody>
				<tr>
					<th><label for="category_base">Filepicker.io API Key</label></th>
					<td class="col1"></td>
					<td class="col2">
						<input type="text" class="regular-text code" value="<?php echo $filepicker_options['api_key']; ?>" id="filepicker-api_key" name="filepicker_options[api_key]">
					</td>
				</tr>
				<tr>
					<th><label for="category_base">Filepicker.io Services</label></th>
					<td class="col1"></td>
					<td class="col2">
						<select id="filepicker-services" name="filepicker_options[services][]" class="large-text" style="width:300px;height:90px" multiple>
						<?php foreach( $filepicker->all_file_picker_services as $service ): ?>
							<option value="<?php print $service ?>" <?php if( in_array($service, $filepicker_options['services']) ){print "selected='selected'";} ?>><?php print $service ?></option>
						<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<th><label for="category_base">OnSuccess Function</label></th>
					<td class="col1"></td>
					<td class="col2">
						<textarea class="regular-text code" id="filepicker-onSuccess" style="width:300px;height:120px" name="filepicker_options[onSuccess]"><?php echo $filepicker_options['onSuccess']; ?></textarea>
					</td>
				</tr>
				<tr>
					<th>&nbsp;</th>
					<td class="col1"></td>
					<td class="col2">
						<input type="submit" value="Save Settings" class="button-primary"/>
					</td>
				</tr>
				</tbody>
			</table>
		</form>

	</div><!-- filepicker-options-form -->

</div>