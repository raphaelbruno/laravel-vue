<?php 
    return [
        'color' => 'purple',
        'version' => '1.0.0',
        'menu' => [
            ['name' => 'admin.dashboard', 'icon' => 'tachometer-alt', 'action' => 'admin::dashboard'],
            ['name' => 'Foos', 'icon' => 'copy', 'resource' => 'foos', 'permission' => 'foos-view', 'children' => [
                    ['name' => 'crud.list', 'icon' => 'list', 'action' => 'admin::foos.index', 'permission' => 'foos-view'],
                    ['name' => 'crud.new', 'icon' => 'plus', 'action' => 'admin::foos.create', 'permission' => 'foos-create']                
                ]
            ]
        ],
        'dateformat' => 'dd/mm/yyyy',
    ];