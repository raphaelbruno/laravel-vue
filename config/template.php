<?php 
    return [
        'theme' => 'purple',
        'version' => '1.0.0',
        'menu' => [
            /**
             * name(string): Name of the item (you can use tralation string).
             * icon(string): FontAwesome icons, only sufix like "fas fa-{use-only-this-sufix}"
             * action(string): Route name (This will set menu item as active if the current page has this route name)
             * resource(string|array): list of resource names (This will set menu item as active if the current page has a resource of the list)
             * permission(string|array): Hide menu item if the user has not this permission
             */
            ['name' => 'admin.dashboard', 'icon' => 'tachometer-alt', 'action' => 'admin:dashboard'],
            ['name' => 'admin.access-control', 'icon' => 'users', 'resource' => ['users', 'roles', 'permissions'], 'permission' => ['users-view', 'roles-view'], 'children' => [
                    ['name' => 'admin.user-list', 'icon' => 'user', 'action' => 'admin:users.index', 'permission' => 'users-view'],
                    ['name' => 'admin.user-new', 'icon' => 'user', 'action' => 'admin:users.create', 'permission' => 'users-create'],
                    ['name' => 'admin.role-list', 'icon' => 'id-card', 'action' => 'admin:roles.index', 'permission' => 'roles-view'],
                    ['name' => 'admin.role-new', 'icon' => 'id-card', 'action' => 'admin:roles.create', 'permission' => 'roles-create'],
                    ['name' => 'admin.permission-list', 'icon' => 'clipboard-list', 'action' => 'admin:permissions.index', 'permission' => 'roles-view'],
                    ['name' => 'admin.permission-new', 'icon' => 'clipboard-list', 'action' => 'admin:permissions.create', 'permission' => 'roles-create']
                ]
            ],
            ['name' => 'Foos', 'icon' => 'copy', 'resource' => 'foos', 'permission' => 'foos-view', 'children' => [
                    ['name' => 'crud.list', 'icon' => 'list', 'action' => 'admin:foos.index', 'permission' => 'foos-view'],
                    ['name' => 'crud.new', 'icon' => 'plus', 'action' => 'admin:foos.create', 'permission' => 'foos-create']
                ]
            ],
        ],
        'dateformat' => 'dd/mm/yyyy',
    ];