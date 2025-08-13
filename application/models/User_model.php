<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * LicenseBox
 *
 * LicenseBox is a full-fledged licenser and updates manager.
 *
 * @package LicenseBox
 * @author CodeMonks
 * @see https://licensebox.app
 * @link https://codecanyon.net/item/licensebox-php-license-and-updates-manager/22351237
 * @license https://codecanyon.net/licenses/standard (Regular or Extended License)
 * @copyright Copyright (c) 2023, CodeMonks. (https://www.licensebox.app)
 * @version 1.6.4
 */

class User_model extends CI_Model
{
    public function login($username, $password)
    {
        $this->db->where('au_username', $username);
        $this->db->or_where('au_email', $username);
        $query = $this->db->get('auth_users');
        $row = ($query) ? $query->row_array() : false;
        if (($query->num_rows() == 1) && password_verify($password, ($row['au_password']))) {
            return $query->row(0)->au_uid;
        } else {
            return false;
        }
    }

    public function edit_user()
    {
        $full_name = strip_tags(trim((string) $this->input->post('full_name')));
        $username = strip_tags(trim((string) $this->input->post('username')));
        $email = strip_tags(trim((string) $this->input->post('email')));

        $data = array(
            'au_name' => $full_name,
            'au_username' => $username,
            'au_email' => $email,
        );

        $this->db->where('au_uid', $this->session->userdata('user_id'));
        return $this->db->update('auth_users', $data);
    }

    public function get_config_from_db($config, $decrypt = false)
    {
        $query = $this->db->get('app_settings');
        foreach ($query->result() as $row) {
            if ($row->as_name == $config) {
                if ($decrypt) {
                    $this->load->library('encryption');
                    return $this->encryption->decrypt($row->as_value);
                } else {
                    return $row->as_value;
                }
            }
        }
        return false;
    }

    public function edit_config()
    {
        $this->load->library('encryption');
        $data = array('as_value' => strip_tags(trim((string) $this->input->post('server_timezone'))));
        $this->db->where('as_name', 'server_timezone');
        $this->db->update('app_settings', $data);
        $envato_uses_limit = $this->input->post('envato_use_limit');
        $data = array('as_value' => (!empty($envato_uses_limit) ? strip_tags(trim($envato_uses_limit)) : null));
        $this->db->where('as_name', 'envato_use_limit');
        $this->db->update('app_settings', $data);
        $envato_parallel_uses = $this->input->post('envato_parallel_use_limit');
        $data = array('as_value' => (!empty($envato_parallel_uses) ? strip_tags(trim($envato_parallel_uses)) : null));
        $this->db->where('as_name', 'envato_parallel_use_limit');
        $this->db->update('app_settings', $data);
        $whitelist_ips = $this->input->post('whitelist_ips');
        $data = array('as_value' => (!empty($whitelist_ips) ? strip_tags(trim($whitelist_ips)) : null));
        $this->db->where('as_name', 'whitelist_ips');
        $this->db->update('app_settings', $data);
        $data = array('as_value' => strip_tags(trim((string) $this->input->post('license_format'))));
        $this->db->where('as_name', 'license_code_format');
        $this->db->update('app_settings', $data);
        $lb_themes = array("classic", "flat", "material");
        $lb_current_theme = strtolower(strip_tags(trim((string) $this->input->post('licensebox_theme'))));
        if (!in_array($lb_current_theme, $lb_themes)) {
            $lb_current_theme = "material";
        }
        $data = array('as_value' => $lb_current_theme);
        $this->db->where('as_name', 'licensebox_theme');
        $this->db->update('app_settings', $data);
        $envato_token = strip_tags(trim((string) $this->input->post('envato_api_token')));
        $data = array('as_value' => (!empty($envato_token)) ? $this->encryption->encrypt($envato_token) : null);
        $this->db->where('as_name', 'envato_api_token');
        $this->db->update('app_settings', $data);
        if (strip_tags(trim((string) $this->input->post('failed_activation_logs'))) == 'on') {
            $failed_activation_logs = 1;
        } else {
            $failed_activation_logs = 0;
        }
        $data = array('as_value' => $failed_activation_logs);
        $this->db->where('as_name', 'failed_activation_logs');
        $this->db->update('app_settings', $data);
        if (strip_tags(trim((string) $this->input->post('failed_update_download_logs'))) == 'on') {
            $failed_update_download_logs = 1;
        } else {
            $failed_update_download_logs = 0;
        }
        $data = array('as_value' => $failed_update_download_logs);
        $this->db->where('as_name', 'failed_update_download_logs');
        $this->db->update('app_settings', $data);
        if (strip_tags(trim((string) $this->input->post('auto_deactivate_activations'))) == 'on') {
            $auto_deactivate_activations = 1;
        } else {
            $auto_deactivate_activations = 0;
        }
        $data = array('as_value' => $auto_deactivate_activations);
        $this->db->where('as_name', 'auto_deactivate_activations');
        $this->db->update('app_settings', $data);
        if (strip_tags(trim((string) $this->input->post('auto_add_licensed_domain'))) == 'on') {
            $auto_add_licensed_domain = 1;
        } else {
            $auto_add_licensed_domain = 0;
        }
        $data = array('as_value' => $auto_add_licensed_domain);
        $this->db->where('as_name', 'auto_add_licensed_domain');
        $this->db->update('app_settings', $data);
        return true;
    }

    public function edit_email_config()
    {
        $data = array('as_value' => strip_tags(trim($this->input->post('email_method'))));
        $this->db->where('as_name', 'email_method');
        $this->db->update('app_settings', $data);

        if (strip_tags(trim((string) $this->input->post('email_method'))) == 'smtp') {
            $this->load->library('encryption');
            $data = array('as_value' => strip_tags(trim((string) $this->input->post('smtp_connection'))));
            $this->db->where('as_name', 'smtp_connection');
            $this->db->update('app_settings', $data);
            $data = array('as_value' => strip_tags(trim((string) $this->input->post('smtp_host'))));
            $this->db->where('as_name', 'smtp_host');
            $this->db->update('app_settings', $data);
            $data = array('as_value' => strip_tags(trim((string) $this->input->post('smtp_requires_authentication'))));
            $this->db->where('as_name', 'smtp_authentication');
            $this->db->update('app_settings', $data);
            $data = array('as_value' => (!empty($this->input->post('smtp_port'))) ? strip_tags(trim($this->input->post('smtp_port'))) : null);
            $this->db->where('as_name', 'smtp_port');
            $this->db->update('app_settings', $data);

            if (strip_tags(trim((string) $this->input->post('smtp_requires_authentication')))) {
                $data = array('as_value' => (!empty($this->input->post('smtp_username'))) ? strip_tags(trim($this->input->post('smtp_username'))) : null);
                $this->db->where('as_name', 'smtp_username');
                $this->db->update('app_settings', $data);
                $smtp_password = strip_tags(trim((string) $this->input->post('smtp_password')));
                if (!empty($smtp_password)) {
                    $data = array('as_value' => $this->encryption->encrypt($smtp_password));
                    $this->db->where('as_name', 'smtp_password');
                    $this->db->update('app_settings', $data);
                }
            }
        }

        $data = array('as_value' => strip_tags(trim((string) $this->input->post('server_email'))));
        $this->db->where('as_name', 'server_email');
        $this->db->update('app_settings', $data);
        $data = array('as_value' => clean_html_codes($this->input->post('license_expiring')));
        $this->db->where('as_name', 'license_expiring');
        $this->db->update('app_settings', $data);
        if (strip_tags(trim((string) $this->input->post('license_expiring_enable'))) == 'on') {
            $license_expiring_enable = 1;
        } else {
            $license_expiring_enable = 0;
        }
        $data = array('as_value' => $license_expiring_enable);
        $this->db->where('as_name', 'license_expiring_enable');
        $this->db->update('app_settings', $data);

        $data = array('as_value' => clean_html_codes($this->input->post('support_expiring')));
        $this->db->where('as_name', 'support_expiring');
        $this->db->update('app_settings', $data);
        if (strip_tags(trim((string) $this->input->post('support_expiring_enable'))) == 'on') {
            $support_expiring_enable = 1;
        } else {
            $support_expiring_enable = 0;
        }
        $data = array('as_value' => $support_expiring_enable);
        $this->db->where('as_name', 'support_expiring_enable');
        $this->db->update('app_settings', $data);

        $data = array('as_value' => clean_html_codes($this->input->post('updates_expiring')));
        $this->db->where('as_name', 'updates_expiring');
        $this->db->update('app_settings', $data);
        if (strip_tags(trim((string) $this->input->post('updates_expiring_enable'))) == 'on') {
            $updates_expiring_enable = 1;
        } else {
            $updates_expiring_enable = 0;
        }
        $data = array('as_value' => $updates_expiring_enable);
        $this->db->where('as_name', 'updates_expiring_enable');
        $this->db->update('app_settings', $data);

        $data = array('as_value' => clean_html_codes($this->input->post('new_update')));
        $this->db->where('as_name', 'new_update');
        $this->db->update('app_settings', $data);
        if (strip_tags(trim((string) $this->input->post('new_update_enable'))) == 'on') {
            $new_update_enable = 1;
        } else {
            $new_update_enable = 0;
        }
        $data = array('as_value' => $new_update_enable);
        $this->db->where('as_name', 'new_update_enable');
        $this->db->update('app_settings', $data);
        return true;
    }

    public function edit_api_config()
    {
        $data = array('as_value' => (!empty($this->input->post('auto_domain_blacklist'))) ? strip_tags(trim($this->input->post('auto_domain_blacklist'))) : null);
        $this->db->where('as_name', 'auto_domain_blacklist');
        $this->db->update('app_settings', $data);
        $data = array('as_value' => (!empty($this->input->post('auto_ip_blacklist'))) ? strip_tags(trim($this->input->post('auto_ip_blacklist'))) : null);
        $this->db->where('as_name', 'auto_ip_blacklist');
        $this->db->update('app_settings', $data);
        if (!empty($this->input->post('api_blacklisted_ips'))) {
            $arr_ips = explode(',', strip_tags(trim($this->input->post('api_blacklisted_ips'))));
            $ips = implode(',', array_unique($arr_ips));
        } else {
            $ips = null;
        }
        $data = array('as_value' => !empty($ips) ? $ips : null);
        $this->db->where('as_name', 'blacklisted_ips');
        $this->db->update('app_settings', $data);
        if (!empty($this->input->post('api_blacklisted_domains'))) {
            $arr_domains = explode(',', strip_tags(trim($this->input->post('api_blacklisted_domains'))));
            $domains = implode(',', array_unique($arr_domains));
        } else {
            $domains = null;
        }
        $data = array('as_value' => !empty($domains) ? $domains : null);
        $this->db->where('as_name', 'blacklisted_domains');
        $this->db->update('app_settings', $data);
        $data = array('as_value' => (!empty($this->input->post('api_rate_limit'))) ? strip_tags(trim($this->input->post('api_rate_limit'))) : null);
        $this->db->where('as_name', 'api_rate_limit');
        $this->db->update('app_settings', $data);
        $data = array('as_value' => (!empty($this->input->post('api_rate_limit_method'))) ? strip_tags(trim($this->input->post('api_rate_limit_method'))) : null);
        $this->db->where('as_name', 'api_rate_limit_method');
        $this->db->update('app_settings', $data);
        return true;
    }

    public function get_api_key($key)
    {
        $this->db->where('key', $key);
        $query = $this->db->get('api_keys');
        if ($query) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    public function get_api_keys($only_external = false)
    {
        if ($only_external) {
            $this->db->where('controller', '/api_external');
        }
        $query = $this->db->get('api_keys');
        return $query->result_array();
    }

    public function get_internal_api_keys()
    {
        $this->db->where('controller', '/api_internal');
        $query = $this->db->get('api_keys');
        return $query->result_array();
    }

    public function add_api_key()
    {
        if (!empty(strip_tags(trim($this->input->post('api_key'))))) {
            $api_key = strip_tags(trim($this->input->post('api_key')));
        } else {
            $api_key = null;
        }
        if (!empty($this->input->post('endpoints'))) {
            $endpoints = implode(",", $this->input->post('endpoints'));
            $endpoints = strip_tags(trim($endpoints));
        } else {
            $endpoints = null;
        }
        if (strip_tags(trim((string) $this->input->post('ignore_limits'))) == 'on') {
            $ignore_limits = 1;
        } else {
            $ignore_limits = 0;
        }
        $data = array(
            'key' => $api_key,
            'controller' => "/api_" . strtolower(strip_tags(trim((string) $this->input->post('api_type')))),
            'endpoints' => $endpoints,
            'ignore_limits' => $ignore_limits,
        );
        return $this->db->insert('api_keys', $data);
    }

    public function delete_api_key()
    {
        $this->db->where('key', strip_tags(trim((string) $this->input->post('key'))));
        $this->db->delete('api_keys');
        return $this->db->affected_rows();
    }

    public function change_password($email = null)
    {
        $password = password_hash(strip_tags(trim((string) $this->input->post('new_password'))), PASSWORD_DEFAULT);
        $data = array(
            'au_password' => $password,
        );
        if (!empty($email)) {
            $this->db->where('au_email', urldecode($email));
        } else {
            $this->db->where('au_uid', $this->session->userdata('user_id'));
        }
        return $this->db->update('auth_users', $data);
    }

    public function get_user($slug)
    {
        $this->db->select('au_uid, au_name, au_username, au_email');
        $query = $this->db->get_where('auth_users', array('au_uid' => $slug));
        if ($query) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    public function get_user_from_username($slug)
    {
        $this->db->select('au_uid, au_name, au_username, au_email, au_reset_key, au_reset_exp');
        $query = $this->db->get_where('auth_users', array('au_username' => $slug));
        return $query->row_array();
    }

    public function get_user_from_email($slug)
    {
        $this->db->select('au_uid, au_name, au_username, au_email, au_reset_key, au_reset_exp');
        $query = $this->db->get_where('auth_users', array('au_email' => $slug));
        return $query->row_array();
    }

    public function get_user_from_token($email, $token)
    {
        $this->db->select('au_uid, au_name, au_username, au_email, au_reset_key, au_reset_exp');
        $query = $this->db->get_where('auth_users', array('au_email' => urldecode($email)));
        $response = $query->row_array();
        if (password_verify($token, ($response['au_reset_key']))) {
            return $response;
        } else {
            return false;
        }
    }

    public function add_password_reset($uid, $reset_key, $reset_exp)
    {
        $data = array(
            'au_reset_key' => $reset_key,
            'au_reset_exp' => $reset_exp,
        );
        $this->db->where('au_uid', $uid);
        return $this->db->update('auth_users', $data);
    }

    public function remove_password_reset($email)
    {
        $data = array(
            'au_reset_key' => null,
            'au_reset_exp' => null,
        );
        $this->db->where('au_email', urldecode($email));
        return $this->db->update('auth_users', $data);
    }

    public function add_log($log)
    {
        if (!empty(trim($log))) {
            $data = array(
                'al_log' => trim($log),
            );
            $this->db->insert('activity_logs', $data);
            return true;
        } else {
            return false;
        }
    }

    public function get_activities($limit, $start, $col, $dir)
    {
        $query = $this->db->limit($limit, $start)->order_by($col, $dir)->get('activity_logs');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }

    }

    public function get_api_call_count($date = null)
    {
        if (!empty($date)) {
            $this->db->where('date', $date);
        }
        $this->db->select_sum('count');
        $query = $this->db->get('api_logs');
        return $query->row()->count;
    }

    public function get_activities_count()
    {
        $query = $this->db->query('SELECT al_id FROM activity_logs');
        return $query->num_rows();
    }

    public function activity_search($limit, $start, $search, $col, $dir)
    {
        $query = $this->db->like('al_log', $search)->or_like('al_date', $search)->limit($limit, $start)->order_by($col, $dir)->get('activity_logs');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function activity_search_count($search)
    {
        $query = $this->db->like('al_log', $search)->or_like('al_date', $search)->get('activity_logs');
        return $query->num_rows();
    }

    public function get_activity_log()
    {
        $query = $this->db->query('SELECT * from activity_logs WHERE al_date > (NOW() - INTERVAL 24 HOUR) ORDER BY al_date DESC');
        return $query->result_array();
    }

    public function get_activity_logs()
    {
        $query = $this->db->query('SELECT * from activity_logs ORDER BY al_date DESC');
        return $query->result_array();
    }

    public function delete_activity($manual_activity = null)
    {
        if (!empty($manual_activity)) {
            $id = strip_tags(trim($manual_activity));
        } else {
            $id = strip_tags(trim((string) $this->input->post('activity_id')));
        }
        $this->db->where('al_id', $id);
        $this->db->delete('activity_logs');
        return $this->db->affected_rows();
    }
}
