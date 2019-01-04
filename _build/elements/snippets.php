<?php

return [
    'Jkwishlist' => [
        'file' => 'jkwishlist',
        'description' => 'Jkwishlist snippet show wishlist',
        'properties' => [
            'tpl' => [
                'type' => 'textfield',
                'value' => '',
            ],

            'limit' => [
                'type' => 'numberfield',
                'value' => 100,
            ],

            'toPlaceholder' => [
                'type' => 'combo-boolean',
                'value' => false,
            ],
        ],
    ],
];