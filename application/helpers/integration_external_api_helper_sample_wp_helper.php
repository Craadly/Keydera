<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Keydera External API Helper Sample for WordPress
 *
 * This is a WordPress-specific sample helper file for integrating with Keydera's external API
 * Uses WordPress native functions for HTTP requests and file operations
 *
 * @package Keydera
 * @author Craadly
 * @version 1.0.0
 */

// Configuration
if (!defined('KEYDERA_API_URL')) define('KEYDERA_API_URL', '{[URL]}api/external/v1/');
if (!defined('KEYDERA_API_KEY')) define('KEYDERA_API_KEY', '{[KEY]}');
if (!defined('KEYDERA_PRODUCT_ID')) define('KEYDERA_PRODUCT_ID', '{[PID]}');
if (!defined('KEYDERA_CURRENT_VERSION')) define('KEYDERA_CURRENT_VERSION', '{[CUR]}');
if (!defined('KEYDERA_LANGUAGE')) define('KEYDERA_LANGUAGE', '{[LANG]}');
if (!defined('KEYDERA_ENVATO')) define('KEYDERA_ENVATO', '{[ENV]}');
if (!defined('KEYDERA_CHECK_PERIOD')) define('KEYDERA_CHECK_PERIOD', '{[CHECK]}');

// Error messages
if (!defined('MSG_CONNECTION_FAILED')) define('MSG_CONNECTION_FAILED', '{[TEXT_CONNECTION_FAILED]}');
if (!defined('MSG_INVALID_RESPONSE')) define('MSG_INVALID_RESPONSE', '{[TEXT_INVALID_RESPONSE]}');
if (!defined('MSG_VERIFIED_RESPONSE')) define('MSG_VERIFIED_RESPONSE', '{[TEXT_VERIFIED_RESPONSE]}');
if (!defined('MSG_UPDATE_PERIOD_EXPIRED')) define('MSG_UPDATE_PERIOD_EXPIRED', '{[TEXT_UPDATE_PERIOD_EXPIRED]}');

/**
 * Verify license with Keydera API (WordPress version)
 */
if (!function_exists('keydera_wp_verify_license')) {
    function keydera_wp_verify_license($license_key, $domain = null) {
        if (!$domain) {
            $domain = parse_url(home_url(), PHP_URL_HOST);
        }
        
        $data = array(
            'license_key' => $license_key,
            'product_id' => KEYDERA_PRODUCT_ID,
            'domain' => $domain,
            'version' => KEYDERA_CURRENT_VERSION,
            'language' => KEYDERA_LANGUAGE,
            'envato' => KEYDERA_ENVATO
        );
        
        $response = keydera_wp_api_request('verify', $data);
        
        if ($response && isset($response['status']) && $response['status'] === 'success') {
            return array(
                'status' => 'verified',
                'message' => MSG_VERIFIED_RESPONSE,
                'data' => $response
            );
        }
        
        return array(
            'status' => 'error',
            'message' => isset($response['message']) ? $response['message'] : MSG_INVALID_RESPONSE
        );
    }
}

/**
 * Check for updates (WordPress version)
 */
if (!function_exists('keydera_wp_check_updates')) {
    function keydera_wp_check_updates($license_key) {
        $data = array(
            'license_key' => $license_key,
            'product_id' => KEYDERA_PRODUCT_ID,
            'current_version' => KEYDERA_CURRENT_VERSION
        );
        
        $response = keydera_wp_api_request('updates/check', $data);
        
        if ($response && isset($response['status'])) {
            return $response;
        }
        
        return array('status' => 'error', 'message' => MSG_CONNECTION_FAILED);
    }
}

/**
 * Make API request to Keydera using WordPress HTTP API
 */
if (!function_exists('keydera_wp_api_request')) {
    function keydera_wp_api_request($endpoint, $data = array()) {
        $url = KEYDERA_API_URL . $endpoint;
        
        $args = array(
            'body' => $data,
            'timeout' => 30,
            'headers' => array(
                'X-API-Key' => KEYDERA_API_KEY,
                'Content-Type' => 'application/x-www-form-urlencoded',
                'User-Agent' => 'Keydera-WordPress-Client/1.0'
            )
        );
        
        $response = wp_remote_post($url, $args);
        
        if (is_wp_error($response)) {
            return array('status' => 'error', 'message' => MSG_CONNECTION_FAILED);
        }
        
        $body = wp_remote_retrieve_body($response);
        $code = wp_remote_retrieve_response_code($response);
        
        if ($code === 200 && $body) {
            $decoded = json_decode($body, true);
            return $decoded ? $decoded : array('status' => 'error', 'message' => MSG_INVALID_RESPONSE);
        }
        
        return array('status' => 'error', 'message' => MSG_CONNECTION_FAILED);
    }
}

/**
 * WordPress specific license validation with transient caching
 */
if (!function_exists('keydera_wp_validate_license_cached')) {
    function keydera_wp_validate_license_cached($license_key, $force_check = false) {
        $cache_key = 'keydera_license_' . md5($license_key . KEYDERA_PRODUCT_ID);
        
        if (!$force_check) {
            $cached = get_transient($cache_key);
            if ($cached !== false) {
                return $cached;
            }
        }
        
        $result = keydera_wp_verify_license($license_key);
        
        // Cache successful verifications for the check period
        if ($result['status'] === 'verified') {
            $cache_duration = KEYDERA_CHECK_PERIOD * DAY_IN_SECONDS;
            set_transient($cache_key, $result, $cache_duration);
        }
        
        return $result;
    }
}
