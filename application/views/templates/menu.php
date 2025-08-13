<?php $coreApp = new L1c3n5380x4P1();
$system_info = get_system_info($coreApp->check_local_license_exist());
?>
<nav id="navbar" class="navbar <?php echo (strtolower(LICENSEBOX_THEME)=="classic")?"is-link has-border2":"is-primary"; ?> is-spaced">
  <div class="container is-fluid menu-container">
    <div class="navbar-brand">
      <a class="navbar-item" href="<?php echo base_url(); ?>" style="max-width: 200px;">
        <img src="<?php echo base_url(); ?>assets/images/logo-white.svg" alt="LicenseBox" class="noselect">
      </a>
      <div id="navbarBurger" class="navbar-burger burger" data-target="navBarLB">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
    <div id="navBarLB" class="navbar-menu">
      <div class="navbar-start">
       <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link" href="#">
          <i class="fas fa-database p-r-xs"></i> Products
        </a>
        <div id="moreDropdown" class="navbar-dropdown">       
          <a class="navbar-item" href="<?php echo base_url(); ?>products/add" title="Add a new product">
            <span>
              <strong><i class="fas fa-plus-circle p-r-xs"></i> Add product</strong>               
            </span>
          </a>          
          <a class="navbar-item" href="<?php echo base_url(); ?>products" title="Manage products and their versions">
            <span>
              <strong><i class="fas fa-database p-r-xs"></i> Manage products</strong>
            </span>
          </a>                    
        </div>
      </div>
      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link" href="#">
          <i class="fas fa-key p-r-xs"></i> Licenses
        </a>
        <div id="moreDropdown" class="navbar-dropdown">       
          <a class="navbar-item" href="<?php echo base_url(); ?>licenses/create" title="Create a new license">
            <span>
              <strong><i class="fas fa-plus-circle p-r-xs"></i> Create license</strong>               
            </span>
          </a>          
          <a class="navbar-item" href="<?php echo base_url(); ?>licenses" title="Manage licenses">
            <span>
              <strong><i class="fas fa-key p-r-xs"></i> Manage licenses</strong>
            </span>
          </a>                    
        </div>
      </div>
      <a class="navbar-item" href="<?php echo base_url(); ?>activations" title="View all product activations">
        <i class="fas fa-hdd p-r-xs"></i> Activations
      </a>
      <a class="navbar-item" href="<?php echo base_url(); ?>update_downloads" title="View all update downloads">
        <i class="fas fa-download p-r-xs"></i> Downloads
      </a>
      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link" href="#">
          <i class="fas fa-tools p-r-xs"></i> Tools
        </a>
        <div id="moreDropdown" class="navbar-dropdown">  
          <div class="nested dropdown">
            <a class="navbar-item">
              <span class="icon-text">
                <strong><i class="fas fa-code p-r-xs"></i> Generate Helper File</strong>
                <span class="icon">
                  <i class="fas fa-chevron-right p-l-md"></i>
                </span>
              </span>
            </a>
            <div class="dropdown-menu" id="dropdown-menu" role="menu">
              <div class="dropdown-content">
                <a href="<?php echo base_url(); ?>generate_external" class="dropdown-item" title="External API helper file for doing license checks, update checks etc.">
                  <span>
                    <strong><i class="fas fa-file-code p-r-xs"></i> External Helper File</strong>               
                  </span>
                </a>
                <a href="<?php echo base_url(); ?>generate_internal" class="dropdown-item" title="Internal API helper file for adding licenses, products etc.">
                  <span>
                    <strong><i class="fas fa-user-secret p-r-xs"></i> Internal Helper File</strong>               
                  </span>
                </a>
              </div>
            </div>
          </div>
          <a class="navbar-item" href="<?php echo base_url(); ?>php_obfuscator" title="Obfuscate your PHP codes.">
            <span>
              <strong><i class="fas fa-shield-alt p-r-xs"></i> PHP Obfuscator</strong>               
            </span>
          </a>
          <a class="navbar-item" href="<?php echo base_url(); ?>run_cron" title="Run a one-time manual Cron.">
            <span>
              <strong><i class="fas fa-sync p-r-xs"></i> Run Manual Cron</strong>
            </span>
          </a>          
        </div>
      </div>
      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link" href="#">
          <i class="fas fa-cogs p-r-xs"></i> Settings
        </a>
        <div id="moreDropdown" class="navbar-dropdown">
          <a class="navbar-item" href="<?php echo base_url(); ?>general_settings" title="Manage general settings">
            <span>
              <strong><i class="fas fa-cog p-r-xs"></i> General Settings</strong>               
            </span>
          </a> 
          <a class="navbar-item" href="<?php echo base_url(); ?>api_settings" title="Manage API settings">
            <span>
              <strong><i class="fas fa-server p-r-xs"></i> API Settings</strong>               
            </span>
          </a>
          <a class="navbar-item" href="<?php echo base_url(); ?>email_settings" title="Manage email settings">
            <span>
              <strong><i class="fas fa-mail-bulk p-r-xs"></i> Email Settings</strong>               
            </span>
          </a>
          <hr class="navbar-divider">          
          <a class="navbar-item" href="<?php echo base_url(); ?>account_settings" title="Manage your account settings">
            <span>
              <strong><i class="fas fa-user-cog p-r-xs"></i> Account Settings</strong>               
            </span>
          </a>     
        </div>
      </div>
      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link" href="#">
          <i class="fas fa-info-circle p-r-xs"></i> Help
        </a>
        <div id="moreDropdown" class="navbar-dropdown">
          <a class="navbar-item" href="https://licensebox.app/documentation/swagger/" target="_blank" title="Check LicenseBox API documentation">
            <span>
              <strong><i class="fa-solid fa-arrow-up-right-from-square p-r-xs"></i> API Documentation</strong>
            </span>
          </a>
          <hr class="navbar-divider">
          <a class="navbar-item" href="mailto:support@licensebox.app?subject=Need help with LicenseBox <?php echo $coreApp->get_current_version();?>&body=LicenseBox System Information: %0D%0A<?php echo urlencode($system_info); ?>%0D%0A%0D%0A(Note: Please explain the issue you are having along with the screenshot below and don't forget to include your purchase code.)%0D%0A" title="Contact LicenseBox Support">
            <span>
              <strong><i class="fa-solid fa-headset p-r-xs"></i> Contact Support</strong>
            </span>
          </a>
          <hr class="navbar-divider">
          <a class="navbar-item" href="<?php echo base_url(); ?>updates" title="Check available updates">
            <span>
              <strong><i class="fas fa-cloud-download-alt p-r-xs"></i> Check For Updates</strong>
            </span>
          </a>
          <hr class="navbar-divider">
          <p class="navbar-item">
            <span>
              <strong>About</strong>
              <br>
              LicenseBox is a full-fledged <br> licenser and updates solution
            </span>
          </p>
          <hr class="navbar-divider">
          <p class="navbar-item">
            <span>
              <strong>Current version: </strong><?php echo $coreApp->get_current_version(); ?>
            </span>
          </p>
        </div>
      </div>
    </div>
    <div class="navbar-end">
      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link" href="#">
           <i class="fas fa-user-circle logged-in-icon"></i>
           <span class="logged-in-text">&nbsp;<?php echo ucfirst($this->session->userdata('fullname')); ?></span>
        </a>
        <div id="moreDropdown" class="navbar-dropdown">    
          <a class="navbar-item " href="<?php echo base_url(); ?>logout" title="Log out of LicenseBox">
            <span>
              <strong><i class="fas fa-sign-out-alt"></i> Logout</strong>
            </span>
          </a>        
        </div>
      </div>
    </div>
  </div>
</div>
</nav>
