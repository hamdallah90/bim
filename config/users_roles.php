<?php

$roles = [
    'CategoryController' => [
        'index' => [
            'admin',
            'customer'
        ],
        'show' => [
            'admin',
            'customer'
        ],
        'store' => [
            'admin'
        ],
        'update' => [
            'admin'
        ],
        'destroy' => [
            'admin'
        ]
    ],
    'TransactionController' => [
        'index' => [
            'admin',
            'customer'
        ],
        'show' => [
            'admin',
            'customer'
        ],
        'store' => [
            'admin'
        ],
        'update' => [
            'admin'
        ],
        'destroy' => [
            'admin'
        ],
        'addPayments' => [
            'admin'
        ],
        'getPayments' => [
            'admin',
            'customer',
        ],
        'removePayment' => [
            'admin'
        ]
    ],
    'ReportController' => [
        "index" => [
            'admin'
        ]
    ],
];

return [
    'roles' => $roles,
    'namespace' => 'App\Http\Controllers',
];
