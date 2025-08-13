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

require APPPATH . '/libraries/REST_Controller.php';

class Api_internal extends REST_Controller
{
    private $client_ip;
    private $client_url;
    private $client_agent;

    public function __construct()
    {
        parent::__construct();
        $_key = 'HTTP_' . strtoupper(str_replace('-', '_', 'LB-API-KEY'));
        $has_key = (!empty($this->input->server($_key))) ? strip_tags(trim($this->input->server($_key))) : 'unspecified';
        $_url = 'HTTP_' . strtoupper(str_replace('-', '_', 'LB-URL'));
        $has_url = (!empty($this->input->server($_url))) ? strip_tags(trim($this->input->server($_url))) : '';
        $_ip = 'HTTP_' . strtoupper(str_replace('-', '_', 'LB-IP'));
        $has_ip = (!empty($this->input->server($_ip))) ? strip_tags(trim($this->input->server($_ip))) : '';
        $_agent = 'HTTP_' . strtoupper(str_replace('-', '_', 'User-Agent'));
        $has_agent = (!empty($this->input->server($_agent))) ? strip_tags(trim($this->input->server($_agent))) : '';

        $has_domain = parse_url($has_url, PHP_URL_HOST);

        if (!empty($has_domain) && str_ends_with($has_domain, '.hwid') !== false) {
            $do_url_validation = false;
        } else {
            $do_url_validation = true;
        }

        $api_key_info = $this->user_model->get_api_key($has_key);

        $this->db->where('api_key', $has_key);
        $this->db->where('date', date('Y-m-d'));
        $query = $this->db->get('api_logs');
        if ($query->num_rows() > 0) {
            $api_log = $query->row_array();
            $new_count = $api_log['count'] + 1;
            $this->db->where('api_key', $has_key);
            $this->db->where('date', date('Y-m-d'));
            $this->db->update('api_logs', array(
                'count' => $new_count,
            ));
        } else {
            $new_count = 1;
            $data = array(
                'api_key' => $has_key,
                'date' => date('Y-m-d'),
                'count' => $new_count,
            );
            $this->db->insert('api_logs', $data);
        }

        if ($do_url_validation) {
            if (!filter_var($has_url, FILTER_VALIDATE_URL)) {
                $this->response([
                    'status' => false,
                    'error' => __('Required request headers are invalid or missing, please recheck.'),
                ], 403);
            }
        }

        if (!filter_var($has_ip, FILTER_VALIDATE_IP)) {
            $this->response([
                'status' => false,
                'error' => __('Required request headers are invalid or missing, please recheck.'),
            ], 403);
        }

        $this->client_ip = $has_ip;
        $this->client_url = $has_url;
        $this->client_agent = !empty($has_agent) ? $has_agent : null;

        $api_rate_limit = $this->user_model->get_config_from_db('api_rate_limit');
        if ($api_rate_limit && !$api_key_info['ignore_limits']) {
            $this->methods['check_connection_int_post']['limit'] = $api_rate_limit;
            $this->methods['create_license_post']['limit'] = $api_rate_limit;
            $this->methods['edit_license_post']['limit'] = $api_rate_limit;
            $this->methods['search_license_post']['limit'] = $api_rate_limit;
            $this->methods['add_product_post']['limit'] = $api_rate_limit;
            $this->methods['get_product_post']['limit'] = $api_rate_limit;
            $this->methods['get_products_post']['limit'] = $api_rate_limit;
            $this->methods['mark_product_active_post']['limit'] = $api_rate_limit;
            $this->methods['mark_product_inactive_post']['limit'] = $api_rate_limit;
            $this->methods['get_license_post']['limit'] = $api_rate_limit;
            $this->methods['delete_license_post']['limit'] = $api_rate_limit;
            $this->methods['block_license_post']['limit'] = $api_rate_limit;
            $this->methods['unblock_license_post']['limit'] = $api_rate_limit;
            $this->methods['deactivate_license_activations_post']['limit'] = $api_rate_limit;
        }

        if (!empty($this->user_model->get_config_from_db('blacklisted_ips')) && !$api_key_info['ignore_limits']) {
            $blacklisted_ips = explode(',', $this->user_model->get_config_from_db('blacklisted_ips'));
            if (in_array($has_ip, $blacklisted_ips)) {
                $this->response([
                    'status' => false,
                    'error' => __('Your IP is blacklisted.'),
                ], 401);
            }
        }

        if (!empty($this->user_model->get_config_from_db('blacklisted_domains')) && !$api_key_info['ignore_limits']) {
            foreach (explode(',', $this->user_model->get_config_from_db('blacklisted_domains')) as $doms) {
                if (strpos($has_domain, $doms) !== false) {
                    $this->response([
                        'status' => false,
                        'error' => __('Your Domain is blacklisted.'),
                    ], 401);
                }
            }
        }
    }

