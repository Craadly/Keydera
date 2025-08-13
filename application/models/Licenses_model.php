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

class Licenses_model extends CI_Model
{
    public function get_licenses_count()
    {
        $query = $this->db->query('SELECT id FROM product_licenses');
        return $query->num_rows();
    }

    public function get_licenses_count_for_chart()
    {
        $query = $this->db->query('SELECT * FROM product_licenses');
        $res = $query->result_array();
        $valid_count = 0;
        $invalid_count = 0;
        $blocked_count = 0;
        foreach ($res as $post) {
            $current_activations = $this->activations_model->get_activation_by_license($post['license_code']);
            $licenses_left0 = $post['uses'] - $current_activations;
            $parallel_licenses_left0 = $post['parallel_uses'] - $current_activations;
            if ($post['validity'] == 1) {
                if (($licenses_left0 > 0) || ($post['uses'] == null)) {
                    if (($parallel_licenses_left0 > 0) || ($post['parallel_uses'] == null)) {
                        $today = date('Y-m-d H:i:s');
                        if (!empty($post['expiry'])) {
                            $expiry_date = $post['expiry'];
                            if ($today >= $expiry_date) {
                                $invalid_count++;
                            } else {
                                $valid_count++;
                            }
                        } else {
                            $valid_count++;
                        }
                    } else {
                        $invalid_count++;
                    }
                } else {
                    $invalid_count++;
                }
            } else {
                $blocked_count++;
            }
        }
        return array('valid' => $valid_count, 'invalid' => $invalid_count, 'blocked' => $blocked_count);
    }

    public function get_licenses_based_on_date($start, $end)
    {
        $this->db->where('added_on >=', $start);
        $this->db->where('added_on <=', $end);
        $query = $this->db->get('product_licenses');
        return $query->num_rows();
    }

