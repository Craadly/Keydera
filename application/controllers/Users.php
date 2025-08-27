<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * keydera-clean
 *
 * Keydera is a full-fledged licenser and updates manager.
 *
 * @package keydera-clean
 * @author Craadly
 * @see https://keydera.app
 * @copyright Copyright (c) 2025, Keydera. (https://www.keydera.app)
 * @version 1.0.0
 */

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require APPPATH . 'third_party/PHPMailer/src/Exception.php';
require APPPATH . 'third_party/PHPMailer/src/PHPMailer.php';
require APPPATH . 'third_party/PHPMailer/src/SMTP.php';

class Users extends CI_Controller
{
    public function login()
    {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }
        if (!empty($this->user_model->get_config_from_db('whitelist_ips'))) {
            $whitelisted_ips = explode(',', $this->user_model->get_config_from_db('whitelist_ips'));
            $current_ip = $this->input->ip_address();
            $ip_allowed = false;
            foreach ($whitelisted_ips as $i) {
                $wildcard_pos = str_has_arrayitem($i, array(".*", "*.", ":*", "*:"));
                if ($wildcard_pos !== false && substr($current_ip, 0, $wildcard_pos) == substr($i, 0, $wildcard_pos)) {
                    $ip_allowed = true;
                } elseif ($i == $current_ip) {
                    $ip_allowed = true;
                }
            }
            if (!$ip_allowed) {
                show_error("Your IP address does not have permission to access this site.", 401, "Access Denied: Unauthorized IP Address");
            }
        }
        $data['title'] = 'Login';
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view('users/login', $data);
            $this->load->view('templates/footer');
        } else {
            $username = strip_tags(trim((string) $this->input->post('username')));
            $password = strip_tags(trim((string) $this->input->post('password')));
            $user_id = $this->user_model->login($username, $password);

            $this->load->library('user_agent');

            if (is_localhost($this->input->ip_address())) {
                $actual_ip = null;
            } else {
                $actual_ip = $this->input->ip_address();
            }

            if ($actual_ip) {
                $getloc = json_decode(get_external_content("http://ip-api.com/json/" . $actual_ip));
                if (!empty($getloc->city) && !empty($getloc)) {
                    $actual_city = $getloc->city;
                    $actual_country = $getloc->country;
                } else {
                    $getloc = json_decode(get_external_content("https://ipapi.co/" . $actual_ip . "/json"));
                    if (!empty($getloc) && !empty($getloc->city)) {
                        $actual_city = $getloc->city;
                        $actual_country = $getloc->country_name;
                    }
                }
            }

            if (isset($actual_city) || isset($actual_country)) {
                $user_location = '(' . $actual_city . ', ' . $actual_country . ')';
            } else {
                $user_location = '';
            }

            if ($this->agent->is_browser()) {
                $agent = $this->agent->browser() . ' ' . $this->agent->version();
            } elseif ($this->agent->is_robot()) {
                $agent = $this->agent->robot();
            } elseif ($this->agent->is_mobile()) {
                $agent = $this->agent->mobile();
            } else {
                $agent = '-';
            }

            $user_ip = $this->input->ip_address();

            $user_platform = $this->agent->platform();

            if ($user_id) {
                $user_data = $this->user_model->get_user($user_id);
                $user_data = array(
                    'user_id' => $user_id,
                    'username' => $username,
                    'fullname' => $user_data['au_name'],
                    'logged_in' => true,
                );
                $this->session->set_userdata($user_data);
                $this->user_model->add_log('Successful login from IP <b>' . s_t($user_ip) . '</b> ' . trim(s_t($user_location)) . ' using ' . s_t($agent) . ' on ' . s_t($user_platform) . '.');
                if ($this->session->userdata('redirectToCurrent')) {
                    $redir_path = $this->session->userdata('redirectToCurrent');
                    $this->session->unset_userdata('redirectToCurrent');
                    
                    // Ensure redirect stays within keydera.local domain
                    $parsed_url = parse_url($redir_path);
                    $current_domain = parse_url(base_url());
                    
                    if (isset($parsed_url['host']) && $parsed_url['host'] !== $current_domain['host']) {
                        // External domain detected, redirect to dashboard instead
                        redirect('dashboard');
                    } else {
                        redirect($redir_path);
                    }
                } else {
                    redirect('dashboard');
                }
            } else {
                $this->session->set_flashdata('login_status', array(
                    'type' => "danger",
                    'message' => 'Email or password is invalid.',
                ));
                $this->user_model->add_log('Failed login attempt from IP <b>' . s_t($user_ip) . '</b> ' . trim(s_t($user_location)) . ' using ' . s_t($agent) . ' on ' . s_t($user_platform) . '.');
                redirect('login');
            }
        }
    }

    public function forgot_password()
    {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }
        $data['title'] = 'Forgot Password?';
        $this->form_validation->set_rules('email', 'Email', 'required');
        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view('users/forgot_password', $data);
            $this->load->view('templates/footer');
        } else {
            $email = $this->input->post('email');
            $user_data = $this->user_model->get_user_from_email($email);
            if (!$user_data) {
                $this->session->set_flashdata('login_status', array(
                    'type' => "danger",
                    'message' => 'Email was not found in our records.',
                ));
                redirect('forgot_password');
            }

            $reset_time = date('Y-m-d H:i:s');
            $reset_exp = date('Y-m-d H:i:s', strtotime('+1 hour', strtotime($reset_time)));
            $reset_key = substr(str_shuffle(MD5(microtime())), 0, 35);
            $reset_keyf = password_hash($reset_key, PASSWORD_DEFAULT);

            $email = $user_data['au_email'];
            $cur_reset_exp = $user_data['au_reset_exp'];
            $user_name = $user_data['au_name'];
            $user_id = $user_data['au_uid'];

            if (!empty($cur_reset_exp) && ($reset_time <= $cur_reset_exp)) {
                $this->session->set_flashdata('login_status', array(
                    'type' => "danger",
                    'message' => 'Password reset already requested, try again after some time.',
                ));
                redirect('forgot_password');
            }

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
                $mail->addAddress($email, ucwords($user_name));

                $mail->isHTML(true);
                $mail->Subject = 'Reset Password - Keydera';
                $clean_email = urlencode($email);
                $trans = array(
                    "{[year]}" => date('Y'),
                    "{[user]}" => $user_name,
                    "{[site_url]}" => base_url(),
                    "{[token]}" => $reset_key,
                    "{[email]}" => $clean_email,
                );
                $mail->Body = strtr("<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'><html xmlns='http://www.w3.org/1999/xhtml'><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><title>Reset Password - Keydera</title><style type='text/css'>body{padding-top:0 !important;padding-bottom:0 !important;padding-top:0 !important;padding-bottom:0 !important;margin:0 !important;width:100% !important;-webkit-text-size-adjust:100% !important;-ms-text-size-adjust:100% !important;-webkit-font-smoothing:antialiased !important}.footer-text {color:#382F2E;}.tableContent a{color:#382F2E}p,h1{color:#382F2E;margin:0}p{text-align:left;color:#7A7A7A;font-size:15px;font-weight:normal;line-height:19px}a.link1{color:#382F2E;text-decoration:none}a.link2{font-size:16px;text-decoration:none;color:#fff}h2{text-align:left;color:#222;font-size:19px;font-weight:normal}div,p,ul,h1{margin:0}.bgBody{background:#fff}.bgItem{background:#fff}@media only screen and (max-width:480px){table[class='MainContainer'],td[class='cell']{width:100% !important;height:auto !important}td[class='specbundle']{width:100% !important;float:left !important;font-size:13px !important;line-height:17px !important;display:block !important;padding-bottom:15px !important}td[class='spechide']{display:none !important}img[class='banner']{width:100% !important;height:auto !important}td[class='left_pad']{padding-left:15px !important;padding-right:15px !important}}@media only screen and (max-width:540px){table[class='MainContainer'],td[class='cell']{width:100% !important;height:auto !important}td[class='specbundle']{width:100% !important;float:left !important;font-size:13px !important;line-height:17px !important;display:block !important;padding-bottom:15px !important}td[class='spechide']{display:none !important}img[class='banner']{width:100% !important;height:auto !important}.font{font-size:18px !important;line-height:22px !important}.font1{font-size:18px !important;line-height:22px !important}}</style></head><body paddingwidth='0' paddingheight='0' style='padding-top: 0; padding-bottom: 0; padding-top: 0; padding-bottom: 0; background-repeat: repeat; width: 100% !important; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased;' offset='0' toppadding='0' leftpadding='0'><table bgcolor='#ffffff' width='100%' border='0' cellspacing='0' cellpadding='0' class='tableContent' align='center' style='font-family:Helvetica, Arial,serif;'><tbody><tr><td><table width='600' border='0' cellspacing='0' cellpadding='0' align='center' bgcolor='#ffffff' class='MainContainer'><tbody><tr><td><table width='100%' border='0' cellspacing='0' cellpadding='0'><tbody><tr><td valign='top' width='40'>&nbsp;</td><td><table width='100%' border='0' cellspacing='0' cellpadding='0'><tbody><tr><td height='75' class='spechide'></td></tr><tr><td class='movableContentContainer ' valign='top'><div class='movableContent' style='border: 0px; padding-top: 0px; position: relative;'><table width='100%' border='0' cellspacing='0' cellpadding='0'><tbody><tr><td height='35'></td></tr><tr><td><p style='text-align:center;margin:0;font-family:Georgia,Time,sans-serif;font-size:26px;color:#222222;'><span class='specbundle2'><span class='font1'>Password Reset - Keydera</span></span></p></td></tr></tbody></table></div><div class='movableContent' style='border: 0px; padding-top: 0px; position: relative;'><table width='100%' border='0' cellspacing='0' cellpadding='0' align='center'><tr><td height='50'></td></tr><tr><td align='left'><div class='contentEditableContainer contentTextEditable'><div class='contentEditable' align='center'><p><span style='color:#222222;font-size: 15px'>Hello {[user]},</span></p></div></div></td></tr><tr><td height='15'></td></tr><tr><td align='left'><div class='contentEditableContainer contentTextEditable'><div class='contentEditable' align='center'><p> Let's reset your Keydera password! Click the link given below to change your account password.</p> <br><p>Please note that the password reset link will expire in an hour.</p></div></div></td></tr><tr><td height='35'></td></tr><tr><td align='center'><table><tr><td align='center' bgcolor='#1A54BA' style='background:#3273dc; padding:15px 18px;-webkit-border-radius: 4px; -moz-border-radius: 40px; border-radius: 40px;'><div class='contentEditableContainer contentTextEditable'><div class='contentEditable' align='center'> <a target='_blank' href='{[site_url]}reset_password/{[email]}/{[token]}' class='link2' style='color:#ffffff;'>Change Password</a></div></div></td></tr></table></td></tr><tr><td height='10'></td></tr></table></div><div class='movableContent' style='border: 0px; padding-top: 0px; position: relative;'><table width='100%' border='0' cellspacing='0' cellpadding='0'><tbody><tr><td height='45'></tr><tr><td style='border-bottom:1px solid #DDDDDD;'></td></tr><tr><td height='25'></td></tr><tr><td style='font-size:12px;'><center><span class='footer-text'>Copyright {[year]} <a class='link1' style='color:#222222;' href='https://keydera.app'>Keydera</a>, All Rights Reserved.</center></span></td></tr><tr><td height='88'></td></tr></tbody></table></div></td></tr></tbody></table></td><td valign='top' width='40'>&nbsp;</td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table></body></html>", $trans);
                $mail->AltBody = strtr("To reset your Keydera password, please visit {[site_url]}reset_password/{[email]}/{[token]}", $trans);
                if (!$mail->send()) {
                    $this->session->set_flashdata('login_status', array(
                        'type' => "danger",
                        'message' => 'Password reset email was not sent, please contact support.',
                    ));
                    redirect('forgot_password');
                }
                $this->user_model->add_password_reset($user_id, $reset_keyf, $reset_exp);
                $this->session->set_flashdata('login_status', array(
                    'type' => "primary",
                    'message' => 'Password reset instructions sent, please check your email.',
                ));
                redirect('forgot_password');
            } catch (Exception $e) {
                $this->session->set_flashdata('login_status', array(
                    'type' => "danger",
                    'message' => 'Password reset email was not sent, please contact support.',
                ));
                log_message('error', 'Error sending email: ' . $e->getMessage());
                redirect('forgot_password');
            }
        }
    }

    public function reset_password()
    {
        if (!empty($this->uri->segment(2)) && !empty($this->uri->segment(3))) {
            $reset_key = strip_tags(trim((string) $this->uri->segment(3)));
            $email = strip_tags(trim((string) $this->uri->segment(2)));
            $user_data = $this->user_model->get_user_from_token($email, $reset_key);
            $reset_time = date('Y-m-d H:i:s');
            if (empty($user_data) && empty($user_data['au_reset_exp']) && ($reset_time > $user_data['au_reset_exp'])) {
                $this->session->set_flashdata('login_status', array(
                    'type' => "danger",
                    'message' => 'Reset token expired or is invalid.',
                ));
                redirect('forgot_password');
            } else {
                $data['title'] = 'Reset Password';
                $data['email'] = $email;
                $data['token'] = $reset_key;
                $this->form_validation->set_rules('new_password', 'new password', 'required');
                $this->form_validation->set_rules('password_confirm', 'confirm password', 'required|matches[new_password]');
                if ($this->form_validation->run() === false) {
                    $this->load->view('templates/header', $data);
                    $this->load->view('users/reset_password', $data);
                    $this->load->view('templates/footer');
                } else {
                    if ($this->user_model->change_password($email)) {
                        $this->session->set_flashdata('login_status', array(
                            'type' => "primary",
                            'message' => 'Your password has changed, Please login.',
                        ));
                        $this->user_model->remove_password_reset($email);
                        redirect('login');
                    } else {
                        $this->session->set_flashdata('login_status', array(
                            'type' => "danger",
                            'message' => 'Please recheck entered values, Password was not changed.',
                        ));
                        redirect('forgot_password');
                    }
                }
            }
        } else {
            $this->session->set_flashdata('login_status', array(
                'type' => "danger",
                'message' => 'Reset token expired or is invalid.',
            ));
            redirect('forgot_password');
        }
    }

    public function logout()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        $this->session->unset_userdata('fullname');
        $this->session->unset_userdata('logged_in');
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('username');
        $this->session->set_flashdata('login_status', array(
            'type' => "primary",
            'message' => 'You have successfully logged out.',
        ));
        redirect('login');
    }

    public function activities()
    {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_userdata('redirectToCurrent', current_url());
            redirect('login');
        }
        $data['title'] = 'All Activity Logs';
        $data['activity_logs'] = $this->user_model->get_activity_logs();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/menu');
        $this->load->view('users/activities', $data);
        $this->load->view('templates/footer');
    }

    public function get_activities()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('users/login');
        }

        $columns = array(
            1 => 'al_log',
            2 => 'al_date',
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $totalData = $this->user_model->get_activities_count();
        $totalFiltered = $totalData;

        if (empty($this->input->post('search')['value'])) {
            $posts = $this->user_model->get_activities($limit, $start, $order, $dir);
        } else {
            $search = $this->input->post('search')['value'];
            $posts = $this->user_model->activity_search($limit, $start, $search, $order, $dir);
            $totalFiltered = $this->user_model->activity_search_count($search);
        }

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData = null;

                $originalDate = $post->al_date;
                $newDate = date($this->config->item('datetime_format'), strtotime($originalDate));
                $nestedData[] = "<center><input class='is-checkradio is-cr-t delete_activity_checkbox is-danger is-small' type='checkbox' id='activities_cr_t_" . $post->al_id . "' value=" . urlencode($post->al_id) . "><label for='activities_cr_t_" . $post->al_id . "'></label></center>";
                $nestedData[] = strip_tags($post->al_log);
                $nestedData[] = $newDate;
                $form_buttons = "<div class='buttons is-centered'>";
                $hidden = array('activity_id' => $post->al_id);

                $title_or_tooltip = (strtolower(KEYDERA_THEME) == "material") ? "title" : "data-tooltip";

                $js = 'id="delete_form_' . $post->al_id . '"';
                $form_buttons .= form_open('/users/delete_activity', $js, $hidden) . '<button type="button" data-id="' . $post->al_id . '" data-title="activity log" data-body="Please note that this action cannot be undone." class="button with-delete-confirmation is-danger is-small tooltip is-tooltip-left" style="padding-top: 0px;padding-bottom: 0px;" ' . $title_or_tooltip . '="Delete Log"><i class="fa fa-trash"></i></button></form>';
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

    public function delete_activity()
    {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_userdata('redirectToCurrent', current_url());
            redirect('users/login');
        }
        if ($this->user_model->delete_activity()) {
            $this->session->set_flashdata('activity_status', array(
                'type' => "success",
                'message' => "Activity log was successfully deleted.",
            ));
            redirect('activities');
        } else {
            $this->session->set_flashdata('activity_status', array(
                'type' => "danger",
                'message' => "An error occured, Activity log was not deleted.",
            ));
            redirect('activities');
        }
    }

    public function delete_selected_activity()
    {
        define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
        if (!IS_AJAX) {header("HTTP/1.0 403 Forbidden");die('Direct access not allowed.');}
        if ($this->input->post('delete_activities_checkbox')) {
            $id = $this->input->post('delete_activities_checkbox');
            for ($count = 0; $count < count($id); $count++) {
                $this->user_model->delete_activity(urldecode($id[$count]));
            }
        }
    }
}

