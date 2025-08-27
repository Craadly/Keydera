<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Keydera External API Helper Sample (Regular)
 *
 * This is a sample helper file for integrating with Keydera's external API
 * Template file - placeholders will be replaced during generation
 *
 * @package Keydera
 * @author Craadly
 * @version 1.0.0
 */

// Configuration
define('KEYDERA_API_URL', '{[URL]}api/external/v1/');
define('KEYDERA_API_KEY', '{[KEY]}');
define('KEYDERA_PRODUCT_ID', '{[PID]}');
define('KEYDERA_CURRENT_VERSION', '{[CUR]}');
define('KEYDERA_LANGUAGE', '{[LANG]}');
define('KEYDERA_ENVATO', '{[ENV]}');
define('KEYDERA_CHECK_PERIOD', {[CHECK]});

// Error messages
define('MSG_CONNECTION_FAILED', '{[TEXT_CONNECTION_FAILED]}');
define('MSG_INVALID_RESPONSE', '{[TEXT_INVALID_RESPONSE]}');
define('MSG_VERIFIED_RESPONSE', '{[TEXT_VERIFIED_RESPONSE]}');
define('MSG_UPDATE_PERIOD_EXPIRED', '{[TEXT_UPDATE_PERIOD_EXPIRED]}');

/**
 * Verify license with Keydera API
 */
function keydera_verify_license($license_key, $domain = null) {
    if (!$domain) {
        $domain = $_SERVER['HTTP_HOST'];
    }
    
    $data = array(
        'license_key' => $license_key,
        'product_id' => KEYDERA_PRODUCT_ID,
        'domain' => $domain,
        'version' => KEYDERA_CURRENT_VERSION,
        'language' => KEYDERA_LANGUAGE,
        'envato' => KEYDERA_ENVATO
    );
    
    $response = keydera_api_request('verify', $data);
    
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

/**
 * Check for updates
 */
function keydera_check_updates($license_key) {
    $data = array(
        'license_key' => $license_key,
        'product_id' => KEYDERA_PRODUCT_ID,
        'current_version' => KEYDERA_CURRENT_VERSION
    );
    
    $response = keydera_api_request('updates/check', $data);
    
    if ($response && isset($response['status'])) {
        return $response;
    }
    
    return array('status' => 'error', 'message' => MSG_CONNECTION_FAILED);
}

/**
 * Make API request to Keydera
 */
function keydera_api_request($endpoint, $data = array()) {
    $url = KEYDERA_API_URL . $endpoint;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-API-Key: ' . KEYDERA_API_KEY,
        'Content-Type: application/x-www-form-urlencoded',
        'User-Agent: Keydera-Client/1.0'
    ));
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);
    
    if ($curl_error) {
        return array('status' => 'error', 'message' => MSG_CONNECTION_FAILED);
    }
    
    if ($http_code === 200 && $response) {
        $decoded = json_decode($response, true);
        return $decoded ? $decoded : array('status' => 'error', 'message' => MSG_INVALID_RESPONSE);
    }
    
    return array('status' => 'error', 'message' => MSG_CONNECTION_FAILED);
}
