<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 */

class Plugin_Activator {

    public static function activate() {
        register_property_taxonomies(); // Register taxonomies on activation
        flush_rewrite_rules(); // Flush permalinks to avoid 404 errors
    }

}