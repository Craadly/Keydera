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

if (!function_exists('clr')) {
    function clr($string)
    {
        $string = strtolower($string);
        $string = preg_replace("/[\s-]+/", " ", $string);
        $string = preg_replace("/[\s_]/", "-", $string);
        $string = str_replace(".", "_", $string);
        return $string;
    }
}

if (!function_exists('str_has_arrayitem')) {
    function str_has_arrayitem($str, array $arr)
    {
        foreach ($arr as $a) {
            if (stripos($str, $a) !== false) {
                return stripos($str, $a);
            }

        }
        return false;
    }
}

if (!function_exists('validate_ips')) {
    function validate_ips($input)
    {
        foreach (explode(',', $input) as $ip) {
            $wildcard_pos = str_has_arrayitem($ip, array(".*", "*.", ":*", "*:"));
            $wildcard_count = substr_count($ip, "*");
            if ($wildcard_pos !== false && $wildcard_count === 1) {
                continue;
            }
            if (!filter_var($ip, FILTER_VALIDATE_IP)) {
                return false;
            }
        }

        return true;
    }
}

if (!function_exists('validate_domains')) {
    function validate_domains($input)
    {
        foreach (explode(',', $input) as $domain) {
            if ($domain != 'localhost') {
                if (!filter_var('lb@' . $domain, FILTER_VALIDATE_EMAIL)) {
                    return false;
                }

            }
        }

        return true;
    }
}

if (!function_exists('return_s')) {
    function return_s($no)
    {
        if ($no != 1) {
            return "s";
        }
    }
}

if (!function_exists('str_replace_first')) {
    function str_replace_first($from, $to, $content)
    {
        $from = '/' . preg_quote($from, '/') . '/';
        return preg_replace($from, $to, $content, 1);
    }
}

if (!function_exists('str_return_s')) {
    function str_return_s($str, $no, $s, $typ)
    {
        if ($typ == 1) {
            if ($no > 1) {
                return number_format($no) . " " . $str . $s;
            } elseif ($no == 1) {
                return $no . " " . $str;
            } else {
                return "no " . $str;
            }
        } else {
            if ($no > 1) {
                return $str . $s;
            } elseif ($no == 1) {
                return $str;
            } else {
                return $str;
            }
        }
    }
}

if (!function_exists('str_truncate_middle')) {
    function str_truncate_middle($text, $maxChars = 25, $filler = '...')
    {
        $length = strlen($text);
        $fillerLength = strlen($filler);
        return ($length > $maxChars)
        ? substr_replace($text, $filler, ($maxChars - $fillerLength) / 2, $length - $maxChars + $fillerLength)
        : $text;
    }
}

if (!function_exists('remove_http_www')) {
    function remove_http_www($input)
    {
        $url = $input;
        $url = filter_var($url, FILTER_SANITIZE_URL);
        if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
            $input = trim($input, '/');
            if (!preg_match('#^http(s)?://#', $input)) {
                $input = 'http://' . $input;
            }
            $urlParts = parse_url($input);
            $domain = preg_replace('/^www\./', '', $urlParts['host']);
            if (!empty($urlParts['path'])) {
                $domain .= $urlParts['path'];
            }
            return $domain;
        } else {
            return $input;
        }
    }
}

if (!function_exists('remove_http_www_bulk')) {
    function remove_http_www_bulk($inputs)
    {
        $gd = array();
        foreach (explode(',', $inputs) as $doms) {
            $url = $doms;
            $url = filter_var($url, FILTER_SANITIZE_URL);
            if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
                $input = trim($doms, '/');
                if (!preg_match('#^http(s)?://#', $input)) {
                    $input = 'http://' . $input;
                }
                $urlParts = parse_url($input);
                $domain = preg_replace('/^www\./', '', $urlParts['host']);
                if (!empty($urlParts['path'])) {
                    $domain .= $urlParts['path'];
                }
                $gd[] = $domain;
            } else {
                $gd[] = $url;
            }
        }
        return implode(',', $gd);
    }
}

