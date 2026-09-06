<?php
$dir = __DIR__;
while(!file_exists($dir.'/wp-load.php') && dirname($dir) !== $dir) { $dir = dirname($dir); }
if(file_exists($dir.'/wp-load.php')) {
    require_once $dir.'/wp-load.php';
    header('Content-Type: text/plain');
    echo "ACTIVE PLUGINS:\n";
    print_r(get_option('active_plugins'));
    echo "\n\nLITESPEED CACHE OPTIONS:\n";
    print_r(get_option('litespeed.conf'));
}
