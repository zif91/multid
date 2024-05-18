<?php

return [
    'settings' => [],
    'templates' => [
        'Color' => [
            'type' => 'row',
            'actions' => ['move', 'del'],
            'value' => false,
            'items' => [
                'title' => [
                    'type' => "text",
                    'title' => "Цвет"
                ],
                'code' => [
                    'type' => "text",
                    'title' => "Код цвета"
                ],
                'image' => [
                    'type' => "image",
                    'title' => "Изображение"
                ],
            ],
        ],
    ]
];
