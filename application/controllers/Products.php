<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Keydera
 *
 * Keydera is a full-fledged licenser and updates manager.
 *
 * @package Keydera
 * @author Keydera
 * @see https://keydera.app
 * @link https://codecanyon.net/item/keydera-php-license-and-updates-manager/22351237
 * @license https://codecanyon.net/licenses/standard (Regular or Extended License)
 * @copyright Copyright (c) 2023, Keydera. (https://www.keydera.app)
 * @version 1.6.4
 */

class Products extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_userdata('redirectToCurrent', current_url());
            redirect('users/login');
        }
    }

    public function index()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('users/login');
        }
        $data['title'] = 'Manage Products';
        $data['products'] = $this->products_model->get_product();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/menu');
        $this->load->view('products/index', $data);
        $this->load->view('templates/footer');
    }

    public function add()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('users/login');
        }

        if ($this->products_model->check_product_exists($this->input->post('product_id'))) {
            $this->session->set_flashdata('product_status', array(
                'type' => "danger",
                'message' => "Product ID already exists, please recheck.",
            ));
            redirect('products/add');
        }
        $data['title'] = 'Add Product';
        $data['product_id'] = strtoupper(substr(MD5(microtime()), 0, 8));
        $this->form_validation->set_rules('name', 'Product name', 'required');
        $this->form_validation->set_rules('product_id', 'Product ID', 'required');
        $this->form_validation->set_rules('product_status', 'Product Status', 'required');
        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/menu');
            $this->load->view('products/add', $data);
            $this->load->view('templates/footer');
        } else {
            if ($this->products_model->add_product()) {
                $this->user_model->add_log('New product <b>' . s_t($this->input->post('name')) . '</b> added by ' . s_t(ucfirst($this->session->userdata('fullname'))) . '.');
                $this->session->set_flashdata('product_status', array(
                    'type' => "success",
                    'message' => "Product (" . s_t($this->input->post('name')) . ") was successfully added.",
                ));
                redirect('products');
            } else {
                $this->session->set_flashdata('product_status', array(
                    'type' => "danger",
                    'message' => "An error occured, Please recheck entered values. Product was not added.",
                ));
                redirect('products/add');
            }
        }

    }

    public function delete()
    {
        $this->activations_model->delete_installation_by_pid($this->input->post('product'));
        $this->downloads_model->delete_downloads_by_pid($this->input->post('product'));
        $this->products_model->delete_versions_by_pid($this->input->post('product'));
        $this->licenses_model->delete_licenses_by_pid($this->input->post('product'));
        $product = $this->products_model->get_product($this->input->post('product'));
        if ($this->products_model->delete_product()) {
            $this->user_model->add_log('Product <b>' . s_t($product['pd_name']) . '</b> deleted by ' . s_t(ucfirst($this->session->userdata('fullname'))) . '.');
            $this->session->set_flashdata('product_status', array(
                'type' => "success",
                'message' => "Product (" . s_t($product['pd_name']) . ") was successfully deleted.",
            ));
            redirect('products');
        } else {
            $this->session->set_flashdata('product_status', array(
                'type' => "danger",
                'message' => "An error occured, Product was not deleted.",
            ));
            redirect('products');
        }
    }

    public function edit()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('users/login');
        }

        if (empty($this->input->post('product'))) {
            redirect('products');
        }

        $data['title'] = 'Edit Product';
        $data['product'] = $this->products_model->get_product($this->input->post('product'));
        $this->form_validation->set_rules('name', 'Product name', 'required');
        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/menu');
            $this->load->view('products/edit', $data);
            $this->load->view('templates/footer');
        } else {
            if ($this->products_model->edit_product() > 0) {
                $this->user_model->add_log('Product <b>' . s_t($data['product']['pd_name']) . '</b> edited by ' . s_t(ucfirst($this->session->userdata('fullname'))) . '.');
                $this->session->set_flashdata('product_status', array(
                    'type' => "success",
                    'message' => "Product (" . s_t($data['product']['pd_name']) . ") was successfully updated.",
                ));
                redirect('products');
            } else {
                $this->session->set_flashdata('product_status', array(
                    'type' => "danger",
                    'message' => "Please recheck entered values or you haven't made any changes, Product was not updated.",
                ));
                redirect('products');
            }
        }
    }

    public function add_version()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('users/login');
        }
        if (empty($this->input->post('product'))) {
            redirect('products');
        }

        $product = $this->products_model->get_product($this->input->post('product'));

        if ($this->products_model->check_version_exists($this->input->post('product'), $this->input->post('version'))) {
            $this->session->set_flashdata('product_status', array(
                'type' => "danger",
                'message' => "Version already exists, please recheck.",
            ));
            $this->session->set_flashdata('product_id', $this->input->post('product'));
            redirect('products/versions');
        }

        $data['title'] = 'Add new ' . $product['pd_name'] . ' version';

        $this->form_validation->set_rules('product', 'Product ID', 'required');
        $this->form_validation->set_rules('version', 'Product version', 'required');
        $this->form_validation->set_rules('released', 'Released date', 'required');
        $this->form_validation->set_rules('changelog', 'Changelog', 'required');

        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/menu');
            $this->load->view('products/add_version', $data);
            $this->load->view('templates/footer');

        } else {
            @chmod(FCPATH . 'version-files/', 0777);

            $max_size_possible = get_file_upload_max_size();

            $config['upload_path'] = './version-files/';
            $config['allowed_types'] = '*';
            $config['max_size'] = ($max_size_possible < 0) ? 0 : $max_size_possible;
            $config['encrypt_name'] = true;

            $config1['upload_path'] = './version-files/';
            $config1['allowed_types'] = '*';
            $config1['max_size'] = ($max_size_possible < 0) ? 0 : $max_size_possible;
            $config1['encrypt_name'] = true;

            $this->load->library('upload', $config);

            $filetype_main_file = pathinfo($_FILES["main_file"]["name"], PATHINFO_EXTENSION);

            if (!$this->upload->do_upload('main_file') || $filetype_main_file != 'zip') {
                $error0 = $this->upload->display_errors('', '');
                $error = (is_array($error0)) ? $error0[0] : $error0;
                $product = $this->products_model->get_product($this->input->post('product'));
                $data['title'] = 'Add new ' . $product['pd_name'] . ' version';
                $data['product'] = $product['pd_pid'];
                $this->session->set_flashdata('upload_status_main', array(
                    'type' => "danger",
                    'message' => "Main files (.zip) was not uploaded. " . $error,
                ));
                $this->load->view('templates/header', $data);
                $this->load->view('templates/menu');
                $this->load->view('products/add_version', $data);
                $this->load->view('templates/footer');
            } else {
                $main_filename = $this->upload->data('file_name');
            }

            unset($this->upload);

            if (!empty($_FILES['sql_file']['name']) && (isset($main_filename))) {
                $filetype_sql_file = pathinfo($_FILES["sql_file"]["name"], PATHINFO_EXTENSION);
                $this->load->library('upload', $config1);
                if (!$this->upload->do_upload('sql_file') || $filetype_sql_file != 'sql') {
                    $error0 = $this->upload->display_errors('', '');
                    $error = (is_array($error0)) ? $error0[0] : $error0;
                    $product = $this->products_model->get_product($this->input->post('product'));
                    $data['title'] = 'Add new ' . $product['pd_name'] . ' version';
                    $data['product'] = $product['pd_pid'];
                    $this->session->set_flashdata('upload_status_sql', array(
                        'type' => "danger",
                        'message' => "SQL file (.sql) was not uploaded. " . $error,
                    ));
                    $this->load->view('templates/header', $data);
                    $this->load->view('templates/menu');
                    $this->load->view('products/add_version', $data);
                    $this->load->view('templates/footer');
                } else {
                    $has_sql = true;
                    $sql_filename = $this->upload->data('file_name');
                }
            } else {
                $has_sql = false;
                $sql_filename = null;
            }

            if (isset($main_filename) && (isset($has_sql))) {
                if ($this->products_model->add_version($main_filename, $sql_filename)) {
                    $this->user_model->add_log('New version <b>' . s_t($this->input->post('version')) . '</b> added for product <b>' . s_t($product['pd_name']) . '</b> by ' . s_t(ucfirst($this->session->userdata('fullname'))) . '.');
                    $this->session->set_flashdata('product_status', array(
                        'type' => "success",
                        'message' => "Version (" . s_t($this->input->post('version')) . ") was successfully added.",
                    ));
                    $this->session->set_flashdata('product_id', $this->input->post('product'));
                    redirect('products/versions');
                } else {
                    $this->session->set_flashdata('product_status', array(
                        'type' => "danger",
                        'message' => "An error occured, please recheck entered values. Version was not added.",
                    ));
                    redirect('products/versions/add');
                }
            }
        }
    }

    public function edit_version()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('users/login');
        }
        if (empty($this->input->post('product'))) {
            redirect('products');
        }

        $product = $this->products_model->get_product($this->input->post('product'));

        if ($this->input->post('version') !== $this->input->post('version_old')) {
            $check_version_exists = $this->products_model->check_version_exists($this->input->post('product'), $this->input->post('version'));
        } else {
            $check_version_exists = false;
        }

        if ($check_version_exists) {
            $this->session->set_flashdata('product_status', array(
                'type' => "danger",
                'message' => "Version already exists, please recheck.",
            ));
            $this->session->set_flashdata('product_id', $this->input->post('product'));
            redirect('products/versions');
        }

        $data['title'] = 'Edit ' . $product['pd_name'] . ' version';
        $data['version'] = $this->products_model->get_version_by_vid($this->input->post('vid'), true);
        $this->form_validation->set_rules('product', 'Product ID', 'required');
        $this->form_validation->set_rules('version', 'Product version', 'required');
        $this->form_validation->set_rules('released', 'Released date', 'required');
        $this->form_validation->set_rules('changelog', 'Changelog', 'required');

        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/menu');
            $this->load->view('products/edit_version', $data);
            $this->load->view('templates/footer');

        } else {
            @chmod(FCPATH . 'version-files/', 0777);

            if (!empty($_FILES['main_file']['name'])) {
                $max_size_possible = get_file_upload_max_size();

                $config['upload_path'] = './version-files/';
                $config['allowed_types'] = '*';
                $config['max_size'] = ($max_size_possible < 0) ? 0 : $max_size_possible;
                $config['encrypt_name'] = true;

                $this->load->library('upload', $config);

                $filetype_main_file = pathinfo($_FILES["main_file"]["name"], PATHINFO_EXTENSION);

                if (!$this->upload->do_upload('main_file') || $filetype_main_file != 'zip') {
                    $error0 = $this->upload->display_errors('', '');
                    $error = (is_array($error0)) ? $error0[0] : $error0;
                    $product = $this->products_model->get_product($this->input->post('product'));
                    $data['product'] = $product['pd_pid'];
                    $data['title'] = 'Edit ' . $product['pd_name'] . ' version';
                    $data['version'] = $this->products_model->get_version_by_vid($this->input->post('vid'), true);
                    $this->session->set_flashdata('upload_status_main', array(
                        'type' => "danger",
                        'message' => "Main files (.zip) was not uploaded. " . $error,
                    ));
                    $this->load->view('templates/header', $data);
                    $this->load->view('templates/menu');
                    $this->load->view('products/edit_version', $data);
                    $this->load->view('templates/footer');
                } else {
                    $main_filename = $this->upload->data('file_name');
                }
                unset($this->upload);
            } else {
                $main_filename = null;
            }

            if (!empty($_FILES['sql_file']['name'])) {
                $max_size_possible = get_file_upload_max_size();

                $config1['upload_path'] = './version-files/';
                $config1['allowed_types'] = '*';
                $config1['max_size'] = ($max_size_possible < 0) ? 0 : $max_size_possible;
                $config1['encrypt_name'] = true;

                $filetype_sql_file = pathinfo($_FILES["sql_file"]["name"], PATHINFO_EXTENSION);
                $this->load->library('upload', $config1);
                if (!$this->upload->do_upload('sql_file') || $filetype_sql_file != 'sql') {
                    $error0 = $this->upload->display_errors('', '');
                    $error = (is_array($error0)) ? $error0[0] : $error0;
                    $product = $this->products_model->get_product($this->input->post('product'));
                    $data['title'] = 'Edit ' . $product['pd_name'] . ' version';
                    $data['version'] = $this->products_model->get_version_by_vid($this->input->post('vid'), true);
                    $data['product'] = $product['pd_pid'];
                    $this->session->set_flashdata('upload_status_sql', array(
                        'type' => "danger",
                        'message' => "SQL file (.sql) was not uploaded. " . $error,
                    ));
                    $this->load->view('templates/header', $data);
                    $this->load->view('templates/menu');
                    $this->load->view('products/edit_version', $data);
                    $this->load->view('templates/footer');
                } else {
                    $sql_filename = $this->upload->data('file_name');
                }
            } else {
                $sql_filename = null;
            }

            if ($this->products_model->edit_version($main_filename, $sql_filename)) {
                $this->user_model->add_log('Version <b>' . s_t($this->input->post('version_old')) . '</b> of product <b>' . s_t($product['pd_name']) . '</b> edited by ' . s_t(ucfirst($this->session->userdata('fullname'))) . '.');
                $this->session->set_flashdata('product_status', array(
                    'type' => "success",
                    'message' => "Version (" . s_t($this->input->post('version_old')) . ") was successfully edited.",
                ));
                $this->session->set_flashdata('product_id', $this->input->post('product'));
                redirect('products/versions');
            } else {
                $this->session->set_flashdata('product_status', array(
                    'type' => "danger",
                    'message' => "An error occured, version was not edited.",
                ));
                $this->session->set_flashdata('product', $this->input->post('product'));
                redirect('products/versions/edit');
            }
        }
    }

    public function view_versions()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('users/login');
        }
        $has_product = $this->session->flashdata('product_id');
        if (!empty($has_product)) {
            $product_id = $has_product;
        } else {
            $product_id = $this->input->post('product');
        }
        if (empty($product_id)) {
            redirect('products');
        }
        $product = $this->products_model->get_product($product_id);
        $data['title'] = 'Manage ' . $product['pd_name'] . ' versions';
        $data['productid'] = $product_id;
        $data['versions'] = $this->products_model->get_version($product_id);
        $this->load->view('templates/header', $data);
        $this->load->view('templates/menu');
        $this->load->view('products/view_versions', $data);
        $this->load->view('templates/footer');
    }

    public function delete_version()
    {
        $product = $this->products_model->get_product($this->input->post('product'));
        $this->downloads_model->delete_downloads_by_vid($this->input->post('vid'));
        if ($this->products_model->delete_version()) {
            $this->user_model->add_log('<b>' . s_t($product['pd_name']) . '</b> version <b>' . s_t($this->input->post('version')) . '</b> deleted by ' . s_t(ucfirst($this->session->userdata('fullname'))) . '.');
            $this->session->set_flashdata('product_status', array(
                'type' => "success",
                'message' => "Version (" . s_t($this->input->post('version')) . ") was successfully deleted.",
            ));
            $this->session->set_flashdata('product_id', $this->input->post('product'));
            redirect('products/versions');
        } else {
            $this->session->set_flashdata('product_status', array(
                'type' => "danger",
                'message' => "An error occured, Version was not deleted.",
            ));
            $this->session->set_flashdata('product_id', $this->input->post('product'));
            redirect('products/versions');
        }
    }

    public function unpublish_version()
    {
        $product = $this->products_model->get_product($this->input->post('product'));
        if ($this->products_model->unpublish_version()) {
            $this->user_model->add_log('<b>' . s_t($product['pd_name']) . '</b> version <b>' . s_t($this->input->post('version')) . '</b> unpublished by ' . s_t(ucfirst($this->session->userdata('fullname'))) . '.');
            $this->session->set_flashdata('product_status', array(
                'type' => "success",
                'message' => "Version (" . s_t($this->input->post('version')) . ") was successfully unpublished.",
            ));
            $this->session->set_flashdata('product_id', $this->input->post('product'));
            redirect('products/versions');
        } else {
            $this->session->set_flashdata('product_status', array(
                'type' => "danger",
                'message' => "An error occured, Version was not unpublished.",
            ));
            $this->session->set_flashdata('product_id', $this->input->post('product'));
            redirect('products/versions');
        }
    }

    public function publish_version()
    {
        $product = $this->products_model->get_product($this->input->post('product'));
        if ($this->products_model->publish_version()) {
            $this->user_model->add_log('<b>' . s_t($product['pd_name']) . '</b> version <b>' . s_t($this->input->post('version')) . '</b> published by ' . s_t(ucfirst($this->session->userdata('fullname'))) . '.');
            $this->session->set_flashdata('product_status', array(
                'type' => "success",
                'message' => "Version (" . s_t($this->input->post('version')) . ") was successfully published.",
            ));
            $this->session->set_flashdata('product_id', $this->input->post('product'));
            redirect('products/versions');
        } else {
            $this->session->set_flashdata('product_status', array(
                'type' => "danger",
                'message' => "An error occured, Version was not published.",
            ));
            $this->session->set_flashdata('product_id', $this->input->post('product'));
            redirect('products/versions');
        }
    }

    public function download_version_files()
    {
        $this->load->helper('download');
        $product = $this->products_model->get_product($this->input->post('product'));
        $version = $this->products_model->get_version_by_vid($this->input->post('vid'), true);
        if (!$version) {
            $this->session->set_flashdata('product_status', array(
                'type' => "danger",
                'message' => "Version was not found.",
            ));
            $this->session->set_flashdata('product_id', $this->input->post('product'));
            redirect('products/versions');
        }
        $path_to_update_file = './version-files/' . $version['main_file'];
        if (!file_exists($path_to_update_file)) {
            $this->session->set_flashdata('product_status', array(
                'type' => "danger",
                'message' => "Version main files were not found.",
            ));
            $this->session->set_flashdata('product_id', $this->input->post('product'));
            redirect('products/versions');
        }
        force_download($product['pd_name'] . '_' . $version['version'] . ".zip", file_get_contents($path_to_update_file));
    }

    public function download_version_sql()
    {
        $this->load->helper('download');
        $product = $this->products_model->get_product($this->input->post('product'));
        $version = $this->products_model->get_version_by_vid($this->input->post('vid'), true);
        if (!$version) {
            $this->session->set_flashdata('product_status', array(
                'type' => "danger",
                'message' => "Version was not found.",
            ));
            $this->session->set_flashdata('product_id', $this->input->post('product'));
            redirect('products/versions');
        }

        if (empty($version['sql_file'])) {
            $this->session->set_flashdata('product_status', array(
                'type' => "danger",
                'message' => "Version has no sql file.",
            ));
            $this->session->set_flashdata('product_id', $this->input->post('product'));
            redirect('products/versions');
        }

        $path_to_update_sql = './version-files/' . $version['sql_file'];
        if (!file_exists($path_to_update_sql)) {
            $this->session->set_flashdata('product_status', array(
                'type' => "danger",
                'message' => "Version sql file was not found.",
            ));
            $this->session->set_flashdata('product_id', $this->input->post('product'));
            redirect('products/versions');
        }
        force_download($product['pd_name'] . '_' . $version['version'] . ".sql", file_get_contents($path_to_update_sql));
    }
}

