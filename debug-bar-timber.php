<?php
/*
	Plugin Name: Timber Debug Bar
	Plugin URI: https://github.com/upstatement/debug-bar-timber/
	Description: Adds Timber render to the debug bar. Requires the debug bar plugin.
	Author: Jared Nova + Upstatement
	Version: 1.0.8
	Author URI: https://upstatement.com/
*/

add_filter('debug_bar_panels', static function($panels) {
    require_once('class-debug-bar-timber.php');
    $panels[] = new Debug_Bar_Timber();
    return $panels;
});

add_action('init', static function() {
	if ( !class_exists('Debug_Bar') ) {
		$class = 'error';

		$url = admin_url('plugin-install.php?tab=plugin-information&amp;plugin=debug-bar&amp;TB_iframe=true&amp;width=772&amp;height=300');

		$text = "In order to use the Timber Debug Bar, you need to install and activate the <a href='$url' class='thickbox'>WordPress Debug Bar</a> Plugin";

		add_action( 'admin_notices', static function() use ( $text, $class ) {
			echo '<div class="'.$class.'"><p>'.$text.'</p></div>';
		}, 1 );
	}
});

function tdb_enqueue_styles() {
	wp_enqueue_style( 'dbt', plugins_url( "timber-debug-bar.css", __FILE__ ), [], '20200710' );
}
add_action( 'wp_enqueue_scripts', 'tdb_enqueue_styles', 99 );
