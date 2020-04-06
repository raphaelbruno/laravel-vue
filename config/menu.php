<?php 
    return [
        ['name' => 'Dashboard', 'icon' => 'tachometer-alt', 'action' => 'admin'],
        ['name' => 'Crud', 'icon' => 'copy', 'children' => [
                ['name' => 'List', 'icon' => 'list'],
                ['name' => 'Add', 'icon' => 'plus']                
            ]
        ]
    ];