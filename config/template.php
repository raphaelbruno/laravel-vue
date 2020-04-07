<?php 
    return [
        'color' => 'purple',
        'version' => '1.0.0',
        'menu' => [
            ['name' => 'Dashboard', 'icon' => 'tachometer-alt', 'action' => 'admin'],
            ['name' => 'Foos', 'icon' => 'copy', 'children' => [
                    ['name' => 'List', 'icon' => 'list', 'action' => 'admin/foos'],
                    ['name' => 'New', 'icon' => 'plus', 'action' => 'admin/foos/create']                
                ]
            ]
        ]
    ];