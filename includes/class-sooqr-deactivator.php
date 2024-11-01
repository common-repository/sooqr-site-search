<?php

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Sooqr
 * @subpackage Sooqr/includes
 * @author     Sooqr <support@sooqr.com>
 */
namespace SooqrSearch;
class Sooqr_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function sooqr_deactivate() {
	    #disable sooqr on the frontend
		update_option('sooqr_enabled', 0);

		#disable sooqr in the json
        $path = plugin_dir_path(__DIR__) . 'public/sooqr.json';
        $json = json_decode(file_get_contents($path), true);
        $json['search']['enabled'] = 0;
        file_put_contents($path, json_encode($json));
	}

}

