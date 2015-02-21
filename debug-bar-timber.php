<?php
/*
    Plugin Name: Timber Debug Bar
    Plugin URI: http://github.com/upstatement/debug-bar-timber/
    Description: Adds Timber render to the debug bar. Requires the debug bar plugin.
    Author: jarednova + upstatement
    Version: 0.3
    Author URI: http://upstatement.com/
    */

    add_filter('debug_bar_panels', function($panels){
        require_once('class-debug-bar-timber.php');
        $panels[] = new Debug_Bar_Timber();
        return $panels;
    });

    if (!class_exists('Debug_Bar')) {
        $class = 'error';
        
        $url = admin_url('plugin-install.php?tab=plugin-information&amp;plugin=debug-bar&amp;TB_iframe=true&amp;width=772&amp;height=300');

        $text = "In order to use the Timber Debug Bar, you need to install and activate the <a href='$url' class='thickbox'>WordPress Debug Bar</a> Plugin";
        
        add_action( 'admin_notices', function() use ( $text, $class ) {
                echo '<div class="'.$class.'"><p>'.$text.'</p></div>';
            }, 1 );
    }