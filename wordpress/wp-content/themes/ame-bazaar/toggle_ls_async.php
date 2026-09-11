<?php
require_once('../../../wp-load.php');
if (isset($_GET['secret_key']) && $_GET['secret_key'] === 'ame123') {
    $action = $_GET['action'];
    if ($action === 'off') {
        update_option('litespeed.conf.optm-css_async', '');
        echo "CSS Async set to OFF. ";
    } elseif ($action === 'on') {
        update_option('litespeed.conf.optm-css_async', '1');
        echo "CSS Async set to ON. ";
    }
    
    // Attempt to purge cache
    if (class_exists('\LiteSpeed\Purge')) {
        \LiteSpeed\Purge::purge_all();
        echo "Purged via LiteSpeed\Purge. ";
    } else {
        echo "Class LiteSpeed\Purge not found. ";
    }
} else {
    echo "Access denied";
}
