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

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require APPPATH . 'third_party/PHPMailer/src/Exception.php';
require APPPATH . 'third_party/PHPMailer/src/PHPMailer.php';
require APPPATH . 'third_party/PHPMailer/src/SMTP.php';

class Settings extends CI_Controller
{

    public function account_settings()
    {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_userdata('redirectToCurrent', current_url());
            redirect('users/login');
        }

        $userid = $this->session->userdata('user_id');
        $data['title'] = 'Account Settings';
        $data['user'] = $this->user_model->get_user($userid);

        if ($this->input->post('type') == 'general') {
            $this->form_validation->set_rules('full_name', 'Name', 'required');
            $this->form_validation->set_rules('username', 'Username', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required');

            if ($this->form_validation->run() === false) {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/menu');
                $this->load->view('settings/account_settings', $data);
                $this->load->view('templates/footer');
            } else {
                if ($this->user_model->edit_user()) {
                    $this->session->set_userdata('username', $this->input->post('username'));
                    $this->session->set_userdata('fullname', $this->input->post('full_name'));
                    $this->session->set_flashdata('user_status', array(
                        'type' => "success",
                        'message' => 'Account settings were successfully updated.',
                    ));
                    redirect('account_settings');
                } else {
                    $this->session->set_flashdata('user_status', array(
                        'type' => "danger",
                        'message' => 'Please recheck entered values or you haven\'t made any changes, Account settings were not updated.',
                    ));
                    redirect('account_settings');
                }
            }

        } elseif ($this->input->post('type') == 'password') {
            $this->form_validation->set_rules('current_password', 'Current Password', 'required');
            $this->form_validation->set_rules('new_password', 'New Password', 'required');
            if ($this->form_validation->run() === false) {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/menu');
                $this->load->view('settings/account_settings', $data);
                $this->load->view('templates/footer');
            } else {
                $user_id = $this->user_model->login($this->session->userdata('username'), $this->input->post('current_password'));
                if ($user_id) {
                    if ($this->user_model->change_password()) {
                        $this->session->set_flashdata('user_status', array(
                            'type' => "success",
                            'message' => 'Your password was successfully changed.',
                        ));
                        redirect('account_settings');
                    } else {
                        $this->session->set_flashdata('user_status', array(
                            'type' => "danger",
                            'message' => 'Please recheck entered values or you haven\'t made any changes, Password was not changed.',
                        ));
                        redirect('account_settings');
                    }
                } else {
                    $this->session->set_flashdata('user_status', array(
                        'type' => "danger",
                        'message' => 'Current password is incorrect, please recheck.',
                    ));
                    redirect('account_settings');
                }
            }
        } else {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/menu');
            $this->load->view('settings/account_settings', $data);
            $this->load->view('templates/footer');
        }
    }

    public function general_settings()
    {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_userdata('redirectToCurrent', current_url());
            redirect('users/login');
        }

        $data['title'] = 'General Settings';
        $data['server_timezone'] = $this->user_model->get_config_from_db('server_timezone');
        $data['license_format'] = $this->user_model->get_config_from_db('license_code_format');
        $data['keydera_theme'] = KEYDERA_THEME;
        $data['envato_api_token'] = $this->user_model->get_config_from_db('envato_api_token', true);
        $data['whitelist_ips'] = $this->user_model->get_config_from_db('whitelist_ips');
        $data['envato_use_limit'] = $this->user_model->get_config_from_db('envato_use_limit');
        $data['envato_parallel_use_limit'] = $this->user_model->get_config_from_db('envato_parallel_use_limit');
        $data['failed_activation_logs'] = $this->user_model->get_config_from_db('failed_activation_logs');
        $data['failed_update_download_logs'] = $this->user_model->get_config_from_db('failed_update_download_logs');
        $data['auto_deactivate_activations'] = $this->user_model->get_config_from_db('auto_deactivate_activations');
        $data['auto_add_licensed_domain'] = $this->user_model->get_config_from_db('auto_add_licensed_domain');
        $data['current_user_ip'] = $this->input->ip_address();

