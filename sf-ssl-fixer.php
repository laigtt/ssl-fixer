<?php
/**
 * @package SSL_Fixer
 * @version 0.1
 */
/*
Plugin Name: SSL Fixer
Plugin URI: https://wordpress.org/extend/plugins/ssl-fixer/
Description: An extremely simple and lightweight plugin that forces your SSL to work!
Author: Frank Altera Novoa
Version: 0.1
Author URI: https://stalwartfox.net/
*/
/*  Copyright 2014  Frank Altera Novoa  (email : contact@stalwartfox.net)
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
defined('ABSPATH') or die("You do not have access to this page!");

function fix_insecure_links() { //Fix insecure links within the database.
	global $wpdb;

	$table = $wpdb->prefix . 'options';
	$query = "UPDATE " . $table . " SET option_value = REPLACE(option_value, 'http://', 'https://') WHERE option_name = 'home' OR option_name = 'siteurl'";
	$wpdb->query($query);

	$table = $wpdb->prefix . 'posts';
	$query = "UPDATE " . $table . " SET post_content = REPLACE(post_content, 'http://', 'https://')";
	$wpdb->query($query);

	$table = $wpdb->prefix . 'postmeta';
	$query = "UPDATE " . $table . " SET meta_value = REPLACE(meta_value, 'http://', 'https://')";
	$wpdb->query($query);

	$table = $wpdb->prefix . 'comments';
	$query = "UPDATE " . $table . " SET comment_content = REPLACE(comment_content, 'http://', 'https://')";
	$wpdb->query($query);
}

function fix_wpconfig() { //Modify any insecure links within the wp-config.php file
	$path = ABSPATH . 'wp-config.php';
	$contents = file_get_contents($path);
	$contents = str_replace('http://', 'https://', $contents);
	file_put_contents($path, $contents);
}

function fix_it() {
	fix_wpconfig();
	fix_insecure_links();
}

register_activation_hook(__FILE__, 'fix_it');
?>