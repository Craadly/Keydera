<div class="container is-fluid main_body"> 
<div class="section">
  <h1 class="title">
    <?php echo $title; ?>
  </h1>
  <?php echo generate_breadcrumb('Generate External'); ?>
  <?php if($this->session->flashdata('product_status')): ?>
    <?php $flash = $this->session->flashdata('product_status');
    echo ' <div class="notification is-'.$flash['type'].'"><button class="delete"></button>'.$flash['message'].'</div>'; ?>
  <?php endif; ?>
  <div class="columns">
    <div class="column is-one-third">
     <div class="box">
      <?php echo form_open('generate_external', array('id' => 'external_helper_form')); ?>
        <div class="field">
          <label class="label">Product</label>
          <div class="control is-expanded">
            <select name="product" class="is-select2" style="width: 100%" value="<?php echo set_value('product'); ?>" required>
              <option disabled="" selected="">Select Product</option>
              <?php foreach($products as $product) : ?>
              <option value="<?php echo $product['pd_pid']; ?>"><?php echo $product['pd_name']." (".($product['pd_status'] ? 'Active' : 'Inactive').")"; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <?php echo form_error('product', '<p class="help is-danger">', '</p>'); ?>
        </div>
        <div class="field">
          <label class="label">API Key for Helper File <a href="<?php echo base_url();?>api_settings#create_api_key"><small>(Create?)</small></a></label>
          <div class="control is-expanded">
            <select name="key" class="is-select2" style="width: 100%" value="<?php echo set_value('key'); ?>" required>
              <option disabled="" selected="">Select API Key</option>
              <?php foreach($api_keys as $key) : ?>
              <option value="<?php echo $key['key']; ?>"><?php echo $key['key']; ?> (External) <?php if ($key['ignore_limits']) echo '(Special)' ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <?php echo form_error('key', '<p class="help is-danger">', '</p>'); ?>
        </div>
        <div class="field">
          <label class="label">API Language <small class="tooltip is-tooltip-multiline is-tooltip-right " data-tooltip="Choose the language in which you wish the LicenseBox API to respond, useful if you are using LicenseBox in an application which is in a different language (e.g German)." style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
          <div class="control is-expanded">
            <select name="language" class="is-select2" style="width: 100%" value="<?php echo set_value('language'); ?>" required>
              <option disabled="" selected="">Select API Language</option>
              <?php foreach($languages as $language) : ?>
                <option value="<?php echo $language; ?>" <?php echo ($language=='english')?'selected="true"':null; ?>><?php echo ucfirst($language); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <?php echo form_error('key', '<p class="help is-danger">', '</p>'); ?>
        </div>
        <div class="field">
          <label class="label">Verification Period <small class="tooltip is-tooltip-right " data-tooltip="Select the duration for background license verifications." style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
          <div class="control is-expanded">
            <select name="period" class="is-select2" style="width: 100%" value="<?php echo set_value('period'); ?>" required> 
              <option disabled="" selected="">Select Verification Period</option>
              <option value="1">every day</option>
              <option value="3">every 3 days</option>
              <option value="7">every week</option>
              <option value="30">every month</option>
              <option value="90">every 3 months</option>
              <option value="365">every year</option>
            </select>
          </div>
        </div>
        <div class="field p-t-xs">
          <input class="is-checkradio is-danger" type="checkbox" name="for_wordpress" id="for_wordpress">
          <label for="for_wordpress" style="margin-left: 0px !important;">Generating Helper for WordPress? <small class="tooltip is-tooltip-multiline is-tooltip-right " data-tooltip="Selecting this option will generate the helper file which only uses core WordPress functions for making API requests and for working with the file system." style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
        </div>
        <div class="field">
          <input class="is-checkradio is-danger" type="checkbox" name="envato" id="envato">
          <label for="envato" style="margin-left: 0px !important;">Allow Envato Purchase Codes?</label>
        </div>
        <div class="field is-grouped">
          <div class="control">
            <button type="submit" id="external_helper_form_submit" class="button is-link">Generate</button>
          </div>
        </div>
        </form>
      </div>
    </div>
    <div class="column">
     <div class="box">
      <div class="field">
        <label class="label">Generated External Helper File</label>
        <textarea class="textarea" name="generated_code" placeholder="Code will be generated here, once the code is generated copy it and follow the instructions from the documentation." rows="18"><?php if(!empty($generated_code)){echo $generated_code; }?></textarea>
      </div>
    </div>
  </div>
</div>
</div> 
</div>