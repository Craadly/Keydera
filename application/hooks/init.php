<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function load_init_configs($param1 = false, $param2 = false, $param3 = false)
{
    $CI = &get_instance();
    $controller_class = $CI->router->fetch_class();
    
    // Skip license check for API and cron controllers
    $skip_controllers = array('api_external', 'api_internal', 'cron');
    
    if (!in_array($controller_class, $skip_controllers)) {
        // Load licensing class
        $license_checker = new L1c3n5380x4P1();
        $license_result = $license_checker->v3r1phy_l1c3n53(true);
        
        // If license is invalid and not already on verify_license page, redirect
        if ($license_result['status'] != true) {
            if (headers_sent() === false && 
                ($CI->router->fetch_class() != 'pages' || $CI->router->fetch_method() != 'verify_license')) {
                redirect('pages/verify_license');
                exit;
            }
        }
    }
    
    // Set theme configuration (only if not already defined)
    if (!defined('KEYDERA_THEME')) {
        $theme_setting = $CI->user_model->get_config_from_db('keydera_theme');
        if (!empty($theme_setting)) {
            define('KEYDERA_THEME', strtolower(strip_tags(trim((string)$theme_setting))));
        } else {
            define('KEYDERA_THEME', 'material');
        }
    }
    
    // Set timezone
    $timezone_setting = $CI->user_model->get_config_from_db('server_timezone');
    if (!empty($timezone_setting)) {
        date_default_timezone_set(trim(strip_tags($timezone_setting)));
        
        // Set MySQL timezone
        $datetime = new DateTime();
        $offset_seconds = $datetime->getOffset() / 60;
        $sign = $offset_seconds < 0 ? -1 : 1;
        $offset_seconds = abs($offset_seconds);
        $hours = floor($offset_seconds / 60);
        $offset_seconds -= $hours * 60;
        $timezone_string = sprintf('%+d:%02d', $hours * $sign, $offset_seconds);
        $CI->db->simple_query("SET time_zone='{$timezone_string}';");
    }
}

function force_ssl()
{
    $CI = &get_instance();
    $controller_class = $CI->router->fetch_class();
    $skip_controllers = array('api_external', 'api_internal', 'cron');
    
    if ($CI->config->config['force_ssl']) {
        if (!in_array($controller_class, $skip_controllers)) {
            $server_name = $_SERVER['SERVER_NAME'];
            $request_uri = $_SERVER['REQUEST_URI'];
            $CI->config->config['base_url'] = str_replace('http://', 'https://', $CI->config->config['base_url']);
            
            if (!is_https()) {
                redirect("https://{$server_name}{$request_uri}");
                exit;
            }
        }
    }
}
