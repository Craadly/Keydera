<div class="main-content main-content-expanded">
  <div class="section">
    <div class="page-header">
      <h1 class="page-title"><?php echo $title; ?></h1>
    </div>

    <?php if($this->session->flashdata('license_status')): ?>
      <?php $flash = $this->session->flashdata('license_status'); ?>
      <div class="notification is-<?php echo $flash['type']; ?> is-light" style="margin-bottom: 1.5rem;">
        <button class="delete"></button>
        <?php echo $flash['message']; ?>
      </div>
    <?php endif; ?>

    <?php echo form_open('licenses/create', array('id' => 'create_form')); ?>
      <div class="create-license-grid">
        
        <!-- Left Column -->
        <div class="form-column">
          <!-- Basic Information Card -->
          <div class="shadcn-card">
            <div class="card-header">
              <h3 class="card-title">Basic Information</h3>
              <p class="card-description">Configure the fundamental license details.</p>
            </div>
            <div class="card-content">
              <div class="form-group">
                <label class="form-label required" for="product-select">License for Product</label>
                <select id="product-select" name="product" class="shadcn-select" required>
                  <option disabled selected>Select a Product...</option>
                  <?php foreach($products as $product) : ?>
                    <option value="<?php echo $product['pd_pid']; ?>"><?php echo $product['pd_name']; ?> (<?php echo $product['pd_status'] ? 'active' : 'inactive'; ?>)</option>
                  <?php endforeach; ?>
                </select>
                <a href="<?php echo base_url();?>products/add" class="form-link">Add New Product?</a>
                <?php echo form_error('product', '<p class="form-error">', '</p>'); ?>
              </div>

              <div class="form-group">
                <label class="form-label required" for="license-code">License Code</label>
                <input id="license-code" class="shadcn-input" type="text" name="license" maxlength="255" minlength="2" value="<?php echo !empty(set_value('license')) ? set_value('license') : $created_license; ?>" placeholder="Enter license code" required>
                <?php echo form_error('license', '<p class="form-error">', '</p>'); ?>
              </div>

              <div class="form-group">
                <label class="form-label" for="license-type">License Type</label>
                <input id="license-type" class="shadcn-input" type="text" name="license_type" maxlength="255" value="<?php echo set_value('license_type'); ?>" placeholder="e.g., Standard, Premium, Trial">
              </div>

              <div class="form-group">
                <label class="form-label" for="invoice-number">Invoice Number</label>
                <input id="invoice-number" class="shadcn-input" type="text" name="invoice" maxlength="255" value="<?php echo set_value('invoice'); ?>" placeholder="Enter invoice/order number">
              </div>
            </div>
          </div>

          <!-- Client Information Card -->
          <div class="shadcn-card">
            <div class="card-header">
              <h3 class="card-title">Client Information</h3>
              <p class="card-description">Associate this license with a specific client.</p>
            </div>
            <div class="card-content">
              <div class="form-group">
                <label class="form-label" for="client-name">Client Name</label>
                <input id="client-name" class="shadcn-input" type="text" name="client" maxlength="255" value="<?php echo set_value('client'); ?>" placeholder="Enter client's name or username">
                 <p class="form-description">Leave empty for use by any client.</p>
              </div>

              <div class="form-group">
                <label class="form-label" for="client-email">Client's Email</label>
                <input id="client-email" class="shadcn-input" type="email" name="email" maxlength="255" value="<?php echo set_value('email'); ?>" placeholder="Enter client's email address">
                <?php echo form_error('email', '<p class="form-error">', '</p>'); ?>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column -->
        <div class="form-column">
          <!-- Usage & Expiration Card -->
          <div class="shadcn-card">
            <div class="card-header">
              <h3 class="card-title">Usage & Expiration</h3>
              <p class="card-description">Define usage limits and time-based restrictions.</p>
            </div>
            <div class="card-content">
              <div class="form-group">
                <label class="form-label" for="total-uses">Total License Uses</label>
                <input id="total-uses" class="shadcn-input" type="number" min="1" name="uses" value="<?php echo set_value('uses'); ?>" placeholder="Unlimited">
                <p class="form-description">How many times this license can be activated.</p>
                <?php echo form_error('uses', '<p class="form-error">', '</p>'); ?>
              </div>

              <div class="form-group">
                <label class="form-label" for="parallel-uses">Parallel Uses</label>
                <input id="parallel-uses" class="shadcn-input" type="number" min="1" name="parallel_uses" value="<?php echo set_value('parallel_uses'); ?>" placeholder="Unlimited">
                 <p class="form-description">How many simultaneous activations are allowed.</p>
                <?php echo form_error('parallel_uses', '<p class="form-error">', '</p>'); ?>
              </div>

              <div class="form-group">
                <label class="form-label" for="expiry-date">License Expiration Date</label>
                <input id="expiry-date" class="shadcn-input date-time-picker" type="text" name="expiry" value="<?php echo set_value('expiry'); ?>" placeholder="Select expiration date">
              </div>

              <div class="form-group">
                <label class="form-label" for="expiry-days">Expiration Days</label>
                <input id="expiry-days" class="shadcn-input" type="number" name="expiry_days" min="1" value="<?php echo set_value('expiry_days'); ?>" placeholder="Days after first activation">
              </div>

              <div class="form-group">
                <label class="form-label" for="updates-till">Updates End Date</label>
                <input id="updates-till" class="shadcn-input date-time-picker" type="text" name="updates_till" maxlength="255" value="<?php echo set_value('updates_till'); ?>" placeholder="Select updates end date">
              </div>

              <div class="form-group">
                <label class="form-label" for="supported-till">Support End Date</label>
                <input id="supported-till" class="shadcn-input date-time-picker" type="text" name="supported_till" maxlength="255" value="<?php echo set_value('supported_till'); ?>" placeholder="Select support end date">
              </div>
            </div>
          </div>

          <!-- Restrictions & Notes Card -->
          <div class="shadcn-card">
            <div class="card-header">
              <h3 class="card-title">Restrictions & Notes</h3>
              <p class="card-description">Add domain/IP restrictions and other settings.</p>
            </div>
            <div class="card-content">
              <div class="form-group">
                <label class="form-label" for="domains">Licensed Domains</label>
                <input id="domains" class="shadcn-input" type="tags" name="domains" value="<?php echo set_value('domains'); ?>" placeholder="Enter domains (comma-separated)">
                <?php echo form_error('domains', '<p class="form-error">', '</p>'); ?>
              </div>

              <div class="form-group">
                <label class="form-label" for="ips">Licensed IPs</label>
                <input id="ips" class="shadcn-input" type="tags" name="ips" value="<?php echo set_value('ips'); ?>" placeholder="Enter IPs (comma-separated)">
                <?php echo form_error('ips', '<p class="form-error">', '</p>'); ?>
              </div>

              <div class="form-group">
                <label class="form-label" for="comments">Comments</label>
                <textarea id="comments" class="shadcn-textarea" name="comments" placeholder="Enter any additional comments or notes"><?php echo set_value('comments'); ?></textarea>
              </div>

              <div class="form-group">
                <div class="shadcn-checkbox-group">
                    <input class="shadcn-checkbox" type="checkbox" name="validity" id="validity">
                    <label for="validity" class="shadcn-checkbox-label">
                        Block license immediately
                        <span class="shadcn-checkbox-description">Check this to create the license in a blocked state.</span>
                    </label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Form Actions Footer -->
      <div class="form-actions-footer">
        <button type="button" class="shadcn-button-secondary" onclick="window.history.back()">
          <i class="fas fa-arrow-left"></i>
          Cancel
        </button>
        <button type="submit" id="create_form_submit" class="shadcn-button">
          <i class="fas fa-save"></i>
          Create License
        </button>
      </div>
    </form>
  </div>
</div>

<style>
.create-license-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
  gap: 2rem;
  align-items: start;
}
.form-column {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}
.form-actions-footer {
  margin-top: 2rem;
  padding: 1.5rem;
  border-top: 1px solid #e2e8f0;
  background-color: #f8fafc;
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  border-radius: 0 0 0.5rem 0.5rem;
}
/* Keep date picker styles working with new input */
.date-time-picker.shadcn-input {
  background-color: #fff; /* Ensure picker icon is visible */
}
</style>
