<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['navigation'] = [
    'main' => [
        'title' => 'Main',
        'items' => [
            ['id' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'fa-home', 'path' => ''],
            ['id' => 'products', 'label' => 'Products', 'icon' => 'fa-boxes', 'path' => 'products'],
            ['id' => 'orders', 'label' => 'Orders', 'icon' => 'fa-shopping-cart', 'path' => 'orders'],
            ['id' => 'customers', 'label' => 'Customers', 'icon' => 'fa-users', 'path' => 'customers'],
            ['id' => 'analytics', 'label' => 'Analytics', 'icon' => 'fa-chart-bar', 'path' => 'analytics'],
        ]
    ],
    'settings' => [
        'title' => 'Settings',
        'items' => [
            ['id' => 'payments', 'label' => 'Payments', 'icon' => 'fa-credit-card', 'path' => 'settings/payments'],
            ['id' => 'appearance', 'label' => 'Appearance', 'icon' => 'fa-palette', 'path' => 'settings/appearance'],
            ['id' => 'integrations', 'label' => 'Integrations', 'icon' => 'fa-plug', 'path' => 'settings/integrations'],
            ['id' => 'users_roles', 'label' => 'Users & Roles', 'icon' => 'fa-shield-alt', 'path' => 'settings/users-roles'],
        ]
    ],
    'footer' => [
        'title' => '',
        'items' => [
            ['id' => 'theme', 'label' => 'Theme', 'icon' => 'fa-sun', 'path' => '#'],
            ['id' => 'account', 'label' => 'Account', 'icon' => 'fa-user', 'path' => 'settings/account'],
        ]
    ]
];
