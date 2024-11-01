<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.sooqr.com
 * @since             1.0.0
 * @package           Sooqr
 *
 * @wordpress-plugin
 * Plugin Name:       Sooqr Search
 * Plugin URI:        https://www.sooqr.com
 * Description:       Generate feeds for Sooqr & enable Sooqr Search for your WooCommerce webshop
 * Version:           1.1.6
 * Author:            Sooqr
 * Author URI:        https://www.sooqr.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sooqr
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('SOOQR_VERSION', '1.1.6');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sooqr-activator.php
 */
function sooqr_activate()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-sooqr-activator.php';
    SooqrSearch\Sooqr_Activator::sooqr_activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sooqr-deactivator.php
 */
function sooqr_deactivate()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-sooqr-deactivator.php';
    SooqrSearch\Sooqr_Deactivator::sooqr_deactivate();
}

/**
 * The code that runs during plugin uninstall.
 * Thie action is documented in includes/class-sooqr-uninstaller.php
 */
function sooqr_uninstall()

{
    require_once plugin_dir_path(__FILE__) . 'includes/class-sooqr-uninstaller.php';
    SooqrSearch\Sooqr_Uninstaller::sooqr_uninstall();
}

register_activation_hook(__FILE__, 'sooqr_activate');
register_deactivation_hook(__FILE__, 'sooqr_deactivate');
register_uninstall_hook(__FILE__, 'sooqr_uninstall');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-sooqr.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function sooqr_run()
{
    $plugin = new SooqrSearch\Sooqr();
    $plugin->sooqr_run();
}

sooqr_run();

