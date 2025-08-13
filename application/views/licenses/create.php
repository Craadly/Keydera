<div class="container is-fluid main_body"> 
<div class="section" >
  <h1 class="title">
    <?php echo $title; ?>
  </h1>
<?php echo generate_breadcrumb(); ?>
<?php if($this->session->flashdata('license_status')): ?>
  <?php $flash = $this->session->flashdata('license_status');
  echo ' <div class="notification is-'.$flash['type'].'"><button class="delete"></button>'.$flash['message'].'</div>'; ?>
<?php endif; ?>
<div class="box">
<?php echo form_open('licenses/create', array('id' => 'create_form')); ?>
<div class="columns" style="margin-bottom:0px!important;">
  <div class="column">
  <div class="field" style="padding-bottom: 3px;">
    <label class="label">License for Product <a href="<?php echo base_url();?>products/add"><small>(Add?)</small></a></label>
    <div style="padding-bottom: 1px;">
      <select name="product" class="is-select2" style="width: 100%" value="<?php echo set_value('product'); ?>" tabindex="1" required>
        <option disabled="" selected="">Select Product</option>
        <?php foreach($products as $product) : ?>
        <option value="<?php echo $product['pd_pid']; ?>"><?php echo $product['pd_name']." (".($product['pd_status'] ? 'active' : 'inactive').")"; ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  <?php echo form_error('product', '<p class="help is-danger">', '</p>'); ?>
  </div>
  <div class="field">
    <label class="label">License Type (optional)</label>
    <div class="control">
      <input class="input" type="text" name="license_type" maxlength="255" value="<?php echo set_value('license_type'); ?>" placeholder="Enter license type" tabindex="3">
    </div>
  </div>
  <div class="field">
    <label class="label">Client (Leave empty for use by any client)</label>
    <div class="control">
      <input class="input" type="text" name="client" maxlength="255" value="<?php echo set_value('client'); ?>" placeholder="Enter client's name or envato username" tabindex="5">
    </div>
  </div>
  <div class="field">
    <label class="label">Total License Use Limit (Leave empty for unlimited uses) <small class="tooltip is-tooltip-multiline is-tooltip-right " data-tooltip="License use limits define how many times a license can be used for activating the given product (e.g if use limit of a license is set to 10 then the given license can be used to activate a product 10 times before the license becomes invalid provided that other conditions like domain, IP, parallel use, expiry etc. are fulfilled)" style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
    <div class="control">
      <input class="input" type="number" min="1" name="uses" value="<?php echo set_value('uses'); ?>" placeholder="Enter no of total available license uses allowed" tabindex="7">
    </div>
    <?php echo form_error('uses', '<p class="help is-danger">', '</p>'); ?>
  </div>
  <div class="field">
    <label class="label">License Expiration Date (optional)</label>
    <div class="control">
      <input class="input date-time-picker" type="text" name="expiry" value="<?php echo set_value('expiry'); ?>" placeholder="Enter license expiry date" tabindex="9">
    </div>
  </div>
  <div class="field">
    <label class="label">Updates End Date (optional) <small class="tooltip is-tooltip-multiline is-tooltip-right " data-tooltip="Product updates can't be installed/downloaded from activation(s) of this license code after the provided updates end date." style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
    <div class="control">
      <input class="input date-time-picker" type="text" name="updates_till" maxlength="255" value="<?php echo set_value('updates_till'); ?>" placeholder="Enter updates period ending date" tabindex="11">
    </div>
  </div>
  <div class="field">
    <label class="label">Licensed Domains (optional)</label>
    <div class="control">
      <input class="input" type="tags" name="domains" value="<?php echo set_value('domains'); ?>" placeholder="Enter licensed domains" tabindex="13">
    </div>
    <?php echo form_error('domains', '<p class="help is-danger" style="margin-top: 1rem;">', '</p>'); ?>
  </div>
</div>
<div class="column">
  <div class="field">
    <label class="label">License Code</label>
    <div class="control">
      <input class="input" type="text" name="license" maxlength="255" minlength="2" value="<?php 
      if(!empty(set_value('license'))) {
        echo set_value('license');
      }
      else{ 
        echo $created_license; 
      } ?>" placeholder="Enter License code" tabindex="2" required>
    </div>
    <?php echo form_error('license', '<p class="help is-danger">', '</p>'); ?>
  </div>
  <div class="field">
    <label class="label">Invoice Number (optional)</label>
    <div class="control">
      <input class="input" type="text" name="invoice" maxlength="255" value="<?php echo set_value('invoice'); ?>" placeholder="Enter invoice/order number" tabindex="4">
    </div>
  </div>
  <div class="field">
    <label class="label">Client's Email (optional)</label>
    <div class="control">
      <input class="input" type="email" name="email" maxlength="255" value="<?php echo set_value('email'); ?>" placeholder="Enter client's email" tabindex="6">
    </div>
    <?php echo form_error('email', '<p class="help is-danger">', '</p>'); ?>
  </div>
  <div class="field">
    <label class="label">Total Parallel Use Limit (Leave empty for unlimited parallel uses) <small class="tooltip is-tooltip-multiline is-tooltip-left " data-tooltip="Parallel license use limits define how many active and valid activations can exist for a license at any given time (e.g if parallel uses of a license is set to 2 then the given license can be used to activate and run two instances of a product simultaneously, for activating a 3rd instance one of the current activation has to be marked as inactive)" style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
    <div class="control">
      <input class="input" type="number" min="1" name="parallel_uses" value="<?php echo set_value('parallel_uses'); ?>" placeholder="Enter no of total simultaneous license uses allowed" tabindex="8">
    </div>
    <?php echo form_error('parallel_uses', '<p class="help is-danger">', '</p>'); ?>
  </div>
  <div class="field">
    <label class="label">License Expiration Days (optional) <small class="tooltip is-tooltip-multiline is-tooltip-left " data-tooltip="License expiration days define in how many days the license will automatically expire after its first activation, useful for creating time based trial licenses." style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
    <div class="control">
      <input class="input" type="number" name="expiry_days" min="1" value="<?php echo set_value('expiry_days'); ?>" placeholder="Enter no of days after which the license expires" tabindex="10">
    </div>
  </div>
  <div class="field">
    <label class="label">Support End Date (optional)</label>
    <div class="control">
      <input class="input date-time-picker" type="text" name="supported_till" maxlength="255" value="<?php echo set_value('supported_till'); ?>" placeholder="Enter support period ending date" tabindex="12">
    </div>
  </div>
  <div class="field">
    <label class="label">Licensed IPs (optional)</label>
    <div class="control">
      <input class="input" type="tags" name="ips" value="<?php echo set_value('ips'); ?>" placeholder="Enter licensed IPs" tabindex="14">
    </div>
    <?php echo form_error('ips', '<p class="help is-danger" style="margin-top: 1rem;">', '</p>'); ?>
  </div>
  </div>
</div>
<div class="field">
  <label class="label">Comments (optional)</label>
  <div class="control">
    <textarea class="textarea" name="comments" placeholder="Enter comments here" tabindex="15"><?php echo set_value('comments'); ?></textarea>
  </div>
</div>
<div class="field">
  <input class="is-checkradio is-danger" type="checkbox" name="validity" id="validity" tabindex="16">
  <label for="validity" style="margin-left: 0px !important;">Block license?</label>
</div>
<div class="field p-t-sm is-grouped">
  <div class="control">
    <button type="submit" id="create_form_submit" class="button is-link">Submit</button>
  </div>
</div>
</form>
</div>
</div> 
</div>