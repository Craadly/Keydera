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
      </div>
    </div>
  </div>
</div>
</div> 
</div>