<?php
/**
 * Plugin Name: WP Metabox
 * Plugin URI: http://github.com/jesseoverright/wp-metabox
 * Description: A lightweight framework for creating custom metaboxes and custom content types in WordPress.
 * Version: 0.7
 * Author: Jesse Overright
 * Author URI: http://jesseoverright.com
 * License: GPL2
 */

/*  Copyright 2014  Jesse Overright  (email : jesseoverright@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once( plugin_dir_path( __FILE__ ) . '/includes/wp-postmeta.php' );
require_once( plugin_dir_path( __FILE__ ) . '/includes/wp-postmeta-factory.php' );
require_once( plugin_dir_path( __FILE__ ) . '/includes/wp-metabox.php' );
require_once( plugin_dir_path( __FILE__ ) . '/includes/wp-custom-content-type.php' );

// include this file for examples of usage
#include_once( plugin_dir_path( __FILE__ ) . '/examples.php' );

function wp_metabox_init() {
    // load any dependent plugins
    do_action( 'wp_metabox_init' );
}

function wp_metabox_enqueue_style() {
    wp_enqueue_style( 'wp-metabox', plugin_dir_url( __FILE__ ) . 'wp-metabox.css' );
}

add_action( 'plugins_loaded', 'wp_metabox_init' );
add_action( 'admin_init', 'wp_metabox_enqueue_style' );