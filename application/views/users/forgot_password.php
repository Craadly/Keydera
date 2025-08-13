<div class="container">  
<section class="hero is-fullheight">
    <div class="hero-body">
        <div class="container has-text-centered">
            <div class="column is-4 is-offset-4">
                <img src="<?php echo base_url(); ?>assets/images/logo-dark.svg" width="270" alt="Keydera" class="noselect">
                <p class="subtitle is-6 p-t-xs p-b-xs">Let's reset your password.</p>
                <?php if($this->session->flashdata('login_status')): ?>
                    <?php $flash = $this->session->flashdata('login_status');
                    echo ' <div class="notification is-'.$flash['type'].'"><button class="delete"></button>'.$flash['message'].'</div>'; ?>
                <?php endif; ?>
                <div class="box">
                 <?php echo form_open('users/forgot_password', array('id' => 'forgot_password_form')); ?>
                 <div class="field">
                    <div class="control has-icons-left">
                        <input class="input is-medium" type="text" name="email" placeholder="Enter your email" autofocus="" required>
                        <span class="icon is-small is-left"><i class="fas fa-user "></i></span>
                    </div>
                </div>
                <button type="submit" id="forgot_password_form_submit" class="button is-block is-link is-medium is-fullwidth">Reset</button>
                <?php echo form_close(); ?>
                <p class="p-t-sm m-t-xs"><a href="<?php echo base_url();?>login" class="has-text-dark"><i class="fas fa-long-arrow-alt-left"></i> Back to login</a></p>
            </div>
        </div>
    </div>
</div>
</section>
</div>
