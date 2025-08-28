<div class="main-content main-content-expanded">
  <div class="section">
    <div class="page-header">
      <h1 class="page-title"><?php echo $title; ?></h1>
    </div>

    <?php if($this->session->flashdata('general_status')){ $flash = $this->session->flashdata('general_status'); ?>
      <div class="notification is-<?php echo $flash['type']; ?> is-light" style="margin-bottom: 1.5rem;">
        <button class="delete"></button>
        <?php echo $flash['message']; ?>
      </div>
      <?php if($flash['type']=='success'){ ?>
        <script>setTimeout(function(){var n=document.getElementsByClassName('notification');if(n[0]) n[0].style.display='none';},4000);</script>
      <?php } ?>
    <?php } ?>

    <?php echo form_open('general_settings', array('id' => 'general_settings_form')); ?>
      <div class="api-settings-layout">
        <div class="settings-forms-grid" style="grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));">

          <!-- General Settings Card -->
          <div class="shadcn-card">
            <div class="card-header">
              <h3 class="card-title">General Settings</h3>
              <p class="card-description">Manage theme, timezone, and access controls.</p>
            </div>
            <div class="card-content">
              <div class="form-group">
                <label class="form-label" for="keydera_theme">Keydera Theme</label>
                <select id="keydera_theme" name="keydera_theme" class="shadcn-select" required>
                  <option value="classic" <?php echo set_select('keydera_theme', 'classic', $keydera_theme == 'classic'); ?>>Classic</option>
                  <option value="flat" <?php echo set_select('keydera_theme', 'flat', $keydera_theme == 'flat'); ?>>Flat</option>
                  <option value="material" <?php echo set_select('keydera_theme', 'material', $keydera_theme == 'material'); ?>>Material</option>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label" for="server_timezone">Timezone</label>
                <select id="server_timezone" name="server_timezone" class="shadcn-select is-select2" style="width:100%" required>
                  <?php foreach(get_timezones() as $t): ?>
                    <option value="<?php echo $t['zone']; ?>" <?php echo ($t['zone'] == $server_timezone) ? 'selected' : ''; ?>>
                      <?php echo $t['diff_from_GMT'] . ' - ' . $t['zone']; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <?php echo form_error('server_timezone', '<p class="form-error">', '</p>'); ?>
              </div>

              <div class="form-group">
                <label class="form-label" for="license_format">License Code Format</label>
                <input id="license_format" class="shadcn-input" type="text" name="license_format" value="<?php echo $license_format; ?>" placeholder="e.g., XXXX-XXXX-XXXX-XXXX" required>
                <p class="form-description">Use {[X]} for numbers, {[Y]} for letters, {[Z]} for alphanumeric.</p>
              </div>

              <div class="form-group">
                <label class="form-label" for="whitelist_ips">Whitelisted IPs for Admin Panel</label>
                <input id="whitelist_ips" class="shadcn-input" type="tags" name="whitelist_ips" value="<?php echo set_value('whitelist_ips', $whitelist_ips); ?>" placeholder="127.0.0.1, 192.168.1.*">
                <p class="form-description">Use * for wildcard. Your IP: <strong><?php echo $current_user_ip; ?></strong></p>
                <?php echo form_error('whitelist_ips', '<p class="form-error">', '</p>'); ?>
              </div>
            </div>
          </div>

          <!-- Behavior Settings Card -->
          <div class="shadcn-card">
            <div class="card-header">
              <h3 class="card-title">Behavior Settings</h3>
              <p class="card-description">Configure logging and automation preferences.</p>
            </div>
            <div class="card-content">
              <div class="form-group">
                <div class="shadcn-checkbox-group">
                  <input class="shadcn-checkbox" type="checkbox" name="failed_activation_logs" id="failed_activation_logs" <?php echo ($failed_activation_logs==1)?'checked':''; ?>>
                  <label for="failed_activation_logs" class="shadcn-checkbox-label">
                    Log Failed Activations
                    <span class="shadcn-checkbox-description">Record failed activation attempts in the main activations log.</span>
                  </label>
                </div>
              </div>
              <div class="form-group">
                <div class="shadcn-checkbox-group">
                  <input class="shadcn-checkbox" type="checkbox" name="failed_update_download_logs" id="failed_update_download_logs" <?php echo ($failed_update_download_logs==1)?'checked':''; ?>>
                  <label for="failed_update_download_logs" class="shadcn-checkbox-label">
                    Log Failed Update Downloads
                    <span class="shadcn-checkbox-description">Record failed update download attempts in the main downloads log.</span>
                  </label>
                </div>
              </div>
              <div class="form-group">
                <div class="shadcn-checkbox-group">
                  <input class="shadcn-checkbox" type="checkbox" name="auto_deactivate_activations" id="auto_deactivate_activations" <?php echo ($auto_deactivate_activations==1)?'checked':''; ?>>
                  <label for="auto_deactivate_activations" class="shadcn-checkbox-label">
                    Auto Deactivate Old Activations
                    <span class="shadcn-checkbox-description">On new activation, automatically mark all previous activations of that license as inactive.</span>
                  </label>
                </div>
              </div>
              <div class="form-group">
                <div class="shadcn-checkbox-group">
                  <input class="shadcn-checkbox" type="checkbox" name="auto_add_licensed_domain" id="auto_add_licensed_domain" <?php echo ($auto_add_licensed_domain==1)?'checked':''; ?>>
                  <label for="auto_add_licensed_domain" class="shadcn-checkbox-label">
                    Auto Add Licensed Domain
                    <span class="shadcn-checkbox-description">Set the domain from the first activation as the 'Licensed Domain' for that license.</span>
                  </label>
                </div>
              </div>
            </div>
          </div>

        </div>

        <!-- Envato Settings Card -->
        <div class="shadcn-card" style="margin-top: 1.5rem;">
          <div class="card-header">
            <h3 class="card-title">Envato Settings</h3>
            <p class="card-description">Manage your Envato API token and default license settings.</p>
          </div>
          <div class="card-content">
            <div class="helper-grid">
                <div class="form-group">
                  <label class="form-label" for="envato_api_token">
                    Envato API Token
                    <span toggle="#envato_api_token" class="tag toggle-password is-link is-rounded" style="font-weight:400; cursor:pointer;">show</span>
                    <?php if($envato_api_token){
                      $this->load->helper('envato_helper');
                      $response = envato_api_connection_test($this->user_model->get_config_from_db('envato_api_token', true));
                      if(isset($response['total-items'])){
                        echo '<span class="modern-tag tag-success">Connection OK</span>';
                      } else {
                        echo '<span class="modern-tag tag-danger">Connection Failed</span>';
                      }
                    } ?>
                  </label>
                  <input class="shadcn-input" type="password" id="envato_api_token" name="envato_api_token" value="<?php echo $envato_api_token; ?>" placeholder="Enter your Envato API token" autocomplete="new-password">
                  <p class="form-description">(<a href="https://build.envato.com/create-token/" target="_blank" rel="noopener">Need one?</a>)</p>
                </div>
                <div class="form-group"></div>
                <div class="form-group">
                  <label class="form-label" for="envato_use_limit">Default License Uses</label>
                  <input id="envato_use_limit" class="shadcn-input" type="number" min="1" step="1" name="envato_use_limit" value="<?php echo $envato_use_limit; ?>" placeholder="e.g., 1">
                  <p class="form-description">Default uses limit for new Envato licenses. Leave empty for unlimited.</p>
                  <?php echo form_error('envato_use_limit', '<p class="form-error">', '</p>'); ?>
                </div>

                <div class="form-group">
                  <label class="form-label" for="envato_parallel_use_limit">Default Parallel Uses</label>
                  <input id="envato_parallel_use_limit" class="shadcn-input" type="number" min="1" step="1" name="envato_parallel_use_limit" value="<?php echo $envato_parallel_use_limit; ?>" placeholder="e.g., 1">
                  <p class="form-description">Default parallel uses for new Envato licenses. Leave empty for unlimited.</p>
                  <?php echo form_error('envato_parallel_use_limit', '<p class="form-error">', '</p>'); ?>
                </div>
            </div>
          </div>
        </div>

        <div class="form-actions" style="background: transparent; padding: 1.5rem 0 0 0; border: none;">
          <button type="submit" id="general_settings_form_submit" class="shadcn-button show_loading">
            <i class="fas fa-save"></i>
            Save All Settings
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
