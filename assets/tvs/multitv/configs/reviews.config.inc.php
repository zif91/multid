<?php
$settings['display'] = 'vertical';
$settings['fields'] = array(
    'title' => array(
        'caption' => 'Заголовок',
        'type' => 'text'
    ),
    'subtitle' => array(
        'caption' => 'Подпись',
        'type' => 'text'
    ),
    'text' => array(
        'caption' => 'Текст отзыва',
        'type' => 'textareamini'
    )
);
$settings['templates'] = array(
    'outerTpl' => '[+wrapper+]',
    'rowTpl' => '<div class="item">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="text">
                            <p>
                                «[+text+]»
                            </p>
                            <h6>[+title+]</h6>
                            <span>[+subtitle+]</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>'
);