    public function check_connection_int_post()
    {
        $this->response([
            'status' => true,
            'message' => __('Connection successful.'),
        ], 200);
    }

    public function add_product_post()
    {
        if (empty($this->post('product_id'))) {
            $product_id = strtoupper(substr(MD5(microtime()), 0, 8));
            if ($this->products_model->check_product_exists($product_id)) {
                $product_id = strtoupper(substr(MD5(microtime()), 0, 8));
            }
        } else {
            $product_id = strip_tags(trim($this->post('product_id')));
            if (!preg_match('/^[a-z0-9]+$/i', $product_id)) {
                $this->response([
                    'status' => false,
                    'message' => __('Provided product ID is invalid, please check.'),
                ], 400);
            }
        }
        if (!empty($this->post('product_name'))) {
            $product_name = strip_tags(trim($this->post('product_name')));
            if ($this->products_model->check_product_exists($product_id)) {
                $this->response([
                    'status' => false,
                    'message' => __('Provided product ID already exists, please recheck.'),
                ], 200);
            }
            $envato_item_id = (!empty($this->post('envato_item_id')) ? strip_tags(trim($this->post('envato_item_id'))) : null);
            $product_details = (!empty($this->post('product_details')) ? strip_tags(trim($this->post('product_details'))) : null);
            $data = array(
                'pd_pid' => $product_id,
                'envato_id' => $envato_item_id,
                'pd_name' => $product_name,
                'pd_details' => $product_details,
                'license_update' => 0,
                'pd_status' => 1,
            );
            if ($this->db->insert('product_details', $data)) {
                $this->user_model->add_log('New product <b>' . s_t($product_name) . '</b> added.');
                $this->response([
                    'status' => true,
                    // TRANSLATORS: First %s will be replaced with the product name.
                    // Second %s will be replaced with the product ID.
                    'message' => __('New product %s having ID %s was successfully added.', $product_name, $product_id),
                    'product_id' => $product_id,
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => __('An error occured, product was not added.'),
                ], 200);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => __('Incorrect method or missing values, please check.'),
            ], 400);
        }
    }

    public function get_product_post()
    {
        if (!empty($this->post('product_id'))) {
            $product_id = strip_tags(trim($this->post('product_id')));
            $product = $this->db->get_where('product_details', array('pd_pid' => $product_id))->row_array();
            if (!empty($product)) {
                $latest_version_res = $this->products_model->get_latest_version($product['pd_pid']);
                if (!empty($latest_version_res)) {
                    $latest_version = $latest_version_res[0]['version'];
                    $latest_version_release_date = $latest_version_res[0]['release_date'];
                } else {
                    $latest_version = null;
                    $latest_version_release_date = null;
                }
                if ($product['pd_status'] == 1) {
                    $product_status = true;
                } else {
                    $product_status = false;
                }
                if ($product['license_update'] == 1) {
                    $license_update = true;
                } else {
                    $license_update = false;
                }
                $this->response([
                    'status' => true,
                    'product_id' => $product['pd_pid'],
                    'envato_item_id' => $product['envato_id'],
                    'product_name' => $product['pd_name'],
                    'product_details' => $product['pd_details'],
                    'latest_version' => $latest_version,
                    'latest_version_release_date' => $latest_version_release_date,
                    'is_product_active' => $product_status,
                    'requires_license_for_downloading_updates' => $license_update,
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => __('Product ID specified is incorrect, please recheck.'),
                ], 200);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => __('Incorrect method or missing values, please check.'),
            ], 400);
        }
    }

    public function get_products_post()
    {
        $products = $this->products_model->get_product();
        $final_data = array();
        foreach ($products as $product) {
            $latest_version_res = $this->products_model->get_latest_version($product['pd_pid']);
            if (!empty($latest_version_res)) {
                $latest_version = $latest_version_res[0]['version'];
                $latest_version_release_date = $latest_version_res[0]['release_date'];
            } else {
                $latest_version = null;
                $latest_version_release_date = null;
            }
            if ($product['pd_status'] == 1) {
                $product_status = true;
            } else {
                $product_status = false;
            }
            if ($product['license_update'] == 1) {
                $license_update = true;
            } else {
                $license_update = false;
            }
            $final_data[] = array(
                'product_id' => $product['pd_pid'],
                'envato_item_id' => $product['envato_id'],
                'product_name' => $product['pd_name'],
                'product_details' => $product['pd_details'],
                'latest_version' => $latest_version,
                'latest_version_release_date' => $latest_version_release_date,
                'is_product_active' => $product_status,
                'requires_license_for_downloading_updates' => $license_update,
            );
        }
        $this->response([
            'status' => true,
            'products' => $final_data,
        ], 200);
    }

    public function mark_product_active_post()
    {
        if (!empty($this->post('product_id'))) {
            $product_id = strip_tags(trim($this->post('product_id')));
            $product = $this->db->get_where('product_details', array('pd_pid' => $product_id))->row_array();
            if (!empty($product)) {
                $this->db->where('pd_pid', $product_id);
                $this->db->update('product_details', array(
                    'pd_status' => 1,
                ));
                if ($this->db->affected_rows() > 0) {
                    $this->user_model->add_log('Product <b>' . s_t($product['pd_name']) . '</b> status changed to active.');
                    $this->response([
                        'status' => true,
                        // TRANSLATORS: First %s will be replaced with the product name.
                        'message' => __('Product %s marked as active.', $product['pd_name']),
                    ], 200);
                } else {
                    $this->response([
                        'status' => false,
                        // TRANSLATORS: First %s will be replaced with the product name.
                        'message' => __('Product %s is already active.', $product['pd_name']),
                    ], 200);
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => __('Product ID specified is incorrect, please recheck.'),
                ], 200);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => __('Incorrect method or missing values, please check.'),
            ], 400);
        }
    }

    public function mark_product_inactive_post()
    {
        if (!empty($this->post('product_id'))) {
            $product_id = strip_tags(trim($this->post('product_id')));
            $product = $this->db->get_where('product_details', array('pd_pid' => $product_id))->row_array();
            if (!empty($product)) {
                $this->db->where('pd_pid', $product_id);
                $this->db->update('product_details', array(
                    'pd_status' => 0,
                ));
                if ($this->db->affected_rows() > 0) {
                    $this->user_model->add_log('Product <b>' . s_t($product['pd_name']) . '</b> status changed to inactive.');
                    $this->response([
                        'status' => true,
                        // TRANSLATORS: First %s will be replaced with the product name.
                        'message' => __('Product %s marked as inactive.', $product['pd_name']),
                    ], 200);
                } else {
                    $this->response([
                        'status' => false,
                        // TRANSLATORS: First %s will be replaced with the product name.
                        'message' => __('Product %s is already inactive.', $product['pd_name']),
                    ], 200);
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => __('Product ID specified is incorrect, please recheck.'),
                ], 200);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => __('Incorrect method or missing values, please check.'),
            ], 400);
        }
    }

    public function create_license_post()
    {
        if (empty($this->post('license_code'))) {
            $this->load->helper('license_helper');
            $license_code = gen_code($this->user_model->get_config_from_db('license_code_format'));
            if ($this->licenses_model->check_license_exists($license_code)) {
                $license_code = gen_code($this->user_model->get_config_from_db('license_code_format'));
            }
        } else {
            $license_code = strip_tags(trim($this->post('license_code')));
            if (!preg_match('/^[a-zA-Z0-9_-]*$/m', $license_code)) {
                $this->response([
                    'status' => false,
                    'message' => __('Provided license code is invalid, please check.'),
                ], 400);
            }
        }
        if (!empty($this->post('product_id'))) {
            $product_id = strip_tags(trim($this->post('product_id')));
            $row = $this->db->get_where('product_details', array('pd_pid' => $product_id))->row_array();
            if (!empty($row)) {
                if ($this->licenses_model->check_license_exists($license_code)) {
                    $this->response([
                        'status' => false,
                        'message' => __('Provided license code already exists, please recheck.'),
                    ], 200);
                }
                $client_name = (!empty($this->post('client_name')) ? strip_tags(trim($this->post('client_name'))) : null);
                $license_type = (!empty($this->post('license_type')) ? strip_tags(trim($this->post('license_type'))) : null);
                $invoice_number = (!empty($this->post('invoice_number')) ? strip_tags(trim($this->post('invoice_number'))) : null);
                $client_email = (!empty($this->post('client_email')) ? strip_tags(trim($this->post('client_email'))) : null);
                if (!empty($client_email)) {
                    if (filter_var($client_email, FILTER_VALIDATE_EMAIL) == false) {
                        $this->response([
                            'status' => false,
                            'message' => __('Provided client email address is incorrect, please check.'),
                        ], 200);
                    }
                }
                $comments = (!empty($this->post('comments')) ? strip_tags(trim($this->post('comments'))) : null);
                $licensed_ips = (!empty($this->post('licensed_ips')) ? strip_tags(trim($this->post('licensed_ips'))) : null);
                if (!empty($licensed_ips)) {
                    $arr_ips = explode(',', $licensed_ips);
                    $licensed_ips = implode(',', array_unique($arr_ips));
                    if (!validate_ips($licensed_ips)) {
                        $this->response([
                            'status' => false,
                            'message' => __('Provided licensed IPs are incorrect, please check.'),
                        ], 200);
                    }
                }
                $licensed_domains = (!empty($this->post('licensed_domains')) ? remove_http_www_bulk(strip_tags(trim($this->post('licensed_domains')))) : null);
                if (!empty($licensed_domains)) {
                    $arr_domains = explode(',', $licensed_domains);
                    $licensed_domains = implode(',', array_unique($arr_domains));
                    if (!validate_domains($licensed_domains)) {
                        $this->response([
                            'status' => false,
                            'message' => __('Provided licensed domains are incorrect, please check.'),
                        ], 200);
                    }
                }
                $support_end_date = (!empty($this->post('support_end_date')) ? strip_tags(trim($this->post('support_end_date'))) : null);
                if (!empty($support_end_date)) {
                    if (!validate_date($support_end_date) && !validate_date($support_end_date, 'Y-m-d H:i:s')) {
                        $this->response([
                            'status' => false,
                            'message' => __('Provided license support end date is incorrect, please check.'),
                        ], 200);
                    }
                }
                $updates_end_date = (!empty($this->post('updates_end_date')) ? strip_tags(trim($this->post('updates_end_date'))) : null);
                if (!empty($updates_end_date)) {
                    if (!validate_date($updates_end_date) && !validate_date($updates_end_date, 'Y-m-d H:i:s')) {
                        $this->response([
                            'status' => false,
                            'message' => __('Provided license updates end date is incorrect, please check.'),
                        ], 200);
                    }
                }
                $expiry_date = (!empty($this->post('expiry_date')) ? strip_tags(trim($this->post('expiry_date'))) : null);
                if (!empty($expiry_date)) {
                    if (!validate_date($expiry_date) && !validate_date($expiry_date, 'Y-m-d H:i:s')) {
                        $this->response([
                            'status' => false,
                            'message' => __('Provided license expiration date is incorrect, please check.'),
                        ], 200);
                    }
                }
                $expiry_days = (!empty($this->post('expiry_days')) ? strip_tags(trim($this->post('expiry_days'))) : null);
                if (!empty($expiry_days)) {
                    if (filter_var($expiry_days, FILTER_VALIDATE_INT) === false) {
                        $this->response([
                            'status' => false,
                            'message' => __('Provided license expiration days is incorrect, please check.'),
                        ], 200);
                    }
                }
                $license_uses = (!empty($this->post('license_uses')) ? strip_tags(trim($this->post('license_uses'))) : null);
                if (!empty($license_uses)) {
                    if (filter_var($license_uses, FILTER_VALIDATE_INT) === false) {
                        $this->response([
                            'status' => false,
                            'message' => __('Provided license use limit is incorrect, please check.'),
                        ], 200);
                    }
                }
                $license_parallel_uses = (!empty($this->post('license_parallel_uses')) ? strip_tags(trim($this->post('license_parallel_uses'))) : null);
                if (!empty($license_parallel_uses)) {
                    if (filter_var($license_parallel_uses, FILTER_VALIDATE_INT) === false) {
                        $this->response([
                            'status' => false,
                            'message' => __('Provided license parallel use limit is incorrect, please check.'),
                        ], 200);
                    }
                }
                $data = array(
                    'pid' => $product_id,
                    'license_code' => $license_code,
                    'license_type' => $license_type,
                    'invoice' => $invoice_number,
                    'client' => $client_name,
                    'email' => $client_email,
                    'comments' => $comments,
                    'ips' => $licensed_ips,
                    'domains' => $licensed_domains,
                    'supported_till' => $support_end_date,
                    'updates_till' => $updates_end_date,
                    'expiry' => $expiry_date,
                    'expiry_days' => $expiry_days,
                    'uses' => $license_uses,
                    'uses_left' => $license_uses,
                    'parallel_uses' => $license_parallel_uses,
                    'parallel_uses_left' => $license_parallel_uses,
                    'validity' => 1,
                );
                if ($this->db->insert('product_licenses', $data)) {
                    $this->user_model->add_log('New ' . s_t($row['pd_name']) . ' license <b>' . s_t($license_code) . '</b> added.');
                    $this->response([
                        'status' => true,
                        // TRANSLATORS: First %s will be replaced with the product name.
                        // Second %s will be replaced with the license code/key.
                        'message' => __('New %s license %s was successfully added.', $row['pd_name'], $license_code),
                        'license_code' => $license_code,
                    ], 200);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => __('An error occured, license was not added.'),
                    ], 200);
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => __('Product ID specified is incorrect, please recheck.'),
                ], 200);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => __('Incorrect method or missing values, please check.'),
            ], 400);
        }
    }

    public function edit_license_post()
    {
        if (!empty($this->post('license_code'))) {
            $license_code = strip_tags(trim($this->post('license_code')));
            $license_row = $this->db->get_where('product_licenses', array('license_code' => $license_code))->row_array();
            if (!empty($license_row)) {
                if (!empty($this->post('product_id'))) {
                    $product_id = strip_tags(trim($this->post('product_id')));
                } else {
                    $product_id = $license_row['pid'];
                }
                if (!empty($product_id)) {
                    $row = $this->db->get_where('product_details', array('pd_pid' => $product_id))->row_array();
                    if (empty($row)) {
                        $this->response([
                            'status' => false,
                            'message' => __('Product ID specified is incorrect, please recheck.'),
                        ], 200);
                    }
                }
                $_client_name = $this->post('client_name');
                if (isset($_client_name) && $this->post('client_name') != '') {
                    $client_name = strip_tags(trim($this->post('client_name')));
                } elseif (isset($_client_name) && $this->post('client_name') == '') {
                    $client_name = null;
                } else {
                    $client_name = $license_row['client'];
                }
                $_license_type = $this->post('license_type');
                if (isset($_license_type) && $this->post('license_type') != '') {
                    $license_type = strip_tags(trim($this->post('license_type')));
                } elseif (isset($_license_type) && $this->post('license_type') == '') {
                    $license_type = null;
                } else {
                    $license_type = $license_row['license_type'];
                }
                $_invoice_number = $this->post('invoice_number');
                if (isset($_invoice_number) && $this->post('invoice_number') != '') {
                    $invoice_number = strip_tags(trim($this->post('invoice_number')));
                } elseif (isset($_invoice_number) && $this->post('invoice_number') == '') {
                    $invoice_number = null;
                } else {
                    $invoice_number = $license_row['invoice'];
                }
                $_client_email = $this->post('client_email');
                if (isset($_client_email) && $this->post('client_email') != '') {
                    $client_email = strip_tags(trim($this->post('client_email')));
                } elseif (isset($_client_email) && $this->post('client_email') == '') {
                    $client_email = null;
                } else {
                    $client_email = $license_row['email'];
                }
                if (!empty($client_email)) {
                    if (filter_var($client_email, FILTER_VALIDATE_EMAIL) == false) {
                        $this->response([
                            'status' => false,
                            'message' => __('Provided client email address is incorrect, please check.'),
                        ], 200);
                    }
                }
                $_comments = $this->post('comments');
                if (isset($_comments) && $this->post('comments') != '') {
                    $comments = strip_tags(trim($this->post('comments')));
                } elseif (isset($_comments) && $this->post('comments') == '') {
                    $comments = null;
                } else {
                    $comments = $license_row['comments'];
                }
                $_licensed_ips = $this->post('licensed_ips');
                if (isset($_licensed_ips) && $this->post('licensed_ips') != '') {
                    $licensed_ips = strip_tags(trim($this->post('licensed_ips')));
                } elseif (isset($_licensed_ips) && $this->post('licensed_ips') == '') {
                    $licensed_ips = null;
                } else {
                    $licensed_ips = $license_row['ips'];
                }
                if (!empty($licensed_ips)) {
                    $arr_ips = explode(',', $licensed_ips);
                    $licensed_ips = implode(',', array_unique($arr_ips));
                    if (!validate_ips($licensed_ips)) {
                        $this->response([
                            'status' => false,
                            'message' => __('Provided licensed IPs are incorrect, please check.'),
                        ], 200);
                    }
                }
                $_licensed_domains = $this->post('licensed_domains');
                if (isset($_licensed_domains) && $this->post('licensed_domains') != '') {
                    $licensed_domains = remove_http_www_bulk(strip_tags(trim($this->post('licensed_domains'))));
                } elseif (isset($_licensed_domains) && $this->post('licensed_domains') == '') {
                    $licensed_domains = null;
                } else {
                    $licensed_domains = $license_row['domains'];
                }
                if (!empty($licensed_domains)) {
                    $arr_domains = explode(',', $licensed_domains);
                    $licensed_domains = implode(',', array_unique($arr_domains));
                    if (!validate_domains($licensed_domains)) {
                        $this->response([
                            'status' => false,
                            'message' => __('Provided licensed domains are incorrect, please check.'),
                        ], 200);
                    }
                }
                $_support_end_date = $this->post('support_end_date');
                if (isset($_support_end_date) && $this->post('support_end_date') != '') {
                    $support_end_date = strip_tags(trim($this->post('support_end_date')));
                } elseif (isset($_support_end_date) && $this->post('support_end_date') == '') {
                    $support_end_date = null;
                } else {
                    $support_end_date = $license_row['supported_till'];
                }
                if (!empty($support_end_date)) {
                    if (!validate_date($support_end_date) && !validate_date($support_end_date, 'Y-m-d H:i:s')) {
                        $this->response([
                            'status' => false,
                            'message' => __('Provided license support end date is incorrect, please check.'),
                        ], 200);
                    }
                }
                $_updates_end_date = $this->post('updates_end_date');
                if (isset($_updates_end_date) && $this->post('updates_end_date') != '') {
                    $updates_end_date = strip_tags(trim($this->post('updates_end_date')));
                } elseif (isset($_updates_end_date) && $this->post('updates_end_date') == '') {
                    $updates_end_date = null;
                } else {
                    $updates_end_date = $license_row['updates_till'];
                }
                if (!empty($updates_end_date)) {
                    if (!validate_date($updates_end_date) && !validate_date($updates_end_date, 'Y-m-d H:i:s')) {
                        $this->response([
                            'status' => false,
                            'message' => __('Provided license updates end date is incorrect, please check.'),
                        ], 200);
                    }
                }
                $_expiry_date = $this->post('expiry_date');
                if (isset($_expiry_date) && $this->post('expiry_date') != '') {
                    $expiry_date = strip_tags(trim($this->post('expiry_date')));
                } elseif (isset($_expiry_date) && $this->post('expiry_date') == '') {
                    $expiry_date = null;
                } else {
                    $expiry_date = $license_row['expiry'];
                }
                if (!empty($expiry_date)) {
                    if (!validate_date($expiry_date) && !validate_date($expiry_date, 'Y-m-d H:i:s')) {
                        $this->response([
                            'status' => false,
                            'message' => __('Provided license expiration date is incorrect, please check.'),
                        ], 200);
                    }
                }
                $_expiry_days = $this->post('expiry_days');
                if (isset($_expiry_days) && $this->post('expiry_days') != '') {
                    $expiry_days = strip_tags(trim($this->post('expiry_days')));
                } elseif (isset($_expiry_days) && $this->post('expiry_days') == '') {
                    $expiry_days = null;
                } else {
                    $expiry_days = $license_row['expiry_days'];
                }
                if (!empty($expiry_days)) {
                    if (filter_var($expiry_days, FILTER_VALIDATE_INT) === false) {
                        $this->response([
                            'status' => false,
                            'message' => __('Provided license expiration days is incorrect, please check.'),
                        ], 200);
                    }
                }
                $_license_uses = $this->post('license_uses');
                if (isset($_license_uses) && $this->post('license_uses') != '') {
                    $license_uses = strip_tags(trim($this->post('license_uses')));
                } elseif (isset($_license_uses) && $this->post('license_uses') == '') {
                    $license_uses = null;
                } else {
                    $license_uses = $license_row['uses'];
                }
                if (!empty($license_uses)) {
                    if (filter_var($license_uses, FILTER_VALIDATE_INT) === false) {
                        $this->response([
                            'status' => false,
                            'message' => __('Provided license use limit is incorrect, please check.'),
                        ], 200);
                    }
                }
                $_license_parallel_uses = $this->post('license_parallel_uses');
                if (isset($_license_parallel_uses) && $this->post('license_parallel_uses') != '') {
                    $license_parallel_uses = strip_tags(trim($this->post('license_parallel_uses')));
                } elseif (isset($_license_parallel_uses) && $this->post('license_parallel_uses') == '') {
                    $license_parallel_uses = null;
                } else {
                    $license_parallel_uses = $license_row['parallel_uses'];
                }
                if (!empty($license_parallel_uses)) {
                    if (filter_var($license_parallel_uses, FILTER_VALIDATE_INT) === false) {
                        $this->response([
                            'status' => false,
                            'message' => __('Provided license parallel use limit is incorrect, please check.'),
                        ], 200);
                    }
                }
                $data = array(
                    'pid' => $product_id,
                    'license_type' => $license_type,
                    'invoice' => $invoice_number,
                    'client' => $client_name,
                    'email' => $client_email,
                    'comments' => $comments,
                    'ips' => $licensed_ips,
                    'domains' => $licensed_domains,
                    'supported_till' => $support_end_date,
                    'updates_till' => $updates_end_date,
                    'expiry' => $expiry_date,
                    'expiry_days' => $expiry_days,
                    'uses' => $license_uses,
                    'uses_left' => $license_uses,
                    'parallel_uses' => $license_parallel_uses,
                    'parallel_uses_left' => $license_parallel_uses,
                    'validity' => 1,
                );
                $this->db->where('license_code', $license_code);
                if ($this->db->update('product_licenses', $data)) {
                    $this->user_model->add_log('License <b>' . s_t($license_code) . '</b> edited.');
                    $this->response([
                        'status' => true,
                        // TRANSLATORS: First %s will be replaced with the license code/key.
                        'message' => __('License %s was successfully edited.', $license_code),
                        'license_code' => $license_code,
                    ], 200);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => __('An error occured, license was not edited.'),
                    ], 200);
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => __('Provided license does not exist, please recheck.'),
                ], 200);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => __('Incorrect method or missing values, please check.'),
            ], 400);
        }
    }

    public function get_license_post()
    {
        if (!empty($this->post('license_code'))) {
            $license_code = strip_tags(trim($this->post('license_code')));
            $license = $this->db->get_where('product_licenses', array('license_code' => $license_code))->row_array();
            if (!empty($license)) {
                $product = $this->db->get_where('product_details', array('pd_pid' => $license['pid']))->row_array();
                if (!empty($product)) {
                    $product_name = $product['pd_name'];
                } else {
                    $product_name = null;
                }
                if ($license['is_envato'] == 1) {
                    $is_envato = true;
                } else {
                    $is_envato = false;
                }
                if ($license['validity'] == 1) {
                    $blocked = false;
                } else {
                    $blocked = true;
                }
                $current_activations = $this->activations_model->get_activation_by_license($license['license_code']);
                $current_activations_active = $this->activations_model->get_activation_by_license_active($license['license_code']);
                $licenses_left0 = $license['uses'] - $current_activations;
                if ($licenses_left0 > 0) {
                    $licenses_left = $licenses_left0;
                } elseif ($license['uses'] == null) {
                    $licenses_left0 = null;
                    $licenses_left = null;
                } else {
                    $licenses_left0 = 0;
                    $licenses_left = 0;
                }
                $parallel_licenses_left0 = $license['parallel_uses'] - $current_activations_active;
                if ($parallel_licenses_left0 > 0) {
                    $parallel_licenses_left = $parallel_licenses_left0;
                } elseif ($license['parallel_uses'] == null) {
                    $parallel_licenses_left0 = null;
                    $parallel_licenses_left = null;
                } else {
                    $parallel_licenses_left0 = 0;
                    $parallel_licenses_left = 0;
                }
                if ($license['validity'] == 1) {
                    $is_valid = true;
                    if (($licenses_left0 > 0) || ($license['uses'] == null)) {
                        $is_valid = true;
                        if (($parallel_licenses_left0 > 0) || ($license['parallel_uses'] == null)) {
                            $is_valid = true;
                            $today = date('Y-m-d H:i:s');
                            if (!empty($license['expiry'])) {
                                $expiry_date = $license['expiry'];
                                if ($today < $expiry_date) {
                                    $is_valid = true;
                                } else {
                                    $is_valid = false;
                                }
                            }
                            if (!empty($license['expiry_days'])) {
                                $expiry_days = $license['expiry_days'];
                                $oldest_activation = $this->activations_model->get_oldest_activation_by_license($license['license_code']);
                                if (!empty($oldest_activation)) {
                                    $oldest_activation_date = new DateTime($oldest_activation['pi_date']);
                                    $date_today = new DateTime($today);
                                    $days_diff = $date_today->diff($oldest_activation_date)->format("%a");
                                    if ($days_diff < $expiry_days) {
                                        $is_valid = true;
                                    } else {
                                        $is_valid = false;
                                    }
                                }
                            }
                        } else {
                            $is_valid = false;
                        }
                    } else {
                        $is_valid = false;
                    }
                } else {
                    $is_valid = false;
                }
                $this->response([
                    'status' => true,
                    'license_code' => $license['license_code'],
                    'product_id' => $license['pid'],
                    'product_name' => $product_name,
                    'license_type' => $license['license_type'],
                    'client_name' => $license['client'],
                    'client_email' => $license['email'],
                    'invoice_number' => $license['invoice'],
                    'license_comments' => $license['comments'],
                    'licensed_ips' => $license['ips'],
                    'licensed_domains' => $license['domains'],
                    'uses' => $license['uses'],
                    'uses_left' => $licenses_left,
                    'parallel_uses' => $license['parallel_uses'],
                    'parallel_uses_left' => $parallel_licenses_left0,
                    'license_expiry' => $license['expiry'],
                    'support_expiry' => $license['supported_till'],
                    'updates_expiry' => $license['updates_till'],
                    'date_modified' => $license['added_on'],
                    'is_blocked' => $blocked,
                    'is_a_envato_purchase_code' => $is_envato,
                    'is_valid_for_future_activations' => $is_valid,
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => __('License code specified is incorrect, please recheck.'),
                ], 200);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => __('Incorrect method or missing values, please check.'),
            ], 400);
        }
    }

    public function search_license_post()
    {
        if (!empty($this->post('keyword'))) {
            $search_keyword = strip_tags(trim($this->post('keyword')));
            if (!empty($search_keyword)) {
                $this->db->select('pid as product_id, license_code, license_type, client as client_name, email as client_email');
                $this->db->from('product_licenses');
                $this->db->like('license_code', $search_keyword);
                $this->db->limit(10);
                $licenses_found = $this->db->get()->result_array();
                if (!empty($licenses_found)) {
                    $this->response([
                        'status' => true,
                        'results_count' => count($licenses_found),
                        'results' => $licenses_found,
                    ], 200);
                }
            }
            $this->response([
                'status' => true,
                'results' => "No corresponding license was found.",
            ], 200);
        } else {
            $this->response([
                'status' => false,
                'message' => __('No keywords were provided for the search. Please check and try again.'),
            ], 400);
        }
    }

    public function delete_license_post()
    {
        if (!empty($this->post('license_code'))) {
            $license_code = strip_tags(trim($this->post('license_code')));
            $license = $this->db->get_where('product_licenses', array('license_code' => $license_code))->row_array();
            if (!empty($license)) {
                $this->activations_model->delete_installation_by_license($license_code);
                if ($this->licenses_model->delete_license($license_code)) {
                    $this->user_model->add_log('License <b>' . s_t($license['license_code']) . '</b> deleted.');
                    $this->response([
                        'status' => true,
                        // TRANSLATORS: First %s will be replaced with the license code/key.
                        'message' => __('License %s was successfully deleted.', $license['license_code']),
                    ], 200);
                } else {
                    $this->response([
                        'status' => false,
                        // TRANSLATORS: First %s will be replaced with the license code/key.
                        'message' => __('License %s was not deleted, please recheck.', $license['license_code']),
                    ], 200);
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => __('License code specified is incorrect, please recheck.'),
                ], 200);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => __('Incorrect method or missing values, please check.'),
            ], 400);
        }
    }

    public function deactivate_license_activations_post()
    {
        if (!empty($this->post('license_code'))) {
            $license_code = strip_tags(trim($this->post('license_code')));
            $license = $this->db->get_where('product_licenses', array('license_code' => $license_code))->row_array();
            if (!empty($license)) {
                $this->db->where('pi_license_code', $license_code);
                $this->db->update('product_activations', array(
                    'pi_isactive' => 0,
                ));
                if ($this->db->affected_rows() > 0) {
                    $this->user_model->add_log('Activations of License <b>' . s_t($license['license_code']) . '</b> deactivated.');
                    $this->response([
                        'status' => true,
                        // TRANSLATORS: First %s will be replaced with the license code/key.
                        'message' => __('License %s was successfully deactivated.', $license['license_code']),
                    ], 200);
                } else {
                    $this->response([
                        'status' => false,
                        // TRANSLATORS: First %s will be replaced with the license code/key.
                        'message' => __('License %s is already deactivated.', $license['license_code']),
                    ], 200);
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => __('License code specified is incorrect, please recheck.'),
                ], 200);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => __('Incorrect method or missing values, please check.'),
            ], 400);
        }
    }

    public function block_license_post()
    {
        if (!empty($this->post('license_code'))) {
            $license_code = strip_tags(trim($this->post('license_code')));
            $license = $this->db->get_where('product_licenses', array('license_code' => $license_code))->row_array();
            if (!empty($license)) {
                $this->db->where('license_code', $license_code);
                $this->db->update('product_licenses', array(
                    'validity' => 0,
                ));
                if ($this->db->affected_rows() > 0) {
                    $this->user_model->add_log('License <b>' . s_t($license['license_code']) . '</b> blocked.');
                    $this->response([
                        'status' => true,
                        // TRANSLATORS: First %s will be replaced with the license code/key.
                        'message' => __('License %s was successfully blocked.', $license['license_code']),
                    ], 200);
                } else {
                    $this->response([
                        'status' => false,
                        // TRANSLATORS: First %s will be replaced with the license code/key.
                        'message' => __('License %s is already blocked.', $license['license_code']),
                    ], 200);
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => __('License code specified is incorrect, please recheck.'),
                ], 200);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => __('Incorrect method or missing values, please check.'),
            ], 400);
        }
    }

    public function unblock_license_post()
    {
        if (!empty($this->post('license_code'))) {
            $license_code = strip_tags(trim($this->post('license_code')));
            $license = $this->db->get_where('product_licenses', array('license_code' => $license_code))->row_array();
            if (!empty($license)) {
                $this->db->where('license_code', $license_code);
                $this->db->update('product_licenses', array(
                    'validity' => 1,
                ));
                if ($this->db->affected_rows() > 0) {
                    $this->user_model->add_log('License <b>' . s_t($license['license_code']) . '</b> unblocked.');
                    $this->response([
                        'status' => true,
                        // TRANSLATORS: First %s will be replaced with the license code/key.
                        'message' => __('License %s was successfully unblocked.', $license['license_code']),
                    ], 200);
                } else {
                    $this->response([
                        'status' => false,
                        // TRANSLATORS: First %s will be replaced with the license code/key.
                        'message' => __('License %s is already unblocked.', $license['license_code']),
                    ], 200);
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => __('License code specified is incorrect, please recheck.'),
                ], 200);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => __('Incorrect method or missing values, please check.'),
            ], 400);
        }
    }
}

