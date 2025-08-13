<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Keydera
 *
 * Keydera is a full-fledged licenser and updates manager.
 *
 * @package Keydera
 * @author CodeMonks
 * @see https://keydera.app
 * @link https://codecanyon.net/item/keydera-php-license-and-updates-manager/22351237
 * @license https://codecanyon.net/licenses/standard (Regular or Extended License)
 * @copyright Copyright (c) 2023, CodeMonks. (https://www.keydera.app)
 * @version 1.6.4
 */

class Activations extends CI_Controller
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
        $data['title'] = 'View Activations';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/menu');
        $this->load->view('activations/index', $data);
        $this->load->view('templates/footer');
    }

    public function get_activations()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('users/login');
        }

        define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
        if (!IS_AJAX) {header("HTTP/1.0 403 Forbidden");die('Direct access not allowed.');}

        $columns = array(
            1 => 'pi_product',
            2 => 'pi_client',
            3 => 'pi_license_code',
            4 => 'pi_url',
            5 => 'pi_ip',
            6 => 'pi_date',
            7 => 'pi_isvalid',
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $totalData = $this->activations_model->get_activations_count();
        $totalFiltered = $totalData;

        if (empty($this->input->post('search')['value'])) {
            $posts = $this->activations_model->get_activations($limit, $start, $order, $dir);
        } else {
            $search = $this->input->post('search')['value'];
            $posts = $this->activations_model->activation_search($limit, $start, $search, $order, $dir);
            $totalFiltered = $this->activations_model->activation_search_count($search);
        }

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData = null;
                $product = $this->products_model->get_product($post->pi_product);
                if ($post->pi_client != null) {
                    $client = $post->pi_client;
                } else {
                    $client = "-";
                }
                $originalDate = $post->pi_date;
                $newDate = date($this->config->item('datetime_format_table'), strtotime($originalDate));
                if (($post->pi_isvalid == 1) && ($post->pi_isactive == 1)) {
                    $is_valid = "Active";
                    $is_valid_typ = "success";
                    $is_valid_tooltip = "Activation is valid and currently active.";
                } elseif (($post->pi_isvalid == 1) && ($post->pi_isactive == 0)) {
                    $is_valid = "Inactive";
                    $is_valid_typ = "primary";
                    $is_valid_tooltip = "Activation is valid but is no longer active.";
                } else {
                    $is_valid = "Invalid";
                    $is_valid_typ = "danger";
                    $is_valid_tooltip = "Activation attempt was invalid.";
                }
                $nestedData[] = "<center><input class='is-checkradio is-cr-t delete_activation_checkbox is-danger is-small' type='checkbox' id='activations_cr_t_" . $post->pi_id . "' value=" . urlencode($post->pi_iid) . "><label for='activations_cr_t_" . $post->pi_id . "'></label></center>";
                $nestedData[] = "<b class='copy_to_clipboard tooltip is-tooltip-right' data-tooltip='ID: " . $post->pi_product . " (click to copy)' data-clipboard-text='" . $post->pi_product . "'>" . $product['pd_name'] . "</b>";
                $nestedData[] = $client;
                $nestedData[] = $post->pi_license_code;
                $nestedData[] = "<a href='" . $post->pi_url . "' class='tooltip' data-tooltip='" . $post->pi_url . "' rel='noreferrer'>" . parse_url($post->pi_url, PHP_URL_HOST) . "&nbsp;<small><i class='fas fa-external-link-alt'></i></small></a>";
                $nestedData[] = $post->pi_ip;
                $nestedData[] = $newDate;
                $nestedData[] = "<center><span class='tag is-" . $is_valid_typ . " is-small is-rounded tooltip' data-tooltip='" . $is_valid_tooltip . "'>" . $is_valid . "</span></center>";

                $form_buttons = "<div class='buttons is-centered'>";
                $title_or_tooltip = (strtolower(KEYDERA_THEME) == "material") ? "title" : "data-tooltip";

                if (($post->pi_isactive != 0) && ($post->pi_isvalid != 0)) {
                    $hidden = array('iid' => $post->pi_iid);
                    $form_buttons .= form_open('/activations/deactivate', '', $hidden) . "<button type='submit' class='button is-warning is-small tooltip' style='padding-top: 0px;padding-bottom: 0px;' " . $title_or_tooltip . "='Mark as Inactive'><i class='fas fa-times'></i></button></form>&nbsp;&nbsp;";
                } elseif ($post->pi_isvalid != 0) {
                    $hidden = array('iid' => $post->pi_iid);
                    $form_buttons .= form_open('/activations/activate', '', $hidden) . "<button type='submit' class='button is-warning is-small tooltip' style='padding-top: 0px;padding-bottom: 0px;' " . $title_or_tooltip . "='Mark as Active'><i class='fas fa-check'></i></button></form>&nbsp;&nbsp;";
                } else {
                    $form_buttons .= "<form><button type='button' class='button is-warning is-small' style='padding-top: 0px;padding-bottom: 0px;' disabled><i class='fas fa-ban'></i></button></form>&nbsp;&nbsp;";
                }

                $hidden = array('iid' => $post->pi_iid);
                $js = 'id="delete_form_' . $post->pi_id . '"';

                $form_buttons .= form_open('/activations/delete', $js, $hidden) . '<button type="button" data-id="' . $post->pi_id . '" data-title=" activation" data-body="Please note that this action cannot be undone." class="button with-delete-confirmation is-danger is-small" style="padding-top: 0px;padding-bottom: 0px;" title="Delete Activation"><i class="fa fa-trash"></i>&nbsp;Delete</button></form>';
                $form_buttons .= "</div>";

                $nestedData[] = $form_buttons;

                $data[] = $nestedData;

            }
        }

        $json_data = array(
            "draw" => intval($this->input->post('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        );

        echo json_encode($json_data);
    }

    public function delete()
    {
        $activation = $this->activations_model->get_installation($this->input->post('iid'));
        $product = $this->products_model->get_product($activation['pi_product']);
        if ($this->activations_model->delete_installation()) {
            $this->user_model->add_log('Client <b>' . s_t($activation['pi_client']) . '\'s</b> activation of product <b>' . s_t($product['pd_name']) . '</b> using license <b>' . s_t($activation['pi_license_code']) . '</b> on URL <a href="' . s_t($activation['pi_url']) . '">' . s_t(remove_http_www($activation['pi_url'])) . '</a> deleted by ' . s_t(ucfirst($this->session->userdata('fullname'))) . '.');
            $this->session->set_flashdata('activations_status', array(
                'type' => "success",
                'message' => "Activation log was successfully deleted.",
            ));
            redirect('activations');
        } else {
            $this->session->set_flashdata('activations_status', array(
                'type' => "danger",
                'message' => "An error occured, Activation log was not deleted.",
            ));
            redirect('activations');
        }
    }

    public function delete_selected()
    {
        define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
        if (!IS_AJAX) {header("HTTP/1.0 403 Forbidden");die('Direct access not allowed.');}

        if ($this->input->post('delete_activations_checkbox')) {
            $id = $this->input->post('delete_activations_checkbox');
            for ($count = 0; $count < count($id); $count++) {
                $activation = $this->activations_model->get_installation(urldecode($id[$count]));
                $product = $this->products_model->get_product($activation['pi_product']);
                if ($this->activations_model->delete_installation(urldecode($id[$count]))) {
                    $this->user_model->add_log('Client <b>' . s_t($activation['pi_client']) . '\'s</b> activation of product <b>' . s_t($product['pd_name']) . '</b> using license <b>' . s_t($activation['pi_license_code']) . '</b> on URL <a href="' . s_t($activation['pi_url']) . '">' . s_t(remove_http_www($activation['pi_url'])) . '</a> deleted by ' . s_t(ucfirst($this->session->userdata('fullname'))) . '.');
                }
            }
        }
    }

    public function activate()
    {
        if ($this->activations_model->activate_activation()) {
            $activation = $this->activations_model->get_installation($this->input->post('iid'));
            $product = $this->products_model->get_product($activation['pi_product']);
            $this->user_model->add_log('Client <b>' . s_t($activation['pi_client']) . '\'s</b> activation of product <b>' . s_t($product['pd_name']) . '</b> using license <b>' . s_t($activation['pi_license_code']) . '</b> on URL <a href="' . s_t($activation['pi_url']) . '">' . s_t(remove_http_www($activation['pi_url'])) . '</a> marked as active by ' . s_t(ucfirst($this->session->userdata('fullname'))) . '.');
            $this->session->set_flashdata('activations_status', array(
                'type' => "success",
                'message' => "Activation was successfully marked as active.",
            ));
            redirect('activations');
        } else {
            $this->session->set_flashdata('activations_status', array(
                'type' => "danger",
                'message' => "An error occured, Activation was not marked as active.",
            ));
            redirect('activations');
        }
    }

    public function deactivate()
    {
        if ($this->activations_model->deactivate_activation()) {
            $activation = $this->activations_model->get_installation($this->input->post('iid'));
            $product = $this->products_model->get_product($activation['pi_product']);
            $this->user_model->add_log('Client <b>' . s_t($activation['pi_client']) . '\'s</b> activation of product <b>' . s_t($product['pd_name']) . '</b> using license <b>' . s_t($activation['pi_license_code']) . '</b> on URL <a href="' . s_t($activation['pi_url']) . '">' . s_t(remove_http_www($activation['pi_url'])) . '</a> marked as inactive by ' . s_t(ucfirst($this->session->userdata('fullname'))) . '.');
            $this->session->set_flashdata('activations_status', array(
                'type' => "success",
                'message' => "Activation was successfully marked as inactive.",
            ));
            redirect('activations');
        } else {
            $this->session->set_flashdata('activations_status', array(
                'type' => "danger",
                'message' => "An error occured, Activation was not marked as inactive.",
            ));
            redirect('activations');
        }
    }
}

