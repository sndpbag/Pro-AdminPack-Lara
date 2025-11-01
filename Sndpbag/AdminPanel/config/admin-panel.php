<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Sidebar Menu Items
    |--------------------------------------------------------------------------
    |
    | Here you can define the menu items that will be displayed in the sidebar.
    | You can add your own items to this array to extend the sidebar.
    |
    */
    'sidebar' => [
        [
            'title' => 'Dashboard',
            'route' => 'dashboard', // রুট এর নাম
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">...</svg>', // SVG আইকন
            'active_on' => 'dashboard*' // কোন রুটে active থাকবে
        ],
        [
            'title' => 'Users',
            'route' => 'users.index',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">...</svg>',
            'active_on' => 'users.*'
        ],
        [
            'title' => 'Settings',
            'route' => 'settings.index',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">...</svg>',
            'active_on' => 'settings.*'
        ],
        [
            'title' => 'User Logs',
            'route' => 'user-logs.index',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24">...</svg>',
            'active_on' => 'user-logs.*'
        ],
		
		[
        'title' => 'Roles',
        'route' => 'dynamic-roles.roles.index', // রোল প্যাকেজের রাউট
        'icon' => '<svg class="w-6 h-6" ...>...</svg>', // আপনার পছন্দ মতো আইকন
        'active_on' => 'dynamic-roles.roles.*'
    ],
    [
        'title' => 'Permissions',
        'route' => 'dynamic-roles.permissions.index', // রোল প্যাকেজের রাউট
        'icon' => '<svg class="w-6 h-6" ...>...</svg>', // আপনার পছন্দ মতো আইকন
        'active_on' => 'dynamic-roles.permissions.*'
    ],
    [
        'title' => 'User Roles',
        'route' => 'dynamic-roles.users.index', // রোল প্যাকেজের রাউট
        'icon' => '<svg class="w-6 h-6" ...>...</svg>', // আপনার পছন্দ মতো আইকন
        'active_on' => 'dynamic-roles.users.*'
    ],
	
],
	
	
	    /*
    |--------------------------------------------------------------------------
    | Table Names
    |--------------------------------------------------------------------------
    */
    'table_names' => [
        'users' => 'users',
        'roles' => 'roles',
        'permissions' => 'permissions',
        'role_permission' => 'role_permission',
        'user_role' => 'user_role',
        'user_permission' => 'user_permission',
    ],

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    */
    'user_model' => \Sndpbag\AdminPanel\Models\User::class,

    /*
    |--------------------------------------------------------------------------
    | Route Prefix
    |--------------------------------------------------------------------------
    */
    'route_prefix' => 'admin/roles-permissions',

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    */
    'middleware' => ['web', 'auth','role:super-admin'],

    /*
    |--------------------------------------------------------------------------
    | Cache Duration
    |--------------------------------------------------------------------------
    */
    'cache_duration' => 60, // 60 minutes

    /*
    |--------------------------------------------------------------------------
    | Super Admin Role
    |--------------------------------------------------------------------------
    */
    'super_admin_role' => 'super-admin',

    /*
    |--------------------------------------------------------------------------
    | Exclude Routes from Permission Sync
    |--------------------------------------------------------------------------
    */
    'exclude_routes' => [
        'login',
        'logout',
        'register',
        'password.*',
        'sanctum.*',
        '_ignition.*',
    ],

    /*
    |--------------------------------------------------------------------------
    | Permission Groups
    |--------------------------------------------------------------------------
    */
    'permission_groups' => [
        'users' => 'User Management',
        'roles' => 'Role Management',
        'permissions' => 'Permission Management',
        'posts' => 'Post Management',
        'categories' => 'Category Management',
    ],
];