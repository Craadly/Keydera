<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Professional Navigation Configuration
 * Organized by user workflow and importance
 */
$config['navigation'] = [
    // Dashboard - Single Item
    'dashboard' => [
        'title' => 'Dashboard',
        'single_items' => [
            ['id' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'fas fa-tachometer-alt', 'path' => 'dashboard'],
        ]
    ],

    // Products Section
    'products' => [
        'title' => 'Products',
        'icon' => 'fas fa-cube',
        'items' => [
            ['id' => 'products_add', 'label' => 'Add Product', 'icon' => 'fas fa-plus', 'path' => 'products/add'],
            ['id' => 'products', 'label' => 'Manage Products', 'icon' => 'fas fa-list', 'path' => 'products'],
        ]
    ],

    // Licenses Section
    'licenses' => [
        'title' => 'Licenses',
        'icon' => 'fas fa-key',
        'items' => [
            ['id' => 'licenses_create', 'label' => 'Create License', 'icon' => 'fas fa-plus-circle', 'path' => 'licenses/create'],
            ['id' => 'licenses', 'label' => 'Manage Licenses', 'icon' => 'fas fa-key', 'path' => 'licenses'],
        ]
    ],

    // Operations - Single level items
    'operations' => [
        'title' => 'Operations',
        'single_items' => [
            ['id' => 'activations', 'label' => 'Activations', 'icon' => 'fas fa-power-off', 'path' => 'activations'],
            ['id' => 'downloads', 'label' => 'Downloads', 'icon' => 'fas fa-download', 'path' => 'update_downloads'],
        ]
    ],

    // Generate Helper File Section
    'generate_helpers' => [
        'title' => 'Generate Helper File',
        'icon' => 'fas fa-file-code',
        'items' => [
            ['id' => 'generate_external', 'label' => 'External Helper File', 'icon' => 'fas fa-code', 'path' => 'generate_external'],
            ['id' => 'generate_internal', 'label' => 'Internal Helper File', 'icon' => 'fas fa-terminal', 'path' => 'generate_internal'],
        ]
    ],

    // Tools Section
    'tools' => [
        'title' => 'Tools',
        'icon' => 'fas fa-tools',
        'items' => [
            ['id' => 'php_obfuscator', 'label' => 'PHP Obfuscator', 'icon' => 'fas fa-shield-alt', 'path' => 'php_obfuscator'],
            ['id' => 'run_cron', 'label' => 'Run Manual Cron', 'icon' => 'fas fa-cogs', 'path' => 'run_cron'],
        ]
    ],

    // Settings Section
    'settings' => [
        'title' => 'Settings',
        'icon' => 'fas fa-cog',
        'items' => [
            ['id' => 'general_settings', 'label' => 'General Settings', 'icon' => 'fas fa-sliders-h', 'path' => 'general_settings'],
            ['id' => 'api_settings', 'label' => 'API Settings', 'icon' => 'fas fa-plug', 'path' => 'api_settings'],
            ['id' => 'email_settings', 'label' => 'Email Settings', 'icon' => 'fas fa-envelope', 'path' => 'email_settings'],
            ['id' => 'account_settings', 'label' => 'Account Settings', 'icon' => 'fas fa-user-cog', 'path' => 'account_settings'],
        ]
    ],

    // Help Section
    'help' => [
        'title' => 'Help',
        'icon' => 'fas fa-question-circle',
        'items' => [
            ['id' => 'api_documentation', 'label' => 'API Documentation', 'icon' => 'fas fa-book', 'path' => 'api_documentation'],
            ['id' => 'contact_support', 'label' => 'Contact Support', 'icon' => 'fas fa-life-ring', 'path' => 'contact_support'],
            ['id' => 'check_updates', 'label' => 'Check For Updates', 'icon' => 'fas fa-sync-alt', 'path' => 'updates'],
        ]
    ],

    // Footer items (if needed)
    'footer' => [
        'title' => '',
        'items' => []
    ]
];
