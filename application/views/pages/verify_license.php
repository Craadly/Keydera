<div class="main-content main-content-expanded">
  <div class="section">
    <div class="page-header">
      <h1 class="page-title">Verify License - Keydera</h1>
    </div>
    <div class="settings-card" style="max-width:700px;margin:0 auto;">
      <?php if($lb_status!=True){ ?>
      <?php if(!empty($lb_msg)){?>
        <div class="notification is-danger m-t-xs" style="margin-bottom: 20px;"><?php echo ucfirst($lb_msg); ?></div>
      <?php }else{ ?>
        <div class="notification is-danger m-t-xs" style="margin-bottom: 20px;">Invalid license! Please enter a valid license below or contact support.</div>
      <?php } ?>
  <div class="form-section">
        <?php echo form_open('verify_license'); ?>
          <div class="form-grid">
            <div class="form-group full-width">
              <label class="form-label">Purchase code</label>
              <input class="form-input" type="text" placeholder="Enter your purchase code" name="license" required>
            </div>
            <div class="form-group full-width">
              <label class="form-label">Envato username</label>
              <input class="form-input" type="text" placeholder="Enter your envato username" name="client" required>
            </div>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Verify</button>
          </div>
        <?php echo form_close(); ?>
      </div>
      <?php } ?>
      <div class="form-section" style="text-align:center;">
        <a class="has-text-grey-darker has-text-weight-semibold" href="mailto:support@keydera.app?subject=Need help with Keydera <?php echo $lb_version;?>&body=(Note: Please explain the issue you are having along with the screenshot below and don't forget to include your purchase code.)">Need Help?</a>
      </div>
    </div>
  </div>
</div>
<div class="content has-text-centered" style="margin-top:1rem;">
  <p>Copyright <?php echo date('Y'); ?> <a style="color: inherit;" href="https://www.keydera.app">Keydera</a>, All rights reserved.</p>
</div>
