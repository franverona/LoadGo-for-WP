<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Init plugin
add_action('admin_init', 'loadgo_init' );
function loadgo_init () {

  register_setting( 'loadgo_plugin_options', 'loadgo_options', 'loadgo_validate_options' );
  
  //  General options
  add_settings_section( 
    'loadgo_plugin_general_options', 
    __( 'General options', 'loadgo-for-wp' ), 
    'loadgo_plugin_general_options_section', 
    'loadgo_plugin_general_options_section' );

  // Visibility
  add_settings_field( 
    'loadgo-visibility', 
    __( 'Visibility', 'loadgo-for-wp' ), 
    'loadgo_plugin_setting_visibility', 
    'loadgo_plugin_general_options_section', 
    'loadgo_plugin_general_options' );

  // Loadgo Image
  add_settings_field( 
    'loadgo-image', 
    __( 'Logo image', 'loadgo-for-wp' ), 
    'loadgo_plugin_setting_image', 
    'loadgo_plugin_general_options_section', 
    'loadgo_plugin_general_options' );

  // Show progress number
  add_settings_field( 
    'loadgo-progress', 
    __( 'Show progress', 'loadgo-for-wp' ), 
    'loadgo_plugin_setting_progress', 
    'loadgo_plugin_general_options_section', 
    'loadgo_plugin_general_options' );

  // Progress number color
  add_settings_field( 
    'loadgo-progress-color', 
    __( 'Progress color', 'loadgo-for-wp' ), 
    'loadgo_plugin_setting_progress_color', 
    'loadgo_plugin_general_options_section', 
    'loadgo_plugin_general_options' );

  // Show message
  add_settings_field( 
    'loadgo-message', 
    __( 'Show message', 'loadgo-for-wp' ), 
    'loadgo_plugin_setting_message', 
    'loadgo_plugin_general_options_section', 
    'loadgo_plugin_general_options' );

  //  ---------------------------
  //  LoadGo options section
  add_settings_section( 
    'loadgo_plugin_display_options', 
    __( 'LoadGo options', 'loadgo-for-wp' ), 
    'loadgo_plugin_display_options_section', 
    'loadgo_plugin_display_options_section' );
  
  // Page background color
  add_settings_field( 
    'loadgo-bgcolor', 
    __( 'Background color', 'loadgo-for-wp' ), 
    'loadgo_plugin_setting_bgcolor', 
    'loadgo_plugin_display_options_section', 'loadgo_plugin_display_options' );

  // Logo size
  add_settings_field( 
    'loadgo-size', 
    __( 'Size', 'loadgo-for-wp' ), 
    'loadgo_plugin_setting_size', 
    'loadgo_plugin_display_options_section', 
    'loadgo_plugin_display_options' );

  // Overlay opacity
  add_settings_field( 
    'loadgo-opacity', 
    __( 'Opacity', 'loadgo-for-wp' ), 
    'loadgo_plugin_setting_opacity', 
    'loadgo_plugin_display_options_section', 
    'loadgo_plugin_display_options' );

  // LoadGo direction
  add_settings_field( 
    'loadgo-direction', 
    __( 'Direction', 'loadgo-for-wp' ), 
    'loadgo_plugin_setting_direction', 
    'loadgo_plugin_display_options_section', 
    'loadgo_plugin_display_options' );

}

// Load color picker
add_action( 'admin_enqueue_scripts', 'loadgo_enqueue_color_picker' );
function loadgo_enqueue_color_picker () {
  wp_enqueue_style( 'wp-color-picker' );
  wp_enqueue_script( 'wp-color-picker' );
}

// Plugin options page
add_action('admin_menu', 'loadgo_add_options_page');
function loadgo_add_options_page () {
  add_options_page(__( 'Loadgo Settings Page', 'loadgo-for-wp' ), 'Loadgo', 'manage_options', __FILE__, 'loadgo_render_form');
}

