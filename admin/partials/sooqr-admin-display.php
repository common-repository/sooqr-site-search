<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.sooqr.com
 * @since      1.0.0
 *
 * @package    Sooqr
 * @subpackage Sooqr/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap sooqrPlugin">
    <h2 class="page-title">Sooqr Search Settings</h2>
    <?php
    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'information';
    ?>
    <h2 class="nav-tab-wrapper">
        <a href="?page=Sooqr+Search&tab=information"
           class="nav-tab <?php echo $active_tab == 'information' ? 'nav-tab-active' : ''; ?>">Information</a>
        <a href="?page=Sooqr+Search&tab=general_options"
           class="nav-tab <?php echo $active_tab == 'general_options' ? 'nav-tab-active' : ''; ?>">General</a>
        <?php
        if (get_option('sooqr_woo_enabled') == 1) {
            $active = $active_tab == "xml_feed_options" ? "nav-tab-active" : "";
            $html = '<a href="?page=Sooqr+Search&tab=xml_feed_options" class="nav-tab ' . $active . '">XML Product Feed</a>';
            echo $html;
        } ?>
        <a href="?page=Sooqr+Search&tab=xml_content_feed_options"
           class="nav-tab <?php echo $active_tab == 'xml_content_feed_options' ? 'nav-tab-active' : ''; ?>">XML Content
            Feed</a>
        <a href="?page=Sooqr+Search&tab=cron_options"
           class="nav-tab <?php echo $active_tab == 'cron_options' ? 'nav-tab-active' : ''; ?>">Cron</a>
        <a href="?page=Sooqr+Search&tab=advanced"
           class="nav-tab <?php echo $active_tab == 'advanced' ? 'nav-tab-active' : ''; ?>">Advanced</a>

    </h2>
    <?php
    settings_errors();
    if ($active_tab == 'information') {
        do_settings_sections('information');
    } elseif ($active_tab == 'general_options') {
        echo '<form method="post" action="options.php">';
        settings_fields('general_options');
        do_settings_sections('general_options');
        submit_button();
        echo '</form>';
    } elseif ($active_tab == 'xml_feed_options') {
        echo '<form method="post" action="options.php">';
        settings_fields('xml_feed_options');
        do_settings_sections('xml_feed_options');
        submit_button();
        echo '</form>';
        //generate xml feed button
        echo '<form method="post" action="options.php?page=Sooqr+Search&tab=xml_feed_options">';
        wp_nonce_field('generate_xml_feed');
        echo '<input type="hidden" value="true" name="generate_xml_feed" />';
        submit_button('Generate xml feed');
    } elseif ($active_tab == 'xml_content_feed_options') {
        echo '<form method="post" action="options.php">';
        settings_fields('xml_content_feed_options');
        do_settings_sections('content_xml_feed_options');
        submit_button();
        echo '</form>';
        //generate content xml feed button
        echo '<form method="post" action="options.php?page=Sooqr+Search&tab=xml_content_feed_options">';
        wp_nonce_field('generate_content_xml_feed');
        echo '<input type="hidden" value="true" name="generate_content_xml_feed" />';
        submit_button('Generate content xml feed');
        echo '</form>';
    } elseif ($active_tab == 'cron_options') {
        echo '<h1>Last Run: ' . date("F j, Y, g:i a", get_option("sooqr_cron_last_run")) . '</h1>';
        echo '<form method="post" action="options.php">';
        settings_fields('cron_options');
        do_settings_sections('cron_options');
        submit_button();
        echo '</form>';
    } elseif ($active_tab == 'advanced') {
        if(isset($_GET['return']))
        {
            switch($_GET['return'])
            {
                case 'success':
                    echo '<div class="notice notice-success is-dismissible"><p>Javascript successfully saved</p></div>';
                    break;
                case 'reset':
                    echo '<div class="notice notice-warning is-dismissible"><p>Successfully reset the javascript. Please save the general options again.</p></div>';
                    break;
                case 'error':
                    echo '<div class="notice notice-error is-dismissible"><p>Error saving javascript, please check if the plugin has rights to write files</p></div>';
                    break;
            }
        }

        echo '<form method="post" action="admin.php?page=Sooqr+Search&tab=advanced" name="jsform" id="editorFormDiv">';
        echo '<h4 class="editorSubHeader"><i>For available Sooqr Javascript functions, please see our <a href="http://support.sooqr.com/customizing-sooqr">support page</a> on the subject.</i></h4>';
        wp_nonce_field('javascript_editor', 'javascript_editor_nonce');
        echo '<input type="hidden" value="true" id="snippetjs" name="snippetjs" />';
        echo '<input type="hidden" name="action" value="sooqr_save_javascript">';
        echo '<input type="hidden" name="needCreds" value="1"/>';
        $js = file_get_contents(esc_js(content_url('sooqr/sooqrsearch.js')));
        $html = '<div id="editor" style="height:600px;width:750px">' . $js . '</div>
                <script>var editor = ace.edit("editor");
                     editor.setTheme("ace/theme/tomorrow_night_eighties");
                     editor.session.setMode("ace/mode/javascript");
                     document.getElementById("snippetjs").value = editor.getSession().getValue();
                     editor.getSession().on("change", function () {
                        document.getElementById("snippetjs").value = editor.getSession().getValue();
                    });
                </script>';
        echo $html;
        submit_button();
        //reset to default button
        echo '<a onclick="confirmReset()" id="resetButton" class="button delete">Reset to Default</a>';
        echo '</form>';
    }
    function sooqr_cron_options_callback()
    {
        //echo "cron options callback";
    }

    function sooqr_enable_cron_callback()
    {
        $html = '<label class="switch"><input type="checkbox" id="sooqr_enable_cron" name="sooqr_enable_cron"
                           value="1"' . checked(1, get_option('sooqr_enable_cron'),
                false) . '/><span class="slider rounded"></span></label>';
        echo $html;
    }

    function sooqr_cron_frequency_callback()
    {
        $html = '<select name="sooqr_cron_frequency" id="sooqr_cron_frequency">
                        <option value="24" ' . selected(get_option('sooqr_cron_frequency'), 24, false) . '>Daily
                        </option>
                        <option value="12" ' . selected(get_option('sooqr_cron_frequency'), 12, false) . '>Every 12 hours
                        </option>
                        <option value="6" ' . selected(get_option('sooqr_cron_frequency'), 6, false) . '>Every 6 hours
                        </option>
                        <option value="3" ' . selected(get_option('sooqr_cron_frequency'), 3, false) . '>Every 3 hours
                        </option>
                        <option value="1" ' . selected(get_option('sooqr_cron_frequency'), 1, false) . '>Every hour
                        </option>
                    </select>';
        echo $html;
    }

    function information_callback()
    {
        echo '<div class="image_wrapper" style="float:right">
            <img src="' . plugin_dir_url(__FILE__) . '../images/sooqr-woocommerce.png' . '" alt="Sooqr"
            style="display:block;padding:12px 0;">
            <a href="https://signup.sooqr.com/?utm_source=extension&utm_medium=woocommerce&utm_campaign=first-tab"
            target="_blank" class="sooqr_button">Get Started Now</a></div>';
        echo '<h1 class="general_heading">Sooqr Search for WooCommerce</h1><p class="general_body">
            Make your visitors actually find what they are looking for with optimised site search. Instant and relevant.
            <br><br>Just a few steps and you will have an awesome site search up and running in your WooCommerce shop.
            <br>All it takes is 5 minutes of your time.<ol><li>
            Start <a href="https://signup.sooqr.com/?utm_source=extension&utm_medium=woocommerce&utm_campaign=first-tab"
            target="_blank">your 30-day free trial</a> for Sooqr Search, fill out the URL of your store
            (<b>' . home_url() . '</b>), take the necessary steps and receive your Sooqr ID in your mail.</li>
            <li>Add the Sooqr ID in the &lsquo;General&lsquo; tab and enable the plugin.</li>
            <li>Check your webshop and start finding products.</li>
            <li>And of course: optimise your site search at the
            <a href="https://my.sooqr.com/?utm_source=extension&utm_medium=woocommerce&utm_campaign=first-tab"
            target="_blank">Sooqr dashboard</a>.</li></ol>
            <br><br>Whenever you need help during this process, please contact
            <a href="mailto:support@sooqr.com">support@sooqr.com</a> or
            <a href="https://calendly.com/sooqrsearch/customersuccess" target="_blank">schedule a meeting</a>
            with our Customer Success Manager.</p> ';
    }

    function sooqr_id_callback()
    {
        $html = '<div id="sooqr_id_container"><input type="textbox" id="sooqr_id" name="sooqr_id"
                           placeholder="123456-1"
                           value="' . get_option('sooqr_id') . '"/></div>';
        echo $html;
    }

    function sooqr_input_id_callback()
    {
        $html = '<input type="textbox" id="sooqr_input_id" name="sooqr_input_id"
                            placeholder="Enter HTML input ID"
                            value="' . get_option('sooqr_input_id') . '"/>';
        echo $html;
    }

    function enable_sooqr_callback()
    {
        $html = '<label class="switch"><input type="checkbox" id="sooqr_enabled" name="sooqr_enabled" value="1"' . checked(1,
                get_option('sooqr_enabled'), false) . '/><span class="slider rounded"></span></label>';
        echo $html;
    }

    function sooqr_xml_feed_settings_callback()
    {
        //echo "xml settings callback";
    }

    function xml_product_callback($field)
    {
        $requiredfields = array('id', 'title', 'url');
        if (in_array($field, $requiredfields)) {
            $html = '<label class="switch switchbasic"><input type="checkbox" id="sooqr_xml_product_' . $field . '" name="sooqr_xml_product_' . $field . '" value="1" checked="checked" onclick="return false;" readonly /><span class="slider disabled rounded"></span></label>';
        } else {
            $html = '<label class="switch switchbasic"><input type="checkbox" id="sooqr_xml_product_' . $field . '" name="sooqr_xml_product_' . $field . '" value="1"' . checked(1,
                    get_option('sooqr_xml_product_' . $field),
                    false) . '/><span class="slider rounded"></span></label>';
        }
        echo $html;
    }

    function sooqr_content_xml_feed_settings_callback()
    {
        //echo "content xml settings callback";
    }

    function sooqr_content_feed_enabled_callback()
    {
        $html = '<label class="switch"><input type="checkbox" id="sooqr_content_feed_enabled" name="sooqr_content_feed_enabled" value="1"' . checked(1,
                get_option('sooqr_content_feed_enabled'), false) . '/><span class="slider rounded"></span></label>';
        echo $html;
    }

    function sooqr_content_feed_enabled_id_callback($ID)
    {
        $html = '<label class="switch"><input type="checkbox" id="sooqr_content_feed_enabled_id_' . $ID . '" name="sooqr_content_feed_enabled_id_' . $ID . '" value="1"' . checked(1,
                get_option('sooqr_content_feed_enabled_id_' . $ID),
                false) . '/><span class="slider rounded"></span></label>';
        echo $html;
    }

    function sooqr_content_xml_feed_settings_posts_callback()
    {
        //echo "content xml settings posts enabled callback";
    }

    function sooqr_javascript_editor_callback()
    {

    }

    ?>


</div>