if (!function_exists('obfuscate_email')) {
    function obfuscate_email($email)
    {
        $em = explode("@", $email);
        $name = implode(array_slice($em, 0, count($em) - 1), '@');
        $len = floor(strlen($name) / 2);
        return substr($name, 0, $len) . str_repeat('*', $len) . "@" . end($em);
    }
}

if (!function_exists('clean_html_codes')) {
    function clean_html_codes($html)
    {
        $dom = new DOMDocument();
        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        $script = $dom->getElementsByTagName('script');
        $remove = [];
        foreach ($script as $item) {
            $remove[] = $item;
        }
        foreach ($remove as $item) {
            $item->parentNode->removeChild($item);
        }
        return preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $dom->saveHTML());
    }
}

if (!function_exists('validate_date')) {
    function validate_date($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}

if (!function_exists('get_file_upload_max_size')) {
    function get_file_upload_max_size()
    {
        static $max_size = -1;
        if ($max_size < 0) {
            $post_max_size = parse_size(@ini_get('post_max_size'));
            if ($post_max_size > 0) {
                $max_size = $post_max_size;
            }
            $upload_max = parse_size(@ini_get('upload_max_filesize'));
            if ($upload_max > 0 && $upload_max < $max_size) {
                $max_size = $upload_max;
            }
        }
        return $max_size;
    }
}

if (!function_exists('parse_size')) {
    function parse_size($size)
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
        $size = preg_replace('/[^0-9\.]/', '', $size);
        if ($unit) {
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        } else {
            return round($size);
        }
    }
}

if (!function_exists('convert_kb')) {
    function convert_kb($bytes)
    {
        if ($bytes > 0) {
            if ($bytes >= 1073741824) {
                $bytes = number_format($bytes / 1073741824, 2) . 'G';
            } elseif ($bytes >= 1048576) {
                $bytes = number_format($bytes / 1048576, 2) . 'M';
            } elseif ($bytes >= 1024) {
                $bytes = number_format($bytes / 1024, 2) . 'K';
            } else {
                $bytes = '1K';
            }
        }
        return $bytes;
    }
}

if (!function_exists('s_t')) {
    function s_t($data)
    {
        if (!empty($data)) {
            return strip_tags($data);
        }
        return $data;
    }
}

if (!function_exists('is_localhost')) {
    function is_localhost($ip, $whitelist = ['127.0.0.1', '::1'])
    {
        return in_array($ip, $whitelist);
    }
}

if (!function_exists('str_ends_with')) {
    function str_ends_with($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }
        return (substr($haystack, -$length) === $needle);
    }
}

if (!function_exists('is_ssl')) {
    function is_ssl()
    {
        if (isset($_SERVER['HTTPS'])) {
            if ('on' == strtolower($_SERVER['HTTPS'])) {
                return true;
            }

            if ('1' == $_SERVER['HTTPS']) {
                return true;
            }

        } elseif (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])) {
            return true;
        }
        return false;
    }
}

if (!function_exists('get_timezones')) {
    function get_timezones()
    {
        $zones_array = array();
        $timestamp = time();
        foreach (timezone_identifiers_list() as $key => $zone) {
            date_default_timezone_set($zone);
            $zones_array[$key]['zone'] = $zone;
            $zones_array[$key]['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);
        }
        return $zones_array;
    }
}

if (!function_exists('is_base64_encoded')) {
    function is_base64_encoded($data)
    {
        if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $data)) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('generate_form_validation_js')) {
    function generate_form_validation_js($form)
    {
        $raw_js = <<<'JSRAW'
<script>
  $(document).ready(function() {
    $("#{[form]}_form_submit").click(function (e) {
      $("#{[form]}_form").validate({
        errorClass: "is-danger",
        ignore: ".dropify, .form_ignore, :hidden",
        errorElement: "p",
        errorPlacement: function(error, element) {
        	if (element.is('select')) {
        		$(element).parent().addClass("has-error");
    			error.insertAfter($(element).parent());
    		}else{
    			error.insertAfter($(element));
    		}
  	  	}
      });
      if($("#{[form]}_form").valid()){
        $("#{[form]}_form_submit").addClass("is-loading");
      }
    });
  });
</script>
JSRAW;
        $trans = array("{[form]}" => $form);
        return strtr($raw_js, $trans);
    }
}

