<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.sooqr.com
 * @since      1.0.0
 *
 * @package    Sooqr
 * @subpackage Sooqr/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Sooqr
 * @subpackage Sooqr/includes
 * @author     Sooqr <support@sooqr.com>
 */

namespace SooqrSearch;

class Sooqr_Activator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function sooqr_activate()
    {
        # Absolute path of the Sooqr folder
        $feedDirectory = get_home_path() . 'sooqr';
        $WooEnabled = 1;

        if (current_user_can('activate_plugins') && !class_exists('WooCommerce')) {
            $WooEnabled = 0;
        }
        add_option('sooqr_woo_enabled', $WooEnabled);

        #Copy default snippet if it doesnt exist
        //check for rights
        $access_type = get_filesystem_method();
        if ($access_type === 'direct') {
            $creds = request_filesystem_credentials(get_home_path(), '', false, false, array());

            /* initialize the API */
            if ( ! WP_Filesystem($creds) ) {
                /* any problems and we exit */
                return false;
            }
            global $wp_filesystem;

            $file = $wp_filesystem->wp_content_dir() . 'sooqr/sooqrsearch.js';
            update_option('sooqr_javascript_location', $file);


            //make dir in content directory
            if (!$wp_filesystem->exists($wp_filesystem->wp_content_dir() . 'sooqr')) {
                $wp_filesystem->mkdir(
                    $wp_filesystem->wp_content_dir() . 'sooqr'
                );
            }


            //make dir in root directory
            if(!$wp_filesystem->exists($feedDirectory))
            {
                $wp_filesystem->mkdir(
                    $feedDirectory
                );
            }


            //put default snippet in js file
            $wp_filesystem->put_contents(
                $file,
                $wp_filesystem->get_contents(plugin_dir_path(__DIR__) . 'public/js/sooqrdefault.js')

            );

        } else {
            add_action('admin_notices', 'sooqr_add_permission_error');
        }


        # Create Sooqr directory if not exists
        if (!is_dir($feedDirectory) && !mkdir($feedDirectory) && !is_dir($feedDirectory)) {
            // Deactivate the plugin.
            deactivate_plugins(plugin_basename(__FILE__));
            // Throw an error in the WordPress admin console.
            $error_message = '<p style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,Oxygen-Sans,Ubuntu,Cantarell,\'Helvetica Neue\',sans-serif;font-size: 13px;line-height: 1.5;color:#444;">Could not create Sooqr directory. Make sure the plugin has access to create files/directories.</p>';
            die($error_message); // WPCS: XSS ok.
        }



        if ($WooEnabled == 1) {
            $countries_obj = new \WC_Countries();
            $country = $countries_obj->get_base_country();
            $currency = get_woocommerce_currency();
            $feedFile = 'sooqrfeed.xml';
            //generate feed with 100 products
            $plugin_admin = new Sooqr_Admin('Sooqr Search', SOOQR_VERSION);
            $plugin_admin->sooqr_generate_xml_feed(100, 1);
        } else {
            $country = 'Unknown';
            $currency = 'Unknown';
            $feedFile = 'sooqrcontentfeed.xml';
            //generate content feed
            $plugin_admin = new Sooqr_Admin('Sooqr Search', SOOQR_VERSION);
            $plugin_admin->sooqr_generate_content_xml_feed(200, true);
        }
        $shopdata = array(
            "search" => array(
                "enabled" => 1
            ),
            "feeds" => array(
                1 => array(
                    "name" => get_bloginfo('name'),
                    "feed_url" => get_site_url() . '/sooqr/' . $feedFile,
                    "currency" => $currency,
                    "locale" => get_locale(),
                    "country" => $country,
                    "extension" => "WooCommerce_Sooqr",
                    "extension_version" => SOOQR_VERSION
                )
            )
        );

        file_put_contents($feedDirectory . 'sooqr.json', json_encode($shopdata));


    }

    public function sooqr_add_permission_error()
    {
        echo '<div class="notice notice-error is-dismissible"><p>Sooqr has no rights to make changes on your filesystem. Please press the "reset to default" button in the advanced tab of the plugin.</p></div>';
    }

}


