<<<<<<< HEAD
<div class="main-content main-content-expanded">
  <div class="section">
    <div class="page-header">
      <h1 class="page-title"><?php echo $title; ?></h1>
    </div>

    <?php if($this->session->flashdata('product_status')): ?>
      <?php $flash = $this->session->flashdata('product_status'); ?>
      <div class="notification is-<?php echo $flash['type']; ?> is-light">
        <button class="delete"></button>
        <?php echo $flash['message']; ?>
      </div>
    <?php endif; ?>

    <div class="helper-grid">
      <div class="shadcn-card">
        <div class="card-header">
          <h3 class="card-title">Helper Configuration</h3>
          <p class="card-description">Select API key and response language for the helper file.</p>
        </div>
        <?php echo form_open('generate_internal', array('id' => 'internal_helper_form', 'class' => 'card-content')); ?>
          <div class="form-group">
            <label class="form-label required" for="api-key-select">API Key</label>
            <select id="api-key-select" name="key" class="shadcn-select" required>
              <option disabled selected>Select an API Key...</option>
              <?php foreach($api_keys as $key) : ?>
                <option value="<?php echo $key['key']; ?>"><?php echo $key['key']; ?> (Internal) <?php if ($key['ignore_limits']) echo '(Special)' ?></option>
              <?php endforeach; ?>
            </select>
            <a href="<?php echo base_url();?>api_settings#create_api_key" class="form-link">Create new key?</a>
            <?php echo form_error('key', '<p class="form-error">', '</p>'); ?>
          </div>

          <div class="form-group">
            <label class="form-label required" for="language-select">API Language</label>
            <select id="language-select" name="language" class="shadcn-select" required>
              <option disabled selected>Select a language...</option>
=======
<div class="container is-fluid main_body"> 
<div class="section">
  <h1 class="title">
    <?php echo $title; ?>
  </h1>
  <?php echo generate_breadcrumb('Generate Internal'); ?>
  <?php if($this->session->flashdata('product_status')): ?>
    <?php $flash = $this->session->flashdata('product_status');
    echo ' <div class="notification is-'.$flash['type'].'"><button class="delete"></button>'.$flash['message'].'</div>'; ?>
  <?php endif; ?>
  <div class="columns">
    <div class="column is-one-third">
     <div class="box">
      <?php echo form_open('generate_internal', array('id' => 'internal_helper_form')); ?>
        <div class="field">
          <label class="label">API Key for Helper File <a href="<?php echo base_url();?>api_settings#create_api_key"><small>(Create?)</small></a></label>
          <div class="control is-expanded">
            <select name="key" class="is-select2" style="width: 100%" value="<?php echo set_value('key'); ?>" required>
              <option disabled="" selected="">Select API Key</option>
              <?php foreach($api_keys as $key) : ?>
              <option value="<?php echo $key['key']; ?>"><?php echo $key['key']; ?> (Internal) <?php if ($key['ignore_limits']) echo '(Special)' ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <?php echo form_error('key', '<p class="help is-danger">', '</p>'); ?>
        </div>
        <div class="field">
          <label class="label">API Language <small class="tooltip is-tooltip-multiline is-tooltip-right " data-tooltip="Choose the language in which you wish the Keydera API to respond, useful if you are using Keydera in an application which is in a different language (e.g German)." style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
          <div class="control is-expanded">
            <select name="language" class="is-select2" style="width: 100%" value="<?php echo set_value('language'); ?>" required>
              <option disabled="" selected="">Select API Language</option>
>>>>>>> origin/main
              <?php foreach($languages as $language) : ?>
                <option value="<?php echo $language; ?>" <?php echo ($language=='english')?'selected="true"':null; ?>><?php echo ucfirst($language); ?></option>
              <?php endforeach; ?>
            </select>
<<<<<<< HEAD
            <p class="form-description">
              Choose the language for API responses.
              <span class="tooltip" data-tooltip="Useful if your application uses a different language (e.g., German).">
                <i class="fas fa-question-circle"></i>
              </span>
            </p>
            <?php echo form_error('language', '<p class="form-error">', '</p>'); ?>
          </div>
          
          <div class="card-footer">
            <button type="submit" id="internal_helper_form_submit" class="shadcn-button">
              <i class="fas fa-magic"></i>
              <span>Generate</span>
            </button>
          </div>
        </form>
      </div>

      <div class="shadcn-card">
        <div class="card-header">
          <h3 class="card-title">Generated Helper File</h3>
          <p class="card-description">Copy the generated code and follow the documentation.</p>
        </div>
        <div class="card-content">
          <div class="form-group">
            <textarea id="internal-generated-code" class="shadcn-textarea" name="generated_code" placeholder="Your generated code will appear here..." rows="12"><?php if(!empty($generated_code)){echo $generated_code; }?></textarea>
          </div>
        </div>
        <div class="card-footer">
          <button type="button" id="copy-internal-code" class="shadcn-button-secondary">
            <i class="fas fa-clipboard"></i>
            <span>Copy Code</span>
          </button>
        </div>
=======
          </div>
          <?php echo form_error('key', '<p class="help is-danger">', '</p>'); ?>
        </div>
        <div class="field is-grouped p-t-xs">
          <div class="control">
            <button type="submit" id="internal_helper_form_submit" class="button is-link">Generate</button>
          </div>
        </div>
        </form>
      </div>
    </div>
    <div class="column">
     <div class="box">
      <div class="field">
        <label class="label">Generated Internal Helper File</label>
        <textarea class="textarea" name="generated_code" placeholder="Code will be generated here, once the code is generated copy it and follow the instructions from the documentation." rows="18"><?php if(!empty($generated_code)){echo $generated_code; }?></textarea>
>>>>>>> origin/main
      </div>
    </div>
  </div>
</div>
<<<<<<< HEAD



<script>
  (function(){
    var copyBtn = document.getElementById('copy-internal-code');
    if(copyBtn){
      copyBtn.addEventListener('click', function(){
        var ta = document.getElementById('internal-generated-code');
        if(!ta) return;
        ta.select();
        ta.setSelectionRange(0, 99999);
        try {
          var ok = document.execCommand('copy');
          if(!ok && navigator.clipboard){ navigator.clipboard.writeText(ta.value); }
        } catch(e) { if(navigator.clipboard){ navigator.clipboard.writeText(ta.value); } }
      });
    }
  })();
</script>
=======
</div> 
</div>
>>>>>>> origin/main
