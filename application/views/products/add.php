<<<<<<< HEAD
<div class="main-content main-content-expanded">
  <div class="section">
    <div class="page-header">
      <h1 class="page-title"><?php echo $title; ?></h1>
    </div>

    <?php if($this->session->flashdata('product_status')): ?>
      <?php $flash = $this->session->flashdata('product_status'); ?>
      <div class="notification is-<?php echo $flash['type']; ?> is-light" style="margin-bottom: 1.5rem;">
        <button class="delete"></button>
        <?php echo $flash['message']; ?>
      </div>
    <?php endif; ?>

    <?php echo form_open('products/add', array('id' => 'add_form')); ?>
      <div class="shadcn-card">
        <div class="card-header">
          <h3 class="card-title">Create a New Product</h3>
          <p class="card-description">Enter the details for your new product below.</p>
        </div>
        <div class="card-content">
          <!-- Basic Information -->
          <h4 class="form-section-title">Basic Information</h4>
          <div class="create-license-grid">
            <div class="form-group">
              <label class="form-label required" for="name">Product Name</label>
              <input id="name" class="shadcn-input" type="text" name="name" maxlength="255" value="<?php echo set_value('name'); ?>" placeholder="e.g., My Awesome App" required>
              <?php echo form_error('name', '<p class="form-error">', '</p>'); ?>
            </div>

            <div class="form-group">
              <label class="form-label required" for="product_id">Product ID</label>
              <input id="product_id" class="shadcn-input" type="text" name="product_id" maxlength="255" minlength="1" value="<?php echo !empty(set_value('product_id')) ? set_value('product_id') : $product_id; ?>" placeholder="Unique product identifier" required>
              <?php echo form_error('product_id', '<p class="form-error">', '</p>'); ?>
            </div>

            <div class="form-group">
              <label class="form-label" for="envato_id">Envato Item ID</label>
              <input id="envato_id" class="shadcn-input" type="text" name="envato_id" maxlength="255" value="<?php echo set_value('envato_id'); ?>" placeholder="Optional: e.g., 12345678">
               <p class="form-description">Validates purchase codes against this item ID.</p>
            </div>

            <div class="form-group">
              <label class="form-label required" for="product_status">Product Status</label>
              <select id="product_status" name="product_status" class="shadcn-select" required>
                <option value='1' <?php echo set_select('product_status', '1', TRUE); ?>>Active</option>
                <option value='0' <?php echo set_select('product_status', '0'); ?>>Inactive</option>
              </select>
              <p class="form-description">Inactive products will not respond to API requests.</p>
              <?php echo form_error('product_status', '<p class="form-error">', '</p>'); ?>
            </div>
          </div>

          <!-- Product Details -->
          <hr class="form-divider">
          <h4 class="form-section-title">Product Details</h4>
          <div class="form-group">
            <label class="form-label" for="details">Product Description</label>
            <textarea id="details" class="shadcn-textarea" name="details" placeholder="Enter a detailed product description, features, or any additional information." rows="4"><?php echo set_value('details'); ?></textarea>
          </div>

          <!-- Update Settings -->
          <hr class="form-divider">
          <h4 class="form-section-title">Update Settings</h4>
          <div class="form-group">
            <div class="shadcn-checkbox-group">
              <input class="shadcn-checkbox" type="checkbox" name="license_update" id="license_update" <?php echo set_checkbox('license_update'); ?>>
              <label for="license_update" class="shadcn-checkbox-label">
                Require valid license for updates
                <span class="shadcn-checkbox-description">Users must provide a valid, unexpired license to download updates.</span>
              </label>
            </div>
          </div>
          <div class="form-group">
            <div class="shadcn-checkbox-group">
              <input class="shadcn-checkbox" type="checkbox" name="serve_latest_updates" id="serve_latest_updates" <?php echo set_checkbox('serve_latest_updates'); ?>>
              <label for="serve_latest_updates" class="shadcn-checkbox-label">
                Always serve latest updates
                <span class="shadcn-checkbox-description">The latest update files are always served, regardless of the user's current version.</span>
              </label>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <button type="button" class="shadcn-button shadcn-button-secondary" onclick="window.history.back()">
            <i class="fas fa-arrow-left"></i>
            Cancel
          </button>
          <button type="submit" id="add_form_submit" class="shadcn-button show_loading">
            <i class="fas fa-plus"></i>
            Create Product
          </button>
