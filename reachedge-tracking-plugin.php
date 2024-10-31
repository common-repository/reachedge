<?php

/**
 * Plugin Name:       LOCALiQ - Tracking Code
 * Plugin URI:        https://github.com/reachlocal/localiq-wordpress-4x-tracking-plugin
 * Description:       Enables the <a href="https://localiq.com/">LOCALiQ</a> Tracking Code on all your site pages.
 * Version:           1.9.1
 * Author:            ReachLocal, Inc.
 * Author URI:        http://www.reachlocal.com/
 * License:           MIT license
 * License URI:       https://opensource.org/licenses/MIT
 */

define('DEFAULT_CODE', '00000000-0000-0000-0000-000000000000');

if (!defined('WPINC')) {
  die;
}

function reachedge_tracking_plugin() {
  $reachlocal_tracking_id = get_option('reachlocal_tracking_code_id' );

  if (strlen($reachlocal_tracking_id) == strlen(constant('DEFAULT_CODE')) && $reachlocal_tracking_id != DEFAULT_CODE) {
    wp_enqueue_script( 'reachlocal_tracking_script', reachedge_code_snippet_src($reachlocal_tracking_id));
    wp_script_add_data( 'reachlocal_tracking_script', 'strategy', 'async' );
  }
}

if (is_admin()) {
  require_once(plugin_dir_path(__FILE__) . 'reachedge-tracking-plugin-settings.php');
} else {
  add_action('wp_head', 'reachedge_tracking_plugin');
}

/**
 * Convert site_id from 'fc62c28f-3f38-4812-85c3-b3fe1329dba8' to '555/6e6/569/cfc4c23ac7e7ab663b58748.js';
 * Return '//cdn.rlets.com/capture_configs/fc6/2c2/8f3/f38481285c3b3fe1329dba8.js#asyncload'
 */
function reachedge_code_snippet_src($reachlocal_tracking_id) {
	$site_id = array();
	array_push($site_id, (substr($reachlocal_tracking_id, 0, 8)));
	array_push($site_id, (substr($reachlocal_tracking_id, 9, 4)));
	array_push($site_id, (substr($reachlocal_tracking_id, 14, 4)));
	array_push($site_id, (substr($reachlocal_tracking_id, 19, 4)));
	array_push($site_id, (substr($reachlocal_tracking_id, 24, 12)));
	$flattened_site_id = implode("",$site_id);
	$snippet_src = array();
	array_push($snippet_src, '//cdn.rlets.com/capture_configs/');
	array_push($snippet_src, (substr($flattened_site_id, 0, 3)));
	array_push($snippet_src, '/');
	array_push($snippet_src, (substr($flattened_site_id, 3, 3)));
	array_push($snippet_src, '/');
	array_push($snippet_src, (substr($flattened_site_id, 6, 3)));
	array_push($snippet_src, '/');
	array_push($snippet_src, (substr($flattened_site_id, 9, 23)));
	array_push($snippet_src, '.js');
	return implode('', $snippet_src);
}

