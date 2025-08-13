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
<?php 
$hidden = array('license_code' => $license['license_code'], 'old_client' => $license['client']); 
echo form_open('licenses/edit', array('id' => 'edit_form'), $hidden); ?>
<div class="columns" style="margin-bottom:0px!important;">
  <div class="column">
    <div class="field" style="padding-bottom: 3px;">
      <label class="label">License for Product</label>
      <div style="padding-bottom: 1px;">
        <select name="product" class="is-select2" style="width: 100%" value="" tabindex="1" required>
        <?php foreach($products as $product) : 
          if($product['pd_pid']==$license['pid']):?>
        <option value="<?php echo $product['pd_pid']; ?>" selected><?php echo $product['pd_name']." (".($product['pd_status'] ? 'active' : 'inactive').")"; ?></option>
        <?php else: ?>
        <option value="<?php echo $product['pd_pid']; ?>"><?php echo $product['pd_name']." (".($product['pd_status'] ? 'active' : 'inactive').")"; ?></option>
        <?php endif;
        endforeach; ?>
        </select>
      </div>
    </div>
    <div class="field">
    <label class="label">License Type (optional)</label>
    <div class="control">
      <input class="input" type="text" name="license_type" maxlength="255" value="<?php if(!empty(set_value('license_type'))) {
          echo set_value('license_type');
        }
        else{ 
          echo $license['license_type']; 
        } ?>" placeholder="Enter license type" tabindex="3">
    </div>
    </div>
    <div class="field">
    <label class="label">Client (Leave empty for use by any client)</label>
    <div class="control">
      <input class="input" type="text" name="client" maxlength="255" value="<?php if(!empty(set_value('client'))) {
        echo set_value('client');
      }
      else{ 
        echo $license['client']; 
      } ?>" placeholder="Enter client's name or envato username" tabindex="5">
    </div>
    </div>
  <div class="field">
    <label class="label">Total License Use Limit (Leave empty for unlimited uses) <small class="tooltip is-tooltip-multiline is-tooltip-right " data-tooltip="License use limits define how many times a license can be used for activating the given product (e.g if use limit of a license is set to 10 then the given license can be used to activate a product 10 times before the license becomes invalid provided that other conditions like domain, IP, parallel use, expiry etc. are fulfilled)" style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
    <div class="control">
      <input class="input" type="number" min="1" name="uses" value="<?php if(!empty(set_value('uses'))) {
        echo set_value('uses');
      }
        else{ echo $license['uses']; 
          } ?>" placeholder="Enter no of total available license uses allowed" tabindex="7">
    </div>
    <?php echo form_error('uses', '<p class="help is-danger">', '</p>'); ?>
  </div>
  <div class="field">
    <label class="label">License Expiration Date (optional)</label>
    <div class="control">
      <input class="input date-time-picker" type="text" name="expiry" value="<?php if(!empty(set_value('expiry'))) {
          echo set_value('expiry');
        }
        else{ 
          echo $license['expiry']; 
        } ?>" placeholder="Enter license expiry date" tabindex="9">
    </div>
  </div>
  <div class="field">
    <label class="label">Updates End Date (optional) <small class="tooltip is-tooltip-multiline is-tooltip-right " data-tooltip="Product updates can't be installed/downloaded from activation(s) of this license code after the provided updates end date." style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
    <div class="control">
      <input class="input date-time-picker" type="text" name="updates_till" maxlength="255" value="<?php if(!empty(set_value('updates_till'))) {
        echo set_value('updates_till');
      }
        else{ echo $license['updates_till']; 
          }
       ?>" placeholder="Enter updates period ending date" tabindex="11">
    </div>
  </div>
  <div class="field">
    <label class="label">Licensed Domains (optional)</label>
    <div class="control">
      <input class="input" type="tags" name="domains" value="<?php if(!empty(set_value('domains'))){
          echo set_value('domains');
        }
        else{ 
          echo $license['domains']; 
        } ?>" placeholder="Enter licensed domains" tabindex="13">
    </div>
    <?php echo form_error('domains', '<p class="help is-danger" style="margin-top: 1rem;">', '</p>'); ?>
  </div>
