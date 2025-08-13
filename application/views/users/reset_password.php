<div class="container">
<section class="hero is-fullheight">
    <div class="hero-body">
        <div class="container has-text-centered">
            <div class="column is-4 is-offset-4">
                <img src="<?php echo base_url(); ?>assets/images/logo-dark.svg" width="270" alt="LicenseBox" class="noselect">
                <p class="subtitle is-6 p-t-xs p-b-xs">Let's reset your password.</p>
                <?php if($this->session->flashdata('login_status')): ?>
                    <?php $flash = $this->session->flashdata('login_status');
                    echo ' <div class="notification is-'.$flash['type'].'"><button class="delete"></button>'.$flash['message'].'</div>'; ?>
                <?php endif; ?>
                <div class="box">
                 <?php echo form_open('reset_password/'.$this->uri->segment(2).'/'.$this->uri->segment(3), array('id' => 'reset_password_form')); ?>
                 <div class="field">
                    <div class="control has-icons-left">
                        <input class="input is-medium" type="password" minlength="8" name="new_password" value="<?php echo set_value('new_password'); ?>" placeholder="Enter your new password" autocomplete="new-password" required>
                        <span class="icon is-small is-left"><i class="fas fa-lock"></i></span>
                    </div>
                    <?php echo form_error('new_password', '<p class="help is-danger">', '</p>'); ?>
                </div>
                <div class="field">
                    <div class="control has-icons-left">
                        <input class="input is-medium" type="password" minlength="8" name="password_confirm" value="<?php echo set_value('password_confirm'); ?>" placeholder="Confirm your password" autocomplete="new-password" required>
                        <span class="icon is-small is-left"><i class="fas fa-lock"></i></span>
                    </div>
                    <?php echo form_error('password_confirm', '<p class="help is-danger">', '</p>'); ?>
                </div>
                <button type="submit" id="reset_password_form_submit" class="button is-block is-link is-medium is-fullwidth">Update</button>
                <?php echo form_close(); ?>
                <p class="p-t-sm m-t-xs"><a href="<?php echo base_url();?>login" class="has-text-dark"><i class="fas fa-long-arrow-alt-left"></i> Back to login</a></p>
            </div>
        </div>
    </div>
</div>
</section>
</div>