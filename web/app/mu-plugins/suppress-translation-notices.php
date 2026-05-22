<?php
/**
 * Plugin Name: Suppress Translation Loading Notices
 * Description: Suppresses "doing it wrong" notices for plugins loading translations too early (WordPress 6.7+)
 * Version: 1.0
 * Author: Sperling Interactive
 */

// Suppress textdomain translation loading notices for plugins (WordPress 6.7+)
add_filter('doing_it_wrong_trigger_error', function($trigger, $function_name) {
    if ($function_name === '_load_textdomain_just_in_time') {
        return false;
    }
    return $trigger;
}, 10, 2);

