<?php
/**
 * Keydera
 *
 * Keydera is a full-fledged licenser and updates manager.
 *
 * @package Keydera
 * @author Craadly
 * @see https://keydera.app
 * @copyright Copyright (c) 2025, Keydera. (https://www.keydera.app)
 * @version 1.0.0
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('envato_api_connection_test')) {
    function envato_api_connection_test($api_key)
    {
        $ch = curl_init();
        $agents = array(
            "Mozilla/5.0 (Linux; Android 8.0.0; SM-G960F Build/R16NW) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.84 Mobile Safari/537.36",
            "Mozilla/5.0 (Linux; Android 6.0.1; SM-G935S Build/MMB29K; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/55.0.2883.91 Mobile Safari/537.36",
            "Mozilla/5.0 (iPhone; CPU iPhone OS 12_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) FxiOS/13.2b11866 Mobile/16A366 Safari/605.1.15",
            "Mozilla/5.0 (iPhone9,3; U; CPU iPhone OS 10_0_1 like Mac OS X) AppleWebKit/602.1.50 (KHTML, like Gecko) Version/10.0 Mobile/14A403 Safari/602.1",
        );
        $agent = $agents[array_rand($agents)];
        curl_setopt_array($ch, array(
            CURLOPT_URL => "https://api.envato.com/v1/market/total-items.json",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $api_key . "",
                "User-Agent: " . $agent . "",
            ),
        ));
        $output = json_decode(curl_exec($ch), true);
        curl_close($ch);
        return $output;
    }
}

if (!function_exists('verify_envato_purchase_code')) {
    function verify_envato_purchase_code($code_to_verify, $api_key)
    {
        $ch = curl_init();
        $agents = array(
            "Mozilla/5.0 (Linux; Android 8.0.0; SM-G960F Build/R16NW) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.84 Mobile Safari/537.36",
            "Mozilla/5.0 (Linux; Android 6.0.1; SM-G935S Build/MMB29K; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/55.0.2883.91 Mobile Safari/537.36",
            "Mozilla/5.0 (iPhone; CPU iPhone OS 12_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) FxiOS/13.2b11866 Mobile/16A366 Safari/605.1.15",
            "Mozilla/5.0 (iPhone9,3; U; CPU iPhone OS 10_0_1 like Mac OS X) AppleWebKit/602.1.50 (KHTML, like Gecko) Version/10.0 Mobile/14A403 Safari/602.1",
        );
        $agent = $agents[array_rand($agents)];
        curl_setopt_array($ch, array(
            CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code=" . $code_to_verify . "",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $api_key . "",
                "User-Agent: " . $agent . "",
            ),
        ));
        $output = json_decode(curl_exec($ch), true);
        curl_close($ch);
        return $output;
    }
}