=======
<div class="container is-fluid main_body"> 
<div class="section">
  <h1 class="title">
    <?php echo $title; ?>
  </h1>
  <?php echo generate_breadcrumb(); ?>
  <?php if($this->session->flashdata('product_status')): ?>
    <?php $flash = $this->session->flashdata('product_status');
    echo ' <div class="notification is-'.$flash['type'].'"><button class="delete"></button>'.$flash['message'].'</div>'; ?>
  <?php endif; ?>
  <div class="box">
    <?php echo form_open('products/add', array('id' => 'add_form')); ?>
    <div class="field">
      <label class="label">Product Status <small class="tooltip is-tooltip-multiline is-tooltip-right " data-tooltip="Product status is checked before responding to any API requests, marking a product inactive will return all API calls related to this product with a 'product not found or is inactive' response." style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
      <div class="control">
        <div class="select">
          <select name="product_status" value="<?php echo set_value('product_status'); ?>" required>
            <option value='1' selected>Active</option>
            <option value='0'>Inactive</option>
          </select>
        </div>
      </div>
      <?php echo form_error('product_status', '<p class="help is-danger">', '</p>'); ?>
      </div>
      <div class="columns" style="margin-bottom: 0px !important;">
        <div class="column">
          <div class="field">
          <label class="label">Product ID</label>
          <div class="control">
            <input class="input" type="text" name="product_id" maxlength="255" minlength="1" value="<?php 
            if(!empty(set_value('product_id'))) {
              echo set_value('product_id');
            }
            else{ echo $product_id; 
            } ?>" placeholder="Enter product ID" required>
          </div>
          <?php echo form_error('product_id', '<p class="help is-danger">', '</p>'); ?>
        </div>
        </div>
        <div class="column">
          <div class="field">
          <label class="label">Envato Item ID (optional) <small class="tooltip is-tooltip-multiline " data-tooltip="When provided, Envato purchase codes will be checked if they are of the specified envato item or not, useful if you have many envato items." style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
          <div class="control">
            <input class="input" type="text" name="envato_id" maxlength="255" value="<?php echo set_value('envato_id'); ?>" placeholder="Enter Envato item ID">
          </div>
        </div>
        </div>
      </div>
      <div class="field">
        <label class="label">Product Name</label>
        <div class="control">
          <input class="input" type="text" name="name" maxlength="255" value="<?php echo set_value('name'); ?>" placeholder="Enter product name" required>
        </div>
        <?php echo form_error('name', '<p class="help is-danger">', '</p>'); ?>
      </div>
      <div class="field">
        <label class="label">Product Details (optional)</label>
        <div class="control">
          <textarea class="textarea" name="details" placeholder="Enter product details here"><?php echo set_value('details'); ?></textarea>
        </div>
      </div>
      <div class="columns" style="margin-bottom: 0px !important;">
        <div class="column">
          <div class="field">
            <input class="is-checkradio is-danger" type="checkbox" name="license_update" id="license_update">
            <label for="license_update" style="margin-left: 0px !important;">Make license check compulsory for downloading updates? <small class="tooltip is-tooltip-multiline " data-tooltip="When checked, updates for this product can only be downloaded by providing a valid license and if that license's update expiration date has not passed."><i class="fas fa-question-circle"></i></small></label>
          </div>
        </div>
        <div class="column">
          <div class="field">
            <input class="is-checkradio is-danger" type="checkbox" name="serve_latest_updates" id="serve_latest_updates">
            <label for="serve_latest_updates" style="margin-left: 0px !important;">Always serve the latest update files? <small class="tooltip is-tooltip-multiline " data-tooltip="When checked, latest updates are always served irrespective of current version."><i class="fas fa-question-circle"></i></small></label>
          </div>
        </div>
      </div>  
      <div class="field p-t-sm is-grouped">
        <div class="control">
          <button type="submit" id="add_form_submit" class="button is-link">Submit</button>
>>>>>>> origin/main
        </div>
      </div>
    </form>
  </div>
<<<<<<< HEAD
</div>

<style>
  .form-section-title {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #111827;
  }
  .form-divider {
    margin: 1.5rem 0;
    border-color: #e5e7eb;
    border-style: solid;
    border-top-width: 1px;
  }
</style>
=======
</div> 
</div>
>>>>>>> origin/main
