<?php
$settings['display'] = 'vertical';
$settings['fields'] = array(
    'image' => array(
        'caption' => 'Картинка',
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
        'caption' => 'Текст',
        'type' => 'textareamini'
    )
);


$settings['templates'] = array(
    'outerTpl' => '<div class="slider">[+wrapper+]</div>',
    'rowTpl' => '<img src="[[phpthumb?&input=`[+image+]`&options=`w=680,h=395,zc=1`]]" alt="[+title+]">'
);

