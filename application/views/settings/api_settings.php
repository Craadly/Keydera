<div class="container is-fluid main_body">
  <div class="section">
    <h1 class="title">
      <?php echo $title; ?>
    </h1>
    <?php echo generate_breadcrumb('API Settings'); ?>
    <?php if($this->session->flashdata('api_settings_status')){
      $flash = $this->session->flashdata('api_settings_status');
      echo '<div class="notification is-'.$flash['type'].'"><button class="delete"></button>'.$flash['message'].'</div>'; 
      if($flash['type']=='success'){
        echo '<script>setTimeout(function(){document.getElementsByClassName("notification")[0].style.display="none";},4000);</script>';
      } 
    } ?>
    <div class="box">
      <?php 
      $hidden = array('type' => 'general'); 
      echo form_open('api_settings', '', $hidden); ?>
      <div class="columns">
        <div class="column">
          <div class="field">
            <label class="label">Blacklist domain after how many failed attempts? <small class="tooltip is-tooltip-multiline is-tooltip-right" data-tooltip="If the specified number of failed activation and update download attempts are reached, the user's domain will be blacklisted automatically. (Note: This feature will only work if the LicenseBox Cron is set up correctly and the option to 'add entries for failed activation and download attempts' is enabled.)" style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
            <div class="control">
              <input class="input" type="number" name="auto_domain_blacklist" value="<?php echo $auto_domain_blacklist; ?>" placeholder="Number of failed attempts for auto domain blacklisting." min="1" tabindex="1">
            </div>
          </div>
          <div class="field">
            <label class="label">API Requests Rate Limiting Method <small class="tooltip is-tooltip-multiline is-tooltip-right" data-tooltip="API rate limiting can be implemented on a per-API key or per-IP address basis." style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
            <div class="control is-expanded">
              <div class="select is-fullwidth">
                <select id="api_rate_limit_method" name="api_rate_limit_method" required tabindex="3">
                  <?php if($api_rate_limit_method=="api_key"):?>
                    <option value="api_key" selected>Limit per API Key</option>
                    <option value="ip_address">Limit per IP Address</option>
                  <?php else: ?>
                    <option value="api_key">Limit per API Key</option>
                    <option value="ip_address" selected>Limit per IP Address</option>
                  <?php endif; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="field" style="margin-top: 1rem!important;">
            <label class="label">API Blacklisted Domains <small class="tooltip is-tooltip-multiline is-tooltip-right" data-tooltip="If specified, access to the LicenseBox API will be blocked for these domains." style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
            <div class="control">
              <input class="input" type="tags" name="api_blacklisted_domains" value="<?php
                if(!empty(set_value('api_blacklisted_domains'))) {
                  echo set_value('api_blacklisted_domains');
                }
                else{ 
                  echo $api_blacklisted_domains; 
                }?>" placeholder="Domains to be blacklisted" tabindex="5">
            </div>
            <?php echo form_error('api_blacklisted_domains', '<p class="help is-danger" style="margin-top: 1rem;">', '</p>'); ?>
          </div>
        </div>
        <div class="column">
          <div class="field">
            <label class="label">Blacklist IP after how many failed attempts? <small class="tooltip is-tooltip-multiline is-tooltip-left" data-tooltip="If the specified number of failed activation and update download attempts are reached, the user's IP will be automatically blacklisted. (Note: This will only work if the LicenseBox Cron is set up correctly and entries for failed activation and download attempts are allowed.)" style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
            <div class="control">
              <input class="input" type="number" name="auto_ip_blacklist" value="<?php echo $auto_ip_blacklist; ?>" placeholder="Number of failed attempts for auto IP blacklisting." min="1" tabindex="2">
            </div>
          </div>
          <div class="field">
            <label class="label">API Requests Rate Limit (per hour) <small class="tooltip is-tooltip-multiline is-tooltip-left" data-tooltip="Rate limiting API allows you to limit the number of requests processed by the API per hour from a specific API key or IP address." style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
            <div class="control">
              <input class="input" type="number" name="api_rate_limit" value="<?php echo $api_rate_limit; ?>" placeholder="Requests allowed per hour (leave empty for unlimited use)" min="1" tabindex="4">
            </div>
          </div>
          <div class="field">
            <label class="label">API Blacklisted IPs <small class="tooltip is-tooltip-multiline is-tooltip-right" data-tooltip="If supplied, access to the LicenseBox API will be blocked for these IP addresses." style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
            <div class="control">
              <input class="input" type="tags" name="api_blacklisted_ips" value="<?php
                if(!empty(set_value('api_blacklisted_ips'))) {
                  echo set_value('api_blacklisted_ips');
                }
                else{ 
                  echo $api_blacklisted_ips; 
                }?>" placeholder="IPs to be blacklisted" tabindex="6">
            </div>
            <?php echo form_error('api_blacklisted_ips', '<p class="help is-danger" style="margin-top: 1rem;">', '</p>'); ?>
          </div>
        </div>
      </div>
      <div class="field is-grouped">
        <div class="control">
          <button type="submit" class="button is-link show_loading">Save Changes</button>
        </div>
      </div>
      </form>
    </div>
    <div class="columns">
      <div class="column is-one-third">
        <div class="box" name="create_api_key" id="create_api_key">
          <?php 
          $hidden = array('type' => 'api'); 
          echo form_open('api_settings', array('id' => 'api_settings_form'), $hidden); ?>
          <div class="field">
            <label class="label">API Key</label>
            <div class="control">
              <input class="input" type="text" name="api_key" minlength="10" value="<?php echo strtoupper(substr(str_shuffle(MD5(microtime())), 0, 20)); ?>" placeholder="Enter api key to add" required>
            </div>
          </div>
          <div class="field">
            <label class="label">API Key Type <small class="tooltip is-tooltip-multiline is-tooltip-right" data-tooltip="Ensure that you use the appropriate API key for the intended purpose and never utilize the internal API in a client-side implementation." style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
            <div class="control is-expanded">
              <div class="select is-fullwidth">
                <select name="api_type" onchange="load_endpoints(this.value)" required>
                  <option disabled="" selected="">Select Type</option>
                  <option value="external">External/Client (for your clients to verify licenses etc.)</option>
                  <option value="internal">Internal (for you to create licenses etc.)</option>
                </select>
              </div>
            </div>
          </div>
          <div id="endpoints" class="field" style="display: none;">
            <label class="label">Endpoints <small class="tooltip is-tooltip-multiline is-tooltip-right" data-tooltip="Deselect the endpoints for which you do not wish to grant permission." style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
            <div id="external_api" class="control" style="display: none;">
              <div class="columns is-gapless is-desktop">
                <div class="column">
                  <input class="is-checkradio is-success" type="checkbox" value="check_connection_ext" name="endpoints[]" id="check_ext_connection" checked>
                  <label for="check_ext_connection" style="margin-left: 0px !important;">check_connection</label>
                  <input class="is-checkradio is-success" type="checkbox" value="latest_version" name="endpoints[]" id="latest_version" checked>
                  <label for="latest_version" style="margin-left: 0px !important;">latest_version</label>
                  <input class="is-checkradio is-success" type="checkbox" value="check_update" name="endpoints[]" id="check_update" checked>
                  <label for="check_update" style="margin-left: 0px !important;">check_update</label>
                  <input class="is-checkradio is-success" type="checkbox" value="get_update_size" name="endpoints[]" id="get_update_size" checked>
                  <label for="get_update_size" style="margin-left: 0px !important;">get_update_size</label>
                </div>
                <div class="column">
                  <input class="is-checkradio is-success" type="checkbox" value="download_update" name="endpoints[]" id="download_update" checked>
                  <label for="download_update" style="margin-left: 0px !important;">download_update</label>
                  <input class="is-checkradio is-success" type="checkbox" value="activate_license" name="endpoints[]" id="activate_license" checked>
                  <label for="activate_license" style="margin-left: 0px !important;">activate_license</label>
                  <input class="is-checkradio is-success" type="checkbox" value="verify_license" name="endpoints[]" id="verify_license" checked>
                  <label for="verify_license" style="margin-left: 0px !important;">verify_license</label>
                  <input class="is-checkradio is-success" type="checkbox" value="deactivate_license" name="endpoints[]" id="deactivate_license" checked>
                  <label for="deactivate_license" style="margin-left: 0px !important;">deactivate_license</label>
                </div>
              </div>
            </div>
            <div id="internal_api" class="control" style="display: none;">
              <div class="columns is-gapless is-desktop">
                <div class="column">
                  <input class="is-checkradio is-success" type="checkbox" value="check_connection_int" name="endpoints[]" id="check_connection_int" checked>
                  <label for="check_connection_int" style="margin-left: 0px !important;">check_connection</label>
                  <input class="is-checkradio is-success" type="checkbox" value="create_license" name="endpoints[]" id="create_license" checked>
                  <label for="create_license" style="margin-left: 0px !important;">create_license</label>
                  <input class="is-checkradio is-success" type="checkbox" value="edit_license" name="endpoints[]" id="edit_license" checked>
                  <label for="edit_license" style="margin-left: 0px !important;">edit_license</label>
                  <input class="is-checkradio is-success" type="checkbox" value="add_product" name="endpoints[]" id="add_product" checked>
                  <label for="add_product" style="margin-left: 0px !important;">add_product</label>
                  <input class="is-checkradio is-success" type="checkbox" value="get_product" name="endpoints[]" id="get_product" checked>
                  <label for="get_product" style="margin-left: 0px !important;">get_product</label>
                  <input class="is-checkradio is-success" type="checkbox" value="get_products" name="endpoints[]" id="get_products" checked>
                  <label for="get_products" style="margin-left: 0px !important;">get_products</label>
                  <input class="is-checkradio is-success" type="checkbox" value="get_license" name="endpoints[]" id="get_license" checked>
                  <label for="get_license" style="margin-left: 0px !important;">get_license</label>
                </div>
                <div class="column">
                  <input class="is-checkradio is-success" type="checkbox" value="mark_product_inactive" name="endpoints[]" id="mark_product_inactive" checked>
                  <label for="mark_product_inactive" style="margin-left: 0px !important;">mark_product_inactive</label>
                  <input class="is-checkradio is-success" type="checkbox" value="mark_product_active" name="endpoints[]" id="mark_product_active" checked>
                  <label for="mark_product_active" style="margin-left: 0px !important;">mark_product_active</label>
                  <input class="is-checkradio is-success" type="checkbox" value="search_license" name="endpoints[]" id="search_license" checked>
                  <label for="search_license" style="margin-left: 0px !important;">search_license</label>
                  <input class="is-checkradio is-success" type="checkbox" value="delete_license" name="endpoints[]" id="delete_license" checked>
                  <label for="delete_license" style="margin-left: 0px !important;">delete_license</label>
                  <input class="is-checkradio is-success" type="checkbox" value="block_license" name="endpoints[]" id="block_license" checked>
                  <label for="block_license" style="margin-left: 0px !important;">block_license</label>
                  <input class="is-checkradio is-success" type="checkbox" value="unblock_license" name="endpoints[]" id="unblock_license" checked>
                  <label for="unblock_license" style="margin-left: 0px !important;">unblock_license</label>
                  <input class="is-checkradio is-success" type="checkbox" value="deactivate_license_activations" name="endpoints[]" id="deactivate_license_activations" checked>
                  <label for="deactivate_license_activations" style="margin-left: 0px !important;">deactivate_license_activations</label>
                </div>
              </div>
            </div>
          </div>
          <div class="field p-t-xs">
            <input class="is-checkradio is-success" type="checkbox" name="ignore_limits" id="ignore_limits">
            <label for="ignore_limits" style="margin-left: 0px !important;">Give special permission? <small class="tooltip is-tooltip-multiline is-tooltip-right " data-tooltip="Choosing this option will grant this API key special access to the LicenseBox API, bypassing any established API limitations, such as rate limits, domain blacklists, and IP blacklists. This can be useful if you do not want your personal API keys to be restricted." style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
          </div>
          <div class="field is-grouped p-t-xs">
            <div class="control">
              <button type="submit" id="api_settings_form_submit" class="button is-link">Add</button>
            </div>
          </div>
          </form>
        </div>
      </div>
      <div class="column">
        <div class="box">
          <table class="table nots" style="width: 100%;">
            <thead>
              <tr>
                <th>API Key</th>
                <th>Type</th>
                <th>Endpoints</th>
                <th>Special</th>
                <th>Date Added</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($api_keys as $key): ?>
              <tr>
                <td><?php echo $key['key']; ?></td>
                <td><?php 
                if($key['controller']=='/api_external'){
                  $key_type = "<span class='tag is-success is-rounded'>External</span>";
                }else{
                  $key_type = "<span class='tag is-warning is-rounded'>Internal</span>";
                }
                echo $key_type; ?></td>
                <td>
                  <div class="tags">
                    <?php
                    if (!empty($key['endpoints'])) {
                        $endpoint_tags = explode(",", $key['endpoints']);
                        foreach ($endpoint_tags as $tag) {
                            echo "<span class='tag is-rounded'>" . $tag . "</span>";
                        }
                    } else {
                      echo "-";
                    } ?>
                  </div>
                </td>
                <td><span class='tag is-light is-rounded'><?php 
                  if($key['ignore_limits']){
                    echo "Yes";
                  }else{
                    echo "No";
                  } ?></span>
                </td>
                <td style="width:20%;"><?php 
                $originalDate = $key['date_created'];
                $newDate = date($this->config->item('date_format'), strtotime($originalDate));
                echo $newDate; ?>
                </td>
                <td>
                  <div class="buttons is-centered"><?php 
                    $hidden = array('key' => $key['key']);
                    $js = 'id="delete_form_'.$key['id'].'"';
                    echo form_open('/settings/delete_api_key',$js, $hidden); ?>
                    <button type="button" id="api_settings_form_submit" data-id="<?php echo $key['id']; ?>" data-title="api key" data-body="Please note that all product integrations which are currently using this api key (<b><?php echo $key['key']; ?></b>) will stop working." title="Delete Api Key" class="button with-delete-confirmation is-danger is-small" style="padding-top: 0px;padding-bottom: 0px;"><i class="fa fa-trash p-r-xs"></i>Delete</button>
                    </form>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <?php if(empty($api_keys)){ ?>
            <center>
              <p class="p-t-sm">No data available in table</p>
            </center>
          <?php }?>
        </div>
      </div>
    </div>
  </div>
</div>