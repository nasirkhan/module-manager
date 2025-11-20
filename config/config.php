<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Module Config
    |--------------------------------------------------------------------------
    |
    */

    'namespace' => 'Modules',

    'stubs' => [
        // 'path' => base_path('stubs/laravel-starter-stubs'),

        'path' => base_path('vendor/nasirkhan/module-manager/src/stubs'),
    ],

    'module' => [
        'files' => [
            'composer'                      => ['composer.stub.php', 'composer.json'],
            'json'                          => ['module.stub.php', 'module.json'],
            'config'                        => ['Config/config.stub.php', 'Config/config.php'],
            'database'                      => ['database/migrations/stubMigration.stub.php', 'database/migrations/stubMigration.php', 'rename'],
            'factories'                     => ['database/factories/stubFactory.stub.php', 'database/factories/stubFactory.php', 'rename'],
            'seeders'                       => ['database/seeders/stubSeeders.stub.php', 'database/seeders/stubSeeders.php', 'rename'],
            'command'                       => ['Console/Commands/StubCommand.stub.php', 'Console/Commands/StubCommand.php', 'rename'],
            'lang'                          => ['lang/en/text.stub.php', 'lang/en/text.php'],
            'models'                        => ['Models/stubModel.stub.php', 'Models/stubModel.php'],
            'providersRoute'                => ['Providers/RouteServiceProvider.stub.php', 'Providers/RouteServiceProvider.php'],
            'providersEvent'                => ['Providers/EventServiceProvider.stub.php', 'Providers/EventServiceProvider.php'],
            'providers'                     => ['Providers/stubServiceProvider.stub.php', 'Providers/stubServiceProvider.php'],
            'route_web'                     => ['routes/web.stub.php', 'routes/web.php'],

            'controller_backend'            => ['Http/Controllers/Backend/stubBackendController.stub.php', 'Http/Controllers/Backend/stubBackendController.php'],
            'controller_frontend'           => ['Http/Controllers/Frontend/stubFrontendController.stub.php', 'Http/Controllers/Frontend/stubFrontendController.php'],
            'views_backend_index'           => ['Resources/views/backend/stubViews/index.blade.stub.php', 'Resources/views/backend/stubViews/index.blade.php'],
            'views_backend_index_datatable' => ['Resources/views/backend/stubViews/index_datatable.blade.stub.php', 'Resources/views/backend/stubViews/index_datatable.blade.php'],
            'views_backend_create'          => ['Resources/views/backend/stubViews/create.blade.stub.php', 'Resources/views/backend/stubViews/create.blade.php'],
            'views_backend_form'            => ['Resources/views/backend/stubViews/form.blade.stub.php', 'Resources/views/backend/stubViews/form.blade.php'],
            'views_backend_show'            => ['Resources/views/backend/stubViews/show.blade.stub.php', 'Resources/views/backend/stubViews/show.blade.php'],
            'views_backend_edit'            => ['Resources/views/backend/stubViews/edit.blade.stub.php', 'Resources/views/backend/stubViews/edit.blade.php'],
            'views_backend_trash'           => ['Resources/views/backend/stubViews/trash.blade.stub.php', 'Resources/views/backend/stubViews/trash.blade.php'],
            'views_frontend_index'          => ['Resources/views/frontend/stubViews/index.blade.stub.php', 'Resources/views/frontend/stubViews/index.blade.php'],
            'views_frontend_show'           => ['Resources/views/frontend/stubViews/show.blade.stub.php', 'Resources/views/frontend/stubViews/show.blade.php'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Composer
    |--------------------------------------------------------------------------
    |
    | Config for the composer.json file
    |
    */

    'composer' => [
        'vendor' => 'nasirkhan',
        'author' => [
            'name'  => 'Nasir Khan',
            'email' => 'nasir8891@gmail.com',
        ],
    ],

    'files' => [
        'module-list' => base_path('modules_statuses.json'),
    ],
];
