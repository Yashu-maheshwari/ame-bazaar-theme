<?php
require_once('../../../wp-load.php');
if (isset($_GET['secret_key']) && $_GET['secret_key'] === 'ame123') {
    global $wpdb;
    $results = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE 'litespeed.conf%'", ARRAY_A);
    echo json_encode($results);
} else {
    echo "Access denied";
}
