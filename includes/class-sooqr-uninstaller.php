<?php


/**
 * Fired during plugin uninstallation.
 *
 * This class defines all code necessary to run during the plugin's uninstallation.
 *
 * @since      1.1.4
 * @package    Sooqr
 * @subpackage Sooqr/includes
 * @author     Sooqr <support@sooqr.com>
 */
namespace SooqrSearch;
class Sooqr_Uninstaller {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function sooqr_uninstall() {
        $alloptions = wp_load_alloptions();

        # Absolute path of the Sooqr folder
        $feedDirectory = get_site_url() . 'sooqr/';

        # Check if the directory exists
        if(is_dir($feedDirectory)) {

            # Init new RecursiveDirectoryIterator for the feed directory (Skipping dots)
            $fileIterator = new \RecursiveDirectoryIterator($feedDirectory, \RecursiveDirectoryIterator::SKIP_DOTS);

            # Init new RecursiveIteratorIterator for all the files
            $folderFiles = new \RecursiveIteratorIterator($fileIterator,\RecursiveIteratorIterator::CHILD_FIRST);

            # Loop through all the files
            foreach($folderFiles as $file) {
                # Check if the file is an directory
                if ($file->isDir()){
                    # Use rmdir to remove the directory
                    rmdir($file->getRealPath());
                } else {
                    # Use unlink to remove the fill
                    unlink($file->getRealPath());
                }
            }

            # Remove the parent/main directory after deletion of all contents inside it
            rmdir($feedDirectory);

        }

        $access_type = get_filesystem_method();
        if ($access_type === 'direct') {
            $creds = request_filesystem_credentials(get_home_path(), '', false, false, array());

            /* initialize the API */
            if ( ! WP_Filesystem($creds) ) {
                /* any problems and we exit */
                return false;
            }
            global $wp_filesystem;

            //check if directory exists
            if ($wp_filesystem->exists($wp_filesystem->wp_content_dir() . 'sooqr')) {
                $wp_filesystem->rmdir(
                    $wp_filesystem->wp_content_dir() . 'sooqr',
                    true
                );
            }

            if($wp_filesystem->exists($feedDirectory))
            {
                $wp_filesystem->rmdir(
                    $feedDirectory,
                    true
                );
            }


        } else {
            add_action('admin_notices', 'sooqr_add_permission_error');
        }

        foreach($alloptions as $name => $value)
        {
            if(substr( $name, 0, 5) === "sooqr"){
                delete_option($name);
            }
        }

    }

    public function sooqr_add_permission_error()
    {
        echo '<div class="notice notice-error is-dismissible"><p>Sooqr has no rights to make changes on your filesystem. There may be leftovers from the plugin left.</p></div>';
    }

}
