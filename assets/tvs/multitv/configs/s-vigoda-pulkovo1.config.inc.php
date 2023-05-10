<?php
$settings['display'] = 'vertical';
$settings['fields'] = array(
    'image' => array(
        'caption' => 'Иконка',
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
     'vigoda' => array(
        'caption' => 'Выгода',
        'type' => 'text'
    ),
    'text' => array(
        'caption' => 'Текст',
        'type' => 'textareamini'
    )
);
$settings['templates'] = array(
    'outerTpl' => '[+wrapper+]',
    'rowTpl' => '<section class="s-block-send section-32 banner-gray-bg banner-catalog-profit">
    <div class="container">
        <div class="blockSend blockSend--profit"
             Style="background: url([+image+]) no-repeat right center, #23262F;
                    background-image: url(../images/new/banner-profit-bg.png), initial;
                    background-position-x: right, initial;
                    background-position-y: center, initial;
                    background-size: cover;
                    background-repeat-x: no-repeat, initial;
                    background-repeat-y: no-repeat, initial;
                    background-attachment: initial, initial;
                    background-origin: initial, initial;
                    background-clip: initial, initial;
                    background-color: rgb(35, 38, 47);
                    background-size: cover;">
            <h2 class="blockSend__title sale--title">
                (([+title+]))[+title+] <span>[+vigoda+]</span></h2>
            <p class="blockSend__text">[+text+]</p>'
);
