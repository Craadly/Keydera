<div class="main-content main-content-expanded">
  <div class="section">
    <div class="page-header">
      <div class="page-header-content">
        <div class="page-header-text">
          <h1 class="page-title"><?php echo $title; ?></h1>
          <p class="page-subtitle">Create and publish a new product version for updates</p>
        </div>
      </div>
    </div>

    <?php if($this->session->flashdata('product_status')): ?>
      <?php $flash = $this->session->flashdata('product_status'); ?>
      <div class="notification is-<?php echo $flash['type']; ?> is-light"><button class="delete"></button><?php echo $flash['message']; ?></div>
    <?php endif; ?>
    <?php if($this->session->flashdata('upload_status_main')): ?>
      <?php $flash = $this->session->flashdata('upload_status_main'); ?>
      <div class="notification is-<?php echo $flash['type']; ?> is-light"><button class="delete"></button><?php echo $flash['message']; ?></div>
    <?php endif; ?>
    <?php if($this->session->flashdata('upload_status_sql')): ?>
      <?php $flash = $this->session->flashdata('upload_status_sql'); ?>
      <div class="notification is-<?php echo $flash['type']; ?> is-light"><button class="delete"></button><?php echo $flash['message']; ?></div>
    <?php endif; ?>

    <div class="form-card">
      <?php 
        $hidden = array('product' => $this->input->post('product'));
        echo form_open_multipart('products/versions/add',array('id' => 'add_version_form'),$hidden); ?>

      <!-- Version Details -->
      <div class="form-section">
        <div class="form-section-header">
          <h3>Version details</h3>
          <p>Set the version identifier and release information</p>
        </div>
        <div class="form-grid">
          <div class="form-field">
            <label class="form-label">Version <span class="hint" title="Stick to a consistent pattern e.g. v1.0.0">?</span></label>
            <input class="input" type="text" name="version" maxlength="255" value="<?php echo set_value('version'); ?>" placeholder="e.g. v1.0.0" required>
          </div>
          <div class="form-field">
            <label class="form-label">Release date</label>
            <input class="input date-time-picker" type="text" name="released" value="<?php echo set_value('released'); ?>" placeholder="YYYY-MM-DD HH:MM:SS" required>
          </div>
          <div class="form-field full">
            <label class="form-label">Update summary</label>
            <input class="input" type="text" name="summary" maxlength="255" value="<?php echo set_value('summary'); ?>" placeholder="This update brings major improvements, upgrade now!">
          </div>
        </div>
      </div>

      <!-- Changelog -->
      <div class="form-section">
        <div class="form-section-header">
          <h3>Changelog</h3>
          <p>Describe what’s new and improved in this version</p>
        </div>
        <div class="form-field">
          <textarea class="textarea" name="changelog" id="changelog" rows="10" placeholder="Enter the update changelog here..." required><?php echo (!empty(set_value('changelog'))?set_value('changelog'):null); ?></textarea>
        </div>
      </div>

      <!-- Files -->
      <div class="form-section">
        <div class="form-section-header">
          <h3>Update files</h3>
          <p>Upload the package and optional SQL migration</p>
        </div>
        <div class="form-grid">
          <div class="form-field">
            <label class="form-label">Main files (.zip) <span class="hint" title="The archive is extracted at app root. Include updated helper with current_version.">?</span></label>
            <?php 
              $max_size_possible = get_file_upload_max_size();
              $get_max_upload_size = ($max_size_possible<0)?0:$max_size_possible;
              $main_file_max_attr = $get_max_upload_size>0 ? ' data-max-file-size="'.convert_kb($get_max_upload_size).'"' : '';
            ?>
            <input type="file" class="dropify" name="main_file" data-height="150"<?php echo $main_file_max_attr; ?> data-allowed-file-extensions="zip" accept=".zip" required/>
          </div>
          <div class="form-field">
            <label class="form-label">SQL file (.sql) [optional] <span class="hint" title="SQL will be imported during the update process.">?</span></label>
            <?php 
              $max_size_possible2 = get_file_upload_max_size();
              $get_max_upload_size2 = ($max_size_possible2<0)?0:$max_size_possible2;
              $sql_file_max_attr = $get_max_upload_size2>0 ? ' data-max-file-size="'.convert_kb($get_max_upload_size2).'"' : '';
            ?>
            <input type="file" class="dropify" name="sql_file" data-height="150"<?php echo $sql_file_max_attr; ?> data-allowed-file-extensions="sql" accept=".sql" />
          </div>
        </div>
      </div>

      <!-- Publish toggle -->
      <div class="form-section">
        <div class="form-section-header">
          <h3>Publish</h3>
          <p>Make this version available to clients</p>
        </div>
        <div class="form-toggle">
          <input class="is-checkradio is-primary" type="checkbox" name="version_status" id="version_status" checked>
          <label for="version_status">Publish this version now</label>
        </div>
      </div>

  <!-- Actions -->
  <div class="form-actions m-t-md">
        <button type="submit" id="add_version_form_submit" class="btn btn-primary">
          <i class="fas fa-cloud-upload-alt"></i>
          Add version
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
.form-card .input, .form-card .textarea { border:2px solid var(--border); border-radius:8px; box-shadow:none; height: 42px; padding: 0 .75rem; }
.form-card .input:focus, .form-card .textarea:focus { border-color: var(--primary); outline: none; box-shadow: 0 0 0 3px rgba(59,130,246,0.15); }
.form-card .textarea { min-height:180px; height:auto; padding:.75rem; }
.form-card ::placeholder { color: var(--text-muted); opacity: .9; }
.ck.ck-editor__main>.ck-editor__editable { min-height: 180px; }
.ck.ck-toolbar { border-radius: 8px 8px 0 0; }
.ck.ck-editor__main .ck-editor__editable { border-radius: 0 0 8px 8px; }
.form-toggle { display:flex; align-items:center; gap:.5rem; margin-top:.75rem; }
.form-actions { display:flex; justify-content:flex-end; gap:.75rem; padding-top:1rem; }
.btn { padding:.6rem 1rem; border:none; border-radius:8px; font-size:.9rem; cursor:pointer; display:inline-flex; align-items:center; gap:.5rem; }
.btn-primary { background:var(--primary); color:#fff; }
.btn-primary:hover { filter:brightness(.95); }
@media (max-width: 768px){ .form-grid { grid-template-columns: 1fr; } }
</style>

<script src="<?php echo base_url(); ?>assets/vendor/Ckeditor/ckeditor.js"></script>
<script>
  ClassicEditor.create(document.querySelector('#changelog'), {
    toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo'],
    link: {
      decorators: {
        addTargetToLinks: {
          mode: 'manual',
          label: 'Open in a new tab',
          attributes: { target: '_blank' }
        }
      }
    }
  });
</script>
