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

class Activations_model extends CI_Model
{
    public function get_activations_count()
    {
        $query = $this->db->get('product_activations');
        return $query->num_rows();
    }

    public function get_activations($limit, $start, $col, $dir)
    {
        $query = $this->db->limit($limit, $start)->order_by($col, $dir)->get('product_activations');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }

    }

    public function get_activations_based_on_date($start, $end)
    {
        $this->db->where('pi_date >=', $start);
        $this->db->where('pi_date <=', $end);
        $this->db->where('pi_isvalid', 1);
        $query = $this->db->get('product_activations');
        return $query->num_rows();
    }

    public function activation_search($limit, $start, $search, $col, $dir)
    {
        $query = $this->db->like('pi_license_code', $search)->or_like('pi_product', $search)->or_like('pi_client', $search)->or_like('pi_date', $search)->or_like('pi_url', $search)->limit($limit, $start)->order_by($col, $dir)->get('product_activations');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function activation_search_count($search)
    {
        $query = $this->db->like('pi_license_code', $search)->or_like('pi_product', $search)->or_like('pi_client', $search)->or_like('pi_date', $search)->or_like('pi_url', $search)->get('product_activations');
        return $query->num_rows();
    }

    public function get_active_activations_count()
    {
        $this->db->where('pi_isvalid', 1);
        $this->db->where('pi_isactive', 1);
        $query = $this->db->get('product_activations');
        return $query->num_rows();
    }

    public function get_installation($slug = false)
    {
        if ($slug === false) {
            $this->db->order_by('pi_id', 'DESC');
            $query = $this->db->get('product_activations');
            return $query->result_array();
        }
        $query = $this->db->get_where('product_activations', array('pi_iid' => $slug));
        if ($query) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    public function get_invalid_activations()
    {
        $query = $this->db->get_where('product_activations', array('pi_isvalid' => 0));
        return $query->result_array();
    }

    public function get_activation($license, $active = false)
    {
        if (!empty($active)) {
            $query = $this->db->get_where('product_activations', array(
                'pi_license_code' => $license,
                'pi_isvalid' => 1,
                'pi_isactive' => 1,
            ));
        } else {
            $query = $this->db->get_where('product_activations', array(
                'pi_license_code' => $license,
                'pi_isvalid' => 1,
            ));
        }
        return $query->result_array();
    }

    public function get_current_activation($license, $client, $domain)
    {
        $domain_final = preg_replace('#^www\.(.+\.)#i', '$1', $domain);
        $query = $this->db->get_where('product_activations', array(
            'pi_license_code' => $license,
            'pi_client' => $client,
            'pi_isvalid' => 1,
            'pi_isactive' => 1,
        ));
        $query_res = $query->result_array();
        $query_res_n = $query->num_rows();
        if (!empty($query_res)) {
            foreach ($query_res as $query) {
                $domain_res = parse_url($query['pi_url'], PHP_URL_HOST);
                $domain_res_final = preg_replace('#^www\.(.+\.)#i', '$1', $domain_res);
                $username_res = strtolower($query['pi_client']);
                if ((strtolower($client) == $username_res) && ($domain_final == $domain_res_final)) {
                    return $query_res_n;
                }
            }
            return false;
        } else {
            return false;
        }
    }

    public function deactivate_current_activation($license, $client, $domain)
    {
        $query = $this->db->get_where('product_activations', array(
            'pi_license_code' => $license,
            'pi_client' => $client,
            'pi_isvalid' => 1,
            'pi_isactive' => 1,
        ));
        $query_res = $query->result_array();
        $query_res_n = $query->num_rows();
        $deactivation_done = false;
        if (!empty($query_res)) {
            foreach ($query_res as $activations) {
                $domain_res = parse_url($activations['pi_url'], PHP_URL_HOST);
                $username_res = strtolower($activations['pi_client']);
                if ((strtolower($client) == $username_res) && ($domain == $domain_res)) {
                    $this->db->where('pi_iid', $activations['pi_iid']);
                    $this->db->update('product_activations', array(
                        'pi_isactive' => 0,
                    ));
                    $deactivation_done = true;
                } else {
                    $deactivation_done = false;
                }
            }
            return $deactivation_done;
        } else {
            return false;
        }
    }

    public function get_oldest_activation_by_license($license)
    {
        $this->db->where('pi_license_code', $license);
        $this->db->where('pi_isvalid', 1);
        $query = $this->db->limit(1)->order_by('pi_date', 'ASC')->get('product_activations');
        if ($query) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    public function get_activation_by_license($license)
    {
        $query = $this->db->get_where('product_activations', array(
            'pi_license_code' => $license,
            'pi_isvalid' => 1,
        ));
        return $query->num_rows();
    }

    public function get_activation_by_license_active($license)
    {
        $query = $this->db->get_where('product_activations', array(
            'pi_license_code' => $license,
            'pi_isvalid' => 1,
            'pi_isactive' => 1,
        ));
        return $query->num_rows();
    }

    public function delete_installation($manual_installation = null)
    {
        if (!empty($manual_installation)) {
            $iid = strip_tags(trim($manual_installation));
        } else {
            $iid = strip_tags(trim((string) $this->input->post('iid')));
        }
        $this->db->where('pi_iid', $iid);
        $this->db->delete('product_activations');
        return $this->db->affected_rows();
    }

    public function delete_installation_by_license($license)
    {
        $this->db->where('pi_license_code', strip_tags(trim((string) $license)));
        $this->db->delete('product_activations');
        return $this->db->affected_rows();
    }

    public function delete_installation_by_pid($pid)
    {
        $this->db->where('pi_product', strip_tags(trim((string) $pid)));
        $this->db->delete('product_activations');
        return $this->db->affected_rows();
    }

    public function activate_activation()
    {
        $this->db->where('pi_iid', strip_tags(trim((string) $this->input->post('iid'))));
        $this->db->update('product_activations', array('pi_isactive' => 1));
        return $this->db->affected_rows();
    }
    public function deactivate_activation()
    {
        $this->db->where('pi_iid', strip_tags(trim((string) $this->input->post('iid'))));
        $this->db->update('product_activations', array('pi_isactive' => 0));
        return $this->db->affected_rows();
    }

    public function change_activation_client($license, $client_old, $client_new)
    {
        $array = array('pi_license_code' => strip_tags(trim((string) $license)), 'pi_client' => strip_tags(trim((string) $client_old)), 'pi_isvalid' => 1);
        $this->db->where($array);
        $this->db->update('product_activations', array(
            'pi_client' => strip_tags(trim((string) $client_new)),
        ));
    }
}

