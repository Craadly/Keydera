<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Keydera
 *
 * Keydera is a full-fledged licenser and updates manager.
 *
 * @package Keydera
 * @author Craadly
 * @see https://craadly.com
 * @copyright Copyright (c) 2025, Craadly. (https://www.craadly.com)
 * @version 1.0.0
 */

use Gettext\Translator;

require_once APPPATH . "third_party/Gettext/src/autoloader.php";
require_once APPPATH . "third_party/cldr-to-gettext-plural-rules/src/autoloader.php";

class Generate_helpers extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_userdata('redirectToCurrent', current_url());
            redirect('users/login');
        }
    }

    public function external_helper()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('users/login');
        }

        $data['products'] = $this->products_model->get_product();
        $data['languages'] = get_available_languages();
        $data['api_keys'] = $this->user_model->get_api_keys(true);
        $data['title'] = 'Generate External Helper File';
        $this->form_validation->set_rules('product', 'Product', 'required');
        $this->form_validation->set_rules('key', 'API Key', 'required');
        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/menu');
            $this->load->view('generate_helpers/external_helper', $data);
            $this->load->view('templates/footer');
        } else {
            if (!empty(strtolower($this->input->post('language')))) {
                $language = trim(strip_tags($this->input->post('language')));
            } else {
                $language = "english";
            }
            if (!is_file(APPPATH . 'language/' . $language . '/' . $language . '.mo')) {
                $try_language = strtolower($language);
                if (!is_file(APPPATH . 'language/' . $try_language . '/' . $try_language . '.mo')) {
                    $language = "english";
                } else {
                    $language = $try_language;
                }
            }
            $t = new Translator();
            $translations = Gettext\Translations::fromMoFile(APPPATH . 'language/' . $language . '/' . $language . '.mo');
            $t->loadTranslations($translations);
            $t->register();
            if (strip_tags(trim((string) $this->input->post('envato'))) == 'on') {
                $is_envato = "envato";
            } else {
                $is_envato = "non_envato";
            }
            if (!empty($this->input->post('product'))) {
                $_product_id = strip_tags(trim($this->input->post('product')));
            } else {
                $_product_id = null;
            }
            $latest_version = $this->products_model->get_latest_version($_product_id, true);

            if (!empty($latest_version)) {
                $ver = $latest_version[0]['version'];
            } else {
                $ver = 'v1.0.0';
            }

            $trans1 = array(
                "{[TEXT_CONNECTION_FAILED]}" => addslashes(__('Server is unavailable at the moment, please try again.')),
                "{[TEXT_INVALID_RESPONSE]}" => addslashes(__('Server returned an invalid response, please contact support.')),
                "{[TEXT_VERIFIED_RESPONSE]}" => addslashes(__('Verified! Thanks for purchasing.')),
                "{[TEXT_PREPARING_MAIN_DOWNLOAD]}" => addslashes(__('Preparing to download main update...')),
                "{[TEXT_MAIN_UPDATE_SIZE]}" => addslashes(__('Main Update size:')),
                "{[TEXT_DONT_REFRESH]}" => addslashes(__('(Please do not refresh the page).')),
                "{[TEXT_DOWNLOADING_MAIN]}" => addslashes(__('Downloading main update...')),
                "{[TEXT_UPDATE_PERIOD_EXPIRED]}" => addslashes(__('Your update period has ended or your license is invalid, please contact support.')),
                "{[TEXT_UPDATE_PATH_ERROR]}" => addslashes(__('Folder does not have write permission or the update file path could not be resolved, please contact support.')),
                "{[TEXT_MAIN_UPDATE_DONE]}" => addslashes(__('Main update files downloaded and extracted.')),
                "{[TEXT_UPDATE_EXTRACTION_ERROR]}" => addslashes(__('Update zip extraction failed.')),
                "{[TEXT_PREPARING_SQL_DOWNLOAD]}" => addslashes(__('Preparing to download SQL update...')),
                "{[TEXT_SQL_UPDATE_SIZE]}" => addslashes(__('SQL Update size:')),
                "{[TEXT_DOWNLOADING_SQL]}" => addslashes(__('Downloading SQL update...')),
                "{[TEXT_SQL_UPDATE_DONE]}" => addslashes(__('SQL update files downloaded.')),
                "{[TEXT_UPDATE_WITH_SQL_IMPORT_FAILED]}" => addslashes(__('Application was successfully updated but automatic SQL importing failed, please import the downloaded SQL file in your database manually.')),
                "{[TEXT_UPDATE_WITH_SQL_IMPORT_DONE]}" => addslashes(__('Application was successfully updated and SQL file was automatically imported.')),
                "{[TEXT_UPDATE_WITH_SQL_DONE]}" => addslashes(__('Application was successfully updated, please import the downloaded SQL file in your database manually.')),
                "{[TEXT_UPDATE_WITHOUT_SQL_DONE]}" => addslashes(__('Application was successfully updated, there were no SQL updates.')),
                "{[LANG]}" => $language,
                "{[PID]}" => $_product_id,
                "{[URL]}" => base_url(),
                "{[KEY]}" => strip_tags(trim((string) $this->input->post('key'))),
                "{[ENV]}" => $is_envato,
                "{[CUR]}" => $ver,
                "{[CHECK]}" => strip_tags(trim((int) $this->input->post('period'))),
                "{[RND1]}" => substr(MD5(microtime()), 0, 15),
            );

            if (strip_tags(trim((string) $this->input->post('for_wordpress'))) == 'on') {
                $data['generated_code'] = strtr(file_get_contents(FCPATH . '/application/libraries/api_helper_samples/external_api_helper_sample_wp.php'), $trans1);
            } else {
                $data['generated_code'] = strtr(file_get_contents(FCPATH . '/application/libraries/api_helper_samples/external_api_helper_sample.php'), $trans1);
            }
            $this->load->view('templates/header', $data);
            $this->load->view('templates/menu');
            $this->load->view('generate_helpers/external_helper', $data);
            $this->load->view('templates/footer');
        }
    }

    public function internal_helper()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('users/login');
        }

        $data['api_keys'] = $this->user_model->get_internal_api_keys();
        $data['languages'] = get_available_languages();
        $data['title'] = 'Generate Internal Helper File';
        $this->form_validation->set_rules('key', 'API Key', 'required');
        if ($this->form_validation->run() === false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/menu');
            $this->load->view('generate_helpers/internal_helper', $data);
            $this->load->view('templates/footer');
        } else {
            if (!empty(strtolower($this->input->post('language')))) {
                $language = trim(strip_tags($this->input->post('language')));
            } else {
                $language = "english";
            }
            if (!is_file(APPPATH . 'language/' . $language . '/' . $language . '.mo')) {
                $try_language = strtolower($language);
                if (!is_file(APPPATH . 'language/' . $try_language . '/' . $try_language . '.mo')) {
                    $language = "english";
                } else {
                    $language = $try_language;
                }
            }
            $t = new Translator();
            $translations = Gettext\Translations::fromMoFile(APPPATH . 'language/' . $language . '/' . $language . '.mo');
            $t->loadTranslations($translations);
            $t->register();
            $trans1 = array(
                "{[TEXT_CONNECTION_FAILED]}" => addslashes(__('Server is unavailable at the moment, please try again.')),
                "{[TEXT_INVALID_RESPONSE]}" => addslashes(__('Server returned an invalid response, please contact support.')),
                "{[LANG]}" => $language,
                "{[URL]}" => base_url(),
                "{[KEY]}" => strip_tags(trim((string) $this->input->post('key'))),
            );

            $data['generated_code'] = strtr(file_get_contents(FCPATH . '/application/libraries/api_helper_samples/internal_api_helper_sample.php'), $trans1);

            $this->load->view('templates/header', $data);
            $this->load->view('templates/menu');
            $this->load->view('generate_helpers/internal_helper', $data);
            $this->load->view('templates/footer');
        }
    }
}

