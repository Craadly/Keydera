<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Keydera
 *
 * Keydera is a full-fledged licenser and updates manager.
 *
 * @package Keydera
 * @author Craadly
 * @see https://keydera.app
 * @copyright Copyright (c) 2025, Keydera. (https://www.keydera.app)
 * @version 1.0.0
 */

class Products_model extends CI_Model
{
    public function get_products_count()
    {
        $query = $this->db->query('SELECT pd_id FROM product_details');
        return $query->num_rows();
    }

    public function get_active_products_count()
    {
        $query = $this->db->query('SELECT pd_id FROM product_details where pd_status=1');
        return $query->num_rows();
    }

    public function get_product($slug = false)
    {
        if ($slug === false) {
            $this->db->order_by('pd_id', 'DESC');
            $query = $this->db->get('product_details');
            return $query->result_array();
        }
        $query = $this->db->get_where('product_details', array('pd_pid' => $slug));
        if ($query) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    public function check_product_status($slug)
    {
        $query = $this->db->get_where('product_details', array('pd_pid' => $slug));
        $pdata = $query->row_array();
        if (!empty($pdata) && $pdata['pd_status'] == 1) {
            return true;
        }
        return false;
    }

    public function check_version_exists($pid, $version)
    {
        $query = $this->db->get_where('product_versions', array('pid' => $pid, 'version' => $version));
        if (empty($query->row_array())) {
            return false;
        } else {
            return true;
        }
    }

    public function check_product_exists($pid)
    {
        $query = $this->db->get_where('product_details', array('pd_pid' => $pid));
        if (empty($query->row_array())) {
            return false;
        } else {
            return true;
        }
    }

    public function get_version($slug, $ignore_status = true)
    {
        $this->db->order_by('release_date', 'ASC');
        if ($ignore_status) {
            $query = $this->db->get_where('product_versions', array('pid' => $slug));
        } else {
            $query = $this->db->get_where('product_versions', array('pid' => $slug, 'status' => 1));
        }
        return $query->result_array();
    }

    public function get_version_by_vid($slug, $ignore_status = false)
    {
        if ($ignore_status) {
            $query = $this->db->get_where('product_versions', array('vid' => $slug));
        } else {
            $query = $this->db->get_where('product_versions', array('vid' => $slug, 'status' => 1));
        }
        if ($query) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    public function get_latest_version($slug, $ignore_status = false)
    {
        if ($ignore_status) {
            $query0 = $this->db->get_where('product_versions', array('pid' => $slug));
        } else {
            $query0 = $this->db->get_where('product_versions', array('pid' => $slug, 'status' => 1));
        }
        $version = $query0->result_array();
        $last_highest = ' ';
        $last_highest_id = '';
        foreach ($version as $version0) {
            $string = $version0['version'];
            if (version_compare($string, $last_highest) > 0) {
                $last_highest = $string;
                $last_highest_id = $version0['version'];
            }
        }
        $query = $this->db->get_where('product_versions', array('version' => $last_highest_id, 'pid' => $slug));
        return $query->result_array();
    }

    public function get_oldest_version($slug, $current, $ignore_status = false)
    {
        $latest_version = $this->get_latest_version($slug);
        if (!$ignore_status) {
            $this->db->where('status', 1);
        }
        $this->db->where('pid', $slug);
        $this->db->where('version !=', $current);
        $query0 = $this->db->get('product_versions');
        $version = $query0->result_array();
        $last_highest = $latest_version[0]['version'];
        $last_highest_id = $latest_version[0]['version'];
        foreach ($version as $version0) {
            $string = $version0['version'];
            if ((version_compare($string, $last_highest) < 0) && (version_compare($string, $current) > 0)) {
                $last_highest = $string;
                $last_highest_id = $version0['version'];
            }
        }
        $query = $this->db->get_where('product_versions', array('version' => $last_highest_id, 'pid' => $slug));
        return $query->result_array();
    }

    public function add_product()
    {
        if (!empty($this->input->post('name'))) {
            $product_name = strip_tags(trim($this->input->post('name')));
        } else {
            $product_name = null;
        }
        if (!empty($this->input->post('details'))) {
            $details = strip_tags(trim($this->input->post('details')));
        } else {
            $details = null;
        }
        if (!empty($this->input->post('envato_id'))) {
            $envato_id = strip_tags(trim($this->input->post('envato_id')));
        } else {
            $envato_id = null;
        }
        if (strip_tags(trim((string) $this->input->post('license_update'))) == 'on') {
            $license_update = 1;
        } else {
            $license_update = 0;
        }
        if (strip_tags(trim((string) $this->input->post('serve_latest_updates'))) == 'on') {
            $serve_latest_updates = 1;
        } else {
            $serve_latest_updates = 0;
        }
        $data = array(
            'pd_pid' => strip_tags(trim((string) $this->input->post('product_id'))),
            'envato_id' => $envato_id,
            'pd_name' => $product_name,
            'pd_details' => $details,
            'license_update' => $license_update,
            'serve_latest_updates' => $serve_latest_updates,
            'pd_status' => strip_tags(trim((int) $this->input->post('product_status'))),

        );
        return $this->db->insert('product_details', $data);
    }

    public function add_version($main, $sql)
    {
        if (strip_tags(trim((string) $this->input->post('version_status'))) == 'on') {
            $status = 1;
        } else {
            $status = 0;
        }
        if (!empty($this->input->post('version'))) {
            $version = strip_tags(trim($this->input->post('version')));
        } else {
            $version = null;
        }
        if (!empty($this->input->post('summary'))) {
            $summary = strip_tags(trim($this->input->post('summary')));
        } else {
            $summary = null;
        }
        $vid = substr(MD5(microtime()), 0, 20);
        $safe_changelog = clean_html_codes($this->input->post('changelog'));
        $data = array(
            'vid' => $vid,
            'pid' => strip_tags(trim((string) $this->input->post('product'))),
            'version' => $version,
            'release_date' => strip_tags(trim((string) $this->input->post('released'))),
            'summary' => $summary,
            'changelog' => $safe_changelog,
            'main_file' => $main,
            'sql_file' => $sql,
            'status' => $status,
        );
        return $this->db->insert('product_versions', $data);
    }

    public function edit_version($main = null, $sql = null)
    {
        if (strip_tags(trim((string) $this->input->post('version_status'))) == 'on') {
            $status = 1;
        } else {
            $status = 0;
        }
        if (!empty($this->input->post('version'))) {
            $version = strip_tags(trim($this->input->post('version')));
        } else {
            $version = null;
        }
        if (!empty($this->input->post('summary'))) {
            $summary = strip_tags(trim($this->input->post('summary')));
        } else {
            $summary = null;
        }
        $safe_changelog = clean_html_codes($this->input->post('changelog'));
        $data = array(
            'version' => $version,
            'release_date' => strip_tags(trim((string) $this->input->post('released'))),
            'summary' => $summary,
            'changelog' => $safe_changelog,
            'status' => $status,
        );
        if ($main && $sql) {
            $arr = array(
                'main_file' => $main,
                'sql_file' => $sql,
            );
        } elseif ($main && !$sql) {
            $arr = array(
                'main_file' => $main,
            );
        } elseif (!$main && $sql) {
            $arr = array(
                'sql_file' => $sql,
            );
        }

        if (isset($arr)) {
            $data_final = array_merge($data, $arr);
        } else {
            $data_final = $data;
        }

        $this->db->where('vid', $this->input->post('vid'));
        return $this->db->update('product_versions', $data_final);
    }

    public function delete_product()
    {
        $this->db->where('pd_pid', strip_tags(trim((string) $this->input->post('product'))));
        $this->db->delete('product_details');
        return $this->db->affected_rows();
    }

    public function edit_product()
    {
        if (!empty($this->input->post('details'))) {
            $details = strip_tags(trim($this->input->post('details')));
        } else {
            $details = null;
        }
        if (!empty($this->input->post('envato_id'))) {
            $envato_id = strip_tags(trim($this->input->post('envato_id')));
        } else {
            $envato_id = null;
        }
        if (strip_tags(trim((string) $this->input->post('license_update'))) == 'on') {
            $license_update = 1;
        } else {
            $license_update = 0;
        }
        if (strip_tags(trim((string) $this->input->post('serve_latest_updates'))) == 'on') {
            $serve_latest_updates = 1;
        } else {
            $serve_latest_updates = 0;
        }
        $data = array(
            'pd_name' => strip_tags(trim((string) $this->input->post('name'))),
            'envato_id' => $envato_id,
            'pd_details' => $details,
            'license_update' => $license_update,
            'serve_latest_updates' => $serve_latest_updates,
            'pd_status' => strip_tags(trim((int) $this->input->post('product_status'))),

        );
        $this->db->where('pd_pid', $this->input->post('product'));
        $this->db->update('product_details', $data);
        return $this->db->affected_rows();
    }

    public function delete_version()
    {
        $query = $this->db->get_where('product_versions', array('vid' => strip_tags(trim((string) $this->input->post('vid')))));
        $get_ver_res = ($query) ? $query->row_array() : false;
        $this->db->where('vid', strip_tags(trim((string) $this->input->post('vid'))));
        $this->db->delete('product_versions');
        if ($this->db->affected_rows()) {
            if (!empty($get_ver_res['sql_file'])) {
                $sql_file_id = $get_ver_res['sql_file'];
                if (is_readable(FCPATH . "version-files/" . $sql_file_id) && unlink(FCPATH . "version-files/" . $sql_file_id)) {
                }
            }
            $main_file_id = $get_ver_res['main_file'];
            if (is_readable(FCPATH . "version-files/" . $main_file_id) && unlink(FCPATH . "version-files/" . $main_file_id)) {
            }
            return true;
        } else {
            return false;
        }
    }

    public function delete_versions_by_pid($pid)
    {
        $this->db->where('pid', strip_tags(trim((string) $pid)));
        $this->db->delete('product_versions');
        return $this->db->affected_rows();
    }

    public function unpublish_version()
    {
        $this->db->where('vid', strip_tags(trim((string) $this->input->post('vid'))));
        $this->db->update('product_versions', array(
            'status' => 0,
        ));
        return $this->db->affected_rows();
    }

    public function publish_version()
    {
        $this->db->where('vid', strip_tags(trim((string) $this->input->post('vid'))));
        $this->db->update('product_versions', array(
            'status' => 1,
        ));
        return $this->db->affected_rows();
    }
}

