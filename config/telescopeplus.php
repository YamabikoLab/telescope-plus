<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Telescope Query Watcher config
    |--------------------------------------------------------------------------
    | Limit parameter can be used to limit the number of stack frames returned. 
    | You can specify the target and color using the highlight parameters.
    */
    'query'=>[
        'limit' => 30,
        'highlight' => [
            [
                'target' => 'App\\',
                'color' => 'red'
            ],
            [
                'target' => 'Illuminate\\Database\\',
                'color' => 'blue'
            ],
            [
                'target' => '\\Middleware\\',
                'color' => 'green'
            ],
        ],
    ],
];