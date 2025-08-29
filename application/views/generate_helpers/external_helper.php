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
          <p class="card-description">Select product, API key, language, and other settings.</p>
        </div>
        <?php echo form_open('generate_external', array('id' => 'external_helper_form', 'class' => '')); ?>
          <div class="card-content">
            <div class="form-group">
              <label class="form-label required" for="product-select">Product</label>
              <select id="product-select" name="product" class="shadcn-select" required>
                <option disabled selected>Select a Product...</option>
                <?php foreach($products as $product) : ?>
                  <option value="<?php echo $product['pd_pid']; ?>"><?php echo $product['pd_name']." (".($product['pd_status'] ? 'Active' : 'Inactive').")"; ?></option>
                <?php endforeach; ?>
              </select>
              <?php echo form_error('product', '<p class="form-error">', '</p>'); ?>
            </div>

            <div class="form-group">
              <label class="form-label required" for="api-key-select">API Key</label>
              <select id="api-key-select" name="key" class="shadcn-select" required>
                <option disabled selected>Select an API Key...</option>
                <?php foreach($api_keys as $key) : ?>
                  <option value="<?php echo $key['key']; ?>"><?php echo $key['key']; ?> (External) <?php if ($key['ignore_limits']) echo '(Special)' ?></option>
                <?php endforeach; ?>
              </select>
              <a href="<?php echo base_url();?>api_settings#create_api_key" class="form-link">Create new key?</a>
              <?php echo form_error('key', '<p class="form-error">', '</p>'); ?>
            </div>

            <div class="form-group">
              <label class="form-label required" for="language-select">API Language</label>
              <select id="language-select" name="language" class="shadcn-select" required>
                <option disabled selected>Select a language...</option>
                <?php foreach($languages as $language) : ?>
                  <option value="<?php echo $language; ?>" <?php echo ($language=='english')?'selected="true"':null; ?>><?php echo ucfirst($language); ?></option>
                <?php endforeach; ?>
              </select>
               <p class="form-description">
                Choose the language for API responses.
                <span class="tooltip" data-tooltip="Useful if your application uses a different language (e.g., German).">
                  <i class="fas fa-question-circle"></i>
                </span>
              </p>
              <?php echo form_error('language', '<p class="form-error">', '</p>'); ?>
            </div>

            <div class="form-group">
              <label class="form-label required" for="period-select">Verification Period</label>
              <select id="period-select" name="period" class="shadcn-select" required>
                <option disabled selected>Select verification period...</option>
                <option value="1">Every day</option>
                <option value="3">Every 3 days</option>
                <option value="7">Every week</option>
                <option value="30">Every month</option>
                <option value="90">Every 3 months</option>
                <option value="365">Every year</option>
              </select>
               <p class="form-description">
                How often background license verifications should run.
              </p>
            </div>

            <div class="form-group">
                <div class="shadcn-checkbox-group">
                    <input class="shadcn-checkbox" type="checkbox" name="for_wordpress" id="for_wordpress">
                    <label for="for_wordpress" class="shadcn-checkbox-label">
                        Generating Helper for WordPress?
                        <span class="shadcn-checkbox-description">Uses core WordPress functions for requests and filesystem operations.</span>
                    </label>
                </div>
            </div>

             <div class="form-group">
                <div class="shadcn-checkbox-group">
                    <input class="shadcn-checkbox" type="checkbox" name="envato" id="envato">
                     <label for="envato" class="shadcn-checkbox-label">
                        Allow Envato Purchase Codes?
                    </label>
                </div>
            </div>

          </div>
          <div class="card-footer">
            <button type="submit" id="external_helper_form_submit" class="shadcn-button">
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
            <textarea id="external-generated-code" class="shadcn-textarea" name="generated_code" placeholder="Your generated code will appear here..." rows="12"><?php if(!empty($generated_code)){echo $generated_code; }?></textarea>
          </div>
        </div>
        <div class="card-footer">
          <button type="button" id="copy-external-code" class="shadcn-button-secondary">
            <i class="fas fa-clipboard"></i>
            <span>Copy Code</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  (function(){
    var copyBtn = document.getElementById('copy-external-code');
    if(copyBtn){
      copyBtn.addEventListener('click', function(){
        var ta = document.getElementById('external-generated-code');
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
