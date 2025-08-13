<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Keydera
 *
 * Keydera is a full-fledged licenser and updates manager.
 *
 * @package Keydera
 * @author Craadly
 * @see https://craadly.com
 * @copyright Copyright (c) 2025, Craadly. (https://www.craadly.com)
 * @version 1.0.0
 */

$active_group = 'default';
$query_builder = true;

// DB_HOST : MySQL hostname
// DB_NAME : The name of the database for Keydera
// DB_USER : MySQL database username
// DB_PASS : MySQL database password
$db['default'] = array(
    'dsn' => 'mysql:host=localhost;dbname=keydera',
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'keydera',
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

