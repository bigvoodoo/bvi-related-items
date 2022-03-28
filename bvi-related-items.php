<?php

/**
 * BVI Related Items
 *
 * @link              https://www.bigvoodoo.com
 * @package           Bvi_Related_Items
 * @author						Christina Gleason
 * @copyright					2021 Big Voodoo Interactive
 *
 * @wordpress-plugin
 * Plugin Name:       BVI Related Items
 * Plugin URI:        https://github.com/bigvoodoo/bvi-related-items
 * Description:       This will allow you to set custom related items, either posts or pages, to a specific set of pages through shortcode [related-items]
 * Version:           4.0.3
 * Requires at least: 5.6
 * Requires PHP:      7.2
 * Author:            Big Voodoo Interactive
 * Author URI:        https://www.bigvoodoo.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bvi-related-items
 * GitHub Plugin URI: https://github.com/bigvoodoo/bvi-related-items
 * 
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'BVI_RELATED_ITEMS_VERSION', '4.0.2' );

if ( ! defined( 'BVI_RELATED_ITEMS_PLUGIN_BASE' ) ) {
	define( 'BVI_RELATED_ITEMS_PLUGIN_BASE', plugin_basename( __FILE__ ) );
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-bvi-related-items.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_bvi_related_items() {

	$plugin = new Bvi_Related_Items();
	$plugin->run();

}
run_bvi_related_items();
