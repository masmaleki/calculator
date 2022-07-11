<?php


return [


    'currencies' => [

        'EUR' => 1,
        'USD' => 1.1497,
        'JPY' => 129.53

    ],

    'commission_fees' => [

        'private' => [
            'deposit'=> 0.0003,
            'withdraw'=> 0.003
        ],
        'business' => [
            'deposit'=> 0.0003,
            'withdraw'=> 0.005
        ]

    ],

    'rate_url' => env('CALC_RATE_URL', 'https://developers.paysera.com/tasks/api/currency-exchange-rates'),

    'limit' => env('CALC_limit', 1000),


];
