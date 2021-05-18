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

		global $wp_scripts;

		wp_enqueue_style( 'jquery-ui-core', 'http://ajax.googleapis.com/ajax/libs/jqueryui/' . $wp_scripts->registered['jquery-ui-core']->ver . '/themes/smoothness/jquery-ui.css', array(), $this->version, 'all' );

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/bvi-related-items-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bvi-related-items-admin.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-sortable' ), $this->version, false );

		wp_localize_script( $this->plugin_name, 'bviriScript', array( 'pluginsUrl' => plugin_dir_url( __DIR__ ) ) );

	}

	/**
	 * Register the settings page for the plugin on the Plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_actions($links) {

		$link = '<a href="' . esc_attr( get_admin_url( null, 'admin.php?page=' . $this->plugin_name ) ) . '">' . __( 'Settings' ) . '</a>';
	
		array_unshift($links, $link);
		
		return $links;
	
	}

	/**
	 * Add an options page under the Settings submenu
	 *
	 * @since  1.0.0
	 */
	public function add_options_page() {
	
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'BVI Related Items Settings', $this->plugin_name ),
			__( 'BVI Related Items', $this->plugin_name ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'create_admin_page' )
		);

	}

	/**
	 * Render the options page for plugin
	 *
	 * @since  1.0.0
	 */
	public function create_admin_page() {
		?>
		<div class="wrap">
			<h2>BVI Related Items Settings</h2>
			<form method="post" action="options.php">
				<?php
				// pulls all existing values from fields registered under 'bvi_settings_options'
				settings_fields($this->plugin_name);
				// this is saying, get all the sections that have been assigned to 'bvi_settings_admin'
				do_settings_sections('bviri_settings_admin');
				submit_button(); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Configures all the settings page options to select from
	 *
	 * @since    1.0.0
	 */
	public function register_settings() {
		// this registers the settings array that will contain all of your option values
		// ie. everything will get saved in array_key[option_name_you_used]
		// the first value is the id of this functionality, so you can access it with settings_fields() function later
		register_setting($this->plugin_name, 'array_key', array($this, 'bviri_validation'));

		// sets up sections
		// the key is just to make it easier to find which line does what
		// 1 => section id, 2 => section H1, 3 => the function callback to show the section's description text, 4 => name of assigned settings sections
		// ie. 4 is what gets called by do_settings_sections - so if you assign them all to the same one, like bvi_settings_admin
		// then all of these sections will appear when you call do_settings_sections('bvi_settings_admin')
		// guess it gives you the options to separate the sections if you were doing some crazy front-end display
		$sections = array(
			'post' => array('bviri_settings_section_post_types', 'Relationship Post Types', array($this, 'bviri_settings_section_text_post'), 'bviri_settings_admin'),
			'display' => array('bviri_settings_section_display_options', 'Display Options', array($this, 'bviri_settings_section_text_display'), 'bviri_settings_admin')
		);

		// sets up fields
		// the key is just to make it easier to find which line does what
		// 1 => field id (NOT the id="" of the input field), 2 => field label, 3 => what input function to use
		// ie. &this means to use the 6th part of the array to assign the names to a generic input function
		// this lets you have a single function for each different kind of input, like input type="text"
		// and then define unique name, ids, values for the field without repeating the function
		// 4 => like the settings section, this will tell it to appear in that same sections call do_settings_section (so redundant) because ...
		// 5 => what section it should appear under that you define in sections (thats why 4 is redundant)
		// 6 => this allows you to set a unique name for the id, name, and value for the input field (in conjunction with the 3th array value)
		$fields = array(
			'bviri_selected_post_types' => array(
        'bviri_selected_post_types', 
        'Enable the Following Post Types:', 
        array(&$this, 'bviri_settings_posttypes_input_checkbox'), 
        'bviri_settings_admin', 
        'bviri_settings_section_post_types', 
        array('field' => 'bviri_settings_posttypes_val')
      ),
			'bviri_display_options' => array(
        'bviri_display_options', 
        'Automatic Display?', 
        array(&$this, 'bviri_settings_input_checkbox'), 
        'bviri_settings_admin', 
        'bviri_settings_section_display_options', 
        array('field' => 'bviri_automatic_display_val')
      ),
		);

		// yeah, we're not calling the settings section 5 times over and over -
		// parses through the sections array you just made, yay!
		foreach($sections as $s){
			add_settings_section($s[0], $s[1], $s[2], $s[3]);
		}

		// same thing - not going to call this over and over
		// parses through the fields array you just made, yay!
		foreach($fields as $f){
			add_settings_field($f[0], $f[1], $f[2], $f[3], $f[4], $f[5]);
		}
	}

  /**
	 * Registers a normal single checkbox value
	 *
	 * @since    1.0.0
	 */
	public function bviri_settings_input_checkbox($data) {
		?><input type="hidden" name="array_key[<?php echo $data['field']; ?>]" value="0" />
		<input type="checkbox" id="<?php echo $data['field']; ?>" name="array_key[<?php echo $data['field']; ?>]" value="1"<?php echo get_option($data['field']) ? ' checked="checked"' : ''; ?> /><?php
		if(isset($data['description'])) {
			?>&nbsp;<span class="description"><?php echo $data['description']; ?></span><?php
		}
	}

	/**
	 * Registers multiple checkbox values based on available post types
	 *
	 * @since    1.0.0
	 */
	public function bviri_settings_posttypes_input_checkbox($data) {
		
		$custom_post_types = get_post_types(array(), "objects");
		$checkboxes = '';
		
		foreach($custom_post_types as $type){
			$type_name = $type->labels->name;
			$type_name2 = $type->name; 
											
			$selected_types = get_option('bviri_settings_posttypes_val');

			if( is_array($selected_types) && array_key_exists( $type_name2,  $selected_types ) && $selected_types[$type_name2] == true) { 
				$checked = 'checked="checked"';                
			} else {
				$checked = '';
			}
			
			$checkboxes .= '<p><input type="checkbox" ' . $checked . ' name="array_key[' . $data['field'] . '][' . $type->name. ']" value="1">';
			$checkboxes .= $type->labels->name . " (" . $type->name . ")</p>";
		} ?>
		<input type="hidden" name="array_key[<?php echo $data['field']; ?>][<?php echo $type->name; ?>]" value="0" /><?php
		echo $checkboxes;
	
	}
	
	/**
	 * Text that appears above the post types checkboxes
	 *
	 * @since    1.0.0
	 */
	public function bviri_settings_section_text_post() {
		?><p>Select which post types you would like BVI Related Items to appear as an option.</p><?php
	}

	/**
	 * Text that appears above the automatic display option
	 *
	 * @since    1.0.0
	 */
	public function bviri_settings_section_text_display() {
		?><p>Shows how to utilize the BVI Related Items to get them to appear on your template.</p>
		<p><b>Related Items Shortcode:</b><br /> [related-items]</p>
		<p><b>Related Items Template Tag:</b><br />  echo do_shortcode('[related-items]');  </p>
		<?php
	}
	
	/**
	 * Validation and actually saves/updates the input options when save is clicked
	 *
	 * @since    1.0.0
	 */
	public function bviri_validation($input) {

		$valid = array();

		// checks each input that has been added
		foreach($input as $key => $value){
			// does a basic check to make sure that the database value is there
			if(get_option($key === FALSE)){
				// adds the field if its not there
				add_option($key, $value);
			} else {
				// updates the field if its already there
				update_option($key, $value);
			}

			// you have to return the value or WordPress will cry
			$valid[$key] = $value;
		}

		// return it and prevent WordPress depression
		return $valid;
	}
	
	/**
	 * Generates the meta boxes for each post type that's been selected in the settings
	 *
	 * @since    1.0.0
	 */
	public function generate_meta_boxes() {

		$custom_post_types = get_post_types(array(), "objects");
		$checkboxes = '';
		
		foreach($custom_post_types as $type){
			
			$type_name = $type->labels->name;
			$type_name2 = $type->name; 

			$selected_types = get_option('bviri_settings_posttypes_val');

			if( is_array( $selected_types ) && array_key_exists( $type_name2,  $selected_types ) && $selected_types[$type_name2] == true) { 
				add_meta_box( $type_name2 . '-bvi-related-items-box', 'BVI Related Items', array(&$this, 'display_meta_box'), $type_name2, 'normal', 'core' );                
			} 

		}

	}

	/**
	 * Meta box display in the admin for the post type allowed
	 *
	 * @since    1.0.0
	 */
	public function display_meta_box() {
		// initialize the options variable to place select options in later
		$bviri_options = '';

		$bviri_results_pages = get_posts(array('posts_per_page' => 999, 'post_type' => 'page', 'post_status' => 'publish', 'orderby' => 'menu_order', 'order' => 'ASC'));
		$bviri_results_posts = get_posts(array('posts_per_page' => 999, 'post_type' => 'post', 'post_status' => 'publish', 'orderby' => 'date', 'order' => 'ASC'));

		$bviri_options .= '<optgroup label="Pages">';

		foreach($bviri_results_pages as $object) {
			$bviri_options .= '<option value="' . $object->ID . '">' . $object->post_title . '</option>';
		}

		$bviri_options .= '</optgroup>
			<optgroup label="Posts">';

		foreach($bviri_results_posts as $object) {
			$bviri_options .= '<option value="' . $object->ID . '">' . $object->post_title . '</option>';
		}

		$bviri_options .= '</optgroup>';
				
		// retrieve the existing related item relationships from the db
		$bviri_existing_items = get_post_meta(get_the_ID(), 'related_items', true);

		// display existing relationships meta box
		echo '<div id="bviri-meta-box">';
		
		// if there is already some related items saved in the db for this page, let's go through it!
		if (!empty($bviri_existing_items)) {
			// initialize the item array - this will hold the values so wordpress will automatically include it on POST
			$item_data = array();

			// go through each related item
			foreach($bviri_existing_items as $item){
				// if a custom title was set for this item, use that instead of the page name
				if( is_array($item) && $item['title'] ) {
					$bviri_existing_post = get_post($item['id']);
					$bviri_existing_id = $item['id'];
					$bviri_existing_title = $item['title'];
				} else {
					$bviri_existing_post = get_post($item);
					$bviri_existing_id = $bviri_existing_post->ID;
					$bviri_existing_title = $bviri_existing_post->post_title;
				}
				// get the post type of this related item
				$type_info = get_post_type_object($bviri_existing_post->post_type);
			
				if( isset( $type_info->labels->name ) && isset( $bviri_existing_post ) ) {
					
					$index = $type_info->labels->name;			
					// initialize the POST item array if it hasnt been already
					if(!isset($item_data[$index])){
						$item_data[$index] = "";
					}
					// add the related page to the POST item array
					$item_data[$index] .= '<div class="related-item" id="related-item" title="Drag and Drop to Reorder" style="font-size:11px;margin:6px 6px 8px;padding:8px;background:#f1f1f1;border:1px solid #dfdfdf;cursor:move;border-radius:4px;-webkit-border-radius:4px;-moz-border-radius:4px;">
						<img src="' . plugin_dir_url( __DIR__ ) . 'admin/images/bullet-green.png" title="This Relationship is Saved!" style="float:left;margin-top:-8px;margin-left:-8px;">
						<input type="hidden" name="related-items[]" value="' . $bviri_existing_id . '">
						<input type="hidden" name="related-items-titles[]" value="' . $bviri_existing_title . '">
						<span class="related-item-title">' . $bviri_existing_title . ' (' . ucfirst(get_post_type($bviri_existing_id)) . ')</span>
						<a href="#" title="Remove from list"><img src="' . plugin_dir_url( __DIR__ ) . 'admin/images/delete.png"></a></div>';
				}
			}
			// we want to see the items currently attached, so add them to the display variable
			$bviri_existing_display = '';
			
			foreach( $item_data as $type => $item ) {
				$bviri_existing_display .= $item;
			}

		}
		// print out the template with all the variables
		if( !empty( $bviri_existing_display ) ) {
      echo $bviri_existing_display;
    } 

		echo '<div id="bviri-items">
				<p>Select items to add a relationship, drag and drop related items to change the order.</p>
				<div class="new_relationship_form">
					Select an Item: 
					<select id="related-items-select" name="related-items-select">
						<option value="0">Select</option>';
		echo $bviri_options;
		echo 		'</select>
					 Alternate Title: <input type="text" name="related-item-title" id="related_item_title" value="" />
					<input type="button" class="button-primary" id="add_relationship" value="Add Relationship" />
				</div>
			</div>
		</div>';
	}

	/**
	 * Save the related item data to the specific post
	 *
	 * @since    1.0.0
	 */
	public function save_each_posts_items( $id ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

		if ( empty( $_POST['related-items'] ) ) {
			delete_post_meta( $id, 'related_items' );
		} else {
			// get the post for the post ids related to this page
			$related_items = $_POST['related-items'];
			// check if there are any titles set
			if( !empty( $_POST['related-items-titles'] ) ) {
				$related_titles = $_POST['related-items-titles'];
			}
			// merge the id with its title to keep it in a single value in the database
			$count = 0;
			
			foreach( $related_items as $r ) {
				$final[] = array('id' => $r, 'title' => $related_titles[$count]);
				$count++;
			}
			// update the post meta table with the data
			update_post_meta( $id, 'related_items', $final );
		}			
	}
}
