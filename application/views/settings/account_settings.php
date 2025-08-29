<div class="main-content main-content-expanded">
  <div class="section">
    <div class="page-header">
      <h1 class="page-title"><?php echo $title; ?></h1>
    </div>

    <?php if($this->session->flashdata('user_status')){
      $flash = $this->session->flashdata('user_status');
      echo '<div class="notification is-'.$flash['type'].' is-light" style="margin-bottom: 1.5rem;"><button class="delete"></button>'.$flash['message'].'</div>';
      if($flash['type']=='success'){
        echo '<script>setTimeout(function(){var n=document.getElementsByClassName("notification")[0]; if(n){n.style.display="none";}},4000);</script>';
      }
    } ?>

    <div class="settings-forms-grid">
      <!-- Profile Information Card -->
      <div class="shadcn-card">
        <?php echo form_open('account_settings', array('id' => 'account_settings_form'), array('type' => 'general')); ?>
          <div class="card-header">
            <h3 class="card-title">Profile Information</h3>
            <p class="card-description">Update your name, username, and email address.</p>
          </div>
          <div class="card-content">
            <div class="helper-grid">
              <div class="form-group">
                <label class="form-label" for="full_name">Full Name</label>
                <input id="full_name" class="shadcn-input" type="text" name="full_name" maxlength="255" value="<?php echo $user['au_name']; ?>" placeholder="Enter your name" required>
              </div>

              <div class="form-group">
                <label class="form-label" for="username">Username</label>
                <input id="username" class="shadcn-input" type="text" name="username" maxlength="255" minlength="5" value="<?php echo $user['au_username']; ?>" placeholder="Enter your username" required>
              </div>
            </div>
            <div class="form-group" style="margin-top: 1rem;">
              <label class="form-label" for="email">Email Address</label>
              <input id="email" class="shadcn-input" type="email" name="email" maxlength="255" value="<?php echo $user['au_email']; ?>" placeholder="Enter your Email" required>
              <p class="form-description">Password reset links will be sent to this email address.</p>
            </div>
          </div>
          <div class="card-footer">
            <button type="submit" id="account_settings_form_submit" class="shadcn-button show_loading">
              <i class="fas fa-save"></i>
              Update Profile
            </button>
          </div>
        </form>
      </div>

      <!-- Change Password Card -->
      <div class="shadcn-card">
        <?php echo form_open('account_settings', array('id' => 'account_settings2_form'), array('type' => 'password')); ?>
          <div class="card-header">
            <h3 class="card-title">Change Password</h3>
            <p class="card-description">Secure your account with a new, strong password.</p>
          </div>
          <div class="card-content">
            <div class="form-group">
              <label class="form-label" for="current_password">
                Current Password
                <span toggle="#current_password" class="tag toggle-password is-link is-rounded" style="cursor:pointer; font-weight: 400;">show</span>
              </label>
              <input id="current_password" class="shadcn-input" type="password" name="current_password" minlength="8" placeholder="Enter your current password" required>
            </div>

            <div class="form-group" style="margin-top: 1rem;">
              <label class="form-label" for="new_password">
                New Password
                <span toggle="#new_password" class="tag toggle-password is-link is-rounded" style="cursor:pointer; font-weight: 400;">show</span>
              </label>
              <input id="new_password" class="shadcn-input" type="password" name="new_password" minlength="8" placeholder="Enter new password" required>
            </div>
          </div>
          <div class="card-footer">
            <button type="submit" id="account_settings2_form_submit" class="shadcn-button show_loading">
              <i class="fas fa-key"></i>
              Change Password
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
