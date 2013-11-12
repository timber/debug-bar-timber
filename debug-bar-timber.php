<?php
/*
	Plugin Name: Timber Debug Bar
	Plugin URI: http://github.com/upstatement/debug-bar-timber/
	Description: Adds Timber render to the debug bar. Requires the debug bar plugin.
	Author: jarednova + upstatement
	Version: 0.2
	Author URI: http://upstatement.com/
	*/

	add_filter('debug_bar_panels', function($panels){
	    require_once('class-debug-bar-timber.php');
	    $panels[] = new Debug_Bar_Timber();
	    return $panels;
	});
