<?php
/*
Plugin Name: Disable Cache on Local
Description: Disables LiteSpeed Cache and other caching plugins on local/development environments
Author: Sperling Interactive
Version: 1.0
*/

defined('ABSPATH') || exit;

if (defined('WP_ENV') && WP_ENV === 'development') {
    add_filter('option_active_plugins', function ($plugins) {
        if (!is_array($plugins)) {
            return $plugins;
        }

        $plugins_to_disable = [
            'litespeed-cache/litespeed-cache.php',
        ];

        foreach ($plugins_to_disable as $plugin) {
            $key = array_search($plugin, $plugins, true);
            if ($key !== false) {
                unset($plugins[$key]);
            }
        }

        return $plugins;
    });

    if (!defined('WP_CACHE')) {
        define('WP_CACHE', false);
    }
}
