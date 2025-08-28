<header class="app-topbar">
    <!-- Toggle Button -->
    <button class="topbar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Breadcrumb -->
    <nav class="breadcrumb">
        <a href="<?php echo base_url(); ?>" class="breadcrumb-item">
            <i class="fas fa-home"></i> Keydera
        </a>
        <span class="breadcrumb-separator">›</span>
        <span class="breadcrumb-current">
            <?php echo htmlspecialchars($title); ?>
        </span>
    </nav>

    <!-- Search -->
    <div class="topbar-search">
        <i class="fas fa-search search-icon"></i>
        <input type="text" class="search-input" placeholder="Search...">
    </div>

    <!-- Actions -->
    <div class="topbar-actions">
        <button class="action-btn" title="Notifications">
            <i class="fas fa-bell"></i>
            <span class="notification-badge"></span>
        </button>
        <a class="action-btn" title="Quick Settings" href="<?php echo base_url('general_settings'); ?>">
            <i class="fas fa-cog"></i>
        </a>
        <?php 
        $this->config->load('navigation', TRUE);
        $navigation = $this->config->item('navigation', 'navigation');
        if(isset($navigation['help'])): ?>
        <div class="topbar-dropdown">
            <button class="action-btn" id="helpMenuButton" aria-haspopup="true" aria-expanded="false" title="Help">
                <i class="fas fa-question-circle"></i>
            </button>
            <div class="topbar-menu" role="menu" aria-labelledby="helpMenuButton">
                <?php foreach ($navigation['help']['items'] as $item): ?>
                    <a class="topbar-menu-item" role="menuitem" href="<?php echo ($item['path'] === '#') ? '#' : base_url($item['path']); ?>">
                        <i class="<?php echo htmlspecialchars($item['icon']); ?>"></i>
                        <span><?php echo htmlspecialchars($item['label']); ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        <a class="action-btn" title="Account" href="<?php echo base_url('account_settings'); ?>">
            <i class="fas fa-user-circle"></i>
        </a>
        <a class="action-btn" title="Logout" href="<?php echo base_url('users/logout'); ?>">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
</header>
