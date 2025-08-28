<<<<<<< HEAD
<div class="auth-wrapper">
    <div class="auth-branding-side">
        <div class="branding-content">
            <div class="logo-container">
                <img src="<?php echo base_url(); ?>assets/images/logo-white.svg" alt="Keydera" class="logo">
            </div>
            <h1 class="tagline">License Management, <br>Perfected.</h1>
            <p class="description">Welcome back. Manage your software licenses, updates, and customer activations all in one place.</p>
        </div>
        <div class="branding-footer">
            <span>© <?php echo date('Y'); ?> Keydera. All rights reserved.</span>
        </div>
    </div>
    <div class="auth-form-side">
        <div class="form-container">
            <div class="form-header">
                <h2 class="form-title">Login</h2>
                <p class="form-subtitle">Enter your credentials to access your account.</p>
            </div>

            <?php if($this->session->flashdata('login_status')): ?>
                <?php $flash = $this->session->flashdata('login_status');
                echo '<div class="notification is-'.$flash['type'].'"><button class="delete"></button>'.$flash['message'].'</div>'; ?>
            <?php endif; ?>

            <?php echo form_open('users/login', array('id' => 'login_form', 'class' => 'login-form')); ?>
                <div class="field">
                    <label for="username" class="label">Email or Username</label>
                    <div class="control has-icons-left">
                        <input class="input" id="username" type="text" name="username" value="<?php echo set_value('username'); ?>" placeholder="you@example.com" autocomplete="on" autofocus required>
                        <span class="icon is-small is-left"><i class="fas fa-user"></i></span>
                    </div>
                </div>

                <div class="field">
                    <label for="password" class="label">Password</label>
                    <div class="control has-icons-left">
                        <input class="input" id="password" type="password" name="password" value="<?php echo set_value('password'); ?>" placeholder="••••••••" autocomplete="on" required>
                        <span class="icon is-small is-left"><i class="fas fa-lock"></i></span>
                    </div>
                </div>

                <div class="field is-grouped is-justify-content-space-between">
                    <div class="control">
                        <label class="checkbox">
                            <input type="checkbox" name="remember">
                            Remember me
                        </label>
                    </div>
                    <div class="control">
                        <a href="<?php echo base_url();?>forgot_password">Forgot password?</a>
                    </div>
                </div>

                <button type="submit" id="login_form_submit" class="button is-block is-primary is-fullwidth is-medium">Sign In</button>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

=======
<div class="container">  
<section class="hero is-fullheight">
    <div class="hero-body">
        <div class="container has-text-centered">
            <div class="column is-4 is-offset-4">
                <img src="<?php echo base_url(); ?>assets/images/logo-dark.svg" width="270" alt="Keydera" class="noselect">
                <p class="subtitle is-6 p-t-xs p-b-xs">Log in to your account.</p>
                <?php if($this->session->flashdata('login_status')): ?>
                    <?php $flash = $this->session->flashdata('login_status');
                    echo ' <div class="notification is-'.$flash['type'].'"><button class="delete"></button>'.$flash['message'].'</div>'; ?>
                <?php endif; ?>
                <div class="box">
                 <?php echo form_open('users/login', array('id' => 'login_form')); ?>
                 <div class="field">
                    <div class="control has-icons-left">
                        <input class="input is-medium" type="text" name="username" value="<?php echo set_value('username'); ?>" placeholder="Email or username" autocomplete="on" autofocus required>
                        <span class="icon is-small is-left"><i class="fas fa-user"></i></span>
                    </div>
                </div>
                <div class="field">
                    <div class="control has-icons-left">
                        <input class="input is-medium" type="password" name="password" value="<?php echo set_value('password'); ?>" placeholder="Password" autocomplete="on" required>
                        <span class="icon is-small is-left"><i class="fas fa-lock"></i></span>
                    </div>
                </div>
                <button type="submit" id="login_form_submit" class="button is-block is-link is-medium is-fullwidth">Login</button>
                <?php echo form_close(); ?>
                <p class="p-t-sm m-t-xs"><a href="<?php echo base_url();?>forgot_password" class="has-text-dark">Forgot your password?</a></p>
            </div>
        </div>
    </div>
</div>
</section>
</div>
>>>>>>> origin/main
