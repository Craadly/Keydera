<?php
/**
 * LicenseBox
 *
 * LicenseBox is a full-fledged licenser and updates manager.
 *
 * @package LicenseBox
 * @author CodeMonks
 * @see https://licensebox.app
 * @link https://codecanyon.net/item/licensebox-php-license-and-updates-manager/22351237
 * @license https://codecanyon.net/licenses/standard (Regular or Extended License)
 * @copyright Copyright (c) 2023, CodeMonks. (https://www.licensebox.app)
 * @version 1.6.4
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('crypto_rand_secure')) {
    function crypto_rand_secure($min, $max)
    {
        $range = $max - $min;
        if ($range < 1) {
            return $min;
        }

        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1;
        $bits = (int) $log + 1;
        $filter = (int) (1 << $bits) - 1;
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd&$filter;
        } while ($rnd > $range);
        return $min + $rnd;
    }
}

if (!function_exists('gen_token')) {
    function gen_token($length, $alphabets = false)
    {
        $token = "";
        if ($alphabets) {
            $codeAlphabet = "abcdefghijklmnopqrstuvwxyz";
        } else {
            $codeAlphabet = "0123456789";
        }
        $max = strlen($codeAlphabet);
        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[crypto_rand_secure(0, $max - 1)];
        }
        return $token;
    }
}

if (!function_exists('gen_code')) {
    function gen_code($format)
    {
        $times = substr_count($format, "{[X]}");
        for ($i = 0; $i <= $times; $i++) {
            $format = str_replace_first("{[X]}", gen_token(1), $format);
        }
        $times = 0;
        $times = substr_count($format, "{[Y]}");
        for ($i = 0; $i <= $times; $i++) {
            $format = str_replace_first("{[Y]}", gen_token(1, true), $format);
        }
        $times = 0;
        $times = substr_count($format, "{[Z]}");
        for ($i = 0; $i <= $times; $i++) {
            $format = str_replace_first("{[Z]}", substr(MD5(microtime()), 0, 1), $format);
        }
        $ff = strtoupper($format);
        return $ff;
    }
}
