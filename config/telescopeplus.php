<?php

return [
    'query'=>[
        'limit' => 30,
        'highlight' => [
            [
                'target' => 'App\\',
                'search' => 'starts',
                'color' => 'red'
            ],
            [
                'target' => 'Illuminate\\Database\\',
                'search' => 'starts',
                'color' => 'blue'
            ],
            [
                'target' => '\\Middleware\\',
                'search' => 'contains',
                'color' => 'green'
            ],
        ],
    ],
];