<?php
defined('BASEPATH') or exit('No direct script access allowed');

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

$config['base_url'] = 'http://localhost/lb/licensebox';
$config['index_page'] = '';
$config['uri_protocol'] = 'REQUEST_URI';
$config['url_suffix'] = '';
$config['language'] = 'english';
$config['charset'] = 'UTF-8';
$config['enable_hooks'] = true;
$config['subclass_prefix'] = 'MY_';
$config['composer_autoload'] = false;
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';
$config['enable_query_strings'] = false;
$config['controller_trigger'] = 'c';
$config['function_trigger'] = 'm';
$config['directory_trigger'] = 'd';
$config['allow_get_array'] = true;
$config['log_threshold'] = 1;
$config['log_path'] = '';
$config['log_file_extension'] = 'log';
$config['log_file_permissions'] = 0644;
$config['log_date_format'] = 'Y-m-d H:i:s';
$config['error_views_path'] = '';
$config['cache_path'] = '';
$config['cache_query_string'] = false;
$config['encryption_key'] = 'iUOEH38OWxCjBUCNp6lnexZJX'; // Heads-up! changing this will invalidate all users installed licenses and tokens.
$config['sess_driver'] = 'files';
$config['sess_cookie_name'] = 'lb_mbkujo9UvR_session';
$config['sess_expiration'] = 7200;
$config['sess_save_path'] = (is_writable(session_save_path())) ? session_save_path() : sys_get_temp_dir();
$config['sess_match_ip'] = false;
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = false;
$config['cookie_prefix'] = '';
$config['cookie_domain'] = '';
$config['cookie_path'] = '/';
$config['cookie_secure'] = false;
$config['cookie_httponly'] = false;
$config['standardize_newlines'] = false;
$config['global_xss_filtering'] = false;
$config['csrf_protection'] = true;
$config['csrf_token_name'] = 'csrf_lbs_vDKhm8aGXa_token';
$config['csrf_cookie_name'] = 'csrf_lbs_8CzIo8NatI_cookie';
$config['csrf_expire'] = 7200;
$config['csrf_regenerate'] = false;
$config['csrf_exclude_uris'] = array("api.*+");
$config['compress_output'] = false;
$config['time_reference'] = 'local';
$config['rewrite_short_tags'] = false;
$config['proxy_ips'] = '';

//Date and Datetime format :
//Learn more : http://php.net/manual/en/function.date.php
$config['datetime_format'] = "j F, Y, g:i a";
$config['datetime_format_table'] = "j M, Y, g:i a";
$config['date_format'] = "j F, Y";

/* Cheers! */
