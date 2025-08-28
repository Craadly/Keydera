<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'pages/view';
$route['404_override'] = 'pages/render_404';
$route['translate_uri_dashes'] = false;

$route['api/check_connection_int'] = 'api_internal/check_connection_int';
$route['api/add_product'] = 'api_internal/add_product';
$route['api/get_product'] = 'api_internal/get_product';
$route['api/get_products'] = 'api_internal/get_products';
$route['api/mark_product_active'] = 'api_internal/mark_product_active';
$route['api/mark_product_inactive'] = 'api_internal/mark_product_inactive';
$route['api/create_license'] = 'api_internal/create_license';
$route['api/edit_license'] = 'api_internal/edit_license';
$route['api/get_license'] = 'api_internal/get_license';
$route['api/search_license'] = 'api_internal/search_license';
$route['api/delete_license'] = 'api_internal/delete_license';
$route['api/block_license'] = 'api_internal/block_license';
$route['api/unblock_license'] = 'api_internal/unblock_license';
$route['api/deactivate_license_activations'] = 'api_internal/deactivate_license_activations';

$route['api/check_connection_ext'] = 'api_external/check_connection_ext';
$route['api/latest_version'] = 'api_external/latest_version';
$route['api/check_update'] = 'api_external/check_update';
$route['api/download_update/(:any)/(:any)'] = 'api_external/download_update/type/$1/vid/$2';
$route['api/get_update_size/(:any)/(:any)'] = 'api_external/get_update_size/type/$1/vid/$2';
$route['api/activate_license'] = 'api_external/activate_license';
$route['api/verify_license'] = 'api_external/verify_license';
$route['api/deactivate_license'] = 'api_external/deactivate_license';
$route['api/download_update'] = 'api_external/download_update';
$route['api/get_update_size'] = 'api_external/get_update_size';
$route['api/(:any)/(:any)/(:any)/(:any)'] = 'api_external/error';
$route['api/(:any)/(:any)/(:any)'] = 'api_external/error';
$route['api/(:any)/(:any)'] = 'api_external/error';
$route['api/(:any)'] = 'api_external/error';
$route['api'] = 'api_external/error';

$route['licenses/create'] = 'licenses/create';
$route['licenses/get_licenses'] = 'licenses/get_licenses';
$route['licenses/email_license'] = 'licenses/email_license';
$route['licenses/edit'] = 'licenses/edit';
$route['licenses/delete'] = 'licenses/delete';
$route['licenses/delete_selected'] = 'licenses/delete_selected';
$route['licenses/block'] = 'licenses/block';
$route['licenses/unblock'] = 'licenses/unblock';
$route['licenses/(:any)'] = 'licenses/view/$1';
$route['licenses'] = 'licenses/index';

$route['generate_external'] = 'generate_helpers/external_helper';
$route['generate_internal'] = 'generate_helpers/internal_helper';

$route['products/add'] = 'products/add';
$route['products/edit'] = 'products/edit';
$route['products/delete'] = 'products/delete';
$route['products/versions/add'] = 'products/add_version';
$route['products/versions/edit'] = 'products/edit_version';
$route['products/versions/delete'] = 'products/delete_version';
$route['products/versions/unpublish'] = 'products/unpublish_version';
$route['products/versions/publish'] = 'products/publish_version';
$route['products/versions/download_files'] = 'products/download_version_files';
$route['products/versions/download_sql'] = 'products/download_version_sql';
$route['products/versions'] = 'products/view_versions';
$route['products/(:any)'] = 'products/view/$1';
$route['products'] = 'products/index';

$route['activations/get_activations'] = 'activations/get_activations';
$route['activations/delete'] = 'activations/delete';
$route['activations/delete_selected'] = 'activations/delete_selected';
$route['activations/activate'] = 'activations/activate';
$route['activations/deactivate'] = 'activations/deactivate';
$route['activations/(:any)'] = 'activations/view/$1';
$route['activations'] = 'activations/index';

$route['update_downloads/get_update_downloads'] = 'downloads/get_update_downloads';
$route['update_downloads/delete'] = 'downloads/delete';
$route['update_downloads/delete_selected'] = 'downloads/delete_selected';
$route['update_downloads/(:any)'] = 'downloads/view/$1';
$route['update_downloads'] = 'downloads/index';

$route['users/get_activities'] = 'users/get_activities';
$route['users/delete_activity'] = 'users/delete_activity';
$route['users/delete_selected_activity'] = 'users/delete_selected_activity';
$route['activities'] = 'users/activities';
$route['login'] = 'users/login';
$route['logout'] = 'users/logout';
$route['forgot_password'] = 'users/forgot_password';
$route['reset_password/(:any)/(:any)'] = 'users/reset_password/$1/$2';
$route['reset_password'] = 'users/reset_password';

$route['api_settings'] = 'settings/api_settings';
$route['email_settings'] = 'settings/email_settings';
$route['send_test_email'] = 'settings/send_test_email';
$route['account_settings'] = 'settings/account_settings';
$route['general_settings'] = 'settings/general_settings';
$route['settings/delete_api_key'] = 'settings/delete_api_key';

$route['run_cron'] = 'cron/run';

$route['dashboard'] = 'pages/dashboard';
$route['updates'] = 'pages/check_updates';
$route['php_obfuscator'] = 'pages/php_obfuscator';
$route['verify_license'] = 'pages/verify_license';
<<<<<<< HEAD
$route['contact_support'] = 'pages/contact_support';
=======
>>>>>>> origin/main

$route['(:any)'] = 'pages/view/$1';