        $this->form_validation->set_rules('license_format', 'License code format', 'required');
        $this->form_validation->set_rules('envato_use_limit', 'Default Envato License Uses Limit', 'is_natural_no_zero');
        $this->form_validation->set_rules('envato_parallel_use_limit', 'Default Envato Parallel Uses Limit', 'is_natural_no_zero');
        $this->form_validation->set_rules('whitelist_ips', 'Whitelisted IP addresses',
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
        $this->form_validation->set_message('ips_check', '{field} are incorrect, please check.');
        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/menu');
            $this->load->view('settings/general_settings', $data);
            $this->load->view('templates/footer');
        } else {
            if ($this->user_model->edit_config()) {
                $this->session->set_flashdata('general_status', array(
                    'type' => "success",
                    'message' => 'General settings were successfully updated.',
                ));
                redirect('general_settings');
            } else {
                $this->session->set_flashdata('general_status', array(
                    'type' => "danger",
                    'message' => 'Please recheck entered values or you haven\'t made any changes, General settings were not updated.',
                ));
                redirect('general_settings');
            }
        }
    }

    public function email_settings()
    {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_userdata('redirectToCurrent', current_url());
            redirect('users/login');
        }

        $data['title'] = 'Email Settings';
        $get_user_data = $this->user_model->get_user($this->session->userdata('user_id'));
        $data['admin_email'] = $get_user_data['au_email'];
        $data['server_email'] = $this->user_model->get_config_from_db('server_email');
        $data['license_expiring'] = $this->user_model->get_config_from_db('license_expiring');
        $data['license_expiring_enable'] = $this->user_model->get_config_from_db('license_expiring_enable');
        $data['updates_expiring'] = $this->user_model->get_config_from_db('updates_expiring');
        $data['updates_expiring_enable'] = $this->user_model->get_config_from_db('updates_expiring_enable');
        $data['support_expiring'] = $this->user_model->get_config_from_db('support_expiring');
        $data['support_expiring_enable'] = $this->user_model->get_config_from_db('support_expiring_enable');
        $data['new_update'] = $this->user_model->get_config_from_db('new_update');
        $data['new_update_enable'] = $this->user_model->get_config_from_db('new_update_enable');
        $data['email_method'] = $this->user_model->get_config_from_db('email_method');
        $data['smtp_connection'] = $this->user_model->get_config_from_db('smtp_connection');
        $data['smtp_authentication'] = $this->user_model->get_config_from_db('smtp_authentication');
        $data['smtp_username'] = $this->user_model->get_config_from_db('smtp_username');
        $data['smtp_password'] = $this->user_model->get_config_from_db('smtp_password', true);
        $data['smtp_host'] = $this->user_model->get_config_from_db('smtp_host');
        $data['smtp_port'] = $this->user_model->get_config_from_db('smtp_port');

        $this->form_validation->set_rules('server_email', 'Server Email', 'required');
        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/menu');
            $this->load->view('settings/email_settings', $data);
            $this->load->view('templates/footer');
        } else {
            if ($this->user_model->edit_email_config()) {
                $this->session->set_flashdata('email_settings_status', array(
                    'type' => "success",
                    'message' => 'Email settings were successfully updated.',
                ));
                redirect('email_settings');
            } else {

                $this->session->set_flashdata('email_settings_status', array(
                    'type' => "danger",
                    'message' => 'Please recheck entered values or you haven\'t made any changes, Email settings were not updated.',
                ));
                redirect('email_settings');
            }
        }
    }

    public function send_test_email()
    {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_userdata('redirectToCurrent', current_url());
            redirect('users/login');
        }
        $mail_type = $this->input->post('mail_type');
        if (!$mail_type) {
            redirect('email_settings');
        }
        $get_user_data = $this->user_model->get_user($this->session->userdata('user_id'));
        $email = $get_user_data['au_email'];
        if (!$email) {
            $this->session->set_flashdata('email_settings_status', array(
                'type' => "danger",
                'message' => 'Please first set your email address in the account settings.',
            ));
            redirect('email_settings');
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
            $mail->Subject = 'Test Mail - Keydera';
            $trans = array(
                "{[year]}" => date('Y'),
            );
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
	<title>Test Mail - Keydera</title>
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
						<td class='container-padding header' align='left' style='font-family:Helvetica, Arial, sans-serif;font-size:24px;font-weight:bold;padding-bottom:18px;color:#DF4726;padding-left:24px;padding-right:24px'>Test Mail - Keydera</td>
					</tr>
					<tr>
						<td class='container-padding content' align='left' style='padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#ffffff'>
							<br>
							<div class='body-text' style='font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333'>
								<p>Hi there,&nbsp;</p>
								<p><b>Congratulations!</b> Your Keydera email settings are set up correctly.</p>
								<p>Regards</p>
								<br>
							</div>
						</td>
					</tr>
					<tr>
						<td class='container-padding footer-text' align='left' style='font-family:Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#aaaaaa;padding-left:24px;padding-right:24px;padding-bottom:5px;'>
							<br>
							<center><small>This is a system generated email.</small></center>
							<br>
							<center>Copyright {[year]} <a style='color:#aaaaaa;text-decoration:none;' href='https://www.keydera.app'>Keydera</a>, All Rights Reserved.</center>
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
            $mail->Body = strtr($mail_template_raw, $trans);
            $mail->AltBody = strtr("Congratulations! Your Keydera email settings are set up correctly.", $trans);
            if (!$mail->send()) {
                $this->session->set_flashdata('email_settings_status', array(
                    'type' => "danger",
                    'message' => 'Test email was not sent, please check error logs.',
                ));
                redirect('email_settings');
            }
            $this->session->set_flashdata('email_settings_status', array(
                'type' => "success",
                'message' => "Test email was successfully sent to your email ($email).",
            ));
            redirect('email_settings');
            redirect('email_settings');
        } catch (Exception $e) {
            $this->session->set_flashdata('email_settings_status', array(
                'type' => "danger",
                'message' => 'Test email was not sent, please check error logs.',
            ));
            log_message('error', 'Error sending email: ' . $e->getMessage());
            redirect('email_settings');
        }
    }

    public function api_settings()
    {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_userdata('redirectToCurrent', current_url());
            redirect('users/login');
        }

        $data['title'] = 'API Settings';
        $data['auto_domain_blacklist'] = $this->user_model->get_config_from_db('auto_domain_blacklist');
        $data['auto_ip_blacklist'] = $this->user_model->get_config_from_db('auto_ip_blacklist');
        $data['api_rate_limit'] = $this->user_model->get_config_from_db('api_rate_limit');
        $data['api_rate_limit_method'] = $this->user_model->get_config_from_db('api_rate_limit_method');
        $data['api_blacklisted_ips'] = $this->user_model->get_config_from_db('blacklisted_ips');
        $data['api_blacklisted_domains'] = $this->user_model->get_config_from_db('blacklisted_domains');
        $data['api_keys'] = $this->user_model->get_api_keys();

        if ($this->input->post('type') == 'general') {
            $this->form_validation->set_rules('api_blacklisted_ips', 'Blacklisted IP addresses',
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
            $this->form_validation->set_rules('api_blacklisted_domains', 'Blacklisted domains',
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
                $this->load->view('settings/api_settings', $data);
                $this->load->view('templates/footer');
            } else {
                if ($this->user_model->edit_api_config()) {
                    $this->session->set_flashdata('api_settings_status', array(
                        'type' => "success",
                        'message' => 'API settings were successfully updated.',
                    ));
                    redirect('api_settings');
                } else {
                    $this->session->set_flashdata('api_settings_status', array(
                        'type' => "danger",
                        'message' => 'Please recheck entered values or you haven\'t made any changes, API settings were not updated.',
                    ));
                    redirect('api_settings');
                }
            }
        } elseif ($this->input->post('type') == 'api') {
            $this->form_validation->set_rules('api_key', 'API Key', 'required');
            $this->form_validation->set_rules('endpoints[]', 'API Endpoint', 'trim|required');
            if ($this->form_validation->run() === false) {
                $this->load->view('templates/header', $data);
                $this->load->view('templates/menu');
                $this->load->view('settings/api_settings', $data);
                $this->load->view('templates/footer');
            } else {
                if ($this->user_model->add_api_key()) {
                    $this->session->set_flashdata('api_settings_status', array(
                        'type' => "success",
                        'message' => 'API key (' . $this->input->post('api_key') . ') was successfully added.',
                    ));
                    redirect('api_settings#create_api_key');
                } else {
                    $this->session->set_flashdata('api_settings_status', array(
                        'type' => "danger",
                        'message' => 'Please check entered values, API Key was not added.',
                    ));
                    redirect('api_settings#create_api_key');
                }
            }
        } else {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/menu');
            $this->load->view('settings/api_settings', $data);
            $this->load->view('templates/footer');
        }
    }

    public function delete_api_key()
    {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_userdata('redirectToCurrent', current_url());
            redirect('login');
        }

        if ($this->user_model->delete_api_key()) {
            $this->session->set_flashdata('api_settings_status', array(
                'type' => "success",
                'message' => 'API key (' . $this->input->post('key') . ') was successfully deleted.',
            ));
            redirect('api_settings');
        } else {
            $this->session->set_flashdata('api_settings_status', array(
                'type' => "danger",
                'message' => 'An error occured, API key was not deleted.',
            ));
            redirect('api_settings');
        }
    }
}

