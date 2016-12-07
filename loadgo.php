<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
Plugin Name: LoadGo for WP
Text Domain: loadgo-for-wp
Domain Path: /languages
Plugin URI: http://franverona.com/loadgo
Description: Create an automatic page load progress bar using <a href="http://github.hubspot.com/pace/docs/welcome/">PACE</a> and <a href="http://franverona.com/loadgo">Loadgo</a> Javascript plugin.
Version: 1.3
Author: Fran Verona
Author URI: http://franverona.com
*/

class loadgo {

  // Constructor for the class.
  public function __construct () {

    // Add default options when activate
    register_activation_hook( __FILE__ , array( $this, 'loadgo_add_defaults' ) );

    // Remove plugin options when uninstall
    register_uninstall_hook( __FILE__, array( $this, 'loadgo_delete_plugin_options' ) );

    add_action( 'init', array( $this, 'loadgo_translation' ) );
    add_action( 'init', array( $this, 'loadgo_options_page' ) );
    add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'loadgo_add_settings_link') );
    add_action( 'wp_enqueue_scripts', array( $this, 'loadgo_sitewide' ) );
	}

	public function loadgo_options_page () {
		require_once( dirname( __FILE__ ) . '/loadgo-options.php' );
	}

  public function loadgo_translation () {

    $domain = 'loadgo-for-wp';
    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );
    if ( $loaded = load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' ) ) {
      return $loaded;
    } else {
      load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
    }

  }

  static function loadgo_add_defaults () {
    $opt = get_option('loadgo_options');
    if ( !is_array($opt) ) {
      // First time activation
      delete_option('loadgo_options');
      $arr = array( 
        "loadgo-visibility"     => "admin",
        "loadgo-image"          => plugin_dir_url( __FILE__ ) . "img/example.png",
        "loadgo-progress"       => "true",
        "loadgo-progress-color" => "#000000",
        "loadgo-message"        => "false",
        "loadgo-bgcolor"        => "#FFFFFF",
        "loadgo-size"           => "100",
        "loadgo-opacity"        => "0.5",
        "loadgo-direction"      => "lr"
      );
      update_option('loadgo_options', $arr);
    }
  }

  static function loadgo_delete_plugin_options () {
    delete_option('loadgo_options');
  }

	public function loadgo_add_settings_link ($links) {
		$settings_link = '<a href="options-general.php?page=loadgo-for-wp/loadgo-options.php">LoadGo for WP</a>';
		$help_link = '<a href="http://franverona.com/loadgo/">Help</a>';
	
		array_push( $links, $settings_link, $help_link );
		return $links;
	}

	public function loadgo_sitewide () {
		include_once('methods/loadgo_sitewide.php');
	}

}

$loadgo = new loadgo();
