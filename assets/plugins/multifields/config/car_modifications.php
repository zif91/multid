<?php

return [
    'settings' => [],
    'templates' => [
        'Modification' => [
            'type' => 'row',
            'title' => 'Модификация',
            'placeholder' => 'Наименование модификации',
            'items' => [
                'engine_volume' => [
                    'title' => 'Объем двигателя, л.',
                    'type' => 'text',
                    'class' => 'col-4 float-left'
                ],
                'engine_displacement' => [
                    'title' => 'Рабочий объем двигателя, см3.',
                    'type' => 'text',
                    'class' => 'col-4 float-left'
                ],
                'engine_type' => [
                    'title' => 'Тип двигателя',
                    'type' => 'text',
                    'class' => 'col-4 float-left'
                ],
                'drive' => [
                    'title' => 'Тип привода',
                    'type' => 'text',
                    'class' => 'col-4 float-left'
                ],
                'engine_power' => [
                    'title' => 'Мощность, л.с.',
                    'type' => 'text',
                    'class' => 'col-4 float-left'
                ],
                'length' => [
                    'title' => 'Длина, мм.',
                    'type' => 'text',
                    'class' => 'col-4 float-left'
                ],
                'width' => [
                    'title' => 'Ширина, мм.',
                    'type' => 'text',
                    'class' => 'col-4 float-left'
                ],
                'height' => [
                    'title' => 'Высота, мм.',
                    'type' => 'text',
                    'class' => 'col-4 float-left'
                ],
                'fuel_consumption_city' => [
                    'title' => 'Расход топлива в городе, л/100 км.',
                    'type' => 'text',
                    'class' => 'col-4 float-left'
                ],
                'fuel_consumption' => [
                    'title' => 'Расход топлива на трассе, л/100 км.',
                    'type' => 'text',
                    'class' => 'col-4 float-left'
                ],
                'combined_fuel' => [
                    'title' => 'Смешанный расход топлива, л/100 км.',
                    'type' => 'text',
                    'class' => 'col-4 float-left'
                ],
                'acceleration' => [
                    'title' => 'Разгон от 0 до 100 км/ч, сек.',
                    'type' => 'text',
                    'class' => 'col-4 float-left'
                ],
                'maximum_speed' => [
                    'title' => 'Максимальная скорость, км/ч.',
                    'type' => 'text',
                    'class' => 'col-4 float-left'
                ],
                'fuel_volume' => [
                    'title' => 'Объем топливного бака, л.',
                    'type' => 'text',
                    'class' => 'col-4 float-left'
                ],
                'luggage_compartment_volume' => [
                    'title' => 'Объем багажного отделения, л.',
                    'type' => 'text',
                    'class' => 'col-4 float-left'
                ],
                'luggage_minimum_volume' => [
                    'title' => 'Минимальный объем багажного отделения, л',
                    'type' => 'text',
                    'class' => 'col-4 float-left'
                ],
                'luggage_maximum_volume' => [
                    'title' => 'Максимальный объем багажного отделения, л',
                    'type' => 'text',
                    'class' => 'col-4 float-left'
                ],
                'clearance' => [
                    'title' => 'Дорожный просвет, мм',
                    'type' => 'text',
                    'class' => 'col-4 float-left'
                ],
                'wheel_base' => [
                    'title' => 'Колесная база, мм.',
                    'type' => 'text',
                    'class' => 'col-4 float-left'
                ],
                'wheel_front_track' => [
                    'title' => 'Передняя колея колес, мм',
                    'type' => 'text',
                    'class' => 'col-4 float-left'
                ],
                'wheel_rear_track' => [
                    'title' => 'Задняя колея колес, мм',
                    'type' => 'text',
                    'class' => 'col-4 float-left'
                ],
                'suspension_front' => [
                    'title' => 'Передняя подвеска',
                    'type' => 'text',
                    'class' => 'col-4 float-left'
                ],
                'suspension_rear' => [
                    'title' => 'Задняя подвеска',
                    'type' => 'text',
                    'class' => 'col-4 float-left'
                ],
                'overhang_front' => [
                    'title' => 'Передний свес, мм',
                    'type' => 'text',
                    'class' => 'col-4 float-left'
                ],
                'overhang_rear' => [
                    'title' => 'Задний свес, мм',
                    'type' => 'text',
                    'class' => 'col-4 float-left'
                ],
                'brakes_front' => [
                    'title' => 'Передние тормоза (тип, размер)',
                    'type' => 'text',
                    'class' => 'col-4 float-left'
                ],
                'brakes_rear' => [
                    'title' => 'Задние тормоза (тип, размер)',
                    'type' => 'text',
                    'class' => 'col-4 float-left'
                ],
                'gear' => [
                    'title' => 'Коробка передач',
                    'type' => 'text',
                    'class' => 'col-4 float-left'
                ],
                'number_gears' => [
                    'title' => 'Количество передач',
                    'type' => 'text',
                    'class' => 'col-4 float-left'
                ],
            ],
            'templates' => ['ModificationItem']
        ],
        'ModificationItem' => [
            'type' => 'row',
            'title' => 'Комплектация',
            'placeholder' => 'Наименование комплектации',
            'hidden' => true,
            'items' => [
                'price' => [
                    'title' => 'Цена',
                    'type' => 'text',
                    'class' => 'col-12 float-left'
                ],
            ],
            'templates' => ['ModificationItemOption']
        ],
        'ModificationItemOption' => [
            'type' => 'row',
            'title' => 'Опция комплектации',
            'placeholder' => 'Наименование опции',
            'hidden' => true,
            'actions' => ['del', 'edit', 'move'],
            'items' => [
                'content' => [
                    'title' => 'Контент',
                    'type' => 'richtext',
                    'class' => 'col-12 float-left'
                ],
            ],
        ]
    ]
];
