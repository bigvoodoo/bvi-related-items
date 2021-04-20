<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.bigvoodoo.com
 * @since      1.0.0
 *
 * @package    Bvi_Related_Items
 * @subpackage Bvi_Related_Items/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bvi_Related_Items
 * @subpackage Bvi_Related_Items/admin
 * @author     Big Voodoo Interactive <programmers@bigvoodoo.com>
 */
class Bvi_Related_Items_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, 'http://ajax.googleapis.com/ajax/libs/jqueryui/' . $wp_scripts->registered['jquery-ui-core']->ver . '/themes/smoothness/jquery-ui.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'jquery-ui-core', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bvi-related-items-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the settings page for the plugin on the Plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_actions($links) {
	
		array_unshift($links, '<a href="options-general.php?page=' . plugin_basename(__FILE__) . '">' . __('Settings') . '</a>');
		
		return $links;
	
	}

	function wporg_options_page() {

		add_submenu_page(
			'tools.php',
			'BVI Related Items',
			'BVI Related Items',
			'manage_options',
			'bvi-related-items',
			''
		);
	}
	
}
