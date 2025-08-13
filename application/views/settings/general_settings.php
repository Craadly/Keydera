<div class="container is-fluid main_body">
  <div class="section">
    <h1 class="title">
      <?php echo $title; ?>
    </h1>
    <?php echo generate_breadcrumb('General Settings'); ?>
    <?php if($this->session->flashdata('general_status')){
      $flash = $this->session->flashdata('general_status');
      echo '<div class="notification is-'.$flash['type'].'"><button class="delete"></button>'.$flash['message'].'</div>'; 
      if($flash['type']=='success'){
        echo '<script>setTimeout(function(){document.getElementsByClassName("notification")[0].style.display="none";},4000);</script>';
      } 
    } ?>
    <div class="columns">
      <div class="column is-two-thirds">
        <div class="box">
          <?php 
          $hidden = array('type' => 'general'); 
          echo form_open('general_settings', array('id' => 'general_settings_form'), $hidden); ?>
          <div class="columns" style="margin-bottom: 0px;">
            <div class="column is-one-third">
              <div class="field">
                <label class="label">LicenseBox Theme</label>
                <div class="control">
                  <div class="select" style="width:100%;">
                    <select name="licensebox_theme" style="width:100%;" required>
                      <?php if($licensebox_theme=="classic"):?>
                      <option value="classic" selected>Classic</option>
                      <option value="flat">Flat</option>
                      <option value="material">Material</option>
                      <?php elseif($licensebox_theme=="flat"): ?>
                      <option value="classic">Classic</option>
                      <option value="flat" selected>Flat</option>
                      <option value="material">Material</option>
                      <?php else: ?>
                      <option value="classic">Classic</option>
                      <option value="flat">Flat</option>
                      <option value="material" selected>Material</option>
                      <?php endif; ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="column">
              <div class="field">
                <label class="label">Timezone</label>
                <div style="padding-bottom: 3px;">
                  <select name="server_timezone" style="width: 100%" class="is-select2" required>
                    <?php foreach(get_timezones() as $t) { ?>
                    <?php if($t['zone']==$server_timezone){ ?>
                    <option value="<?php echo $t['zone']; ?>" selected>
                      <?php echo $t['diff_from_GMT'] . ' - ' . $t['zone']; ?></option>
                    <?php }else{ ?>
                    <option value="<?php echo $t['zone'] ?>"><?php echo $t['diff_from_GMT'] . ' - ' . $t['zone'] ?>
                    </option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
                <?php echo form_error('server_timezone', '<p class="help is-danger">', '</p>'); ?>
              </div>
            </div>
          </div>
          <div class="field">
            <label class="label">License Code Format</label>
            <div class="control">
              <input class="input" type="text" name="license_format" value="<?php echo $license_format; ?>" placeholder="Enter license code format" tabindex="1" required>
            </div>
            <p class="help">Use {[X]} for any number from 0 to 9, {[Y]} for any letter from a to z, {[Z]} for both numbers and letters, and any other characters will not be replaced.</p>
          </div>
          <div class="field">
            <label class="label">Whitelisted IPs for Admin Panel (use * for wildcard) <small class="tooltip is-tooltip-multiline is-tooltip-right" style="font-weight: 400;" data-tooltip="Enter the public IP addresses from where you want to allow access to the admin panel, access will be blocked for all other IPs."><i class="fas fa-question-circle"></i></small> <small class="has-text-weight-normal has-text-grey"> (Your current IP is <strong><?=$current_user_ip?></strong>)</small></label>
            <div class="control">
              <input class="input" type="tags" name="whitelist_ips" value="<?php
              if(!empty(set_value('whitelist_ips'))) {
                echo set_value('whitelist_ips');
              }
              else{ 
                echo $whitelist_ips; 
              }?>" placeholder="Whitelisted IPs" tabindex="6">
            </div>
            <?php echo form_error('whitelist_ips', '<p class="help is-danger" style="margin-top: 1rem;">', '</p>'); ?>
          </div>
          <div class="columns" style="margin-bottom: 0px;">
            <div class="column">
              <div class="field">
                <label class="label">Entries for failed activation attempts <small class="tooltip is-tooltip-multiline is-tooltip-right" style="font-weight: 400;" data-tooltip="If checked, failed activation attempts will be recorded. If not checked, they will only be visible in activity logs and not on the activations page."><i class="fas fa-question-circle"></i></small></label>
                <?php if($failed_activation_logs==1):?>
                  <input class="is-checkradio is-success" type="checkbox" name="failed_activation_logs" id="failed_activation_logs" checked>
                <?php else: ?>
                  <input class="is-checkradio is-success" type="checkbox" name="failed_activation_logs" id="failed_activation_logs">
                <?php endif; ?>
                <label for="failed_activation_logs" style="margin-left: 0px !important;">Add entries for failed activation attempts?</label>
              </div>
              <div class="field">
                <label class="label">Auto deactivate old activations on new activation <small class="tooltip is-tooltip-multiline is-tooltip-right" style="font-weight: 400;" data-tooltip="If checked, all previous activations of a license will be automatically marked as inactive on any new activation of the provided license."><i class="fas fa-question-circle"></i></small></label>
                <?php if($auto_deactivate_activations==1):?>
                  <input class="is-checkradio is-danger" type="checkbox" name="auto_deactivate_activations" id="auto_deactivate_activations" checked>
                <?php else: ?>
                  <input class="is-checkradio is-danger" type="checkbox" name="auto_deactivate_activations" id="auto_deactivate_activations">
                <?php endif; ?>
                <label for="auto_deactivate_activations" style="margin-left: 0px !important;">Deactivate old activations of license on new activation?</label>
              </div>
            </div>
            <div class="column">
              <div class="field">
                <label class="label">Entries for failed update download attempts <small class="tooltip is-tooltip-multiline is-tooltip-left" style="font-weight: 400;" data-tooltip="If checked, failed update download attempts will be recorded. If not checked, they will only be visible in activity logs and not on the update downloads page."><i class="fas fa-question-circle"></i></small></label>
                <?php if($failed_update_download_logs==1):?>
                  <input class="is-checkradio is-success" type="checkbox" name="failed_update_download_logs" id="failed_update_download_logs" checked>
                <?php else: ?>
                  <input class="is-checkradio is-success" type="checkbox" name="failed_update_download_logs" id="failed_update_download_logs">
                <?php endif; ?>
                <label for="failed_update_download_logs" style="margin-left: 0px !important;">Add entries for failed update download attempts?</label>
              </div>
              <div class="field">
                <label class="label">Auto add first activated domain as 'licensed_domain' <small class="tooltip is-tooltip-multiline is-tooltip-left" style="font-weight: 400;" data-tooltip="If checked, the domain of the initial activation of any license will automatically get added as a 'Licensed Domain' for that license."><i class="fas fa-question-circle"></i></small></label>
                <?php if($auto_add_licensed_domain==1):?>
                  <input class="is-checkradio is-danger" type="checkbox" name="auto_add_licensed_domain" id="auto_add_licensed_domain" checked>
                <?php else: ?>
                  <input class="is-checkradio is-danger" type="checkbox" name="auto_add_licensed_domain" id="auto_add_licensed_domain">
                <?php endif; ?>
                <label for="auto_add_licensed_domain" style="margin-left: 0px !important;">Add the domain of first activation as licensed domain?</label>
              </div>
            </div>
          </div>
          <div class="field is-grouped">
            <div class="control">
              <button type="submit" id="general_settings_form_submit" class="button is-link">Save Changes</button>
            </div>
          </div>
        </div>
      </div>
      <div class="column">
        <div class="box">
          <div class="field">
            <?php
              if($envato_api_token){
                $this->load->helper('envato_helper');
                $response = envato_api_connection_test($this->user_model->get_config_from_db('envato_api_token', true));
                if(isset($response['total-items'])){
                  $message = "API Connection OK";
                  $tooltip_message = "Connection to the Envato API was successful.";
                  $tag_type = "success";
                }else{
                  $message = "API Connection Failed";
                  $tooltip_message = "Connection to the Envato API failed, the API token may be invalid or your API token may be rate limited by Envato due to overuse.";
                  $tag_type = "danger";
                }
              }
            ?>
            <label class="label">Envato API Token <span toggle="#envato_api_token" style="font-weight: 400;" class="tag toggle-password is-link is-rounded">show</span> <?php if($envato_api_token){ ?><span style="font-weight: 400;" class="tag tooltip <?php echo ($tag_type=="danger")?"is-tooltip-multiline":""; ?> is-<?=$tag_type?> is-rounded" data-tooltip="<?=$tooltip_message?>"><?=$message?></span> <?php } ?> <small class="has-text-weight-normal has-text-grey"> (<a href="https://build.envato.com/create-token/" target="_blank">Need one?</a>)</small></label>
            <div class="control">
              <input class="input" type="password" id="envato_api_token" name="envato_api_token" value="<?php echo $envato_api_token; ?>" placeholder="Enter your Envato API token" autocomplete="new-password" tabindex="3">
            </div>
          </div>
          <div class="field">
            <label class="label">Default Envato License Uses Limit <small class="tooltip is-tooltip-multiline is-tooltip-left" style="font-weight: 400;" data-tooltip="​Enter the default license uses limit which will be added to all newly added Envato purchase codes in LicenseBox. Leave it empty to allow unlimited usage by default.​"><i class="fas fa-question-circle"></i></small></label>
            <div class="control">
              <input class="input" type="number" min="1" step="1" id="envato_use_limit" name="envato_use_limit" value="<?php echo $envato_use_limit; ?>" placeholder="Enter default license uses for Envato purchase codes" tabindex="3">
            </div>
            <?php echo form_error('envato_use_limit', '<p class="help is-danger">', '</p>'); ?>
          </div>
          <div class="field">
            <label class="label">Default Envato Parallel Uses Limit <small class="tooltip is-tooltip-multiline is-tooltip-left" style="font-weight: 400;" data-tooltip="​Enter the default parallel uses limit which will be added to all newly added Envato purchase codes in LicenseBox. Leave it empty to allow unlimited parallel usage by default.​"><i class="fas fa-question-circle"></i></small></label>
            <div class="control">
              <input class="input" type="number" min="1" step="1" id="envato_parallel_use_limit" name="envato_parallel_use_limit" value="<?php echo $envato_parallel_use_limit; ?>" placeholder="Enter default parallel uses for Envato purchase codes" tabindex="3">
            </div>
            <?php echo form_error('envato_parallel_use_limit', '<p class="help is-danger">', '</p>'); ?>
          </div>
          <div class="field is-grouped">
            <div class="control">
              <button type="submit" id="general_settings_form_submit" class="button is-link">Save Changes</button>
            </div>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>