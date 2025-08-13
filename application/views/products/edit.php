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
    <?php $hidden = array('product' => $product['pd_pid']);
    echo form_open('products/edit', array('id' => 'edit_form'), $hidden); ?>
      <div class="field">
        <label class="label">Product Status <small class="tooltip is-tooltip-multiline is-tooltip-right " data-tooltip="Product status is checked before responding to any API requests, marking a product inactive will return all API calls related to this product with a 'product not found or is inactive' response." style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
        <div class="control">
          <div class="select">
            <select name="product_status" value="" required>
              <?php if($product['pd_status']==1):?>
              <option value='1' selected>Active</option>
              <option value='0'>Inactive</option>
              <?php else: ?>
              <option value='1'>Active</option>
              <option value='0' selected>Inactive</option>
              <?php endif; ?>
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
              <input class="input" type="text" name="product_id" maxlength="255" minlength="6" value="<?php 
              echo $product['pd_pid']; 
              ?>" disabled required>
            </div>
          </div>
        </div>
        <div class="column">
          <div class="field">
            <label class="label">Envato Item ID (optional) <small class="tooltip is-tooltip-multiline " data-tooltip="When provided, Envato purchase codes will be checked if they are of the specified envato item or not, useful if you have many envato items." style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
            <div class="control">
              <input class="input" type="text" name="envato_id" maxlength="255" value="<?php 
            echo $product['envato_id']; 
            ?>" placeholder="Enter Envato item ID">
            </div>
          </div>
        </div>
      </div>
      <div class="field">
        <label class="label">Product Name</label>
        <div class="control">
          <input class="input" type="text" name="name" maxlength="255" value="<?php 
          echo $product['pd_name']; 
          ?>" placeholder="Enter product name" required>
        </div>
      </div>
      <div class="field">
        <label class="label">Product Details (optional)</label>
        <div class="control">
          <textarea class="textarea" name="details" placeholder="Enter product details here"><?php echo $product['pd_details']; ?></textarea>
        </div>
      </div>
      <div class="columns" style="margin-bottom: 0px !important;">
        <div class="column">
          <div class="field">
            <?php if($product['license_update']==1):?>
            <input class="is-checkradio is-danger" type="checkbox" name="license_update" id="license_update" checked>
            <?php else: ?>
            <input class="is-checkradio is-danger" type="checkbox" name="license_update" id="license_update" tabindex="15">
            <?php endif; ?>
            <label for="license_update" style="margin-left: 0px !important;">Make license check compulsory for downloading updates? <small class="tooltip is-tooltip-multiline " data-tooltip="When checked, updates for this product can only be downloaded by providing a valid license and if that license's update expiration date has not passed." style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
          </div>
        </div>
        <div class="column">
          <div class="field">
            <?php if($product['serve_latest_updates']==1):?>
            <input class="is-checkradio is-danger" type="checkbox" name="serve_latest_updates" id="serve_latest_updates" checked>
            <?php else: ?>
            <input class="is-checkradio is-danger" type="checkbox" name="serve_latest_updates" id="serve_latest_updates" tabindex="15">
            <?php endif; ?>
            <label for="serve_latest_updates" style="margin-left: 0px !important;">Always serve the latest update files? <small class="tooltip is-tooltip-multiline " data-tooltip="When checked, latest updates are always served irrespective of current version."><i class="fas fa-question-circle"></i></small></label>
          </div>
        </div>
      </div> 
      <div class="field p-t-sm is-grouped">
        <div class="control">
          <button type="submit" id="edit_form_submit" class="button is-link">Save Changes</button>
        </div>
      </div>
    </form>
  </div>
</div> 
</div>