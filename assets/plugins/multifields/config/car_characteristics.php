<?php

return [
    'settings' => [],
    'templates' => [
        'CharacteristicsGroup' => [
            'type' => 'row',
            'title' => 'Группа комплектаций',
            'placeholder' => 'Заголовок группы комплектаций',
            'templates' => ['CharacteristicsGroupItem'],
        ],
        'CharacteristicsGroupItem' => [
            'type' => 'row',
            'title' => 'Комплектация',
            'hidden' => true,
            'value' => false,
            'actions' => ['del', 'move'],
            'items' => [
                'title' => [
                    'type' => 'text',
                    'title' => 'Имя комплектации',
                    'class' => 'col-10 float-left'
                ],
                'price' => [
                    'type' => 'text',
                    'title' => 'Стоимость',
                    'class' => 'col-6 float-right'
                ],
                'price_old' => [
                    'type' => 'text',
                    'title' => 'Стоимость зачеркнутая',
                    'class' => 'col-6 float-right'
                ],
                'transmission' => [
                    'type' => 'text',
                    'title' => 'Коробка передач',
                    'class' => 'col-7 float-right'
                ],
                'drive' => [
                    'type' => 'text',
                    'title' => 'Привод',
                    'class' => 'col-5 float-right'
                ],
                'expense' => [
                    'type' => 'text',
                    'title' => 'Расход',
                    'class' => 'col-6 float-right'
                ],
                'engine_type' => [
                    'type' => 'dropdown',
                    'title' => 'Тип двигателя',
                    'class' => 'col-6 float-right',
                    'elements' => "||Бензин||Дизель||Гибрид||Электрический"
                ],
                'power' => [
                    'type' => 'text',
                    'title' => 'Мощность',
                    'class' => 'col-12 float-right'
                ],
                'description' => [
                    'type' => 'richtext',
                    'title' => 'Описание',
                    'class' => 'col-12 float-right'
                ],
            ]
        ]
    ]
];
