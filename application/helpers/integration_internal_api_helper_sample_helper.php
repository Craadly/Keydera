<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Keydera Internal API Helper Sample
 *
 * This is a sample helper file for integrating with Keydera's internal API
 * Template file - placeholders will be replaced during generation
 *
 * @package Keydera
 * @author Craadly
 * @version 1.0.0
 */

// Configuration
define('KEYDERA_INTERNAL_API_URL', '{[URL]}api/internal/v1/');
define('KEYDERA_INTERNAL_API_KEY', '{[KEY]}');
define('KEYDERA_API_LANGUAGE', '{[LANG]}');

// Error messages
define('MSG_INTERNAL_CONNECTION_FAILED', '{[TEXT_CONNECTION_FAILED]}');
define('MSG_INTERNAL_INVALID_RESPONSE', '{[TEXT_INVALID_RESPONSE]}');

/**
 * Get license information
 */
function keydera_internal_get_license($license_id) {
    $data = array(
        'license_id' => $license_id,
        'language' => KEYDERA_API_LANGUAGE
    );
    
    $response = keydera_internal_api_request('licenses/get', $data);
    
    if ($response && isset($response['status']) && $response['status'] === 'success') {
        return array(
            'status' => 'success',
            'data' => $response['data']
        );
    }
    
    return array(
        'status' => 'error',
        'message' => isset($response['message']) ? $response['message'] : MSG_INTERNAL_INVALID_RESPONSE
    );
}

/**
 * Create new license
 */
function keydera_internal_create_license($data) {
    $response = keydera_internal_api_request('licenses/create', $data);
    
    if ($response && isset($response['status'])) {
        return $response;
    }
    
    return array('status' => 'error', 'message' => MSG_INTERNAL_CONNECTION_FAILED);
}

/**
 * Update license information
 */
function keydera_internal_update_license($license_id, $data) {
    $data['license_id'] = $license_id;
    
    $response = keydera_internal_api_request('licenses/update', $data);
    
    if ($response && isset($response['status'])) {
        return $response;
    }
    
    return array('status' => 'error', 'message' => MSG_INTERNAL_CONNECTION_FAILED);
}

/**
 * Get product information
 */
function keydera_internal_get_products() {
    $data = array(
        'language' => KEYDERA_API_LANGUAGE
    );
    
    $response = keydera_internal_api_request('products/list', $data);
    
    if ($response && isset($response['status']) && $response['status'] === 'success') {
        return array(
            'status' => 'success',
            'data' => $response['data']
        );
    }
    
    return array(
        'status' => 'error',
        'message' => isset($response['message']) ? $response['message'] : MSG_INTERNAL_INVALID_RESPONSE
    );
}

/**
 * Make API request to Keydera Internal API
 */
function keydera_internal_api_request($endpoint, $data = array()) {
    $url = KEYDERA_INTERNAL_API_URL . $endpoint;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-API-Key: ' . KEYDERA_INTERNAL_API_KEY,
        'Content-Type: application/x-www-form-urlencoded',
        'User-Agent: Keydera-Internal-Client/1.0'
    ));
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);
    
    if ($curl_error) {
        return array('status' => 'error', 'message' => MSG_INTERNAL_CONNECTION_FAILED);
    }
    
    if ($http_code === 200 && $response) {
        $decoded = json_decode($response, true);
        return $decoded ? $decoded : array('status' => 'error', 'message' => MSG_INTERNAL_INVALID_RESPONSE);
    }
    
    return array('status' => 'error', 'message' => MSG_INTERNAL_CONNECTION_FAILED);
}
