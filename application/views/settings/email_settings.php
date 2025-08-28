<div class="main-content main-content-expanded">
  <div class="section">
    <div class="page-header">
      <h1 class="page-title"><?php echo $title; ?></h1>
      <?php echo form_open('/send_test_email', 'id="test_email_form"', ['mail_type' => 'send_email_to_admin']); ?>
        <button type="submit" id="test_email_form_submit" class="shadcn-button shadcn-button-secondary show_loading" data-tooltip="Send a test mail to your email address (<?php echo $admin_email; ?>)">
          <i class="fas fa-envelope"></i>
          Send Test Mail
        </button>
      </form>
    </div>

    <?php if($this->session->flashdata('email_settings_status')){ $flash = $this->session->flashdata('email_settings_status'); ?>
      <div class="notification is-<?php echo $flash['type']; ?> is-light" style="margin-bottom: 1.5rem;">
        <button class="delete"></button>
        <?php echo $flash['message']; ?>
      </div>
      <?php if($flash['type']=='success'){ ?>
        <script>setTimeout(function(){var n=document.getElementsByClassName('notification');if(n[0]) n[0].style.display='none';},5000);</script>
      <?php } ?>
    <?php } ?>

    <?php echo form_open('email_settings', array('id' => 'email_settings_form')); ?>
      <div class="api-settings-layout">
        <!-- Email Configuration Card -->
        <div class="shadcn-card">
          <div class="card-header">
            <h3 class="card-title">Email Configuration</h3>
            <p class="card-description">Manage transport method and server details.</p>
          </div>
          <div class="card-content">
            <div class="helper-grid">
              <div class="form-group">
                <label class="form-label" for="email_method">Email Method</label>
                <select id="email_method" name="email_method" class="shadcn-select" required>
                  <option value="default" <?php echo set_select('email_method', 'default', $email_method == 'default'); ?>>PHP Mail (default)</option>
                  <option value="sendmail" <?php echo set_select('email_method', 'sendmail', $email_method == 'sendmail'); ?>>Sendmail</option>
                  <option value="smtp" <?php echo set_select('email_method', 'smtp', $email_method == 'smtp'); ?>>SMTP</option>
                </select>
                <p class="form-description">Choose the email method for sending automated emails.</p>
              </div>
              <div class="form-group">
                <label class="form-label" for="server_email">From/Reply-To Email</label>
                <input id="server_email" class="shadcn-input" type="text" name="server_email" value="<?php echo $server_email; ?>" placeholder="e.g., noreply@example.com" required>
                <p class="form-description">Used as the 'From' address. Some hosts require a registered email.</p>
              </div>
            </div>
            
            <!-- SMTP Settings -->
            <div id="smtp_settings" style="display:none; margin-top: 1.5rem; border-top: 1px solid #e5e7eb; padding-top: 1.5rem;">
              <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 1rem;">SMTP Settings</h4>
              <div class="helper-grid">
                <div class="form-group">
                  <label class="form-label" for="smtp_connection">SMTP Connection Type</label>
                  <select id="smtp_connection" name="smtp_connection" class="shadcn-select" required>
                    <option value="default" <?php echo set_select('smtp_connection', 'default', $smtp_connection == 'default'); ?>>Default (unsecure)</option>
                    <option value="tls" <?php echo set_select('smtp_connection', 'tls', $smtp_connection == 'tls'); ?>>TLS</option>
                    <option value="ssl" <?php echo set_select('smtp_connection', 'ssl', $smtp_connection == 'ssl'); ?>>SSL</option>
                  </select>
                </div>
                <div class="form-group">
                  <label class="form-label" for="smtp_host">SMTP Host</label>
                  <input id="smtp_host" class="shadcn-input" type="text" name="smtp_host" value="<?php echo $smtp_host; ?>" placeholder="e.g., smtp.gmail.com" required>
                </div>
                <div class="form-group">
                  <label class="form-label" for="smtp_requires_authentication">SMTP Requires Authentication?</label>
                  <select id="smtp_requires_authentication" name="smtp_requires_authentication" class="shadcn-select" required>
                    <option value="1" <?php echo set_select('smtp_requires_authentication', '1', $smtp_authentication == 1); ?>>Yes</option>
                    <option value="0" <?php echo set_select('smtp_requires_authentication', '0', $smtp_authentication == 0); ?>>No</option>
                  </select>
                </div>
                <div class="form-group">
                  <label class="form-label" for="smtp_port">SMTP Port</label>
                  <input id="smtp_port" class="shadcn-input" type="number" min="0" name="smtp_port" value="<?php echo $smtp_port; ?>" placeholder="e.g., 587" required>
                </div>
              </div>
              <div id="smtp_authentication" style="display:none; margin-top: 1rem;">
                <div class="helper-grid">
                  <div class="form-group">
                    <label class="form-label" for="smtp_username">SMTP Username</label>
                    <input id="smtp_username" class="shadcn-input" type="text" name="smtp_username" value="<?php echo $smtp_username; ?>" placeholder="Enter SMTP username" required>
                  </div>
                  <div class="form-group">
                    <label class="form-label" for="smtp_password">SMTP Password <span toggle="#smtp_password" class="tag toggle-password is-link is-rounded" style="cursor:pointer; font-weight: 400;">show</span></label>
                    <input id="smtp_password" class="shadcn-input" type="password" name="smtp_password" value="<?php echo $smtp_password; ?>" placeholder="Enter SMTP password" required>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Email Templates Card -->
        <div class="shadcn-card" style="margin-top: 1.5rem;">
          <div class="card-header">
            <h3 class="card-title">Automated Email Templates</h3>
            <p class="card-description">Customize and enable/disable client notifications.</p>
          </div>
          <div class="card-content">
            <!-- License Expiring Template -->
            <div class="form-group">
              <label class="form-label">License Expiring Today</label>
              <textarea id="license_expiring" name="license_expiring" rows="6"><?php echo set_value('license_expiring', $license_expiring); ?></textarea>
              <p class="form-description">Placeholders: {[client]}, {[product]}</p>
              <div class="shadcn-checkbox-group" style="margin-top: 0.5rem;">
                <input class="shadcn-checkbox" type="checkbox" name="license_expiring_enable" id="license_expiring_enable" <?php echo ($license_expiring_enable==1)?'checked':''; ?>>
                <label for="license_expiring_enable" class="shadcn-checkbox-label">Enable "License Expired Today" notification</label>
              </div>
            </div>
            <hr style="margin: 1.5rem 0; border-color: #e5e7eb; border-style: dashed;">

            <!-- Updates Expiring Template -->
            <div class="form-group">
              <label class="form-label">Update Period Ending Today</label>
              <textarea id="updates_expiring" name="updates_expiring" rows="6"><?php echo set_value('updates_expiring', $updates_expiring); ?></textarea>
              <p class="form-description">Placeholders: {[client]}, {[product]}</p>
              <div class="shadcn-checkbox-group" style="margin-top: 0.5rem;">
                <input class="shadcn-checkbox" type="checkbox" name="updates_expiring_enable" id="updates_expiring_enable" <?php echo ($updates_expiring_enable==1)?'checked':''; ?>>
                <label for="updates_expiring_enable" class="shadcn-checkbox-label">Enable "Updates Period Ending Today" notification</label>
              </div>
            </div>
            <hr style="margin: 1.5rem 0; border-color: #e5e7eb; border-style: dashed;">

            <!-- Support Expiring Template -->
            <div class="form-group">
              <label class="form-label">Support Period Ending Today</label>
              <textarea id="support_expiring" name="support_expiring" rows="6"><?php echo set_value('support_expiring', $support_expiring); ?></textarea>
              <p class="form-description">Placeholders: {[client]}, {[product]}</p>
              <div class="shadcn-checkbox-group" style="margin-top: 0.5rem;">
                <input class="shadcn-checkbox" type="checkbox" name="support_expiring_enable" id="support_expiring_enable" <?php echo ($support_expiring_enable==1)?'checked':''; ?>>
                <label for="support_expiring_enable" class="shadcn-checkbox-label">Enable "Support Expired Today" notification</label>
              </div>
            </div>
            <hr style="margin: 1.5rem 0; border-color: #e5e7eb; border-style: dashed;">

            <!-- New Update Template -->
            <div class="form-group">
              <label class="form-label">New Update Released</label>
              <textarea id="new_update" name="new_update" rows="6"><?php echo set_value('new_update', $new_update); ?></textarea>
              <p class="form-description">Placeholders: {[client]}, {[product]}, {[version]}, {[summary]}, {[changelog]}</p>
              <div class="shadcn-checkbox-group" style="margin-top: 0.5rem;">
                <input class="shadcn-checkbox" type="checkbox" name="new_update_enable" id="new_update_enable" <?php echo ($new_update_enable==1)?'checked':''; ?>>
                <label for="new_update_enable" class="shadcn-checkbox-label">Enable "New Version Released" notification</label>
              </div>
            </div>
          </div>
        </div>

        <div class="form-actions" style="background: transparent; padding: 1.5rem 0 0 0; border: none;">
          <button type="submit" id="email_settings_form_submit" class="shadcn-button show_loading">
            <i class="fas fa-save"></i>
            Save All Settings
          </button>
        </div>
        <p class="form-description" style="margin-top: 1rem;">Note: Automated emails will only be sent if the cron job is set up correctly and the client's email address exists.</p>
      </div>
    </form>
  </div>
</div>

<script src="<?php echo base_url(); ?>assets/vendor/Ckeditor/ckeditor.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var CkToolbar = [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo'];
    var editorConfig = {
      toolbar: CkToolbar,
      link: {
        decorators: {
          addTargetToLinks: {
            mode: 'manual',
            label: 'Open in a new tab',
            attributes: { target: '_blank' }
          }
        }
      }
    };
    ClassicEditor.create(document.querySelector('#license_expiring'), editorConfig);
    ClassicEditor.create(document.querySelector('#updates_expiring'), editorConfig);
    ClassicEditor.create(document.querySelector('#support_expiring'), editorConfig);
    ClassicEditor.create(document.querySelector('#new_update'), editorConfig);
  });
</script>
