<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.bigvoodoo.com
 * @since      1.0.0
 *
 * @package    Bvi_Related_Items
 * @subpackage Bvi_Related_Items/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Bvi_Related_Items
 * @subpackage Bvi_Related_Items/includes
 * @author     Big Voodoo Interactive <programmers@bigvoodoo.com>
 */
class Bvi_Related_Items {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Bvi_Related_Items_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		
		if ( defined( 'BVI_RELATED_ITEMS_VERSION' ) ) {
			$this->version = BVI_RELATED_ITEMS_VERSION;
		} else {
			$this->version = '1.0.0';
		}

		$this->plugin_name = 'bvi-related-items';

		$this->load_dependencies();

		if(is_admin()) {
			$this->define_admin_hooks();
		}

		add_shortcode('related-items', array($this, 'frontend_display') );

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Bvi_Related_Items_Loader. Orchestrates the hooks of the plugin.
	 * - Bvi_Related_Items_i18n. Defines internationalization functionality.
	 * - Bvi_Related_Items_Admin. Defines all hooks for the admin area.
	 * - Bvi_Related_Items_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bvi-related-items-loader.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-bvi-related-items-admin.php';

		$this->loader = new Bvi_Related_Items_Loader();

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Bvi_Related_Items_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_options_page' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'generate_meta_boxes' );
		
		$this->loader->add_filter( 'plugin_action_links_' . BVI_RELATED_ITEMS_PLUGIN_BASE, $plugin_admin, 'add_plugin_actions' );

		// Register hook to save the related items when saving the post
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_each_posts_items' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Bvi_Related_Items_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Display the frontend HTML of the related items associated with this ID
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function frontend_display() {
		// get the current post ID
		$id = get_the_ID();
		// initialize the list array for the items
		$list_data = array();
		$related_posts = '';

		if ( !empty( $id ) && is_numeric( $id ) ) {
			// get the post meta value of related_items associated with this page
			$related_posts = get_post_meta( $id, 'related_items', true );
			
		}

		if ( empty( $related_posts ) || empty( $id ) || !is_numeric( $id ) ) {
			// if there are no related pages set, use the homepage's set list
			$homepage = get_page_by_title( 'Home' );
			$id = $homepage->ID;
			$related_posts = get_post_meta( $id, 'related_items', true );

		}

		if( $related_posts ) {

			foreach ( $related_posts as $related_post ) {
				// if a custom title was set for this item, use that instead of the page name
				if(is_array($related_post) && $related_post['title']){
					$related_post_id = $related_post['id'];
					$related_post_title = $related_post['title'];
				} else {
					$post = get_post($related_post);
					$related_post_id = $post->ID;
					$related_post_title = $post->post_title;
				}

				$related_items[] = array('id' => $related_post_id, 'title' => $related_post_title);
			}
			// beginning of the html
			$list_output = '<div class="related-items">';
			// for each related item
			foreach ($related_items as $item) {
				// lord jesus, if that array is not already initialized, please do so here - because nobody likes a non-existent variable!
				if(!empty($item['title'])){
					$list_data[$item['title']] = "";
				}
				// add in the related item to the unordered list
				$list_data[$item['title']] .= '<li><a href="' . get_permalink($item['id']) . '">' . $item['title'] . '</a></li>';
			}

			$list_output .= '<ul class="related-items-menu">';
			// put all those fancy lis in a happy little ul
			foreach($list_data as $item => $data){
				$list_output = $data;
			}

			$list_output .= '</ul>';
			// close out the last of the html
			$list_output .= '</div>';
			// return it! or all that work for nothing...
			return $list_output;
		}

	}

}
