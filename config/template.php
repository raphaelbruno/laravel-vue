<?php 
    return [
        'color' => 'purple',
        'version' => '1.0.0',
        'menu' => [
            ['name' => 'admin.dashboard', 'icon' => 'tachometer-alt', 'action' => 'admin::dashboard'],
            ['name' => 'Foos', 'icon' => 'copy', 'resource' => 'foos', 'children' => [
                    ['name' => 'crud.list', 'icon' => 'list', 'action' => 'admin::foos.index'],
                    ['name' => 'crud.new', 'icon' => 'plus', 'action' => 'admin::foos.create']                
                ]
            ]
        ],
        'dateformat' => 'dd/mm/yyyy',
    ];