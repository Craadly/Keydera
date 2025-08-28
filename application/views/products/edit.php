<div class="main-content main-content-expanded">
  <div class="section">
    <div class="page-header">
      <div class="page-header-content">
        <div class="page-header-text">
          <h1 class="page-title"><?php echo $title; ?></h1>
          <p class="page-subtitle">Edit product details and update settings</p>
        </div>
      </div>
    </div>

    <?php if($this->session->flashdata('product_status')): ?>
      <?php $flash = $this->session->flashdata('product_status'); ?>
      <div class="notification is-<?php echo $flash['type']; ?> is-light"><button class="delete"></button><?php echo $flash['message']; ?></div>
    <?php endif; ?>

    <div class="form-card">
      <?php $hidden = array('product' => $product['pd_pid']); echo form_open('products/edit', array('id' => 'edit_form'), $hidden); ?>

      <!-- Status -->
      <div class="form-section">
        <div class="form-section-header">
          <h3>Status</h3>
          <p>Control whether this product is active for APIs</p>
        </div>
        <div class="form-grid">
          <div class="form-field">
            <label class="form-label">Product status <span class="hint" title="If inactive, API calls for this product return 'product not found or inactive'.">?</span></label>
            <select class="input" name="product_status" required>
              <?php if($product['pd_status']==1):?>
                <option value='1' selected>Active</option>
                <option value='0'>Inactive</option>
              <?php else: ?>
                <option value='1'>Active</option>
                <option value='0' selected>Inactive</option>
              <?php endif; ?>
            </select>
            <?php echo form_error('product_status', '<p class="help is-danger">', '</p>'); ?>
          </div>
        </div>
      </div>

      <!-- Identifiers -->
      <div class="form-section">
        <div class="form-section-header">
          <h3>Identifiers</h3>
          <p>Internal product ID and optional Envato item ID</p>
        </div>
        <div class="form-grid">
          <div class="form-field">
            <label class="form-label">Product ID</label>
            <input class="input" type="text" name="product_id" maxlength="255" minlength="6" value="<?php echo $product['pd_pid']; ?>" disabled required>
          </div>
          <div class="form-field">
            <label class="form-label">Envato Item ID <span class="hint" title="If set, Envato purchase codes will be validated against this item.">?</span></label>
            <input class="input" type="text" name="envato_id" maxlength="255" value="<?php echo $product['envato_id']; ?>" placeholder="Enter Envato item ID">
          </div>
        </div>
      </div>

      <!-- Basic info -->
      <div class="form-section">
        <div class="form-section-header">
          <h3>Basic info</h3>
          <p>Product name and description</p>
        </div>
        <div class="form-grid">
          <div class="form-field">
            <label class="form-label">Product name</label>
            <input class="input" type="text" name="name" maxlength="255" value="<?php echo $product['pd_name']; ?>" placeholder="Enter product name" required>
          </div>
          <div class="form-field full">
            <label class="form-label">Product details</label>
            <textarea class="textarea" name="details" placeholder="Enter product details here"><?php echo $product['pd_details']; ?></textarea>
          </div>
        </div>
      </div>

      <!-- Update settings -->
      <div class="form-section">
        <div class="form-section-header">
          <h3>Update settings</h3>
          <p>Download access rules and version serving</p>
        </div>
        <div class="form-grid">
          <div class="form-field">
            <?php if($product['license_update']==1):?>
              <input class="is-checkradio is-danger" type="checkbox" name="license_update" id="license_update" checked>
            <?php else: ?>
              <input class="is-checkradio is-danger" type="checkbox" name="license_update" id="license_update">
            <?php endif; ?>
            <label for="license_update">Require valid license for updates <span class="hint" title="Clients must have a valid license and unexpired updates to download.">?</span></label>
          </div>
          <div class="form-field">
            <?php if($product['serve_latest_updates']==1):?>
              <input class="is-checkradio is-danger" type="checkbox" name="serve_latest_updates" id="serve_latest_updates" checked>
            <?php else: ?>
              <input class="is-checkradio is-danger" type="checkbox" name="serve_latest_updates" id="serve_latest_updates">
            <?php endif; ?>
            <label for="serve_latest_updates">Always serve latest update <span class="hint" title="Ignores client's current version and serves the latest package.">?</span></label>
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="form-actions m-t-md">
        <button type="submit" id="edit_form_submit" class="btn btn-primary">
          <i class="fas fa-save"></i>
          Save changes
        </button>
      </div>

      </form>
    </div>
  </div>
</div>

<style>
.page-header { margin-bottom: 1.5rem; }
.page-header-content { display:flex; justify-content:space-between; align-items:flex-start; }
.page-title { font-size:2rem; font-weight:700; color:var(--text-primary); margin-bottom:.25rem; }
.page-subtitle { color:var(--text-secondary); font-size:.9rem; }

.form-card { background:var(--bg-card); border-radius:12px; box-shadow:var(--shadow); padding:1.5rem; }
.form-section { padding:1rem 0; border-bottom:1px solid var(--border); }
.form-section:last-of-type { border-bottom:none; }
.form-section-header h3 { margin:0 0 .25rem; font-size:1.1rem; font-weight:600; color:var(--text-primary); }
.form-section-header p { margin:0; color:var(--text-secondary); font-size:.9rem; }
.form-grid { display:grid; grid-template-columns: repeat(2, minmax(240px, 1fr)); gap:1rem 1.25rem; margin-top:1rem; }
.form-field { display:flex; flex-direction:column; gap:.4rem; }
.form-field.full { grid-column: 1 / -1; }
.form-label { font-weight:600; color:var(--text-primary); }
.hint { display:inline-flex; align-items:center; justify-content:center; width:18px; height:18px; border-radius:50%; background:var(--bg-main); color:var(--text-secondary); font-size:.75rem; cursor:help; margin-left:.4rem; }
.form-card .input, .form-card .textarea, .form-card select { border:2px solid var(--border); border-radius:8px; box-shadow:none; height: 42px; padding: 0 .75rem; }
.form-card .textarea { min-height:140px; height:auto; padding:.75rem; }
.form-card .input:focus, .form-card .textarea:focus, .form-card select:focus { border-color: var(--primary); outline: none; box-shadow: 0 0 0 3px rgba(59,130,246,0.15); }
.form-actions { display:flex; justify-content:flex-end; gap:.75rem; padding-top:1rem; }
.btn { padding:.6rem 1rem; border:none; border-radius:8px; font-size:.9rem; cursor:pointer; display:inline-flex; align-items:center; gap:.5rem; }
.btn-primary { background:var(--primary); color:#fff; }
.btn-primary:hover { filter:brightness(.95); }
@media (max-width: 768px){ .form-grid { grid-template-columns: 1fr; } }
</style>