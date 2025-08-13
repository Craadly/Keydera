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

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require APPPATH . 'third_party/PHPMailer/src/Exception.php';
require APPPATH . 'third_party/PHPMailer/src/PHPMailer.php';
require APPPATH . 'third_party/PHPMailer/src/SMTP.php';

class Licenses extends CI_Controller
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
        $data['title'] = 'Manage Licenses';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/menu');
        $this->load->view('licenses/index', $data);
        $this->load->view('templates/footer');
    }

    public function get_licenses()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('users/login');
        }

        define('IS_AJAX',
            isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
        );
        if (!IS_AJAX) {header("HTTP/1.0 403 Forbidden");die('Direct access not allowed.');}

        $columns = array(
            1 => 'license_code',
            2 => 'pid',
            3 => 'client',
            4 => 'added_on',
            5 => 'uses_left',
            7 => 'validity',
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $totalData = $this->licenses_model->get_licenses_count();
        $totalFiltered = $totalData;

        if (empty($this->input->post('search')['value'])) {
            $posts = $this->licenses_model->get_licenses($limit, $start, $order, $dir);
        } else {
            $search = $this->input->post('search')['value'];
            $posts = $this->licenses_model->license_search($limit, $start, $search, $order, $dir);
            $totalFiltered = $this->licenses_model->license_search_count($search);
        }

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData = null;
                $product = $this->products_model->get_product($post->pid);
                if ($post->client != null) {
                    $client = $post->client;
                } else {
                    $client = "-";
                }
                $originalDate = $post->added_on;
                $newDate = date($this->config->item('datetime_format_table'), strtotime($originalDate));
                $originalDateExpiration = $post->expiry;
                $newDateExpiration = date($this->config->item('datetime_format_table'), ($originalDateExpiration) ? strtotime($originalDateExpiration) : null);
                $current_activations = $this->activations_model->get_activation_by_license($post->license_code);
                $current_activations_active = $this->activations_model->get_activation_by_license_active($post->license_code);
                $licenses_left0 = $post->uses - $current_activations;
                if ($licenses_left0 > 0) {
                    $licenses_left = str_return_s(" use", $licenses_left0, "s", 1);
                    $licenses_left_raw = $licenses_left0;
                } elseif ($post->uses == null) {
                    $licenses_left0 = null;
                    $licenses_left = '∞ uses';
                    $licenses_left_raw = 'Unlimited';
                } else {
                    $licenses_left0 = 0;
                    $licenses_left = 'no uses';
                    $licenses_left_raw = 0;
                }
                $parallel_licenses_left0 = $post->parallel_uses - $current_activations_active;
                if ($parallel_licenses_left0 > 0) {
                    $parallel_licenses_left = $parallel_licenses_left0 . ' parallel';
                } elseif ($post->parallel_uses == null) {
                    $parallel_licenses_left0 = null;
                    $parallel_licenses_left = '∞ parallel';
                } else {
                    $parallel_licenses_left0 = 0;
                    $parallel_licenses_left = 'no parallel';
                }
                if ($post->validity == 1) {
                    if (($licenses_left0 > 0) || ($post->uses == null)) {
                        if (($parallel_licenses_left0 > 0) || ($post->parallel_uses == null)) {
                            $is_valid = "Valid";
                            $is_valid_typ = "success";
                            $is_valid_tooltip_multiline = false;
                            $is_valid_tooltip = "License is valid and can be used for future activations.";
                            $today = date('Y-m-d H:i:s');
                            if (!empty($post->expiry)) {
                                $expiry_date = $post->expiry;
                                if ($today >= $expiry_date) {
                                    $is_valid = "Invalid";
                                    $is_valid_typ = "danger";
                                    $is_valid_tooltip_multiline = true;
                                    $is_valid_tooltip = "License has expired and cannot be used for future activations.";
                                }
                            }
                            if (!empty($post->expiry_days)) {
                                $expiry_days = $post->expiry_days;
                                $oldest_activation = $this->activations_model->get_oldest_activation_by_license($post->license_code);
                                if (!empty($oldest_activation)) {
                                    $oldest_activation_date = new DateTime($oldest_activation['pi_date']);
                                    $date_today = new DateTime($today);
                                    $days_diff = $date_today->diff($oldest_activation_date)->format("%a");
                                    if ($days_diff >= $expiry_days) {
                                        $is_valid = "Invalid";
                                        $is_valid_typ = "danger";
                                        $is_valid_tooltip_multiline = true;
                                        $is_valid_tooltip = "License has expired and cannot be used for future activations.";
                                    }
                                }
                            }
                        } else {
                            $is_valid = "Invalid";
                            $is_valid_typ = "danger";
                            $is_valid_tooltip_multiline = true;
                            $is_valid_tooltip = "Parallel uses exhausted therefore it can&#39;t be used for future activations.";
                        }
                    } else {
                        $is_valid = "Invalid";
                        $is_valid_typ = "danger";
                        $is_valid_tooltip_multiline = true;
                        $is_valid_tooltip = "License uses exhausted therefore it can&#39;t be used for future activations.";
                    }
                } else {
                    $is_valid = "Blocked";
                    $is_valid_typ = "danger";
                    $is_valid_tooltip = "License is blocked and cannot be used for future activations.";
                }
                $has_activations = $this->activations_model->get_activation($post->license_code, true);
                if ($has_activations) {
                    $size_activations = sizeof($has_activations);
                    $is_activated = "Active";
                    $is_activated_typ = (strtolower(KEYDERA_THEME) == "flat") ? "info" : "link";
                    $is_activated_tooltip = "License is currently active on " . $size_activations . " domain(s).";
                } else {
                    $is_activated = "Inactive";
                    $is_activated_typ = (strtolower(KEYDERA_THEME) == "material") ? "info" : ((strtolower(KEYDERA_THEME) == "flat") ? "link" : "primary");
                    $is_activated_tooltip = "License is currently not active on any domain.";
                }
                $nestedData[] = "<center><input class='is-checkradio is-cr-t delete_license_checkbox is-danger is-small' type='checkbox' id='licenses_cr_t_" . $post->id . "' value=" . urlencode($post->license_code) . "><label for='licenses_cr_t_" . $post->id . "'></label></center>";
                $nestedData[] = "<b>" . $post->license_code . "</b>";
                $nestedData[] = "<span class='copy_to_clipboard tooltip is-tooltip-right' data-tooltip='ID: " . $product['pd_pid'] . " (click to copy)' data-clipboard-text='" . $product['pd_pid'] . "'>" . $product['pd_name'] . "</span>";
                $nestedData[] = $client;
                $nestedData[] = $newDate;
                $nestedData[] = $licenses_left . " (" . $parallel_licenses_left . ") ";
                $nestedData[] = "<center><span class='tag is-" . $is_activated_typ . " is-small is-rounded tooltip' data-tooltip='" . $is_activated_tooltip . "'>" . $is_activated . "</span></center>";
                $nestedData[] = "<center><span class='tag is-" . $is_valid_typ . " is-small is-rounded tooltip' data-tooltip='" . $is_valid_tooltip . "'>" . $is_valid . "</span></center>";

                $form_buttons = "<div class='buttons is-centered'>";
                $title_or_tooltip = (strtolower(KEYDERA_THEME) == "material") ? "title" : "data-tooltip";

                if ($post->validity != 0) {
                    $hidden = array('license' => $post->license_code);
                    $form_buttons .= form_open('/licenses/block', '', $hidden) . "<button type='submit' class='button is-warning is-small tooltip' style='padding-top: 0px;padding-bottom: 0px;' " . $title_or_tooltip . "='Block License'><i class='fa fa-lock'></i></button></form>&nbsp;&nbsp;";
                } else {
                    $hidden = array('license' => $post->license_code);
                    $form_buttons .= form_open('/licenses/unblock', '', $hidden) . "<button type='submit' class='button is-warning is-small tooltip' style='padding-top: 0px;padding-bottom: 0px;' " . $title_or_tooltip . "='Unblock License'><i class='fa fa-unlock'></i></button></form>&nbsp;&nbsp;";
                }
                $hidden = array('license_code' => $post->license_code);
                $form_buttons .= form_open('/licenses/edit', '', $hidden) . "<button type='submit' class='button is-success is-small tooltip' style='padding-top: 0px;padding-bottom: 0px;' " . $title_or_tooltip . "='Edit License'><i class='fa fa-edit'></i></button></form>&nbsp;&nbsp;";
                $hidden = array('license' => $post->license_code);
                $js = 'id="email_license_form_' . $post->id . '"';
                $form_buttons .= form_open('/licenses/email_license', $js, $hidden) . '<button type="button" data-id="' . $post->id . '" data-license="' . $post->license_code . '" data-client="' . (($post->client) ? ('Name: <b>' . ucwords($post->client) . '</b> <br>') : null) . '" data-email="' . $post->email . '" data-product="' . $product['pd_name'] . '" data-uses="' . $licenses_left_raw . '" data-expiration="' . (($post->expiry) ? ('<br> License Expiration: <b>' . $newDateExpiration . '</b>') : null) . '" class="button with-email-confirmation is-link is-small tooltip is-tooltip-left" style="padding-top: 0px;padding-bottom: 0px;" ' . $title_or_tooltip . '="Email License Details"><i class="fa fa-envelope"></i></button></form>&nbsp;&nbsp;';
                $hidden = array('license' => $post->license_code);
                $js = 'id="delete_form_' . $post->id . '"';
                $form_buttons .= form_open('/licenses/delete', $js, $hidden) . '<button type="button" data-id="' . $post->id . '" data-title="license" data-body="Please note that all of the license <b>' . $post->license_code . '</b>\'s relevant records like (activation logs) will also be permanently deleted." class="button with-delete-confirmation is-danger is-small tooltip is-tooltip-left" style="padding-top: 0px;padding-bottom: 0px;" ' . $title_or_tooltip . '="Delete License"><i class="fa fa-trash"></i></button></form>';
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

    public function create()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('users/login');
        }

        if ($this->licenses_model->check_license_exists($this->input->post('license'))) {
            $this->session->set_flashdata('license_status', array(
                'type' => "danger",
                'message' => "License code already exists, please recheck.",
            ));
            redirect('licenses/create');
        }

        $data['title'] = 'Create new license';
        $this->form_validation->set_rules('license', 'License code', 'required');
        $this->form_validation->set_rules('email', 'Client email', 'valid_email');
        $this->form_validation->set_rules('uses', 'Uses limit', 'integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('parallel_uses', 'Parallel uses', 'integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('expiry_days', 'Expiry days', 'integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('product', 'Product', 'required');
        $this->form_validation->set_rules('ips', 'Licensed IP addresses',
            array(
                array('ips_check',
                    function ($str) {
                        if (empty($str)) {
                            return true;
                        } else {
                            if (validate_ips($str)) {
                                return true;
                            } else {
                                return false;
                            }
                        }

                    },
                ),
            )
        );
        $this->form_validation->set_rules('domains', 'Licensed domains',
            array(
                array('domains_check',
                    function ($str) {
                        if (empty($str)) {
                            return true;
                        } else {
                            $ytr = remove_http_www_bulk($str);
                            if (validate_domains($ytr)) {
                                return $ytr;
                            } else {
                                return false;
                            }
                        }
                    },
                ),
            )
        );
        $this->form_validation->set_message('ips_check', '{field} are incorrect, please check.');
        $this->form_validation->set_message('domains_check', '{field} are incorrect, please check.');
        $this->load->helper('license_helper');
        $data['created_license'] = gen_code($this->user_model->get_config_from_db('license_code_format'));
        $data['products'] = $this->products_model->get_product();
        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/menu');
            $this->load->view('licenses/create', $data);
            $this->load->view('templates/footer');
        } else {
            if ($this->licenses_model->create_license()) {
                $product = $this->products_model->get_product(strip_tags(trim((string) $this->input->post('product'))));
                $this->user_model->add_log('New ' . s_t($product['pd_name']) . ' license <b>' . s_t($this->input->post('license')) . '</b> added by ' . s_t(ucfirst($this->session->userdata('fullname'))) . '.');
                $this->session->set_flashdata('license_status', array(
                    'type' => "success",
                    'message' => "License (" . s_t($this->input->post('license')) . ") was successfully added.",
                ));
                redirect('licenses');
            } else {
                $this->session->set_flashdata('license_status', array(
                    'type' => "danger",
                    'message' => "An error occured, Please recheck entered values. License was not added.",
                ));
                redirect('licenses/create');
            }
        }
    }

    public function edit()
    {
        if (empty($this->input->post('license_code'))) {
            redirect('licenses');
        }

        $data['title'] = 'Edit License';
        $data['license'] = $this->licenses_model->get_license($this->input->post('license_code'));
        $data['products'] = $this->products_model->get_product();
        $this->form_validation->set_rules('product', 'Product', 'required');
        $this->form_validation->set_rules('email', 'Client email', 'valid_email');
        $this->form_validation->set_rules('uses', 'Uses limit', 'integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('parallel_uses', 'Parallel uses', 'integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('expiry_days', 'Expiry days', 'integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('ips', 'Licensed IP addresses',
            array(
                array('ips_check',
                    function ($str) {
                        if (empty($str)) {
                            return true;
                        } else {
                            if (validate_ips($str)) {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    },
                ),
            )
        );
        $this->form_validation->set_rules('domains', 'Licensed domains',
            array(
                array('domains_check',
                    function ($str) {
                        if (empty($str)) {
                            return true;
                        } else {
                            $ytr = remove_http_www_bulk($str);
                            if (validate_domains($ytr)) {
                                return $ytr;
                            } else {
                                return false;
                            }
                        }
                    },
                ),
            )
        );
        $this->form_validation->set_message('ips_check', '{field} are incorrect, please check.');
        $this->form_validation->set_message('domains_check', '{field} are incorrect, please check.');
        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/menu');
            $this->load->view('licenses/edit', $data);
            $this->load->view('templates/footer');
        } else {
            if ($this->licenses_model->edit_license()) {
                if (strtolower($this->input->post('client')) != strtolower($this->input->post('old_client'))) {
                    $this->activations_model->change_activation_client($this->input->post('license_code'), $this->input->post('old_client'), $this->input->post('client'));
                }
                $this->user_model->add_log('License <b>' . s_t($this->input->post('license_code')) . '</b> edited by ' . s_t(ucfirst($this->session->userdata('fullname'))) . '.');
                $this->session->set_flashdata('license_status', array(
                    'type' => "success",
                    'message' => "License (" . s_t($this->input->post('license_code')) . ") was successfully updated.",
                ));
                redirect('licenses');
            } else {
                $this->session->set_flashdata('license_status', array(
                    'type' => "danger",
                    'message' => "Please check entered values or you haven't made any changes, License was not updated.",
                ));
                redirect('licenses');
            }
        }
    }

    public function delete()
    {
        $this->activations_model->delete_installation_by_license($this->input->post('license'));
        if ($this->licenses_model->delete_license()) {
            $this->user_model->add_log('License <b>' . s_t($this->input->post('license')) . '</b> deleted by ' . s_t(ucfirst($this->session->userdata('fullname'))) . '.');
            $this->session->set_flashdata('license_status', array(
                'type' => "success",
                'message' => "License (" . s_t($this->input->post('license')) . ") was successfully deleted.",
            ));
            redirect('licenses');
        } else {
            $this->session->set_flashdata('license_status', array(
                'type' => "danger",
                'message' => "An error occured, License was not deleted. Make sure the license does not have any activations.",
            ));
            redirect('licenses');
        }
    }

    public function delete_selected()
    {
        define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
        if (!IS_AJAX) {header("HTTP/1.0 403 Forbidden");die('Direct access not allowed.');}
        if ($this->input->post('delete_licenses_checkbox')) {
            $id = $this->input->post('delete_licenses_checkbox');
            for ($count = 0; $count < count($id); $count++) {
                $this->activations_model->delete_installation_by_license(urldecode($id[$count]));
                if ($this->licenses_model->delete_license(urldecode($id[$count]))) {
                    $this->user_model->add_log('License <b>' . s_t($id[$count]) . '</b> deleted by ' . s_t(ucfirst($this->session->userdata('fullname'))) . '.');
                }
            }
        }
    }

    public function block()
    {
        if ($this->licenses_model->block_license()) {
            $this->user_model->add_log('License <b>' . s_t($this->input->post('license')) . '</b> blocked by ' . s_t(ucfirst($this->session->userdata('fullname'))) . '.');
            $this->session->set_flashdata('license_status', array(
                'type' => "success",
                'message' => "License (" . s_t($this->input->post('license')) . ") was successfully blocked.",
            ));
            redirect('licenses');
        } else {
            $this->session->set_flashdata('license_status', array(
                'type' => "danger",
                'message' => "An error occured, License was not blocked.",
            ));
            redirect('licenses');
        }
    }

    public function unblock()
    {
        if ($this->licenses_model->unblock_license()) {
            $this->user_model->add_log('License <b>' . s_t($this->input->post('license')) . '</b> unblocked by ' . s_t(ucfirst($this->session->userdata('fullname'))) . '.');
            $this->session->set_flashdata('license_status', array(
                'type' => "success",
                'message' => "License (" . s_t($this->input->post('license')) . ") was successfully unblocked.",
            ));
            redirect('licenses');
        } else {
            $this->session->set_flashdata('license_status', array(
                'type' => "danger",
                'message' => "An error occured, License was not unblocked.",
            ));
            redirect('licenses');
        }
    }

    public function email_license()
    {
        $year = date('Y');
        $product = strip_tags(trim((string) $this->input->post('product')));
        $email = strip_tags(trim((string) $this->input->post('email')));
        $subject = strip_tags(trim((string) $this->input->post('subject')));
        $message = clean_html_codes($this->input->post('email_license_details'));
        $mail = new PHPMailer(true);
        try {
            $email_method = $this->user_model->get_config_from_db('email_method');
            $smtp_connection = $this->user_model->get_config_from_db('smtp_connection');
            $smtp_authentication = $this->user_model->get_config_from_db('smtp_authentication');
            $smtp_username = $this->user_model->get_config_from_db('smtp_username');
            $smtp_password = $this->user_model->get_config_from_db('smtp_password', true);
            $smtp_host = $this->user_model->get_config_from_db('smtp_host');
            $smtp_port = $this->user_model->get_config_from_db('smtp_port');

            switch ($email_method) {
                case 'sendmail':
                    $mail->isSendmail();
                    break;
                case 'smtp':
                    $mail->isSMTP();
                    $mail->Host = $smtp_host;
                    $mail->Port = $smtp_port;
                    if ($this->user_model->get_config_from_db('smtp_authentication')) {
                        $mail->SMTPAuth = true;
                        $mail->Username = $smtp_username;
                        $mail->Password = $smtp_password;
                    }
                    if (strtolower($this->user_model->get_config_from_db('smtp_connection')) == 'tls') {
                        $mail->SMTPSecure = 'tls';
                    } elseif (strtolower($this->user_model->get_config_from_db('smtp_connection')) == 'ssl') {
                        $mail->SMTPSecure = 'ssl';
                    } else {
                        $mail->SMTPSecure = '';
                        $mail->SMTPAutoTLS = false;
                    }
                    break;
            }

            $mail->setFrom($this->user_model->get_config_from_db('server_email'));
            $mail->addReplyTo($this->user_model->get_config_from_db('server_email'));
            $mail->addAddress($email);
            $mail->isHTML(true);

            $trans = array(
                "{[year]}" => $year,
                "{[product]}" => $product,
                "{[message]}" => $message);
            $mail_template_raw = <<<'MAIL'
<!DOCTYPE html PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' xmlns:v='urn:schemas-microsoft-com:vml' xmlns:o='urn:schemas-microsoft-com:office:office'>
<head>
<!--[if gte mso 9]><xml> <o:OfficeDocumentSettings> <o:AllowPNG/> <o:PixelsPerInch>96</o:PixelsPerInch> </o:OfficeDocumentSettings> </xml><![endif]-->
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<meta http-equiv='X-UA-Compatible' content='IE=edge'>
<meta name='format-detection' content='date=no'>
<meta name='format-detection' content='telephone=no'>
<title>{[product]}</title>
<style type='text/css'>
	body {
		margin: 0;
		padding: 0;
		-ms-text-size-adjust: 100%;
		-webkit-text-size-adjust: 100%
	}
	table {
		border-spacing: 0
	}
	table td {
		border-collapse: collapse
	}
	.ExternalClass {
		width: 100%
	}
	.ExternalClass,
	.ExternalClass p,
	.ExternalClass span,
	.ExternalClass font,
	.ExternalClass td,
	.ExternalClass div {
		line-height: 100%
	}
	.ReadMsgBody {
		width: 100%;
		background-color: #ebebeb
	}
	table {
		mso-table-lspace: 0pt;
		mso-table-rspace: 0pt
	}
	img {
		-ms-interpolation-mode: bicubic
	}
	.yshortcuts a {
		border-bottom: none !important
	}
	@media screen and (max-width: 599px) {
		.force-row,
		.container {
			width: 100% !important;
			max-width: 100% !important
		}
	}
	@media screen and (max-width: 400px) {
		.container-padding {
			padding-left: 12px !important;
			padding-right: 12px !important
		}
	}
	.ios-footer a {
		color: #aaa !important;
		text-decoration: underline
	}
	a[href^='x-apple-data-detectors:'],
	a[x-apple-data-detectors] {
		color: inherit !important;
		text-decoration: none !important;
		font-size: inherit !important;
		font-family: inherit !important;
		font-weight: inherit !important;
		line-height: inherit !important
	}
</style>
</head>
<body style='margin:0; padding:0;' bgcolor='#F0F0F0' leftmargin='0' topmargin='0' marginwidth='0' marginheight='0'>
<table border='0' width='100%' height='100%' cellpadding='0' cellspacing='0' bgcolor='#F0F0F0'>
	<tr>
		<td align='center' valign='top' bgcolor='#F0F0F0' style='background-color: #F0F0F0;'>
			<br>
			<br>
			<table border='0' width='600' cellpadding='0' cellspacing='0' class='container' style='width:600px;max-width:600px'>
				<tr>
					<td class='container-padding header' align='left' style='font-family:Helvetica, Arial, sans-serif;font-size:24px;font-weight:bold;padding-bottom:18px;color:#DF4726;padding-left:24px;padding-right:24px'>{[product]}</td>
				</tr>
				<tr>
					<td class='container-padding content' align='left' style='padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#ffffff'>
						<br>
						<div class='body-text' style='font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333'>{[message]}
							<br>
						</div>
					</td>
				</tr>
				<tr>
					<td class='container-padding footer-text' align='left' style='font-family:Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#aaaaaa;padding-left:24px;padding-right:24px;padding-bottom:5px;'>
						<br>
						<center><small>This is a system generated email.</small></center>
						<br>
						<center>Copyright {[year]}, All Rights Reserved.</center>
						<br>
						<br>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>
MAIL;

            $mail->Subject = $subject;
            $mail->Body = strtr($mail_template_raw, $trans);
            $mail->AltBody = $message;
            if (!$mail->send()) {
                $this->session->set_flashdata('license_status', array(
                    'type' => "danger",
                    'message' => "License details email was not sent, please check error logs.",
                ));
                redirect('licenses');
            } else {
                $this->session->set_flashdata('license_status', array(
                    'type' => "success",
                    'message' => "License details email was successfully sent to email ($email).",
                ));
                redirect('licenses');
            }
        } catch (Exception $e) {
            $this->session->set_flashdata('license_status', array(
                'type' => "danger",
                'message' => "License details email was not sent, please check error logs.",
            ));
            redirect('licenses');
        }
    }
}

