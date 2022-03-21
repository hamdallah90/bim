<?php

use Illuminate\Validation\Rule;

$rules = [
    'AuthController' => [
        'register' => [
            'name' => 'required|string',
            'email' => 'required|string|unique:users',
            'type' => 'required|in:admin,customer',
            'password' => 'required|string',
            'c_password' => 'required|same:password'
        ],
        'login' => [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]
    ],

    'CategoryController' => [
        'store' => [
            'name'      => 'required|min:3|max:255|string',
            'parent_id' => 'sometimes|nullable|numeric'
        ],
        'update' => [
            'name'      => 'required|min:3|max:255|string',
            'parent_id' => 'sometimes|nullable|numeric'
        ]
    ],

    'TransactionController' => [
        'store' => [
            'total'      => 'required|min:0|numeric',
            'due_on' => 'required|date|date_format:Y-m-d',
            'payer_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:categories,id|different:category_id',
            'vat' =>  'required|min:0|numeric',
            'is_vat_inclusive' => 'required|boolean'
        ],
        'update' => [
            'total'      => 'required|min:0|numeric',
            'due_on' => 'required|date|date_format:Y-m-d',
            'payer_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:categories,id|different:category_id',
            'vat' =>  'required|min:0|numeric',
            'is_vat_inclusive' => 'required|boolean'
        ],
        'addPayments' => function($request) {
            $transaction = \App\Models\Transaction::findOrFail($request->route()->id);
            $totalPaid = $transaction->records()->sum('amount');
            $max = $transaction->total - $totalPaid;

            return [
                "amount" => "required|max:$max|min:0.00001|numeric",
                "paid_on" => "required|date|date_format:Y-m-d",
                "details" => "nullable"
            ];
        }
    ],
    'ReportController' => [
        "index" => [
            "start_date" => "required|date|date_format:Y-m-d",
            "end_date" => "required|date|date_format:Y-m-d|after:start_date"
        ]
    ],
];

return [
    'rules' => $rules,
    'namespace' => 'App\Http\Controllers',
];