</div>
<div class="column">
  <div class="field">
    <label class="label">License Code</label>
    <div class="control">
      <input class="input" type="text" name="license" maxlength="255" minlength="7" value="<?php echo $license['license_code']; ?>" placeholder="Enter License code" tabindex="2" required disabled>
    </div>
    <?php echo form_error('license', '<p class="help is-danger">', '</p>'); ?>
  </div>
  <div class="field">
    <label class="label">Invoice Number (optional)</label>
    <div class="control">
      <input class="input" type="text" name="invoice" maxlength="255" value="<?php if(!empty(set_value('invoice'))) {
          echo set_value('invoice');
        }
        else{ 
          echo $license['invoice']; 
        } ?>" placeholder="Enter invoice/order number" tabindex="4">
    </div>
  </div>
  <div class="field">
    <label class="label">Client's Email (optional)</label>
    <div class="control">
      <input class="input" type="email" name="email" maxlength="255" value="<?php if(!empty(set_value('email'))) {
          echo set_value('email');
        }
        else{ 
          echo $license['email']; 
        } ?>" placeholder="Enter client's email" tabindex="6">
    </div>
    <?php echo form_error('email', '<p class="help is-danger">', '</p>'); ?>
  </div>
  <div class="field">
    <label class="label">Total Parallel Use Limit (Leave empty for unlimited parallel uses) <small class="tooltip is-tooltip-multiline is-tooltip-left " data-tooltip="Parallel license use limits define how many active and valid activations can exist for a license at any given time (e.g if parallel uses of a license is set to 2 then the given license can be used to activate and run two instances of a product simultaneously, for activating a 3rd instance one of the current activation has to be marked as inactive)" style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
    <div class="control">
      <input class="input" type="number" min="1" name="parallel_uses" value="<?php if(!empty(set_value('parallel_uses'))) {
          echo set_value('parallel_uses');
        }
        else{ 
          echo $license['parallel_uses']; 
        } ?>" placeholder="Enter no of total simultaneous license uses allowed" tabindex="8">
    </div>
    <?php echo form_error('parallel_uses', '<p class="help is-danger">', '</p>'); ?>
  </div>
  <div class="field">
    <label class="label">License Expiration Days (optional) <small class="tooltip is-tooltip-multiline is-tooltip-left " data-tooltip="License expiration days define in how many days the license will automatically expire after its first activation, useful for creating time based trial licenses." style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
    <div class="control">
      <input class="input" type="number" name="expiry_days" min="1" value="<?php if(!empty(set_value('email'))) {
          echo set_value('expiry_days');
        }
        else{ 
          echo $license['expiry_days']; 
        } ?>" placeholder="Enter no of days after which the license expires" tabindex="10">
    </div>
  </div>
  <div class="field">
    <label class="label">Support End Date (optional)</label>
    <div class="control">
      <input class="input date-time-picker" type="text" name="supported_till" maxlength="255" value="<?php if(!empty(set_value('supported_till'))) {
        echo set_value('supported_till');
      }
      else{ 
        echo $license['supported_till']; 
      } ?>" placeholder="Enter support period ending date" tabindex="12">
    </div>
  </div>
  <div class="field">
    <label class="label">Licensed IPs (optional)</label>
    <div class="control">
      <input class="input" type="tags" name="ips" value="<?php if(!empty(set_value('ips'))) {
          echo set_value('ips');
        }
        else{ 
          echo $license['ips']; 
        } ?>" placeholder="Enter licensed IPs" tabindex="14">
    </div>
    <?php echo form_error('ips', '<p class="help is-danger" style="margin-top: 1rem;">', '</p>'); ?>
  </div>
</div>
</div>
<div class="field">
  <label class="label">Comments (optional)</label>
  <div class="control">
    <textarea class="textarea" name="comments" tabindex="15" placeholder="Enter comments here"><?php if(!empty(set_value('comments'))) {
        echo set_value('comments');
      }
      else{ 
        echo $license['comments']; 
      } ?></textarea>
  </div>
</div>
<div class="field">
  <?php if($license['validity']==0):?>
    <input class="is-checkradio is-danger" type="checkbox" name="validity" id="validity" tabindex="16" checked>
  <?php else: ?>
    <input class="is-checkradio is-danger" type="checkbox" name="validity" id="validity" tabindex="16">
  <?php endif; ?>
    <label for="validity" style="margin-left: 0px !important;">Block license?</label>
</div>
<div class="field p-t-sm is-grouped">
  <div class="control">
    <button type="submit" id="edit_form_submit" class="button is-link">Save Changes</button>
  </div>
</div>
<p class="help has-text-centered ">Please note: Any changes made above will only take effect during the next background license check when the license is in use.</p>
</form>
</div>
</div>
</div>