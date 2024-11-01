<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.sooqr.com
 * @since      1.0.0
 *
 * @package    Sooqr
 * @subpackage Sooqr/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Sooqr
 * @subpackage Sooqr/public
 * @author     Sooqr <support@sooqr.com>
 */
namespace SooqrSearch;
class Sooqr_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;


    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function sooqr_enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Sooqr_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Sooqr_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        //wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sooqr-public.css', array(), $this->version, 'all' );

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function sooqr_enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Sooqr_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Sooqr_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        if (get_option('sooqr_enabled') == 1) {
            wp_enqueue_script('sooqr-javascript', content_url('sooqr/sooqrsearch.js'), array(), false, true);
        }

        //jquery disabled, not needed (yet?)
        //wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sooqr-public.js', array( 'jquery' ), $this->version, false );

    }

    public function sooqr_add_cron_schedules()
    {
        add_filter('cron_schedules', array($this, 'sooqr_add_six_hours_cron_interval'));
        add_filter('cron_schedules', array($this, 'sooqr_add_three_hours_cron_interval'));
    }

    public function sooqr_add_six_hours_cron_interval($schedules)
    {
        $schedules['six_hours'] = array(
            'interval' => 21600,
            'display' => esc_html__('Every 6 hours'),
        );
        return $schedules;
    }

    public function sooqr_add_three_hours_cron_interval($schedules)
    {
        $schedules['three_hours'] = array(
            'interval' => 10800,
            'display' => esc_html__('Every 3 hours'),
        );
        return $schedules;
    }


}
