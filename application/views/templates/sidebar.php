<?php
$this->config->load('navigation', TRUE);
$navigation = $this->config->item('navigation', 'navigation');
$current_class = $this->router->fetch_class();
?>
<aside class="menu is-sidebar" role="navigation" aria-label="main navigation">
    <div class="sidebar-header">
        <a href="<?php echo base_url(); ?>">
            <img src="<?php echo base_url(); ?>assets/images/logo-dark.svg" alt="Keydera" class="sidebar-logo">
        </a>
    </div>

    <div class="sidebar-body" id="sidebar-body">
        <?php foreach ($navigation as $section_key => $section): ?>
            <?php if ($section_key !== 'footer'): ?>
                <p class="menu-label"><?php echo $section['title']; ?></p>
                <ul class="menu-list">
                    <?php foreach ($section['items'] as $item): ?>
                        <li>
                            <a href="<?php echo ($item['path'] === '#') ? '#' : base_url($item['path']); ?>"
                               id="<?php echo $item['id']; ?>"
                               class="<?php echo ($current_class == $item['id']) ? 'is-active' : ''; ?>">
                                <span class="icon"><i class="fas <?php echo $item['icon']; ?>" aria-hidden="true"></i></span>
                                <span class="menu-text"><?php echo $item['label']; ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <div class="sidebar-footer">
        <ul class="menu-list">
            <?php foreach ($navigation['footer']['items'] as $item): ?>
                <li>
                    <a href="<?php echo ($item['path'] === '#') ? '#' : base_url($item['path']); ?>"
                       id="<?php echo $item['id']; ?>"
                       class="<?php echo ($current_class == $item['id']) ? 'is-active' : ''; ?>">
                        <span class="icon"><i class="fas <?php echo $item['icon']; ?>" aria-hidden="true"></i></span>
                        <span class="menu-text"><?php echo $item['label']; ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="sidebar-toggle-desktop-container">
            <a class="sidebar-toggle-desktop" aria-controls="sidebar-body" aria-expanded="true">
                <span class="icon"><i class="fas fa-angle-left" aria-hidden="true"></i></span>
            </a>
        </div>
    </div>
</aside>
