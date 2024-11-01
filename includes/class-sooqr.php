<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.sooqr.com
 * @since      1.0.0
 *
 * @package    Sooqr
 * @subpackage Sooqr/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Sooqr
 * @subpackage Sooqr/includes
 * @author     Sooqr <support@sooqr.com>
 */
namespace SooqrSearch;
class Sooqr
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Sooqr_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        if (defined('SOOQR_VERSION')) {
            $this->version = SOOQR_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'Sooqr Search';

        $this->sooqr_load_dependencies();
        $this->sooqr_set_locale();
        $this->sooqr_define_admin_hooks();
        $this->sooqr_define_public_hooks();


    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Sooqr_Loader. Orchestrates the hooks of the plugin.
     * - Sooqr_i18n. Defines internationalization functionality.
     * - Sooqr_Admin. Defines all hooks for the admin area.
     * - Sooqr_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function sooqr_load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-sooqr-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-sooqr-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-sooqr-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-sooqr-public.php';

        /**
         * load less
         */

        require_once plugin_dir_path(dirname(__FILE__)) . 'less/wp-less.php';

        $this->loader = new Sooqr_Loader();

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Sooqr_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function sooqr_set_locale()
    {

        $plugin_i18n = new Sooqr_i18n();

        $this->loader->sooqr_add_action('plugins_loaded', $plugin_i18n, 'sooqr_load_plugin_textdomain');

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function sooqr_define_admin_hooks()
    {

        $plugin_admin = new Sooqr_Admin($this->sooqr_get_plugin_name(), $this->sooqr_get_version());
        $this->loader->sooqr_add_action('admin_enqueue_scripts', $plugin_admin, 'sooqr_enqueue_styles');
        $this->loader->sooqr_add_action('admin_enqueue_scripts', $plugin_admin, 'sooqr_enqueue_scripts');
        $this->loader->sooqr_add_action('admin_menu', $plugin_admin, 'sooqr_options_update');
        $this->loader->sooqr_add_action('admin_menu', $plugin_admin, 'sooqr_add_plugin_admin_menu');
        $this->loader->sooqr_add_action('admin_menu', $plugin_admin, 'sooqr_add_options');
        $this->loader->sooqr_add_action('admin_menu', $plugin_admin, 'sooqr_generate_feed');
        $this->loader->sooqr_add_action('sooqr_update_xml_feed', $plugin_admin, 'sooqr_generate_feed');
        $this->loader->sooqr_add_action('admin_post_sooqr_save_javascript', $plugin_admin, 'sooqr_save_javascript');

        //add settings link to the plugin
        $plugin_basename = plugin_basename(plugin_dir_path(__DIR__) . $this->plugin_name . '.php');
        $this->loader->sooqr_add_filter('plugin_action_links_' . $plugin_basename, $plugin_admin, 'sooqr_add_action_links');

    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function sooqr_define_public_hooks()
    {

        $plugin_public = new Sooqr_Public($this->sooqr_get_plugin_name(), $this->sooqr_get_version());

        $this->loader->sooqr_add_action('init', $plugin_public, 'sooqr_add_cron_schedules');
        $this->loader->sooqr_add_action('wp_enqueue_scripts', $plugin_public, 'sooqr_enqueue_styles');
        $this->loader->sooqr_add_action('wp_enqueue_scripts', $plugin_public, 'sooqr_enqueue_scripts');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function sooqr_run()
    {
        $this->loader->sooqr_run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function sooqr_get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Sooqr_Loader    Orchestrates the hooks of the plugin.
     */
    public function sooqr_get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function sooqr_get_version()
    {
        return $this->version;
    }


}
