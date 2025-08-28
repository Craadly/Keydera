<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Disable error reporting in production
@ini_set('display_errors', 0);

// Set memory and execution limits
if (!(@ini_get('max_execution_time') !== '0' && @ini_get('max_execution_time') < 600)) {
    @ini_set('max_execution_time', 600);
}
@ini_set('memory_limit', '256M');

// Define constants for licensing system
define('atSEXsTF', true);
define('lbI3Kt1y', true);

// Helper functions for CodeIgniter
if (!function_exists('config_item')) {
    function config_item($item) {
        static $config;
        if (empty($config)) {
            // Check if get_config function exists before calling it
            if (function_exists('get_config')) {
                $config[0] = &get_config();
            } else {
                // Fallback: return commonly used config values
                $config[0] = array(
                    'charset' => 'UTF-8',
                    'base_url' => '',
                    'index_page' => 'index.php',
                    'language' => 'english'
                );
            }
        }
        return isset($config[0][$item]) ? $config[0][$item] : null;
    }
}

if (!function_exists('html_escape')) {
    function html_escape($var, $double_encode = true) {
        if (empty($var)) {
            return $var;
        }
        if (is_array($var)) {
            foreach (array_keys($var) as $key) {
                $var[$key] = html_escape($var[$key], $double_encode);
            }
            return $var;
        }
        return htmlspecialchars($var, ENT_QUOTES, config_item('charset'), $double_encode);
    }
}

if (!function_exists('thousands_currency_format')) {
    function thousands_currency_format($number, $return_array = false) {
        if ($number <= 1000) {
            $result = array($number, '');
            return !empty($return_array) ? $result : $number;
        }
        
        $rounded = round($number);
        $formatted = number_format($rounded);
        $parts = explode(',', $formatted);
        $suffixes = array('k', 'm', 'b', 't');
        $count = count($parts) - 1;
        $value = $rounded;
        $value = $parts[0] . ((int)$parts[1][0] !== 0 ? '.' . $parts[1][0] : '');
        $suffix = $suffixes[$count - 1];
        $result = array($value, $suffix);
        return !empty($return_array) ? $result : $value . $suffix;
    }
}

if (!function_exists('generate_breadcrumb')) {
    function generate_breadcrumb($custom = null) {
        $CI = &get_instance();
        $index = 1;
        $uri_segment = $CI->uri->segment($index);
<<<<<<< HEAD

        // Start new breadcrumb markup
        $breadcrumb = '<nav class="breadcrumb">';
        $breadcrumb .= '<a href="' . base_url() . '" class="breadcrumb-item"><i class="fas fa-home"></i> Keydera</a>';

        $built = '';
        $segments = array();
        while ($uri_segment != '') {
            $segments[] = $uri_segment;
            $index++;
            $uri_segment = $CI->uri->segment($index);
        }

        $total = count($segments);
        foreach ($segments as $i => $seg) {
            $built .= $seg . '/';
            $title = ($custom && $i === $total - 1) ? ucfirst($custom) : ucfirst($seg);
            $breadcrumb .= '<span class="breadcrumb-separator">›</span>';
            if ($i === $total - 1) {
                $breadcrumb .= '<span class="breadcrumb-current">' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</span>';
            } else {
                $breadcrumb .= '<a href="' . site_url($built) . '" class="breadcrumb-item">' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</a>';
            }
        }

        $breadcrumb .= '</nav>';
=======
        $breadcrumb = '<nav class="breadcrumb" aria-label="breadcrumbs">
            <ul><li><a href="' . base_url() . '">Home</a></li>';
        
        while ($uri_segment != '') {
            $breadcrumb_uri = '';
            $count = 1;
            while ($count <= $index) {
                $breadcrumb_uri .= $CI->uri->segment($count) . '/';
                $count++;
            }
            
            if ($CI->uri->segment($index + 1) == '') {
                if ($custom) {
                    $breadcrumb .= '<li class="is-active"><a href="' . site_url($breadcrumb_uri) . '">';
                    $breadcrumb .= ucfirst($custom) . '</a></li>';
                } else {
                    $breadcrumb .= '<li class="is-active"><a href="' . site_url($breadcrumb_uri) . '">';
                    $breadcrumb .= ucfirst($CI->uri->segment($index)) . '</a></li>';
                }
            } else {
                $breadcrumb .= '<li><a href="' . site_url($breadcrumb_uri) . '">';
                $breadcrumb .= ucfirst($CI->uri->segment($index)) . '</a><span class="divider"></span></li>';
            }
            
            $index++;
            $uri_segment = $CI->uri->segment($index);
        }
        
        $breadcrumb .= '</ul></nav>';
>>>>>>> origin/main
        return $breadcrumb;
    }
}

if (!function_exists('get_system_info')) {
    function get_system_info($core_init) {
        $system_info = array(
            'Server' => $_SERVER['SERVER_SOFTWARE'],
            'PHP Version' => phpversion(),
            'Max POST Size' => @ini_get('post_max_size'),
            'Max Memory Limit' => @ini_get('memory_limit'),
            'Max Upload Size' => @ini_get('upload_max_filesize'),
            'Curl Version' => function_exists('curl_version') ? curl_version()['version'] : 'Nil',
            'Core Init' => $core_init
        );
        return json_encode($system_info, JSON_PRETTY_PRINT);
    }
}

if (!function_exists('minify_html')) {
    function minify_html($buffer) {
        $search = array(
            '/(\n|^)(\x20+|\t)/',
            '/(\n|^)\/\/(.*?)(\n|$)/',
            '/\n/',
            '/\<\!--.*?--\>/',
            '/(\x20+|\t)/',
            '/\>\s+\</',
            '/(\"|\')\s+\>/',
            '/=\s+(\"|\')/'
        );
        $replace = array("\n", "\n", ' ', '', ' ', '><', '$1>', '=$1');
        $buffer = preg_replace($search, $replace, $buffer);
        return $buffer;
    }
}

if (!function_exists('password_verify')) {
    function password_verify($password, $hash) {
        if (strlen($hash) !== 60 || strlen($password = crypt($password, $hash)) !== 60) {
            return false;
        }
        $status = 0;
        $i = 0;
        while ($i < 60) {
            $status |= ord($password[$i]) ^ ord($hash[$i]);
            $i++;
        }
        return $status === 0;
    }
}