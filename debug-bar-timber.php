<?php
/*
	Plugin Name: Timber Debug Bar
	Plugin URI: http://github.com/upstatement/debug-bar-timber/
	Description: Adds Timber render to the debug bar. Requires the debug bar plugin.
	Author: jarednova + upstatement
	Version: 0.1
	Author URI: http://upstatement.com/
	*/

	if (!class_exists('Debug_Bar_Panel')){
		echo 'In order to use the Timber Debugger you need to install the <a href="http://wordpress.org/plugins/debug-bar/" target="_blank">WordPress Debug Bar</a>';
	}

	add_filter('debug_bar_panels', function($panels){
	    require_once('class-debug-bar-timber.php');
	    $panels[] = new Debug_Bar_Timber();
	    return $panels;
	});