// Plugin options form
function loadgo_render_form () {
?>
  <div class="wrap">
    
    <!-- Display Plugin Icon, Header, and Description -->
    <div style="float:left;padding-top: 14px;margin-right: 6px;">
      <span class="dashicons dashicons-admin-generic"></span>
    </div>
    <h1><?php _e( 'Options', 'loadgo-for-wp' ) ?></h1>
        
<?php    
  if ( !current_user_can( 'manage_options' ) )
    wp_die( _e( 'You do not have sufficient permissions to access this page.', 'loadgo-for-wp' ) );
  else {
?>
    <!-- Beginning of the Plugin Options Form -->
    <form method="post" action="options.php" enctype="multipart/form-data">
      <?php settings_fields('loadgo_plugin_options'); ?>

      <?php 
        do_settings_sections('loadgo_plugin_general_options_section');
        do_settings_sections('loadgo_plugin_display_options_section');
      ?>

      <p class="submit">
       <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
      </p>
    </form>

    <script>
      jQuery(document).ready(function($){ 
        $('.loadgo-bgcolor').wpColorPicker();

        $('#loadgo-input-file').on('change', function () {
          jQuery('#loadgo-input-file-error').hide();
          if (this.files && this.files[0]) {
            var ext = $(this).val().split('.').pop().toLowerCase();
            if ( $.inArray( ext, [ 'gif', 'png', 'jpg', 'jpeg' ] ) === -1 ) {
              jQuery('#loadgo-input-file-error').show();
              $(this).val('');
              return;
            }
            var reader = new FileReader();
            reader.onload = function (e) {
              $('#loadgo-logo-preview img').attr('src', e.target.result);
            };
            reader.readAsDataURL(this.files[0]);
          }
        });

        $('#message').on('change', function () {
          var selected_option = $('#message option:selected').val();
          if (selected_option === 'true')
            $('#thanks').fadeIn();
          else
            $('#thanks').hide();
        });
      });
    </script>
    <style>
      .form-table td {line-height: 30px !important; }
      #loadgo-logo-preview {
        border: 1px solid #D4D4D4;
        margin-top: 10px;
        padding: 10px;
        max-width: 300px;
        text-align: center;
        height: auto;
      }
      #loadgo-logo-preview img {
        max-width: 300px;
        height: auto;
        display: table;
      }
      .loadgo-ul {
        list-style: initial;
        padding-left: 25px;
        margin-top: 0;
      }
    </style>

<?php
  }
?>
  </div>
<?php 
}

// General Section description
function loadgo_plugin_general_options_section () {
  echo '';
}

// LoadGo Section description
function loadgo_plugin_display_options_section () {
  _e( 'These settings let you customize Loadgo style. You can modify overlay color, animation direction, etc. Take a look at <a href="http://franverona.com/loadgo" target="_blank">Loadgo Official site</a> for info and examples.', 'loadgo-for-wp' );
}

//
// Visibility
//
function loadgo_plugin_setting_visibility () {
  $options = get_option('loadgo_options');
  $loadgovisibility = $options['loadgo-visibility']? $options['loadgo-visibility'] : 'admin';
  ?>
    <select name='loadgo_options[loadgo-visibility]'>
      <option value='all' <?php selected('all', $loadgovisibility); ?>><?php _e( 'Everyone', 'loadgo-for-wp' ) ?></option>
      <option value='admin' <?php selected('admin', $loadgovisibility); ?>><?php _e( 'Only admins', 'loadgo-for-wp' ) ?></option>
    </select>
  <?php
  echo '<div class="description">' . __( '"Only admins" option is a good choice if you are testing a new image and do not want your new visitors to see it.', 'loadgo-for-wp' ) . '</div>';
}

//
// Logo image
//
function loadgo_plugin_setting_image () {
  $options = get_option('loadgo_options');
  $loadgoimage = $options['loadgo-image']? $options['loadgo-image'] : plugin_dir_url( __FILE__ ) . 'img/example.png';

  echo '<input id="loadgo-input-file" type="file" name="loadgo_image" value="'. $loadgoimage .'" />';
  echo '<div id="loadgo-input-file-error" style="display:none;color:#c30710">' . __( 'Invalid file. Try to upload an image file: jpg, jpeg, gif, png.', 'loadgo-for-wp' ) . '</div>';
  echo '
    <div id="loadgo-logo-preview">
      <img src="' . $loadgoimage . '" alt="logo" />
    </div>';
  echo '
    <div style="margin-top:10px;">
      ' . __( 'Upload your image here.', 'loadgo-for-wp' ) .
      '<ul class="loadgo-ul">
      <li>' . __( 'An image with transparency (PNG) provides best results.', 'loadgo-for-wp' ) . '</li>
      <li>' . __( 'Optimize your image: do not upload a big file if you will not need it.', 'loadgo-for-wp' ) . '</li>
      <li>' . __( 'Try to upload a small version of your logo. Images < 100Kb work better.', 'loadgo-for-wp' ) . '</li>
      </ul>
    </div>';
}

