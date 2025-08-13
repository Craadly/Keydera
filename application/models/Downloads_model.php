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

class Downloads_model extends CI_Model
{
    public function get_downloads_count()
    {
        $this->db->where('isvalid', 1);
        $query = $this->db->get('update_downloads');
        return $query->num_rows();
    }

    public function get_downloads($limit, $start, $col, $dir)
    {
        $query = $this->db->limit($limit, $start)->order_by($col, $dir)->get('update_downloads');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function get_update_downloads_based_on_date($start, $end)
    {
        $this->db->where('isvalid', 1);
        $this->db->where('download_date >=', $start);
        $this->db->where('download_date <=', $end);
        $query = $this->db->get('update_downloads');
        return $query->num_rows();
    }

    public function get_update_downloads_based_on_product($product)
    {
        $this->db->where('product', $product);
        $this->db->where('isvalid', 1);
        $query = $this->db->get('update_downloads');
        return $query->num_rows();
    }

    public function get_update_downloads_based_on_version($version)
    {
        $this->db->where('vid', $version);
        $this->db->where('isvalid', 1);
        $query = $this->db->get('update_downloads');
        return $query->num_rows();
    }

    public function get_blocked_update_downloads()
    {
        $this->db->where('isvalid', 0);
        $query = $this->db->get('update_downloads');
        return $query->result_array();
    }

    public function download_search($limit, $start, $search, $col, $dir)
    {
        $query = $this->db->like('product', $search)->or_like('download_date', $search)->or_like('url', $search)->or_like('ip', $search)->limit($limit, $start)->order_by($col, $dir)->get('update_downloads');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function downloads_search_count($search)
    {
        $query = $this->db->like('download_date', $search)->or_like('url', $search)->or_like('ip', $search)->get('update_downloads');
        return $query->num_rows();
    }

    public function delete_download($manual_download = null)
    {
        if (!empty($manual_download)) {
            $did = strip_tags(trim($manual_download));
        } else {
            $did = strip_tags(trim((string) $this->input->post('did')));
        }
        $this->db->where('did', $did);
        $this->db->delete('update_downloads');
        return $this->db->affected_rows();
    }

    public function delete_downloads_by_vid($vid)
    {
        $this->db->where('vid', strip_tags(trim((string) $vid)));
        $this->db->delete('update_downloads');
        return $this->db->affected_rows();
    }

    public function delete_downloads_by_pid($pid)
    {
        $this->db->where('product', strip_tags(trim((string) $pid)));
        $this->db->delete('update_downloads');
        return $this->db->affected_rows();
    }
}

