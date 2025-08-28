<<<<<<< HEAD
<div class="main-content main-content-expanded">
  <div class="section">
    <div class="page-header">
      <h1 class="page-title"><?php echo $title; ?></h1>
    </div>

    <?php if($this->session->flashdata('api_settings_status')){
      $flash = $this->session->flashdata('api_settings_status'); ?>
      <div class="notification is-<?php echo $flash['type']; ?> is-light" style="margin-bottom: 1.5rem;">
        <button class="delete"></button>
        <?php echo $flash['message']; ?>
      </div>
      <?php if($flash['type']=='success'){ ?>
        <script>setTimeout(function(){var n=document.getElementsByClassName('notification');if(n[0]) n[0].style.display='none';},4000);</script>
      <?php } ?>
    <?php } ?>

    <div class="api-settings-layout">
      <div class="settings-forms-grid">
        <!-- General API Settings Card -->
        <div class="shadcn-card">
          <?php $hidden = array('type' => 'general'); echo form_open('api_settings', '', $hidden); ?>
            <div class="card-header">
              <h3 class="card-title">General API Settings</h3>
              <p class="card-description">Configure rate limits and blacklist controls.</p>
            </div>
            <div class="card-content">
              <div class="form-group">
                <label class="form-label" for="auto_domain_blacklist">Auto Blacklist Domains (failed attempts)</label>
                <input id="auto_domain_blacklist" class="shadcn-input" type="number" name="auto_domain_blacklist" value="<?php echo $auto_domain_blacklist; ?>" placeholder="e.g., 5" min="1">
                <p class="form-description">Auto-blacklist a domain after this many failed activation attempts.</p>
              </div>

              <div class="form-group">
                <label class="form-label" for="auto_ip_blacklist">Auto Blacklist IPs (failed attempts)</label>
                <input id="auto_ip_blacklist" class="shadcn-input" type="number" name="auto_ip_blacklist" value="<?php echo $auto_ip_blacklist; ?>" placeholder="e.g., 10" min="1">
                 <p class="form-description">Auto-blacklist an IP after this many failed attempts.</p>
              </div>

              <div class="form-group">
                <label class="form-label" for="api_rate_limit_method">Rate Limiting Method</label>
                <select id="api_rate_limit_method" name="api_rate_limit_method" class="shadcn-select" required>
                  <option value="api_key" <?php echo ($api_rate_limit_method=="api_key") ? 'selected' : ''; ?>>Limit per API Key</option>
                  <option value="ip_address" <?php echo ($api_rate_limit_method=="ip_address") ? 'selected' : ''; ?>>Limit per IP Address</option>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label" for="api_rate_limit">Rate Limit (requests per hour)</label>
                <input id="api_rate_limit" class="shadcn-input" type="number" name="api_rate_limit" value="<?php echo $api_rate_limit; ?>" placeholder="e.g., 1000" min="1">
                <p class="form-description">Leave empty for unlimited requests.</p>
              </div>

              <div class="form-group">
                <label class="form-label" for="api_blacklisted_domains">API Blacklisted Domains</label>
                <input id="api_blacklisted_domains" class="shadcn-input" type="tags" name="api_blacklisted_domains" value="<?php echo set_value('api_blacklisted_domains', $api_blacklisted_domains); ?>" placeholder="domain.com, another.com">
                <?php echo form_error('api_blacklisted_domains', '<p class="form-error">', '</p>'); ?>
              </div>

              <div class="form-group">
                <label class="form-label" for="api_blacklisted_ips">API Blacklisted IPs</label>
                <input id="api_blacklisted_ips" class="shadcn-input" type="tags" name="api_blacklisted_ips" value="<?php echo set_value('api_blacklisted_ips', $api_blacklisted_ips); ?>" placeholder="127.0.0.1, 192.168.1.1">
                <?php echo form_error('api_blacklisted_ips', '<p class="form-error">', '</p>'); ?>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="shadcn-button show_loading">
                <i class="fas fa-save"></i>
                Save General Settings
              </button>
            </div>
          </form>
        </div>

        <!-- Create API Key Card -->
        <div class="shadcn-card" id="create_api_key">
          <?php $hidden = array('type' => 'api'); echo form_open('api_settings', array('id' => 'api_settings_form'), $hidden); ?>
            <div class="card-header">
              <h3 class="card-title">Create API Key</h3>
              <p class="card-description">Generate a new key with specific permissions.</p>
            </div>
            <div class="card-content">
              <div class="form-group">
                <label class="form-label" for="api_key">API Key</label>
                <input id="api_key" class="shadcn-input" type="text" name="api_key" minlength="10" value="<?php echo strtoupper(substr(str_shuffle(MD5(microtime())), 0, 20)); ?>" required>
              </div>

              <div class="form-group">
                <label class="form-label" for="api_type">API Key Type</label>
                <select id="api_type" class="shadcn-select" name="api_type" onchange="load_endpoints(this.value)" required>
                  <option disabled selected>Select Type...</option>
                  <option value="external">External/Client</option>
                  <option value="internal">Internal/Admin</option>
                </select>
                 <p class="form-description">External for client apps, Internal for admin tasks.</p>
              </div>

              <div id="endpoints" class="form-group" style="display:none;">
                <label class="form-label">Endpoints</label>
                <div id="external_api" class="endpoint-grid" style="display:none;">
                  <?php $ext_endpoints = ['check_connection_ext' => 'check_connection', 'latest_version' => 'latest_version', 'check_update' => 'check_update', 'get_update_size' => 'get_update_size', 'download_update' => 'download_update', 'activate_license' => 'activate_license', 'verify_license' => 'verify_license', 'deactivate_license' => 'deactivate_license'];
                  foreach($ext_endpoints as $val => $label): ?>
                  <div class="shadcn-checkbox-group">
                    <input class="shadcn-checkbox" type="checkbox" value="<?php echo $val; ?>" name="endpoints[]" id="<?php echo $val; ?>" checked>
                    <label for="<?php echo $val; ?>" class="shadcn-checkbox-label"><?php echo $label; ?></label>
                  </div>
                  <?php endforeach; ?>
                </div>
                <div id="internal_api" class="endpoint-grid" style="display:none;">
                  <?php $int_endpoints = ['check_connection_int' => 'check_connection', 'create_license' => 'create_license', 'edit_license' => 'edit_license', 'add_product' => 'add_product', 'get_product' => 'get_product', 'get_products' => 'get_products', 'get_license' => 'get_license', 'mark_product_inactive' => 'mark_product_inactive', 'mark_product_active' => 'mark_product_active', 'search_license' => 'search_license', 'delete_license' => 'delete_license', 'block_license' => 'block_license', 'unblock_license' => 'unblock_license', 'deactivate_license_activations' => 'deactivate_license_activations'];
                  foreach($int_endpoints as $val => $label): ?>
                  <div class="shadcn-checkbox-group">
                    <input class="shadcn-checkbox" type="checkbox" value="<?php echo $val; ?>" name="endpoints[]" id="<?php echo $val; ?>" checked>
                    <label for="<?php echo $val; ?>" class="shadcn-checkbox-label"><?php echo $label; ?></label>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>

              <div class="form-group">
                <div class="shadcn-checkbox-group">
                  <input class="shadcn-checkbox" type="checkbox" name="ignore_limits" id="ignore_limits">
                  <label for="ignore_limits" class="shadcn-checkbox-label">
                    Give special permission?
                    <span class="shadcn-checkbox-description">Bypass API rate limits and blacklists for this key.</span>
                  </label>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" id="api_settings_form_submit" class="shadcn-button">
                <i class="fas fa-plus"></i>
                Create API Key
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Existing API Keys Card -->
      <div class="shadcn-card">
        <div class="card-header">
          <h3 class="card-title">Existing API Keys</h3>
          <p class="card-description">Manage and revoke API access.</p>
        </div>
        <div class="card-content" style="padding: 0;">
          <div class="table-container">
            <table class="modern-table" style="width: 100%;">
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
                  <td><code><?php echo $key['key']; ?></code></td>
                  <td>
                    <?php if($key['controller']=='/api_external'): ?>
                      <span class="modern-tag tag-success">External</span>
                    <?php else: ?>
                      <span class="modern-tag tag-warning">Internal</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <div class="endpoint-tags">
                      <?php if (!empty($key['endpoints'])) { $endpoint_tags = explode(",", $key['endpoints']); foreach ($endpoint_tags as $tag) { echo "<span class='modern-tag'>" . $tag . "</span>"; } } else { echo "-"; } ?>
                    </div>
                  </td>
                  <td>
                    <?php if($key['ignore_limits']): ?>
                      <span class="modern-tag tag-special">Yes</span>
                    <?php else: ?>
                      <span class="modern-tag">No</span>
                    <?php endif; ?>
                  </td>
                  <td><?php echo date($this->config->item('date_format'), strtotime($key['date_created'])); ?></td>
                  <td>
                    <?php echo form_open('/settings/delete_api_key', 'id="delete_form_'.$key['id'].'"', array('key' => $key['key'])); ?>
                      <button type="button"
                              data-id="<?php echo $key['id']; ?>"
                              data-title="API key"
                              data-body="This will revoke access for <b><?php echo $key['key']; ?></b>. Associated integrations will stop working."
                              class="shadcn-button-destructive with-delete-confirmation">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <?php if(empty($api_keys)): ?>
              <p style="text-align: center; padding: 2rem; color: #64748b;">No API keys found.</p>
            <?php endif;?>
          </div>
