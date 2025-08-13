<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
$query_builder = TRUE;

// DB_HOST : MySQL hostname
// DB_PORT : MySQL port number
// DB_NAME : The name of the database for LicenseBox
// DB_USER : MySQL database username
// DB_PASS : MySQL database password
$db['default'] = array(
	'dsn'	=> 'mysql:host={[DB_HOST]};dbname={[DB_NAME]}{[DB_PORT]}',
	'hostname' => '{[DB_HOST_PORT]}',
	'username' => '{[DB_USER]}',
	'password' => '{[DB_PASS]}',
	'database' => '{[DB_NAME]}',
	'dbdriver' => 'pdo',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8mb4',
	'dbcollat' => 'utf8mb4_unicode_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

{[DB_DEFAULT_PORT]}

/* Cheers! */