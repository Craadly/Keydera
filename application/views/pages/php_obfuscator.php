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

    <div class="shadcn-card">
      <?php echo form_open('php_obfuscator'); ?>
        <div class="card-header">
          <h3 class="card-title">PHP Code Obfuscator</h3>
          <p class="card-description">Obfuscate your PHP code to make it difficult to read and understand. This process is done in real-time and your code is never stored.</p>
        </div>
        <div class="card-content">
          <div class="helper-grid">
            <div class="form-group">
              <label class="form-label" for="obfuscate_type">Obfuscation Type</label>
              <select id="obfuscate_type" name="obfuscate_type" class="shadcn-select" required>
                <option value="lite" <?php echo set_select('obfuscate_type', 'lite', TRUE); ?>>Lite Obfuscation (Online)</option>
                <option value="advanced" <?php echo set_select('obfuscate_type', 'advanced'); ?>>Advanced Obfuscation (Online)</option>
              </select>
              <p class="form-description">Select the type of PHP Obfuscation to apply.</p>
            </div>

            <div class="form-group">
              <label class="form-label" for="minify_type">Minify HTML</label>
              <select id="minify_type" name="minify_type" class="shadcn-select" required>
                <option value="none" <?php echo set_select('minify_type', 'none', TRUE); ?>>Don't Minify</option>
                <option value="html" <?php echo set_select('minify_type', 'html'); ?>>Minify HTML</option>
              </select>
              <p class="form-description">Minify HTML output to reduce size and speed up page load.</p>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label" for="php_source_code">PHP Source Code</label>
            <textarea id="php_source_code" class="shadcn-textarea" name="php_source_code" placeholder="Paste your PHP Code here..." rows="15"><?php echo set_value('php_source_code', !empty($php_source_code) ? $php_source_code : ''); ?></textarea>
            <?php echo form_error('php_source_code', '<p class="form-error">', '</p>'); ?>
            <p class="form-description">Paste the PHP code which you want to obfuscate.</p>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="shadcn-button show_loading">
            <i class="fas fa-user-secret"></i>
            Obfuscate Code
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
