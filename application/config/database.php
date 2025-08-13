<?php defined('BASEPATH') or exit('No direct script access allowed');

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

$active_group = 'default';
$query_builder = true;

// DB_HOST : MySQL hostname
// DB_NAME : The name of the database for LicenseBox
// DB_USER : MySQL database username
// DB_PASS : MySQL database password
$db['default'] = array(
    'dsn' => 'mysql:host=localhost;dbname=licensebox',
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'licensebox',
    'dbdriver' => 'pdo',
    'dbprefix' => '',
    'pconnect' => false,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => false,
    'cachedir' => '',
    'char_set' => 'utf8mb4',
    'dbcollat' => 'utf8mb4_unicode_ci',
    'swap_pre' => '',
    'encrypt' => false,
    'compress' => false,
    'stricton' => false,
    'failover' => array(),
    'save_queries' => true,
);

//$db['default']['port'] = 3306;

/* Cheers! */
