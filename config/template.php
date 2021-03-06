<?php
    return [
        'logo-icon' => 'fab fa-laravel',
        'dark-mode' => false,
        'theme' => 'teal', // ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark', 'blue', 'indigo', 'purple', 'pink', 'red', 'orange', 'yellow', 'green', 'teal', 'cyan']
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
            ['name' => 'admin.access-control', 'icon' => 'users', 'resource' => ['users', 'roles', 'permissions'], 'permission' => ['users-view', 'roles-view'],
                'children' => [
                    ['name' => 'admin.users', 'resource' => 'users', 'icon' => 'user', 'action' => 'admin:users.index', 'permission' => 'users-view'],
                    ['name' => 'admin.roles', 'resource' => 'roles', 'icon' => 'id-card', 'action' => 'admin:roles.index', 'permission' => 'roles-view'],
                    ['name' => 'admin.permissions', 'resource' => 'permissions', 'icon' => 'clipboard-list', 'action' => 'admin:permissions.index', 'permission' => 'roles-view'],
                ]
            ],
            ['name' => 'Foos', 'resource' => 'foos', 'icon' => 'copy', 'action' => 'admin:foos.index', 'permission' => 'foos-view'],
        ],
        'dateformat' => 'dd/mm/yyyy',
    ];