if (!function_exists('get_available_languages')) {
    function get_available_languages()
    {
        $languages = array();
        $language_path = APPPATH . 'language';
        $blacklist = array('.', '..', 'index.php', 'index.html', '.htaccess');
        $files = scandir($language_path);
        foreach ($files as $filename) {
            if (!in_array($filename, $blacklist)) {
                if (is_file($language_path . '/' . $filename . '/' . $filename . '.po') && is_file($language_path . '/' . $filename . '/' . $filename . '.mo')) {
                    $languages[] = $filename;
                }
            }
        }
        return $languages;
    }
}

if (!function_exists('get_external_content')) {
    function get_external_content($url)
    {
        $ch = curl_init();
        $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "User-Agent: " . $agent . "",
        ));
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}

if (!function_exists('get_system_info')) {
    function get_system_info($license_status = false)
    {
        $CI = &get_instance();
        
        $info = array();
        $info[] = "PHP Version: " . PHP_VERSION;
        $info[] = "CodeIgniter Version: " . CI_VERSION;
        $info[] = "Server Software: " . $_SERVER['SERVER_SOFTWARE'];
        $info[] = "Database Type: " . $CI->db->platform();
        $info[] = "License Status: " . ($license_status ? 'Valid' : 'Invalid');
        $info[] = "Base URL: " . base_url();
        $info[] = "User Agent: " . $CI->input->user_agent();
        
        return implode(' | ', $info);
    }
}

if (!function_exists('generate_breadcrumb')) {
    function generate_breadcrumb($custom = null)
    {
        $CI = &get_instance();
        $segments = $CI->uri->segments;

        // Start new breadcrumb markup without aria-label and without list elements
        $breadcrumb = '<nav class="breadcrumb">';

        // Home item
        $breadcrumb .= '<a href="' . base_url() . '" class="breadcrumb-item"><i class="fas fa-home"></i> Keydera</a>';

        // Build path progressively
        $url = '';
        $total = count($segments);
        $index = 0;
        foreach ($segments as $segment) {
            $index++;
            $url .= $segment . '/';
            $title = ($custom && $index === $total) ? ucfirst($custom) : ucfirst(str_replace('_', ' ', $segment));

            $breadcrumb .= '<span class="breadcrumb-separator">›</span>';
            if ($index === $total) {
                $breadcrumb .= '<span class="breadcrumb-current">' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</span>';
            } else {
                $breadcrumb .= '<a href="' . site_url($url) . '" class="breadcrumb-item">' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</a>';
            }
        }

        $breadcrumb .= '</nav>';
        return $breadcrumb;
    }
}

if (!function_exists('thousands_currency_format')) {
    function thousands_currency_format($number, $return_array = false)
    {
        if (!is_numeric($number)) {
            return $return_array ? array($number, '') : $number;
        }
        
        if ($number < 1000) {
            return $return_array ? array($number, '') : $number;
        }
        
        $number = round($number);
        $units = array('k', 'm', 'b', 't');
        $thousands = 0;
        
        while ($number >= 1000 && $thousands < count($units)) {
            $number /= 1000;
            $thousands++;
        }
        
        $formatted = round($number, 1);
        $suffix = $units[$thousands - 1];
        
        return $return_array ? array($formatted, $suffix) : $formatted . $suffix;
    }
}

