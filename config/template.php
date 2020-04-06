<?php 
    return [
        'color' => 'purple',
        'version' => '1.0.0',
        'menu' => [
            ['name' => 'Dashboard', 'icon' => 'tachometer-alt', 'action' => 'admin'],
            ['name' => 'Crud', 'icon' => 'copy', 'children' => [
                    ['name' => 'List', 'icon' => 'list'],
                    ['name' => 'Add', 'icon' => 'plus']                
                ]
            ]
        ]
    ];