//
// Progress
//
function loadgo_plugin_setting_progress () {
  $options = get_option('loadgo_options');
  $loadgoprogress = $options['loadgo-progress']? $options['loadgo-progress'] : 'true';
  ?>
    <select name='loadgo_options[loadgo-progress]'>
      <option value='true' <?php selected('true', $loadgoprogress); ?>><?php _e( 'Yes', 'loadgo-for-wp') ?></option>
      <option value='false' <?php selected('false', $loadgoprogress); ?>><?php _e( 'No', 'loadgo-for-wp' ) ?></option>
    </select>
  <?php
  echo '<div class="description">' . __( 'Set to "Yes" if you want to display progress number while loading.', 'loadgo-for-wp') . '</div>';
}

//
// Progress color
//
function loadgo_plugin_setting_progress_color () {
  $options = get_option('loadgo_options');
  $loadgoprogresscolor = $options['loadgo-progress-color']? $options['loadgo-progress-color'] : '#000';
  echo '<input class="loadgo-bgcolor" data-default-color="#0d77b6" type="text" name="loadgo_options[loadgo-progress-color]" value="'. $loadgoprogresscolor .'" />';
  echo '<div class="description">' . __( 'Progress color.', 'loadgo-for-wp' ) . '</div>';
}

//
// Loadgo message
//
function loadgo_plugin_setting_message () {
  $options = get_option('loadgo_options');
  $loadgomessage = $options['loadgo-message']? $options['loadgo-message'] : 'false';
  ?>
    <select id="message" name='loadgo_options[loadgo-message]'>
      <option value='true' <?php selected('true', $loadgomessage); ?>><?php _e( 'Yes' ) ?></option>
      <option value='false' <?php selected('false', $loadgomessage); ?>><?php _e( 'No' ) ?></option>
    </select>
    <span id="thanks" style="opacity: 0;"><?php _e( 'Thank you so much! :)', 'loadgo-for-wp' ) ?></span>
  <?php
  echo '<div class="description">' . __( 'Set to "Yes" if you want to display a message so your visitors will know that you are using "LoadGo for WP". If you set this to "Yes", I will be really grateful.', 'loadgo-for-wp' ) . '</div>';
}

//
// Background page color
//
function loadgo_plugin_setting_bgcolor () {
  $options = get_option('loadgo_options');
  $loadgocolor = $options['loadgo-bgcolor'];
  echo '<input class="loadgo-bgcolor" data-default-color="#0d77b6" type="text" name="loadgo_options[loadgo-bgcolor]" value="'. $loadgocolor .'" />';
  echo '<div class="description">' . __( 'Page background color. If LoadGo background color is equals to your logo background color, you page would look better.', 'loadgo-for-wp') . '</div>';
}

//
// Logo size
//
function loadgo_plugin_setting_size () {
  $options = get_option('loadgo_options');
  $loadgosize = $options['loadgo-size']? $options['loadgo-size'] : '100';
  ?>
    <select name='loadgo_options[loadgo-size]'>
      <option value='10' <?php selected('10', $loadgosize); ?>>10%</option>
      <option value='20' <?php selected('20', $loadgosize); ?>>20%</option>
      <option value='30' <?php selected('30', $loadgosize); ?>>30%</option>
      <option value='40' <?php selected('40', $loadgosize); ?>>40%</option>
      <option value='50' <?php selected('50', $loadgosize); ?>>50%</option>
      <option value='60' <?php selected('60', $loadgosize); ?>>60%</option>
      <option value='70' <?php selected('70', $loadgosize); ?>>70%</option>
      <option value='80' <?php selected('80', $loadgosize); ?>>80%</option>
      <option value='90' <?php selected('90', $loadgosize); ?>>90%</option>
      <option value='100' <?php selected('100', $loadgosize); ?>>100%</option>
    </select>
  <?php
  echo '<div class="description">' . __( 'Logo size regarding to its original image size (in percentage).', 'loadgo-for-wp' ) . '</div>';
}

