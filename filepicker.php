<?php

/*
Plugin Name:  Filepicker.io
Description:  Integration with Filepicker.io - just drop this shortcode into a post or page: [filepicker]
Text Domain:  filepicker
Version:      2.0.5
Author:       5ifty&5ifty
Author URI:   https://www.fiftyandfifty.org/
Contributors: shanaver, filepicker

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

define('FILEPICKER_VERSION', '2.0.5');

if(!defined('FILEPICKER_DEBUG')){define('FILEPICKER_DEBUG', false);}

define('FILEPICKER_PLUGIN_URL', plugin_dir_url( __FILE__ ));
define('FILEPICKER_PLUGIN_PATH', plugin_dir_path(__FILE__) );
define('FILEPICKER_PLUGIN_BASENAME', plugin_basename(__FILE__));

require_once( FILEPICKER_PLUGIN_PATH . '/lib/filepicker.class.php');
require_once( FILEPICKER_PLUGIN_PATH . '/lib/scripts.php');
require_once( FILEPICKER_PLUGIN_PATH . '/lib/shortcodes.php');
require_once( FILEPICKER_PLUGIN_PATH . '/lib/admin_integration.php');
require_once( FILEPICKER_PLUGIN_PATH . '/lib/filepicker_ajax.php');
require_once( FILEPICKER_PLUGIN_PATH . '/lib/languages.php');

$_GLOBALS['filepicker'] = new Filepicker();
