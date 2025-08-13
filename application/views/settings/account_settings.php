<div class="container is-fluid main_body"> 
<div class="section">
  <h1 class="title">
    <?php echo $title; ?>
  </h1>
  <?php echo generate_breadcrumb($title); ?>
  <?php if($this->session->flashdata('user_status')){
    $flash = $this->session->flashdata('user_status');
    echo '<div class="notification is-'.$flash['type'].'"><button class="delete"></button>'.$flash['message'].'</div>'; 
    if($flash['type']=='success'){
      echo '<script>setTimeout(function(){document.getElementsByClassName("notification")[0].style.display="none";},4000);</script>';
    } 
  } ?>
<div class="columns">
<div class="column">
  <div class="box">
    <?php 
    $hidden = array('type' => 'general'); 
    echo form_open('account_settings', array('id' => 'account_settings_form'), $hidden); ?>
    <div class="field">
      <label class="label">Full Name</label>
      <div class="control">
        <input class="input" type="text" name="full_name" maxlength="255" value="<?php 
      echo $user['au_name']; ?>" placeholder="Enter your name" required>
      </div>
    </div>
    <div class="field">
      <label class="label">Username</label>
      <div class="control">
        <input class="input" type="text" name="username" maxlength="255" minlength="5" value="<?php 
      echo $user['au_username']; ?>" placeholder="Enter your username" required>
      </div>
    </div>
    <div class="field">
      <label class="label">Email <small class="tooltip is-tooltip-right " data-tooltip="Password reset emails will be sent to this email." style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
      <div class="control">
        <input class="input" type="email" name="email" maxlength="255" value="<?php 
      echo $user['au_email']; ?>" placeholder="Enter your Email" required>
      </div>
    </div>
    <div class="field is-grouped">
      <div class="control">
        <button type="submit" id="account_settings_form_submit" class="button is-link">Update</button>
      </div>
  </div>
  </form>
  </div>
</div>
<div class="column">
<div class="box">
  <?php 
  $hidden = array('type' => 'password'); 
  echo form_open('account_settings', array('id' => 'account_settings2_form'), $hidden); ?>
  <div class="field">
    <label class="label">Current Password <span toggle="#current_password" class="tag toggle-password is-link is-rounded">show</span></label>
    <div class="control">
      <input class="input" type="password" id="current_password" name="current_password" minlength="8" value="" placeholder="Enter your current password" required>
    </div>
  </div>
  <div class="field">
    <label class="label">New Password <span toggle="#new_password" class="tag toggle-password is-link is-rounded">show</span></label>
    <div class="control">
      <input class="input" type="password" id="new_password" name="new_password" minlength="8" value="" placeholder="Enter new password" required>
    </div>
  </div>
  <div class="field is-grouped">
    <div class="control">
      <button type="submit" id="account_settings2_form_submit" class="button is-link">Change Password</button>
    </div>
  </div>
  </form>
</div>
</div>
</div> 
</div>
</div>