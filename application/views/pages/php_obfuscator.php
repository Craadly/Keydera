<div class="container is-fluid main_body"> 
<div class="section">
  <h1 class="title">
    <?php echo $title; ?>
  </h1>
  <?php echo generate_breadcrumb('PHP Obfuscator'); ?>
  <?php if($this->session->flashdata('product_status')): ?>
    <?php $flash = $this->session->flashdata('product_status');
    echo ' <div class="notification is-'.$flash['type'].'"><button class="delete"></button>'.$flash['message'].'</div>'; ?>
  <?php endif; ?>
  <div class="columns">
    <div class="column">
     <div class="box">
      <?php echo form_open('php_obfuscator'); ?>
      <div class="columns" style="margin-bottom: 0px;">
        <div class="column">
          <div class="field">
            <label class="label">Obfuscation Type <small class="tooltip is-tooltip-right " data-tooltip="Select the type of PHP Obfuscation." style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
            <div class="control is-expanded">
            <select name="obfuscate_type" class="is-select2" style="width: 100%" value="<?php echo set_value('obfuscate_type'); ?>" required> 
              <option value="lite" selected="">Lite Obfuscation (Online)</option>
              <option value="advanced">Advanced Obfuscation (Online)</option>
            </select>
            </div>
          </div>
        </div>
        <div class="column">
          <div class="field">
            <label class="label">Minify HTML <small class="tooltip is-tooltip-right is-tooltip-multiline" data-tooltip="Select if you want to minify HTML output by removing comments, whitespaces etc. This reduces file size which helps in increasing the page-load speed." style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
            <div class="control is-expanded">
            <select name="minify_type" class="is-select2" style="width: 100%" value="<?php echo set_value('minify_type'); ?>" required> 
              <option value="none" selected="">Don't Minify</option>
              <option value="html">Minify HTML</option>
            </select>
            </div>
          </div>
        </div>
      </div>
      <div class="field">
        <label class="label">PHP Source Code <small class="tooltip is-tooltip-right is-tooltip-multiline" data-tooltip="Paste the PHP code which you want to obfuscate. The PHP code will be sent to the server for real-time obfuscation using the selected method which means the code is never saved. " style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
        <textarea class="textarea" name="php_source_code" placeholder="Paste your PHP Code here..." rows="20"><?php if(!empty($php_source_code)){echo $php_source_code; }?></textarea>
        <?php echo form_error('php_source_code', '<p class="help is-danger">', '</p>'); ?>
      </div>
      <div class="field is-grouped">
        <div class="control">
          <button type="submit" id="external_helper_form_submit" class="button is-link">Obfuscate</button>
        </div>
      </div>
      </form>
    </div>
  </div>
</div>
</div> 
</div>