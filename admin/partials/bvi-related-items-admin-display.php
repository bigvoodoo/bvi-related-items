<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.bigvoodoo.com
 * @since      1.0.0
 *
 * @package    Bvi_Related_Items
 * @subpackage Bvi_Related_Items/admin/partials
 */

  // if user clicked "Save Changes" save them
  if(isset($_POST['Submit'])) {
    foreach($this->_options as $option => $value) {
      if(array_key_exists($option, $_POST)) {
        update_option($option, $_POST[$option]);
      } else {
        update_option($option, $value);
      }
    }

    $this->_messages['updated'][] = 'Options updated!';
  }

  foreach($this->_messages as $namespace => $messages) {
    foreach($messages as $message) {
      ?>
      <div class="<?php echo $namespace; ?>">
        <p><strong><?php echo $message; ?></strong></p>
      </div>
  <?php }
  } ?>
  <script type="text/javascript">var wpurl = "<?php get_option('siteurl'); ?>";</script>
  <div class="wrap">
    <div id="icon-options-general" class="icon32"><br /></div>
    <h2>BVI Related Items</h2>
    <div id="poststuff" class="metabox-holder has-right-sidebar">
      <div class="has-sidebar sm-padded" >			
        <div id="post-body-content" class="has-sidebar-content">
          <div class="meta-box-sortabless">
            <form method="post" action="">	
              <div id="related-items-general" class="postbox">
                <h3 class="hndle"><span><?php echo $title ?></span></h3>
                <div class="inside">	
                  <p>Enable Relationships for the Following Post Types:</p>
                  <?php $CPTs = get_post_types(array(), "objects");
                  foreach($CPTs as $type) {
                    $type_name = $type->labels->name;
                    $type_name2 = $type->name; 
                                    
                    $selected_types = get_option('related-items-selected-types');                               
                                    
                    if(in_array($type_name2,  $selected_types)) {
                      echo "<p><input type='checkbox' checked='checked' name='related-items-selected-types[]' value='".$type->name."'> ";
                      echo $type->labels->name . " (" . $type->name . ")</p>";                              
                    } else {
                      echo "<p><input type='checkbox' name='related-items-selected-types[]' value='".$type->name."'> ";
                      echo $type->labels->name . " (" . $type->name . ")</p>";                                
                    }
                  }                                
                  ?>
                    <p class="submit">
                    <input type="submit" name="Submit" class="button-primary" value="Save Changes" />
                  </p>
                </div>
              </div>
              <div id="related-items-display" class="postbox">
                <h3 class="hndle"><span><?php echo $title ?></span></h3>
                <div class="inside">	
                  <?php $ri_options = get_option('related_items_options'); ?>
                  <?php $checked = isset($ri_options['automatic_after_content']) ? "checked='checked'" : ""; ?>
                  <p><b>Related Items Automatic Display:  </b><br />  
                    <input type="checkbox" name='related_items_options[automatic_after_content]' <?php echo $checked; ?> /> Automatic Display Enabled
                  </p>
                  <p><b>Related Items Shortcode:</b><br /> [related-items]</p>
                  <p><b>Related Items Template Tag:</b><br />  echo do_shortcode('[related-items]');  </p>		
                  <p class="submit">
                    <input type="submit" name="Submit" class="button-primary" value="Save Changes" />
                  </p>
                </div>		
              </div>		
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
