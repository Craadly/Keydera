<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Include the db.php configuration
if (file_exists(APPPATH . 'config/db.php')) {
    require_once(APPPATH . 'config/db.php');
}

$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
    'dsn'	=> '',
    'hostname' => isset($db_host) ? $db_host : 'localhost',
    'username' => isset($db_username) ? $db_username : 'root',
    'password' => isset($db_password) ? $db_password : '',
    'database' => isset($db_database) ? $db_database : 'keydera_db',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);
