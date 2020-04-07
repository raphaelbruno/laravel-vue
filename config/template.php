<?php 
    return [
        'color' => 'purple',
        'version' => '1.0.0',
        'menu' => [
            ['name' => 'admin.dashboard', 'icon' => 'tachometer-alt', 'action' => 'admin'],
            ['name' => 'Foos', 'icon' => 'copy', 'children' => [
                    ['name' => 'crud.list', 'icon' => 'list', 'action' => 'admin/foos'],
                    ['name' => 'crud.new', 'icon' => 'plus', 'action' => 'admin/foos/create']                
                ]
            ]
        ]
    ];