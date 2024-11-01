<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.sooqr.com
 * @since      1.0.0
 *
 * @package    Sooqr
 * @subpackage Sooqr/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Sooqr
 * @subpackage Sooqr/includes
 * @author     Sooqr <support@sooqr.com>
 */
namespace SooqrSearch;
class Sooqr_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function sooqr_load_plugin_textdomain() {

		load_plugin_textdomain(
			'sooqr',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
