<?php
$this->config->load('navigation', TRUE);
$navigation = $this->config->item('navigation', 'navigation');
$current_class = $this->router->fetch_class();
$current_method = $this->router->fetch_method();
$current_uri = uri_string(); // Get current URI path
?>
<aside class="app-sidebar" id="sidebar">
    <!-- Logo -->
    <div class="sidebar-logo">
        <div class="logo-icon">K</div>
        <span class="logo-text">Keydera</span>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <?php foreach ($navigation as $section_key => $section): ?>
            <?php if ($section_key !== 'footer' && $section_key !== 'help'): ?>
                <div class="nav-section <?php echo isset($section['single_items']) ? 'single-items-section' : 'submenu-section'; ?>">
                    <?php if (isset($section['single_items'])): ?>
                        <!-- Single items without submenu (like Activations, Downloads) -->
                        <?php foreach ($section['single_items'] as $item): ?>
                            <?php
                                // Determine active state using URI path matching
                                $is_single_active = false;
                                if ($item['path'] !== '#') {
                                    $item_path = trim($item['path'], '/');
                                    $current_path = trim($current_uri, '/');
                                    $is_single_active = ($current_path === $item_path) || ($current_class === $item['id']);
                                }
                            ?>
                            <div class="nav-item">
                                <a href="<?php echo ($item['path'] === '#') ? '#' : base_url($item['path']); ?>" 
                                   class="nav-link <?php echo $is_single_active ? 'active' : ''; ?>"
                                   title="<?php echo htmlspecialchars($item['label']); ?>"
                                   <?php echo $is_single_active ? "aria-current='page'" : ""; ?>>
                                    <i class="<?php echo htmlspecialchars($item['icon']); ?>"></i>
                                    <span class="nav-text"><?php echo htmlspecialchars($item['label']); ?></span>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Submenu sections -->
                        <div class="nav-submenu">
                            <button class="nav-submenu-toggle" data-section="<?php echo $section_key; ?>" onclick="toggleSubmenu(this, '<?php echo $section_key; ?>')" 
                                    aria-expanded="false" aria-controls="submenu-<?php echo $section_key; ?>">
                                <div class="submenu-header">
                                    <span class="submenu-title">
                                        <i class="<?php echo htmlspecialchars($section['icon']); ?>"></i>
                                        <span class="nav-text"><?php echo htmlspecialchars($section['title']); ?></span>
                                    </span>
                                    <i class="submenu-arrow fas fa-chevron-down"></i>
                                </div>
                            </button>
                            <div class="nav-submenu-content" id="submenu-<?php echo $section_key; ?>">
                                <?php foreach ($section['items'] as $item): ?>
                                    <?php
                                        // Determine active state using URI path matching for better accuracy
                                        $is_active = false;
                                        
                                        // Check if current URI matches the item's path
                                        if ($item['path'] !== '#') {
                                            $item_path = trim($item['path'], '/');
                                            $current_path = trim($current_uri, '/');
                                            
                                            // Exact match
                                            $is_active = ($current_path === $item_path);
                                            
                                            // Handle index methods (if current path is just the controller)
                                            if (!$is_active && strpos($item_path, '/') === false) {
                                                $is_active = ($current_class === $item_path && ($current_method === 'index' || $current_method === ''));
                                            }
                                        }
                                        
                                        // Fallback to old logic for specific cases
                                        if (!$is_active) {
                                            if ($item['id'] === 'products_add') {
                                                $is_active = ($current_class === 'products' && $current_method === 'add');
                                            } elseif ($item['id'] === 'products') {
                                                $is_active = ($current_class === 'products' && $current_method !== 'add');
                                            } elseif ($item['id'] === 'licenses_create') {
                                                $is_active = ($current_class === 'licenses' && $current_method === 'create');
                                            } elseif ($item['id'] === 'licenses') {
                                                $is_active = ($current_class === 'licenses' && $current_method !== 'create');
                                            } else {
                                                $is_active = ($current_class === $item['id']);
                                            }
                                        }
                                    ?>
                                    <div class="nav-item submenu-item">
                                        <a href="<?php echo ($item['path'] === '#') ? '#' : base_url($item['path']); ?>" 
                                           class="nav-link submenu-link <?php echo $is_active ? 'active' : ''; ?>"
                                           title="<?php echo htmlspecialchars($item['label']); ?>"
                                           <?php echo $is_active ? "aria-current='page'" : ""; ?>>
                                            <i class="<?php echo htmlspecialchars($item['icon']); ?>"></i>
                                            <span class="nav-text"><?php echo htmlspecialchars($item['label']); ?></span>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </nav>

</aside>

<!-- Mobile overlay for sidebar -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>
