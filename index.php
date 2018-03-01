<?php
/*
Plugin Name: Vendhub Functions Kit
Plugin URI: http://www.vendhub.net
Description: This a plugin that contains snippets used by Vendhhub devs to remove or add basic metaboxs and other features when starting a new wordpress project
Author: Marshall Fungai
Version: 1.3.3
Author URI: http://digitalartists.biz

*/



define('PLUGIN_PATH',plugin_dir_path(__FILE__));

//ob_start();

require_once PLUGIN_PATH . 'incl/bank-account-settings.php';
require_once PLUGIN_PATH . 'incl/add_support.php';
