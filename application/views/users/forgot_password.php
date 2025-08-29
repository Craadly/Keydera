<div class="auth-wrapper">
    <div class="auth-branding-side">
        <div class="branding-content">
            <div class="logo-container">
                <img src="<?php echo base_url(); ?>assets/images/logo-white.svg" alt="Keydera" class="logo">
            </div>
            <h1 class="tagline">Forgot your password?</h1>
            <p class="description">Enter your email address and we'll send you a link to reset your password.</p>
        </div>
        <div class="branding-footer">
            <span>© <?php echo date('Y'); ?> Keydera. All rights reserved.</span>
        </div>
    </div>
    <div class="auth-form-side">
        <div class="form-container">
            <div class="form-header">
                <h2 class="form-title">Reset Password</h2>
                <p class="form-subtitle">We will email you a secure link.</p>
            </div>
            <?php if($this->session->flashdata('login_status')): ?>
                <?php $flash = $this->session->flashdata('login_status');
                echo '<div class="notification is-'.$flash['type'].'"><button class="delete"></button>'.$flash['message'].'</div>'; ?>
            <?php endif; ?>

            <?php echo form_open('users/forgot_password', array('id' => 'forgot_password_form', 'class' => 'login-form')); ?>
                <div class="field">
                    <label for="email" class="label">Email</label>
                    <div class="control has-icons-left">
                        <input id="email" class="input" type="email" name="email" placeholder="you@example.com" autofocus required>
                        <span class="icon is-left"><i class="fas fa-envelope"></i></span>
                    </div>
                </div>

                <button type="submit" id="forgot_password_form_submit" class="button is-primary is-fullwidth is-medium">Send Reset Link</button>
            <?php echo form_close(); ?>
            <div class="p-t-sm" style="margin-top:1rem;">
                <a href="<?php echo base_url();?>login">← Back to login</a>
            </div>
        </div>
    </div>
</div>
