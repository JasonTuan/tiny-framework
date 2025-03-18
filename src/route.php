<?php

return [
    'homepage' => [
        'path' => '/',
        'controller' => \TinyFramework\Controller\HomeController::class,
        'action' => 'index',
        'method' => 'GET',
    ],
//    'calendar.' => [
//        'path' => '/calendar',
//        'controller' => \TinyFramework\Controller\CalendarController::class,
//        'groups' => [
//            'index' => [
//                'path' => '/',
//                'method' => 'GET',
//                'action' => 'index',
//            ],
//            'demo' => [
//                'path' => '/demo',
//                'method' => 'GET',
//                'action' => 'demo',
//            ],
//            'svg_grid_month' => [
//                'path' => '/svg/grid-month',
//                'method' => 'GET',
//                'action' => 'genSvgMonth',
//            ],
//        ],
//    ],
//    'demo1' => [
//        'path' => '/demo1/{category_id}/{?slug}',
//        'controller' => \TinyFramework\Controller\DemoController::class,
//        'action' => 'index',
//        'method' => 'GET',
//    ],
    'api.' => [
        'path' => '/api',
        'middlewares' => [
            \TinyFramework\Middlewares\AllowOriginMiddleware::class,
        ],
        'groups' => [
            // Url: http://localhost:8000/api/app/token
            // Example curl call: curl -X GET http://localhost:8000/api/app/token
            'app.token' => [
                'controller' => \TinyFramework\Controller\API\AppController::class,
                'path' => '/app/token',
                'method' => 'GET',
                'action' => 'getToken',
            ],
            // Url: http://localhost:8000/api/demo/hello/Jason
            // Example curl call: curl -X GET http://localhost:8000/api/demo/hello/Jason
            'demo.' => [
                'path' => '/demo',
                'controller' => \TinyFramework\Controller\API\DemoController::class,
                'groups' => [
                    'sayHello' => [
                        'path' => '/hello/{?name}',
                        'method' => 'GET',
                        'action' => 'sayHello',
                    ],
                    'demo1' => [
                        'path' => '/demo1',
                        'method' => 'GET',
                        'action' => 'demo1',
                    ],
                ],
            ],
            'user.' => [
                'path' => '/users',
                'controller' => \TinyFramework\Controller\API\UserController::class,
                'groups' => [
                    'list' => [
                        'path' => '/',
                        'method' => 'GET',
                        'action' => 'index',
                    ],
                    'store' => [
                        'path' => '/',
                        'method' => 'POST',
                        'action' => 'store',
                    ],
                    'show' => [
                        'path' => '/{id}',
                        'method' => 'GET',
                        'action' => 'show',
                    ],
                    'update' => [
                        'path' => '/{id}',
                        'method' => 'PUT',
                        'action' => 'update',
                    ],
                    'delete' => [
                        'path' => '/{id}',
                        'method' => 'DELETE',
                        'action' => 'delete',
                    ],
                ],
            ],
//            'calendar.month' => [
//                'controller' => \TinyFramework\Controller\API\DemoController::class,
//                'middlewares' => [
//                    //\TinyFramework\Middlewares\CheckCsrfTokenMiddleware::class,
//                ],
//                'path' => '/calendar/month/{?month}',
//                'method' => 'GET',
//                'action' => 'getMonth',
//            ],
        ],
    ],
];