=======
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
            <label class="label">Blacklist domain after how many failed attempts? <small class="tooltip is-tooltip-multiline is-tooltip-right" data-tooltip="If the specified number of failed activation and update download attempts are reached, the user's domain will be blacklisted automatically. (Note: This feature will only work if the Keydera Cron is set up correctly and the option to 'add entries for failed activation and download attempts' is enabled.)" style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
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
            <label class="label">API Blacklisted Domains <small class="tooltip is-tooltip-multiline is-tooltip-right" data-tooltip="If specified, access to the Keydera API will be blocked for these domains." style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
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
            <label class="label">Blacklist IP after how many failed attempts? <small class="tooltip is-tooltip-multiline is-tooltip-left" data-tooltip="If the specified number of failed activation and update download attempts are reached, the user's IP will be automatically blacklisted. (Note: This will only work if the Keydera Cron is set up correctly and entries for failed activation and download attempts are allowed.)" style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
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
            <label class="label">API Blacklisted IPs <small class="tooltip is-tooltip-multiline is-tooltip-right" data-tooltip="If supplied, access to the Keydera API will be blocked for these IP addresses." style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
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
            <label for="ignore_limits" style="margin-left: 0px !important;">Give special permission? <small class="tooltip is-tooltip-multiline is-tooltip-right " data-tooltip="Choosing this option will grant this API key special access to the Keydera API, bypassing any established API limitations, such as rate limits, domain blacklists, and IP blacklists. This can be useful if you do not want your personal API keys to be restricted." style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
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
>>>>>>> origin/main
        </div>
      </div>
    </div>
  </div>
