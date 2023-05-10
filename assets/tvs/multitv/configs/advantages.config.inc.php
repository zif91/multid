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
    'text' => array(
        'caption' => 'Текст',
        'type' => 'textareamini'
    )
);
$settings['templates'] = array(
    'outerTpl' => '[+wrapper+]',
    'rowTpl' => '<div class="col-lg-6 col-xl-5">
                            <div class="item">
                                <div class="icon icon-1">
                                    <i style="background-image:url([+image+]);"></i>
                                </div>
                                <h6>[+title+]</h6>
                                <p>[+text+]</p>
                            </div>
                        </div>'
);
