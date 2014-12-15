<?php

/**
 * myfossil-logging bootstrap
 *
 * This file is read by WordPress to generate the plugin information in the
 * plugin Dashboard. This file also includes all of the dependencies used by
 * the plugin, registers the activation and deactivation functions, and defines
 * a function that starts the plugin.
 *
 * @link              https://github.com/myfossil/myfossil-logging
 * @package           myfossil-logging
 *
 * @wordpress-plugin
 * Plugin Name:       myFOSSIL Logging
 * Plugin URI:        https://github.com/myfossil/myfossil-logging
 * Description:       Enhances logging of user activity
 * Version:           0.3.1
 * Author:            myFOSSIL 
 * Author URI:        https://github.com/myfossil/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       myfossil-logging
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-myfossil-logging-activator.php';

/**
 * The code that runs during plugin deactivation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-myfossil-logging-deactivator.php';

/** 
 * This action is documented in includes/class-myfossil-logging-activator.php
 */
register_activation_hook( __FILE__, array( 'myFOSSIL_Logging_Activator', 'activate' ) );

/** 
 * This action is documented in includes/class-myfossil-logging-deactivator.php
 */
register_deactivation_hook( __FILE__, array( 'myFOSSIL_Logging_Deactivator', 'deactivate' ) );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-myfossil-logging.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.0.1
 */
function run_plugin_name() {

	$plugin = new myFOSSIL_Logging();
	$plugin->run();

}

run_plugin_name();