    public function get_licenses($limit, $start, $col, $dir)
    {
        $query = $this->db->limit($limit, $start)->order_by($col, $dir)->get('product_licenses');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function license_search($limit, $start, $search, $col, $dir)
    {
        $query = $this->db->like('license_code', $search)->or_like('client', $search)->or_like('email', $search)->or_like('license_type', $search)->or_like('pid', $search)->limit($limit, $start)->order_by($col, $dir)->get('product_licenses');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function license_search_count($search)
    {
        $query = $this->db->like('license_code', $search)->or_like('client', $search)->or_like('email', $search)->or_like('license_type', $search)->or_like('pid', $search)->get('product_licenses');
        return $query->num_rows();
    }

    public function get_active_licenses_count()
    {
        $query = $this->db->query('SELECT DISTINCT(pi_license_code) FROM product_activations WHERE pi_isvalid=1 AND pi_isactive=1');
        return $query->num_rows();
    }

    public function get_license($slug = false)
    {
        if ($slug === false) {
            $this->db->order_by('id', 'DESC');
            $query = $this->db->get('product_licenses');
            return $query->result_array();
        }
        $query = $this->db->get_where('product_licenses', array('license_code' => $slug));
        if ($query) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    public function check_license_exists($license)
    {
        $query = $this->db->get_where('product_licenses', array('license_code' => $license));
        if (empty($query->row_array())) {
            return false;
        } else {
            return true;
        }
    }

    public function edit_license()
    {
        if (strip_tags(trim((string) $this->input->post('validity'))) == 'on') {
            $is_valid = 0;
        } else {
            $is_valid = 1;
        }
        if (($this->input->post('client') != null) && ($this->input->post('client') != '')) {
            $client = strip_tags(trim($this->input->post('client')));
        } else {
            $client = null;
        }
        if (($this->input->post('uses') != null) && ($this->input->post('uses') != '')) {
            $uses = strip_tags(trim($this->input->post('uses')));
            $uses_left = $uses;
        } else {
            $uses = null;
            $uses_left = null;
        }
        if (($this->input->post('parallel_uses') != null) && ($this->input->post('parallel_uses') != '')) {
            $parallel_uses = strip_tags(trim($this->input->post('parallel_uses')));
            $parallel_uses_left = $parallel_uses;
        } else {
            $parallel_uses = null;
            $parallel_uses_left = null;
        }
        if (($this->input->post('expiry_days') != null) && ($this->input->post('expiry_days') != '')) {
            $expiry_days = strip_tags(trim($this->input->post('expiry_days')));
        } else {
            $expiry_days = null;
        }
        if (!empty($this->input->post('expiry'))) {
            $expiry_date = strip_tags(trim($this->input->post('expiry')));
        } else {
            $expiry_date = null;
        }
        if (!empty($this->input->post('supported_till'))) {
            $supported_till = strip_tags(trim($this->input->post('supported_till')));
        } else {
            $supported_till = null;
        }
        if (!empty($this->input->post('updates_till'))) {
            $updates_till = strip_tags(trim($this->input->post('updates_till')));
        } else {
            $updates_till = null;
        }
        if (!empty($this->input->post('license_type'))) {
            $license_type = strip_tags(trim($this->input->post('license_type')));
        } else {
            $license_type = null;
        }
        if (!empty($this->input->post('comments'))) {
            $comments = strip_tags(trim($this->input->post('comments')));
        } else {
            $comments = null;
        }
        if (!empty($this->input->post('invoice'))) {
            $invoice = strip_tags(trim($this->input->post('invoice')));
        } else {
            $invoice = null;
        }
        if (!empty($this->input->post('ips'))) {
            $arr_ips = explode(',', strip_tags(trim($this->input->post('ips'))));
            $ips = implode(',', array_unique($arr_ips));
        } else {
            $ips = null;
        }
        if (!empty($this->input->post('domains'))) {
            $arr_domains = explode(',', strip_tags(trim($this->input->post('domains'))));
            $domains = implode(',', array_unique($arr_domains));
        } else {
            $domains = null;
        }
        if (!empty($this->input->post('email'))) {
            $email = strip_tags(trim($this->input->post('email')));
        } else {
            $email = null;
        }
        $data = array(
            'pid' => strip_tags(trim((string) $this->input->post('product'))),
            'license_type' => $license_type,
            'invoice' => $invoice,
            'client' => $client,
            'email' => $email,
            'comments' => $comments,
            'ips' => $ips,
            'domains' => $domains,
            'expiry' => $expiry_date,
            'expiry_days' => $expiry_days,
            'supported_till' => $supported_till,
            'updates_till' => $updates_till,
            'uses' => $uses,
            'uses_left' => $uses_left,
            'parallel_uses' => $parallel_uses,
            'parallel_uses_left' => $parallel_uses_left,
            'validity' => $is_valid,

        );
        $this->db->where('license_code', $this->input->post('license_code'));
        return $this->db->update('product_licenses', $data);
    }

    public function create_license()
    {
        if (strip_tags(trim((string) $this->input->post('validity'))) == 'on') {
            $is_valid = 0;
        } else {
            $is_valid = 1;
        }
        if (($this->input->post('client') != null) && ($this->input->post('client') != '')) {
            $client = strip_tags(trim($this->input->post('client')));
        } else {
            $client = null;
        }
        if (($this->input->post('uses') != null) && ($this->input->post('uses') != '')) {
            $uses = strip_tags(trim($this->input->post('uses')));
            $uses_left = $uses;
        } else {
            $uses = null;
            $uses_left = null;
        }
        if (($this->input->post('parallel_uses') != null) && ($this->input->post('parallel_uses') != '')) {
            $parallel_uses = strip_tags(trim($this->input->post('parallel_uses')));
            $parallel_uses_left = $parallel_uses;
        } else {
            $parallel_uses = null;
            $parallel_uses_left = null;
        }
        if (($this->input->post('expiry_days') != null) && ($this->input->post('expiry_days') != '')) {
            $expiry_days = strip_tags(trim($this->input->post('expiry_days')));
        } else {
            $expiry_days = null;
        }
        if (!empty($this->input->post('expiry'))) {
            $expiry_date = strip_tags(trim($this->input->post('expiry')));
        } else {
            $expiry_date = null;
        }
        if (!empty($this->input->post('supported_till'))) {
            $supported_till = strip_tags(trim($this->input->post('supported_till')));
        } else {
            $supported_till = null;
        }
        if (!empty($this->input->post('updates_till'))) {
            $updates_till = strip_tags(trim($this->input->post('updates_till')));
        } else {
            $updates_till = null;
        }
        if (!empty($this->input->post('license_type'))) {
            $license_type = strip_tags(trim($this->input->post('license_type')));
        } else {
            $license_type = null;
        }
        if (!empty($this->input->post('invoice'))) {
            $invoice = strip_tags(trim($this->input->post('invoice')));
        } else {
            $invoice = null;
        }
        if (!empty($this->input->post('comments'))) {
            $comments = strip_tags(trim($this->input->post('comments')));
        } else {
            $comments = null;
        }
        if (!empty($this->input->post('ips'))) {
            $arr_ips = explode(',', strip_tags(trim($this->input->post('ips'))));
            $ips = implode(',', array_unique($arr_ips));
        } else {
            $ips = null;
        }
        if (!empty($this->input->post('domains'))) {
            $arr_domains = explode(',', strip_tags(trim($this->input->post('domains'))));
            $domains = implode(',', array_unique($arr_domains));
        } else {
            $domains = null;
        }
        if (!empty($this->input->post('email'))) {
            $email = strip_tags(trim($this->input->post('email')));
        } else {
            $email = null;
        }
        if (!empty($this->input->post('license'))) {
            $license = strip_tags(trim($this->input->post('license')));
        } else {
            $license = null;
        }
        if (preg_match("/^(\{)?[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}(?(1)\})$/i", $license)) {
            $is_envato = 1;
        } else {
            $is_envato = null;
        }
        $data = array(
            'pid' => strip_tags(trim((string) $this->input->post('product'))),
            'license_code' => $license,
            'license_type' => $license_type,
            'invoice' => $invoice,
            'is_envato' => $is_envato,
            'client' => $client,
            'email' => $email,
            'comments' => $comments,
            'ips' => $ips,
            'domains' => $domains,
            'supported_till' => $supported_till,
            'updates_till' => $updates_till,
            'expiry' => $expiry_date,
            'expiry_days' => $expiry_days,
            'uses' => $uses,
            'uses_left' => $uses_left,
            'parallel_uses' => $parallel_uses,
            'parallel_uses_left' => $parallel_uses_left,
            'validity' => $is_valid,
        );

        return $this->db->insert('product_licenses', $data);
    }

    public function delete_license($manual_license = null)
    {
        if (!empty($manual_license)) {
            $license_code = strip_tags(trim($manual_license));
        } else {
            $license_code = strip_tags(trim((string) $this->input->post('license')));
        }
        $this->db->where('license_code', $license_code);
        $this->db->delete('product_licenses');
        return $this->db->affected_rows();
    }

    public function delete_licenses_by_pid($pid)
    {
        $this->db->where('pid', strip_tags(trim((string) $pid)));
        $this->db->delete('product_licenses');
        return $this->db->affected_rows();
    }

    public function block_license()
    {
        $this->db->where('license_code', strip_tags(trim((string) $this->input->post('license'))));
        $this->db->update('product_licenses', array(
            'validity' => 0,
        ));
        return $this->db->affected_rows();
    }

    public function unblock_license()
    {
        $this->db->where('license_code', strip_tags(trim((string) $this->input->post('license'))));
        $this->db->update('product_licenses', array(
            'validity' => 1,
        ));
        return $this->db->affected_rows();
    }
}