</div>
<<<<<<< HEAD
<script>
function load_endpoints(api_type){
  if(api_type == 'external'){
    document.getElementById('endpoints').style.display = 'block';
    document.getElementById('external_api').style.display = 'grid';
    document.getElementById('internal_api').style.display = 'none';
  } else if(api_type == 'internal'){
    document.getElementById('endpoints').style.display = 'block';
    document.getElementById('internal_api').style.display = 'grid';
    document.getElementById('external_api').style.display = 'none';
  }
}
</script>

<style>
.page-header { margin-bottom: 1.5rem; }
.page-title { font-size: 1.75rem; font-weight: 700; color: var(--text-primary); }
.settings-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem; }
.settings-card { background: var(--bg-card); border-radius: 12px; box-shadow: var(--shadow); overflow: hidden; }
.form-section { padding: 1.5rem; border-bottom: 1px solid var(--border); }
.form-section:last-child { border-bottom: none; }
.section-header { margin-bottom: 1rem; }
.section-title { font-size: 1.1rem; font-weight: 600; color: var(--text-primary); display: flex; align-items: center; gap: .5rem; }
.section-title i { color: var(--primary); }
.section-subtitle { color: var(--text-secondary); font-size: .875rem; margin: 0; }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.form-group { display: flex; flex-direction: column; }
.form-group.full-width { grid-column: 1 / -1; }
.form-label { font-weight: 500; color: var(--text-primary); margin-bottom: .5rem; display: flex; align-items: center; justify-content: space-between; }
.help-icon { color: var(--text-muted); cursor: help; font-size: .85rem; }
.form-input, .form-select, .form-textarea { padding: .75rem 1rem; border: 2px solid var(--border); border-radius: 8px; font-size: .9rem; transition: all .2s; background: #fff; }
.form-input, .form-select { height: 42px; }
.form-input:focus, .form-select:focus, .form-textarea:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(99, 102, 241, .1); }
.form-input::placeholder, .form-textarea::placeholder { color: var(--text-muted); opacity: 1; }
.form-error { color: var(--danger); font-size: .75rem; margin-top: .25rem; font-weight: 500; }
.checkbox-container { display: flex; align-items: flex-start; cursor: pointer; font-weight: 500; color: var(--text-primary); position: relative; }
.checkbox-input { position: absolute; opacity: 0; cursor: pointer; }
.checkmark { height: 20px; width: 20px; background: #fff; border: 2px solid var(--border); border-radius: 4px; margin-right: .75rem; transition: all .2s; flex-shrink: 0; margin-top: 2px; }
.checkbox-container:hover .checkmark { border-color: var(--primary); }
.checkbox-container .checkbox-input:checked ~ .checkmark { background-color: var(--primary); border-color: var(--primary); }
.checkmark:after { content: ""; position: absolute; display: none; left: 6px; top: 2px; width: 6px; height: 10px; border: solid #fff; border-width: 0 2px 2px 0; transform: rotate(45deg); }
.checkbox-container .checkbox-input:checked ~ .checkmark:after { display: block; }
.checkbox-help { color: var(--text-secondary); font-size: .75rem; margin: 0; font-weight: 400; margin-top: .25rem; }
.endpoint-columns { display: grid; grid-template-columns: 1fr 1fr; gap: .5rem 1.5rem; }
.endpoint-col { display: flex; flex-direction: column; gap: .5rem; }
.form-actions { padding: 1rem 1.5rem; background: var(--bg-main); border-top: 1px solid var(--border); display: flex; justify-content: flex-end; }
.btn { padding: .65rem 1rem; border-radius: 8px; font-weight: 500; font-size: .875rem; cursor: pointer; transition: all .2s; display: inline-flex; align-items: center; gap: .5rem; border: none; text-decoration: none; }
.btn-primary { background: var(--primary); color: #fff; }
.btn-primary:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: var(--shadow-lg); }
.table-container { overflow-x: auto; }

/* Scoped: style tags within the Existing API Keys table only */
.settings-card .table .tags { display: flex; flex-wrap: wrap; gap: .35rem; }
.settings-card .table .tag {
  display: inline-flex;
  align-items: center;
  gap: .35rem;
  height: 26px;
  padding: 0 .55rem;
  border-radius: 999px;
  background: rgba(99, 102, 241, 0.10);
  color: var(--primary);
  border: 1px solid rgba(99, 102, 241, 0.25);
  font-size: 12px;
  font-weight: 600;
}
.settings-card .table .tag.is-success { background: rgba(16,185,129,.12); color: var(--success); border-color: rgba(16,185,129,.25); }
.settings-card .table .tag.is-warning { background: rgba(245,158,11,.12); color: var(--warning); border-color: rgba(245,158,11,.25); }
.settings-card .table .tag.is-light { background: var(--bg-main); color: var(--text-secondary); border-color: var(--border); }

@media (max-width: 1024px) {
  .settings-grid { grid-template-columns: 1fr; }
  .form-grid { grid-template-columns: 1fr; }
}
</style>
=======
>>>>>>> origin/main
