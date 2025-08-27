<nav class="navbar top-bar">
    <div class="navbar-brand">
        <a role="button" class="navbar-burger sidebar-toggle-mobile" aria-label="menu" aria-expanded="false" aria-controls="sidebar-body">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </a>
        <div class="navbar-item">
            <nav class="breadcrumb" aria-label="breadcrumbs">
                <ul>
                    <li><a href="<?php echo base_url(); ?>">Keydera</a></li>
                    <?php
                    $segments = explode(' / ', $title);
                    foreach ($segments as $index => $segment) {
                        if ($index == count($segments) - 1) {
                            echo '<li class="is-active"><a href="#" aria-current="page">' . $segment . '</a></li>';
                        } else {
                            echo '<li><a href="#">' . $segment . '</a></li>';
                        }
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </div>
    <div class="navbar-menu">
        <div class="navbar-end">
            <div class="navbar-item">
                <div class="field has-addons">
                    <div class="control">
                        <input class="input" type="text" placeholder="Search...">
                    </div>
                    <div class="control">
                        <a class="button is-info">
                            <i class="fas fa-search"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">
                   <i class="fas fa-user-circle logged-in-icon"></i>
                   <span class="logged-in-text">&nbsp;<?php echo ucfirst($this->session->userdata('fullname')); ?></span>
                </a>
                <div class="navbar-dropdown is-right">
                  <a class="navbar-item" href="<?php echo base_url(); ?>account_settings" title="Manage your account settings">
                      <span>
                          <i class="fas fa-user-cog p-r-xs"></i> Account Settings
                      </span>
                  </a>
                  <hr class="navbar-divider">
                  <a class="navbar-item" href="<?php echo base_url(); ?>logout" title="Log out of Keydera">
                    <span>
                      <i class="fas fa-sign-out-alt"></i> Logout
                    </span>
                  </a>
                </div>
            </div>
        </div>
    </div>
</nav>
