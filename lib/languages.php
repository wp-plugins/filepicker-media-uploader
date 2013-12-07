<?php

function filepicker_init() {
 $plugin_dir = basename(dirname(__FILE__));
 load_plugin_textdomain( 'filepicker', false, $plugin_dir . 'languages' );
}
add_action('plugins_loaded', 'filepicker_init');