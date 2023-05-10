<?php
$settings['display'] = 'vertical';
$settings['fields'] = array(
    'image' => array(
        'caption' => 'Фото',
        'type' => 'image'
    ),
    'thumb' => array(
        'caption' => 'Thumbnail',
        'type' => 'thumb',
        'thumbof' => 'image'
    ),
    'title' => array(
        'caption' => 'Заголовок',
        'type' => 'text'
    ),
    'text' => array(
        'caption' => 'Описание',
        'type' => 'textareamini'
    ),
    'price' => array(
        'caption' => 'Цена',
        'type' => 'text'
    )
);
$settings['templates'] = array(
    'outerTpl' => '[+wrapper+]',
    'rowTpl' => '[+title+] - [+price+]'
);