//
// Overlay opacity
//
function loadgo_plugin_setting_opacity () {
  $options = get_option('loadgo_options');
  $loadgoopacity = $options['loadgo-opacity']? $options['loadgo-opacity'] : '0.5';
  echo '<input class="loadgo-opacity" type="text" name="loadgo_options[loadgo-opacity]" value="'. $loadgoopacity .'"  />';
  echo '<div class="description">' . __( 'Overlay opacity (value must be between 0 and 1, both inclusive).', 'loadgo-for-wp' ) . '</div>';
}

//
// Direction
//
function loadgo_plugin_setting_direction () {
  $options = get_option('loadgo_options');
  $loadgodirection = $options['loadgo-direction'];
  ?>
    <select name='loadgo_options[loadgo-direction]'>
      <option value='lr' <?php selected('lr', $loadgodirection); ?>><?php _e( 'Left to Right', 'loadgo-for-wp' ) ?></option>
      <option value='rl' <?php selected('rl', $loadgodirection); ?>><?php _e( 'Right to Left', 'loadgo-for-wp' ) ?></option>
      <option value='tb' <?php selected('tb', $loadgodirection); ?>><?php _e( 'Top to Bottom', 'loadgo-for-wp' ) ?></option>
      <option value='bt' <?php selected('bt', $loadgodirection); ?>><?php _e( 'Bottom to Top', 'loadgo-for-wp' ) ?></option>
    </select>
  <?php
  echo '<div class="description">' . __( 'Animation direction.', 'loadgo-for-wp' ) . '</div>';
}

// Validate plugin options on save
function loadgo_validate_options ($input) {

  // Upload file
  $keys = array_keys($_FILES);
  $i = 0;
  foreach ( $_FILES as $image ) {
    if ($image['size']) {     
      if ( preg_match('/(jpg|jpeg|png|gif)$/', $image['type']) ) {       
        $override = array('test_form' => false);       
        // save the file, and store an array, containing its location in $file
        $file = wp_handle_upload( $image, $override );
        $input['loadgo-image'] = $file['url'];
        break;
      }
      else {
        // No image uploaded
        continue;
      }
    }
    else {
      break;
    }
    $i++;
  }

  if ( $input['loadgo-image'] === NULL ) {
    $options = get_option('loadgo_options');
    $input['loadgo-image'] = $options['loadgo-image'];
  }

  $input['loadgo-visibility'] = strtolower( $input['loadgo-visibility'] );
  if ( !in_array( $input['loadgo-visibility'], array('all', 'admin') ) )
    $input['loadgo-visibility'] = 'admin';

  $input['loadgo-progress'] = strtolower( $input['loadgo-progress'] );
  if ( !in_array( $input['loadgo-progress'], array('true', 'false') ) )
    $input['loadgo-progress'] = 'false';
  $input['loadgo-progress-color'] = wp_filter_nohtml_kses($input['loadgo-progress-color']);

  $input['loadgo-message'] = strtolower( $input['loadgo-message'] );
  if ( !in_array( $input['loadgo-message'], array('true', 'false') ) )
    $input['loadgo-message'] = 'false';

  $input['loadgo-bgcolor'] = wp_filter_nohtml_kses($input['loadgo-bgcolor']);

  $input['loadgo-size'] = intval( $input['loadgo-size'] );

  $opacity = floatval( $input['loadgo-opacity'] );
  if ( $opacity < 0 || $opacity > 1 )
    $opacity = 0.5;
  $input['loadgo-opacity'] = $opacity;

  $input['loadgo-direction'] = strtolower( $input['loadgo-direction'] );
  if ( !in_array( $input['loadgo-direction'], array('lr', 'rl', 'tb', 'bt') ) )
    $input['loadgo-direction'] = 'lr';

  return $input;

} 
