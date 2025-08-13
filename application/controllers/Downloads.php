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

class Downloads extends CI_Controller
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
        $data['title'] = 'View Update Downloads';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/menu');
        $this->load->view('downloads/index', $data);
        $this->load->view('templates/footer');
    }

    public function get_update_downloads()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('users/login');
        }

        define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
        if (!IS_AJAX) {header("HTTP/1.0 403 Forbidden");die('Direct access not allowed.');}

        $columns = array(
            1 => 'product',
            2 => 'vid',
            3 => 'url',
            4 => 'ip',
            5 => 'download_date',
            6 => 'isvalid',
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $totalData = $this->downloads_model->get_downloads_count();

        $totalFiltered = $totalData;

        if (empty($this->input->post('search')['value'])) {
            $posts = $this->downloads_model->get_downloads($limit, $start, $order, $dir);
        } else {
            $search = $this->input->post('search')['value'];
            $posts = $this->downloads_model->download_search($limit, $start, $search, $order, $dir);
            $totalFiltered = $this->downloads_model->downloads_search_count($search);
        }

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData = null;
                $product = $this->products_model->get_product($post->product);
                $version = $this->products_model->get_version_by_vid($post->vid, true);
                $originalDate = $post->download_date;
                $newDate = date($this->config->item('datetime_format'), strtotime($originalDate));

                if ($post->isvalid == 1) {
                    $is_valid = "Valid";
                    $is_valid_typ = "success";
                    $is_valid_tooltip = "Update files were successfully served.";
                } else {
                    $is_valid = "Invalid";
                    $is_valid_typ = "danger";
                    $is_valid_tooltip = "Update download attempt was invalid.";
                }
                $nestedData[] = "<center><input class='is-checkradio is-cr-t delete_download_checkbox is-danger is-small' type='checkbox' id='downloads_cr_t_" . $post->id . "' value=" . urlencode($post->did) . "><label for='downloads_cr_t_" . $post->id . "'></label></center>";
                $nestedData[] = "<b class='copy_to_clipboard tooltip is-tooltip-right' data-tooltip='ID: " . $post->product . " (click to copy)' data-clipboard-text='" . $post->product . "'>" . $product['pd_name'] . "</b>";
                $nestedData[] = $version['version'];
                $nestedData[] = "<a href='" . $post->url . "' class='tooltip' data-tooltip='" . $post->url . "' rel='noreferrer'>" . parse_url($post->url, PHP_URL_HOST) . "&nbsp;<small><i class='fas fa-external-link-alt'></i></small></a>";
                $nestedData[] = $post->ip;
                $nestedData[] = $newDate;
                $nestedData[] = "<center><span class='tag is-" . $is_valid_typ . " is-small is-rounded tooltip' data-tooltip='" . $is_valid_tooltip . "'>" . $is_valid . "</span></center>";

                $form_buttons = "<div class='buttons is-centered'>";

                $hidden = array('did' => $post->did);
                $js = 'id="delete_form_' . $post->id . '"';

                $form_buttons .= form_open('/update_downloads/delete', $js, $hidden) . '<button type="button" data-id="' . $post->id . '" data-title=" download log" data-body="Please note that this action cannot be undone." class="button with-delete-confirmation is-danger is-small" style="padding-top: 0px;padding-bottom: 0px;" title="Delete Download"><i class="fa fa-trash"></i>&nbsp;Delete</button></form>';
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
        if ($this->downloads_model->delete_download()) {
            $this->session->set_flashdata('update_downloads_status', array(
                'type' => "success",
                'message' => "Update download log was successfully deleted.",
            ));
            redirect('update_downloads');
        } else {
            $this->session->set_flashdata('update_downloads_status', array(
                'type' => "danger",
                'message' => "An error occured, Update download log was not deleted.",
            ));
            redirect('update_downloads');
        }
    }

    public function delete_selected()
    {
        define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
        if (!IS_AJAX) {header("HTTP/1.0 403 Forbidden");die('Direct access not allowed.');}
        if ($this->input->post('delete_downloads_checkbox')) {
            $id = $this->input->post('delete_downloads_checkbox');
            for ($count = 0; $count < count($id); $count++) {
                $this->downloads_model->delete_download(urldecode($id[$count]));
            }
        }
    }
}

