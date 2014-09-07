<?php
/**
 * Plugin Name: WP Metabox
 * Plugin URI: http://github.com/jesseoverright/wp-metabox
 * Description: A lightweight framework for creating custom metaboxes in WordPress.
 * Version: 0.1
 * Author: Jesse Overright
 * Author URI: http://jesseoverright.com
 * License: GPL2
 */

/*  Copyright 2013  Jesse Overright  (email : jesseoverright@gmail.com)

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

include_once( dirname( __FILE__ ) . '/lib/wp-postmeta.php' );
include_once( dirname( __FILE__ ) . '/lib/wp-metabox.php' );
include_once( dirname( __FILE__ ) . '/test.php' );