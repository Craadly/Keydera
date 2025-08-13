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

class Pages extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function view($page = 'dashboard')
    {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_userdata('redirectToCurrent', current_url());
            redirect('users/login');
        }
        if (!file_exists(APPPATH . 'views/pages/' . $page . '.php')) {
            $this->render_404();
        } else {
            $this->dashboard();
        }
    }

    public function dashboard()
    {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_userdata('redirectToCurrent', current_url());
            redirect('users/login');
        }

        $today = date('Y-m-d');

        $data['title'] = 'Dashboard';
        $data['license_count'] = $this->licenses_model->get_licenses_count();
        $data['license_count_active'] = $this->licenses_model->get_active_licenses_count();
        $data['product_count'] = $this->products_model->get_products_count();
        $data['product_count_active'] = $this->products_model->get_active_products_count();
        $data['installation_count'] = $this->activations_model->get_activations_count();
        $data['installation_count_active'] = $this->activations_model->get_active_activations_count();
        $data['download_count'] = $this->downloads_model->get_downloads_count();
        $data['download_count_today'] = $this->downloads_model->get_update_downloads_based_on_date($today, $today . " 23:59:59");
        $data['api_call_count'] = $this->user_model->get_api_call_count();
        $data['api_call_count_today'] = $this->user_model->get_api_call_count($today);
        $data['activity_logs'] = $this->user_model->get_activity_log();

        if (rand(1, 30) == 30) {
            $data['show_message'] = true;
        } else {
            $data['show_message'] = false;
        }

        $this->load->view('templates/header', $data);
        $this->load->view('templates/menu');
        $this->load->view('pages/dashboard', $data);
        $this->load->view('templates/footer');
    }

    public function check_updates()
    {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_userdata('redirectToCurrent', current_url());
            redirect('users/login');
        }
        $api = new L1c3n5380x4P1();

        $title = 'Check for Updates';

        $this->form_validation->set_rules('update_id', 'Update ID', 'trim');
        $this->form_validation->set_rules('has_sql', 'Has SQL?', 'trim');
        $this->form_validation->set_rules('version', 'Version', 'trim');

        if ($this->form_validation->run() == true) {
            $data = array(
                'title' => $title,
                'update_data' => $api->check_update(),
                'show_loader' => true,
                'lbapi' => $api,
                'update_id' => strip_tags($this->input->post('update_id')),
                'has_sql' => strip_tags($this->input->post('has_sql')),
                'version' => strip_tags($this->input->post('version')),
            );
        } else {
            $data = array(
                'title' => $title,
                'update_data' => $api->check_update(),
                'show_loader' => false,
                'lbapi' => $api,
                'update_id' => null,
                'has_sql' => null,
                'version' => null,
            );
        }

        echo $this->load->view('templates/header', $data, true);
        echo $this->load->view('templates/menu', '', true);
        echo $this->load->view('pages/check_updates', $data, true);
        echo $this->load->view('templates/footer', '', true);
    }

    public function verify_license()
    {
        $api = new L1c3n5380x4P1();
        $res = $api->v3r1phy_l1c3n53();

        $data['title'] = "Verify License";
        $data['lb_status'] = $res['status'];
        $data['lb_msg0'] = $res['message'];
        $data['lb_version'] = $api->get_current_version();
        $this->form_validation->set_rules('license', 'License Code', 'required|trim');
        $this->form_validation->set_rules('client', 'Client Name', 'required|trim');

        if ($this->form_validation->run() == true) {
            $license_code = strip_tags($this->input->post('license'));
            $client_name = strip_tags($this->input->post('client'));

            $activate_response = $api->activate_license($license_code, $client_name);
            if (empty($activate_response)) {
                $msg = 'Server is unavailable, please try again later.';
            } else {
                $msg = $activate_response['message'];
            }
            if ($activate_response['status'] != true) {
                $data['lb_msg'] = $msg;
                $this->load->view('templates/header', $data);
                $this->load->view('pages/verify_license', $data);
            } else {
                redirect('dashboard');
            }
        } else {
            if ($res['status'] != true) {
                $this->load->view('templates/header', $data);
                $this->load->view('pages/verify_license', $data);
            } else {
                redirect('dashboard');
            }
        }
    }

    public function php_obfuscator()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('users/login');
        }

        $data['title'] = 'PHP Obfuscator';
        $this->form_validation->set_rules('php_source_code', 'PHP Source Code',
            array(
                array('php_source_code',
                    function ($str) {
                        if (empty($str)) {
                            return false;
                        } else {
                            if ((strpos($this->input->post('php_source_code'), '<?php') !== false) || (strpos($this->input->post('php_source_code'), '<?') !== false)) {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    },
                ),
            )
        );
        $this->form_validation->set_message('php_source_code', 'Please enter {field} only.');
        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/menu');
            $this->load->view('pages/php_obfuscator', $data);
            $this->load->view('templates/footer');
        } else {
            if ($this->input->post('minify_type') == 'html') {
                $minified_code = minify_html($this->input->post('php_source_code'));
            } else {
                $minified_code = $this->input->post('php_source_code');
            }
            $initApp = new L1c3n5380x4P1();
            $php_obfuscate_res = $initApp->php_08phu5c473($minified_code);
            if (!empty($php_obfuscate_res['obfuscated'])) {
                $data['php_source_code'] = base64_decode($php_obfuscate_res['obfuscated']);
            } else {
                $data['php_source_code'] = $php_obfuscate_res['message'];
            }
            $this->load->view('templates/header', $data);
            $this->load->view('templates/menu');
            $this->load->view('pages/php_obfuscator', $data);
            $this->load->view('templates/footer');
        }
    }

    public function render_404()
    {
        $data['title'] = 'Page not found';
        $this->load->view('templates/header', $data);
        $this->load->view('errors/render_404');
        $this->load->view('templates/footer');
    }
}

