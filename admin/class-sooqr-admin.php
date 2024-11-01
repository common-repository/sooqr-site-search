<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.sooqr.com
 * @since      1.0.0
 *
 * @package    Sooqr
 * @subpackage Sooqr/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Sooqr
 * @subpackage Sooqr/admin
 * @author     Sooqr <support@sooqr.com>
 */

namespace SooqrSearch;

class Sooqr_Admin
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

    private $xmlfields;
    private $validationnr;
    private $posts;
    private $feedDirectory;
    private $feedUrl;
    private $permError;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->xmlfields = array(
            'id',
            'title',
            'url',
            'sku',
            'price',
            'img',
            'weight',
            'cat',
            'description',
            'tags',
            'stock',
            'attributes'
        );
        $this->posts = get_pages();
        $this->validationnr = 1;

        $this->feedDirectory = ABSPATH . '/sooqr/';
        $this->feedUrl = get_site_url() . '/sooqr/';
        $this->permError = false;
    }

    /**
     * Register the stylesheets for the admin area.
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

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/sooqr-admin.css', array(), $this->version,
            'all');
        wp_enqueue_style('style', plugin_dir_url(__FILE__) . 'css/sooqr-admin-style.less');

    }

    /**
     * Register the JavaScript for the admin area.
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

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/sooqr-admin.js', array('jquery'),
            $this->version, false);
        wp_enqueue_script('ace', 'https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.2/ace.js');

    }

    public function sooqr_add_plugin_admin_menu()
    {
        /*
         * Add a the Sooqr menu item in the settings menu
         *
         *
         */
        add_menu_page('Sooqr Search Options', 'Sooqr Search', 'administrator', $this->plugin_name,
            array($this, 'display_plugin_setup_page'),
            "data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+CjwhLS0gQ3JlYXRlZCB3aXRoIElua3NjYXBlIChodHRwOi8vd3d3Lmlua3NjYXBlLm9yZy8pIC0tPgoKPHN2ZwogICB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iCiAgIHhtbG5zOmNjPSJodHRwOi8vY3JlYXRpdmVjb21tb25zLm9yZy9ucyMiCiAgIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyIKICAgeG1sbnM6c3ZnPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIKICAgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIgogICB4bWxuczpzb2RpcG9kaT0iaHR0cDovL3NvZGlwb2RpLnNvdXJjZWZvcmdlLm5ldC9EVEQvc29kaXBvZGktMC5kdGQiCiAgIHhtbG5zOmlua3NjYXBlPSJodHRwOi8vd3d3Lmlua3NjYXBlLm9yZy9uYW1lc3BhY2VzL2lua3NjYXBlIgogICB3aWR0aD0iMjEwbW0iCiAgIGhlaWdodD0iMjk3bW0iCiAgIHZpZXdCb3g9IjAgMCAyMTAgMjk3IgogICB2ZXJzaW9uPSIxLjEiCiAgIGlkPSJzdmc4IgogICBpbmtzY2FwZTp2ZXJzaW9uPSIwLjkyLjMgKDI0MDU1NDYsIDIwMTgtMDMtMTEpIgogICBzb2RpcG9kaTpkb2NuYW1lPSJzb29xcl9sb2dvX2ljb24uc3ZnIj4KICA8ZGVmcwogICAgIGlkPSJkZWZzMiIgLz4KICA8c29kaXBvZGk6bmFtZWR2aWV3CiAgICAgaWQ9ImJhc2UiCiAgICAgcGFnZWNvbG9yPSIjZmZmZmZmIgogICAgIGJvcmRlcmNvbG9yPSIjNjY2NjY2IgogICAgIGJvcmRlcm9wYWNpdHk9IjEuMCIKICAgICBpbmtzY2FwZTpwYWdlb3BhY2l0eT0iMSIKICAgICBpbmtzY2FwZTpwYWdlc2hhZG93PSIyIgogICAgIGlua3NjYXBlOnpvb209IjAuNDk0OTc0NzUiCiAgICAgaW5rc2NhcGU6Y3g9Ii00ODUuNTc1NjQiCiAgICAgaW5rc2NhcGU6Y3k9IjY5OS45Njk1OCIKICAgICBpbmtzY2FwZTpkb2N1bWVudC11bml0cz0ibW0iCiAgICAgaW5rc2NhcGU6Y3VycmVudC1sYXllcj0ibGF5ZXIxIgogICAgIHNob3dncmlkPSJmYWxzZSIKICAgICBpbmtzY2FwZTp3aW5kb3ctd2lkdGg9IjE5MjAiCiAgICAgaW5rc2NhcGU6d2luZG93LWhlaWdodD0iMTAwMSIKICAgICBpbmtzY2FwZTp3aW5kb3cteD0iMjU1MSIKICAgICBpbmtzY2FwZTp3aW5kb3cteT0iMTMyIgogICAgIGlua3NjYXBlOndpbmRvdy1tYXhpbWl6ZWQ9IjEiIC8+CiAgPG1ldGFkYXRhCiAgICAgaWQ9Im1ldGFkYXRhNSI+CiAgICA8cmRmOlJERj4KICAgICAgPGNjOldvcmsKICAgICAgICAgcmRmOmFib3V0PSIiPgogICAgICAgIDxkYzpmb3JtYXQ+aW1hZ2Uvc3ZnK3htbDwvZGM6Zm9ybWF0PgogICAgICAgIDxkYzp0eXBlCiAgICAgICAgICAgcmRmOnJlc291cmNlPSJodHRwOi8vcHVybC5vcmcvZGMvZGNtaXR5cGUvU3RpbGxJbWFnZSIgLz4KICAgICAgICA8ZGM6dGl0bGU+PC9kYzp0aXRsZT4KICAgICAgPC9jYzpXb3JrPgogICAgPC9yZGY6UkRGPgogIDwvbWV0YWRhdGE+CiAgPGcKICAgICBpbmtzY2FwZTpsYWJlbD0iTGF5ZXIgMSIKICAgICBpbmtzY2FwZTpncm91cG1vZGU9ImxheWVyIgogICAgIGlkPSJsYXllcjEiPgogICAgPHBhdGgKICAgICAgIGlua3NjYXBlOmNvbm5lY3Rvci1jdXJ2YXR1cmU9IjAiCiAgICAgICBpZD0icGF0aDE1MSIKICAgICAgIGQ9Im0gMTYzLjc0MzAyLDI0NS41MzI3NiBjIC0xLjUyNDY2LC0wLjc1NDY5IC0zLjYxMjM1LC0yLjQxODI1IC00LjYzOTI4LC0zLjY5Njc5IC0xLjAyNjk1LC0xLjI3ODU2IC02Ljg3MDk0LC0xMC41NzEyNSAtMTIuOTg2NywtMjAuNjUwNDYgLTkuODg0MjcsLTE2LjI5MDA0IC0xMS4yOTc1MywtMTguMzIwNDcgLTEyLjcyMjAxLC0xOC4yNzc3NSAtMC44ODEzNiwwLjAyNjQgLTQuMzUxMzUsMC41NDI3NCAtNy43MTEwNywxLjE0NzMyIC0xNy4zOTA0LDMuMTI5MzcgLTM4LjM3NzA2NSwxLjUwNTkyIC01NC42MTk1MjEsLTQuMjI1MjIgLTI3LjQxNjgxMSwtOS42NzM5NSAtNDguNjEwMDA3LC0zMS44MjAyNyAtNTUuMjUwMDA1LC01Ny43MzQ3MSAtMi44MDA4NjEsLTEwLjkzMTE1IC0zLjU1MzEsLTE4LjQyMzk2IC0zLjA5NDE5NywtMzAuODIwMzkgMC44MDczNSwtMjEuODA5MTM1IDcuMjA4MTEyLC00MS45ODA5MDkgMTcuMzE4NzMyLC01NC41NzkzNTQgMjAuMTcwNjIzLC0yNS4xMzM4MTEgNDguMzI4MDQsLTM2Ljc0MDQwMSA4NC4yNTQwMTEsLTM0LjcyOTgxMSAzNC4xOTQyMywxLjkxMzY3NSA2My4zMDQxNywyMC4xNTQxMTUgNzcuMjk1NTIsNDguNDMzODExIDcuNDYzMjEsMTUuMDg0NzczIDEwLjYyMzQ4LDMwLjg4NTAxNCA5LjcwNzg0LDQ4LjUzNTM0NCAtMS40OTAyLDI4LjcyNDk5IC0xNC44MTMxOSw1NC4wNTYzNyAtMzYuMzc4NTcsNjkuMTY3NjMgLTIuODM0ODIsMS45ODY0MiAtNS4xNTQyMiwzLjg1OTczIC01LjE1NDIyLDQuMTYyOTEgMCwwLjMwMzE4IDQuNDc3MDEsNy45MzcxOSA5Ljk0ODkxLDE2Ljk2NDQzIDEwLjM3MzYyLDE3LjExMzgzIDExLjQyNDM5LDE5LjEwMjQgMTIuMTU0NzIsMjMuMDAyNDggMS44MzQ1Myw5Ljc5NzAyIC05LjA1NjAzLDE3Ljc4OTE5IC0xOC4xMjQxNiwxMy4zMDA1NiB6IG0gLTQwLjMwOTYxLC02OC41OTQ0NSBjIDEyLjg1MTQzLC0zLjYyODU3IDIwLjY4MDg4LC04LjIxOTkyIDI5LjA2MTA5LC0xNy4wNDIwOSAyNS42MTczMSwtMjYuOTY4MzYgMjIuMTU4ODYsLTc2Ljk3OTkyNyAtNi44NDIzNSwtOTguOTQ0ODM5IEMgMTIxLjAxMTMsNDIuMjg4OTA1IDgyLjcyODQ5Myw0NC42NDM0NDIgNjEuODU5NTE2LDY2LjEwNDk0NSA0OC44MzU4ODQsNzkuNDk4MzU4IDQyLjU2MTUxNCw5Ny4wNzk0MTMgNDMuNTg4NDY1LDExNy4zMDEyMSBjIDEuNTc4MTcxLDMxLjA3NTkzIDIwLjA5MjM0NSw1My45OTY2MiA0OC42NTg4OTksNjAuMjQwMSA3LjQ0NzQ0NSwxLjYyNzcgMjQuNDQ5Nzc2LDEuMjk4OTUgMzEuMTg2MDQ2LC0wLjYwMyB6IgogICAgICAgc3R5bGU9ImZpbGw6IzU3NTc1ODtzdHJva2Utd2lkdGg6MC42NDMwMTExNSIgLz4KICA8L2c+Cjwvc3ZnPgo=");
    }

    public function sooqr_add_action_links($links)
    {
        /*
         * Add a settings link to "Deactivate |Edit"
         *
         *
         */
        $settings_link = array(
            '<a href="' . admin_url('admin.php?page=' . $this->plugin_name) . '">' . __('Settings',
                $this->plugin_name) . '</a>',
        );
        return array_merge($settings_link, $links);
    }

    public function display_plugin_setup_page()
    {
        if(isset($_POST['needCreds']))
        {
            $url = wp_nonce_url('admin.php?page=Sooqr+Search&tab=advanced');
            if (false === ($creds = request_filesystem_credentials($url, '', false, false, ['snippetjs', 'needCreds', 'javascript_editor_nonce', 'reset_javascript']) ) ) {
                return; // stop processing here
            }
            $this->sooqr_save_javascript();
        }
        include_once('partials/sooqr-admin-display.php');
    }

    public function sooqr_options_update()
    {
        if (true === $this->validate_priviliges()) {
            foreach ($this->xmlfields as $field) {
                register_setting('xml_feed_options', 'sooqr_xml_product_' . $field,
                    array($this, 'sooqr_sanitize_xml_product_setting', 'default' => 1));
            }
            register_setting('general_options', 'sooqr_enabled', array($this, 'sooqr_sanitize_sooqr_enabled'));
            register_setting('general_options', 'sooqr_id', array($this, 'sooqr_sanitize_id'));
            register_setting('general_options', 'sooqr_input_id',
                array('sanitize_callback' => array($this, 'sooqr_sanitize_input_id'), 'default' => 'search'));
            register_setting('cron_options', 'sooqr_enable_cron', array($this, 'sooqr_sanitize_enable_cron'));
            register_setting('cron_options', 'sooqr_cron_frequency', array($this, 'sooqr_sanitize_cron_frequency'));
            register_setting('cron_last_run', 'sooqr_cron_last_run');
            register_setting('xml_content_feed_options', 'sooqr_content_feed_enabled',
                array($this, 'sooqr_sanitize_content_feed_enabled'));
            foreach ($this->posts as $post) {
                register_setting('xml_content_feed_options', 'sooqr_content_feed_enabled_id_' . $post->ID,
                    array($this, 'sooqr_sanitize_content_feed_enabled_id', 'default' => 1));
            }
            register_setting('javascript_editor', 'sooqr_javascript_location', array($this));
        }

    }

    public function sooqr_sanitize_content_feed_enabled_id($input)
    {
        $valid = (isset($input) && !empty($input)) ? 1 : 0;
        return $valid;
    }

    public function sooqr_sanitize_sooqr_enabled($input)
    {
        $valid = (isset($input) && !empty($input)) ? 1 : 0;
        if ($valid != get_option('sooqr_enabled')) {
            $this->sooqr_change_enabled_json($valid);
        }
        return $valid;
    }

    public function sooqr_sanitize_content_feed_enabled($input)
    {
        $valid = (isset($input) && !empty($input)) ? 1 : 0;
        return $valid;
    }

    public function sooqr_sanitize_id($input)
    {
        if (true === $this->validate_priviliges()) {
            $id = sanitize_text_field($input);
            if (preg_match("/^([0-9]{6})\-[0-9]{1}\z/", $input)) {
                $this->sooqr_change_id($id);
                return $id;
            } else {
                add_settings_error('sooqr_id', '', 'Invalid Sooqr ID, format should be \'123456-1\'');
            }
        } else {
            $this->sooqr_add_permission_error();
        }
    }

    public function sooqr_sanitize_input_id($input)
    {
        if (true === $this->validate_priviliges()) {
            if (!empty($input)) {
                $this->sooqr_change_input_id($input);
                return $input;
            }
        } else {
            $this->sooqr_add_permission_error();
        }
    }

    public function sooqr_sanitize_cron_frequency($input)
    {
        if (true === $this->validate_priviliges()) {
            $input = sanitize_text_field($input);
            if ($input != get_option('sooqr_cron_frequency')) {
                $this->sooqr_update_cron($input, get_option('sooqr_enable_cron'));
            }
            return $input;
        } else {
            $this->sooqr_add_permission_error();
        }
    }

    public function sooqr_sanitize_enable_cron($input)
    {
        if (true === $this->validate_priviliges()) {
            $valid = (isset($input) && !empty($input)) ? 1 : 0;
            if ($valid !== get_option('sooqr_enable_cron')) {
                $this->sooqr_update_cron(get_option('sooqr_cron_frequency'), $valid);
            }
            return $valid;
        } else {
            $this->sooqr_add_permission_error();
        }
    }

    public function sooqr_sanitize_xml_product_setting($input)
    {
        $valid = (isset($input) && !empty($input)) ? 1 : 0;
        if ($this->validationnr == 1) {
            $this->validationnr = 2;
        }
        return $valid;
    }

    public function sooqr_add_options()
    {
        if (true === $this->validate_priviliges()) {
            /*
             * general options
             */
            add_settings_section('information_section',
                'Information',
                'information_callback',
                'information'
            );
            add_settings_section('general_options_section',
                'General options',
                '',
                'general_options'
            );
            add_settings_field('sooqr_enabled',
                'Enable Sooqr',
                'enable_sooqr_callback',
                'general_options',
                'general_options_section'
            );
            add_settings_field('sooqr_id',
                'Sooqr ID:',
                'sooqr_id_callback',
                'general_options',
                'general_options_section'
            );
            add_settings_field('sooqr_input_id',
                'Search input ID:',
                'sooqr_input_id_callback',
                'general_options',
                'general_options_section'
            );

            /*
             * cron options
             */
            add_settings_section('cron_options_section',
                'Cron options',
                'sooqr_cron_options_callback',
                'cron_options'
            );

            add_settings_field('sooqr_enable_cron',
                'Enable Cron:',
                'sooqr_enable_cron_callback',
                'cron_options',
                'cron_options_section'
            );

            add_settings_field('sooqr_cron_frequency',
                'Cron frequency:',
                'sooqr_cron_frequency_callback',
                'cron_options',
                'cron_options_section'
            );

            /*
             * xml feed settings
             */
            add_settings_section('sooqr_xml_feed_settings_section',
                'XML feed settings',
                'sooqr_xml_feed_settings_callback',
                'xml_feed_options'
            );
            foreach ($this->xmlfields as $field) {
                add_settings_field('xml_product_' . $field,
                    'Product ' . $field . ':',
                    'xml_product_callback',
                    'xml_feed_options',
                    'sooqr_xml_feed_settings_section',
                    $field
                );
            }
            /*
             * content xml feed settings
             */
            add_settings_section('sooqr_content_xml_feed_settings_section',
                'Content XML feed settings',
                'sooqr_content_xml_feed_settings_callback',
                'content_xml_feed_options'
            );

            add_settings_section('sooqr_content_xml_feed_settings_posts_section',
                'Content XML posts enabled',
                'sooqr_content_xml_feed_settings_posts_callback',
                'content_xml_feed_options'
            );
            add_settings_field('sooqr_content_feed_enabled',
                'Content feed enabled',
                'sooqr_content_feed_enabled_callback',
                'content_xml_feed_options',
                'sooqr_content_xml_feed_settings_section'
            );
            foreach ($this->posts as $post) {
                add_settings_field('sooqr_content_feed_enabled_id_' . $post->ID,
                    $post->post_title,
                    'sooqr_content_feed_enabled_id_callback',
                    'content_xml_feed_options',
                    'sooqr_content_xml_feed_settings_posts_section',
                    $post->ID
                );
            }

        }
    }

    public function sooqr_change_id($id)
    {
        if (true === $this->validate_priviliges()) {
            $file = get_option('sooqr_javascript_location');
            $content = file_get_contents($file);
            file_put_contents($file,
                preg_replace('/(var sooqrAccount).*/ ', 'var sooqrAccount = \'' . $id . '\';', $content));
        } else {
            $this->sooqr_add_permission_error();
        }
    }

    public function sooqr_change_input_id($id)
    {
        if (true === $this->validate_priviliges()) {
            $file = get_option('sooqr_javascript_location');
            $content = file_get_contents($file);
            file_put_contents($file, preg_replace('/(var inputID).*/', 'var inputID = \'' . $id . '\';', $content));
        } else {
            $this->sooqr_add_permission_error();
        }
    }

    public function sooqr_change_enabled_json($value)
    {
        if (true === $this->validate_priviliges()) {
            $path = plugin_dir_path(__DIR__) . 'public/sooqr.json';
            $json = json_decode(file_get_contents($path), true);
            $json['search']['enabled'] = $value;
            file_put_contents($path, json_encode($json));
        } else {
            $this->sooqr_add_permission_error();
        }
    }

    public function sooqr_generate_feed()
    {
        if (isset($_POST['generate_xml_feed']) || isset($_POST['generate_content_xml_feed']) || defined('DOING_CRON')) {
            if (true === $this->validate_priviliges() || defined('DOING_CRON')) {
                //product xml feed
                if ((isset($_POST['generate_xml_feed']) && check_admin_referer('generate_xml_feed')) || defined('DOING_CRON')) {
                    $this->sooqr_generate_xml_feed();
                }

                //content feed
                if ((isset($_POST['generate_content_xml_feed']) && check_admin_referer('generate_content_xml_feed')) || (defined('DOING_CRON')) && get_option('sooqr_content_feed_enabled') == 1) {
                    $this->sooqr_generate_content_xml_feed();
                }
            } else {
                $this->sooqr_add_permission_error();
            }
        }

    }

    public function sooqr_generate_content_xml_feed($block_size = 200, $allPages = false)
    {
        //make array with only xmlfields that are enabled
        $starttime = microtime(true);
        $xmlfields = $this->xmlfields;
        $enabledXmlfields = array();
        foreach ($xmlfields as $xmlfield) {
            if (get_option('sooqr_xml_product_' . $xmlfield) == 1 || $allPages === true) {
                array_push($enabledXmlfields, $xmlfield);
            }
        }
        $posts = get_pages();
        $total = 0;
        $xmlMain = new \SimpleXMLElement("<rss encoding='utf-8'><config><system>Woocommerce_Sooqr</system><extension_version>$this->version</extension_version><store>" . get_bloginfo('name') . "</store><url>" . get_site_url() . "</url></config></rss>");
        $result = $xmlMain->addChild('items');

        foreach ($posts as $post) {
            if (get_option('sooqr_content_feed_enabled_id_' . $post->ID) == 1 || $allPages === true) {
                $postxml = $result->addChild('item');
                $postxml->addChild('content_type', 'post');
                foreach ($enabledXmlfields as $xmlfield) {
                    switch ($xmlfield) {
                        case 'id':
                            $postxml->addChild($xmlfield, $post->ID);
                            break;
                        case 'title':
                            $postxml->addChild($xmlfield, $post->post_title);
                            break;
                        case 'url':
                            $postxml->addChild($xmlfield, get_permalink($post));
                            break;
                        case 'description':
                            $postxml->addChild($xmlfield, $this->sooqr_get_stripped_description($post->post_content));
                            break;
                    }
                }
            }
            $total++;
        }

        $endtime = microtime(true);
        $exectime = ($endtime - $starttime);
        $results = $xmlMain->addchild('results');
        $results->addchild('posts_total', $total);
        $results->addchild('processing_time', $exectime);
        $results->addchild('date_created', date("Y-m-d H:i:s"));
        $file = $this->feedDirectory . 'sooqrcontentfeed.xml';
        file_put_contents($file, $xmlMain->asXML());
        if (isset($_POST['generate_content_xml_feed']) && check_admin_referer('generate_content_xml_feed')) {
            $url = $this->feedUrl . 'sooqrcontentfeed.xml';
            add_settings_error('none', 'xml-feed-link',
                "XML content feed: <a target=\"_blank\" href=\"" . $url . "\">" . $url . "</a>", 'test');
        }
    }

    public function sooqr_generate_xml_feed($block_size = 200, $maxPages = 0)
    {
        $starttime = microtime(true);
        //make array with only xmlfields that are enabled
        $xmlfields = $this->xmlfields;
        $enabledXmlfields = array();
        foreach ($xmlfields as $xmlfield) {
            if (get_option('sooqr_xml_product_' . $xmlfield) == 1 || $maxPages !== 0) {
                array_push($enabledXmlfields, $xmlfield);
            }
        }
        $numberOfPages = wc_get_products(array(
            'limit' => $block_size,
            'paginate' => true,
            'lazy_load_term_meta' => false,
            'update_post_term_cache' => false,
            'cache_results' => false,
        ))->max_num_pages;
        //limit pages based on param
        $numberOfPages = ($maxPages === 0) ? $numberOfPages : $maxPages;
        $productcount = 0;
        // need 2 fix XML header
        $xmlMain = new \SimpleXMLElement("<rss encoding='utf-8'><config><system>Woocommerce_Sooqr</system><extension_version>$this->version</extension_version><store>" . get_bloginfo('name') . "</store><url>" . get_site_url() . "</url></config></rss>");
        $result = $xmlMain->addChild('items');
        for ($page = 1; $page <= $numberOfPages; $page++) {
            $products = wc_get_products(array(
                'limit' => $block_size,
                'orderby' => 'id',
                'order' => 'ASC',
                'page' => $page,
                'lazy_load_term_meta' => false,
                'update_post_term_cache' => false,
                'cache_results' => false,
            ));
            foreach ($products as $product) {
                if ($product->get_status() === 'publish' && $product->is_visible()) {

                    $productxml = $result->addChild('item');
                    $productxml->addChild('content_type', "product");
                    foreach ($enabledXmlfields as $xmlfield) {
                        switch ($xmlfield) {
                            case 'id':
                                $productxml->addChild($xmlfield, $product->get_id());
                                break;
                            case 'sku':
                                $productxml->addChild($xmlfield, $product->get_sku());
                                break;
                            case 'title':
                                $productxml->addChild($xmlfield, $product->get_title());
                                break;
                            case 'price':
                                $productxml->addChild($xmlfield, $product->get_price());
                                if ($product->is_on_sale()) {
                                    $productxml->addChild('normal_price', $product->get_regular_price());
                                }
                                break;
                            case 'img':
                                $productxml->addChild($xmlfield, get_the_post_thumbnail_url($product->get_id()));
                                break;
                            case 'url':
                                $productxml->addChild($xmlfield, $product->get_permalink());
                                break;
                            case 'weight':
                                $productxml->addChild($xmlfield, $product->get_weight());
                                break;
                            case 'cat':
                                $categoryIDs = $product->get_category_ids();
                                if (!empty($categoryIDs)) {
                                    $categories = array();
                                    //loop over all category ID's, get their name and level, put it in an array
                                    foreach ($categoryIDs as $categoryID) {
                                        //get the category & level
                                        $category = get_term_by('id', $categoryID, 'product_cat');
                                        $level = $this->sooqr_get_category_level($categoryID);
                                        //if level does not exist, create
                                        if (!isset($categories[$level])) {
                                            $categories[$level] = array();
                                        }
                                        //if category name does not exists in its level, put it in
                                        if (!isset($categories[$level][$category->name])) {
                                            array_push($categories[$level], $category->name);
                                        }
                                    }

                                    //loop over all the categories by their level
                                    foreach ($categories as $level => $categoryLevel) {
                                        //make the category level xml
                                        $categoryLevelxml = $productxml->addChild('category' . $level);
                                        //put every category (name) in there as a node
                                        foreach ($categoryLevel as $category) {
                                            $categoryLevelxml->addChild('node', $category);
                                        }
                                    }
                                }
                                break;
                            case 'description':
                                $productxml->addChild($xmlfield,
                                    $this->sooqr_get_stripped_description($product->get_description()));
                                break;
                            case 'tags':
                                $terms = get_the_terms($product->get_id(), 'product_tag');
                                $tagxml = $productxml->addchild('tags');
                                if (!empty($terms)) {
                                    foreach ($terms as $term) {
                                        $tagxml->addChild('tag', $term->name);
                                    }
                                }
                                break;
                            case 'stock':
                                $productxml->addChild('is_in_stock', $product->get_stock_status());
                                $productxml->addChild('stock', $product->get_stock_quantity());
                                break;
                            case 'attributes':
                                $attributes = $product->get_attributes();
                                foreach ($attributes as $attribute) {
                                    if (substr($attribute->get_name(), 0, 3) == 'pa_') {
                                        $attributexml = $productxml->addchild('sqr-' . str_replace([' ', '/'], '_',
                                                substr($attribute->get_name(), 3)));
                                        $options = $attribute->get_options();
                                        foreach ($options as $option) {
                                            $attributexml->addchild('value', get_term($option)->name);
                                        }
                                    } else {
                                        $attributexml = $productxml->addchild('sqr-' . str_replace([' ', '/'], '_',
                                                $attribute->get_name()));
                                        foreach ($attribute->get_options() as $option) {
                                            $attributexml->addchild('value', $option);
                                        }
                                    }
                                }
                                break;
                            //case not in list, try to guess?
                        }
                    }
                }
                $productcount++;
            }
            $this->sooqr_clear_object_cache();
        }
        $endtime = microtime(true);
        $exectime = ($endtime - $starttime);
        $results = $xmlMain->addchild('results');
        $results->addchild('products_total', $productcount);
        $results->addchild('processing_time', $exectime);
        $results->addchild('date_created', date("Y-m-d H:i:s"));
        $file = $this->feedDirectory . 'sooqrfeed.xml';
        file_put_contents($file, $xmlMain->asXML());
        update_option('sooqr_cron_last_run', time());
        if (isset($_POST['generate_xml_feed']) && check_admin_referer('generate_xml_feed')) {
            $url = $this->feedUrl . 'sooqrfeed.xml';
            add_settings_error('none', 'xml-feed-link',
                "XML feed: <a target=\"_blank\" href=\"" . $url . "\">" . $url . "</a>", 'test');
        }


    }

    public function sooqr_getMemoryUsage()
    {
        $memoryUsage = memory_get_usage(true);
        if ($memoryUsage < 1024) {
            $usage = $memoryUsage . ' b';
        } elseif ($memoryUsage < 1048576) {
            $usage = round($memoryUsage / 1024, 2) . ' KB';
        } else {
            $usage = round($memoryUsage / 1048576, 2) . ' MB';
        }
        return $usage;
    }

    public function sooqr_get_category_level($id)
    {
        return (count(get_ancestors($id, 'product_cat')));
    }

    public function sooqr_update_cron($frequency, $enabled = 0)
    {
        if (true === $this->validate_priviliges()) {
            if ($enabled == 1) {

                if (wp_next_scheduled('sooqr_update_xml_feed')) {
                    $timestamp = wp_next_scheduled('sooqr_update_xml_feed');
                    wp_unschedule_event($timestamp, 'sooqr_update_xml_feed');
                }
                switch ($frequency) {
                    case 24:
                        $frequencyString = 'daily';
                        break;
                    case 12:
                        $frequencyString = 'twicedaily';
                        break;
                    case 6:
                        $frequencyString = 'six_hours';
                        break;
                    case 3:
                        $frequencyString = 'three_hours';
                        break;
                    case 1:
                        $frequencyString = 'hourly';
                        break;
                    default:
                        //just to be safe, set default on longest
                        $frequencyString = 'daily';
                }
                //timestamp = unix timestamp, need to calculate if they set a custom time to run
                wp_schedule_event(time(), $frequencyString, 'sooqr_update_xml_feed');

            } else {
                if (wp_next_scheduled('sooqr_update_xml_feed')) {
                    $timestamp = wp_next_scheduled('sooqr_update_xml_feed');
                    wp_unschedule_event($timestamp, 'sooqr_update_xml_feed');
                }
            }
        } else {
            $this->sooqr_add_permission_error();
        }
    }

    public function sooqr_save_javascript()
    {
        if (isset($_POST['snippetjs']) || isset($_POST['reset_javascript'])) {
            check_admin_referer('javascript_editor', 'javascript_editor_nonce');
            if (true === $this->validate_priviliges()) {

                //check for rights
                $url = wp_nonce_url('admin.php?page=Sooqr+Search&tab=advanced', 'javascript_editor_nonce');
                if (false === ($creds = request_filesystem_credentials($url, '', false, false, null))) {
                    return false; // stop processing here
                }
                if (!WP_Filesystem($creds)) {
                    request_filesystem_credentials($url, '', true, false, null);
                    return false;
                }


                global $wp_filesystem;

                //update location of js in db (we cant access wp_content_dir from wp_filesystem on updating general options, so we need to save it
                $file = $wp_filesystem->wp_content_dir() . 'sooqr/sooqrsearch.js';
                update_option('sooqr_javascript_location', $file);


                //check if directory exists
                if (!$wp_filesystem->exists($wp_filesystem->wp_content_dir() . 'sooqr')) {
                    $wp_filesystem->mkdir(
                        $wp_filesystem->wp_content_dir() . 'sooqr'
                    );
                }

                if (isset($_POST['reset_javascript']) !== true) {
                    $wp_filesystem->put_contents(
                        $file,
                        stripslashes($_POST['snippetjs'])

                    );
                    wp_redirect('admin.php?page=Sooqr+Search&tab=advanced&return=success');
                } else {
                    $wp_filesystem->put_contents(
                        $file,
                        $wp_filesystem->get_contents(plugin_dir_path(__DIR__) . 'public/js/sooqrdefault.js')
                    );
                    wp_redirect('admin.php?page=Sooqr+Search&tab=advanced&return=reset');
                }

            } else {
                $this->sooqr_add_permission_error();
            }
        }

    }

    public function sooqr_clear_object_cache()
    {
        global $wpdb, $wp_object_cache;
        $wpdb->queries = array(); // or define( 'WP_IMPORTING', true );
        if (!is_object($wp_object_cache)) {
            return;
        }
        // The following are Memcached (Redux) plugin specific (see https://core.trac.wordpress.org/ticket/31463).
        if (isset($wp_object_cache->group_ops)) {
            $wp_object_cache->group_ops = array();
        }
        if (isset($wp_object_cache->stats)) {
            $wp_object_cache->stats = array();
        }
        if (isset($wp_object_cache->memcache_debug)) {
            $wp_object_cache->memcache_debug = array();
        }
        // Used by `WP_Object_Cache` also.
        if (isset($wp_object_cache->cache)) {
            $wp_object_cache->cache = array();
        }
    }

    private function sooqr_get_stripped_description($description)
    {
        //strip all ugly shortcodes from page builders etc
        $description = preg_replace('/\[.*?\]/', "", $description);

        return str_replace("&nbsp;", " ", trim(stripslashes(
            wp_filter_nohtml_kses(html_entity_decode(strip_shortcodes($description))))));
    }

    private function sooqr_add_permission_error()
    {
        if (!$this->permError) {
            echo "<div class=\"notice notice-error is-dismissible\"><p>You are not allowed to perform this action! (edit-plugins)</p></div>";
            $this->permError = true;
        }
    }


    /**
     * Validate if user has sufficient priviliges.
     *
     * @since 1.1.6
     */
    public function validate_priviliges() {
        $user = wp_get_current_user();
        $allowed_roles = array( 'administrator' );
        if ( array_intersect( $allowed_roles, $user->roles ) ) {
            return true;
        }
        return false;
    }


}
