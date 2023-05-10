<?php
$settings['display'] = 'vertical';
$settings['fields'] = array(
    'title' => array(
        'caption' => 'Вопрос',
        'type' => 'text'
    ),
    'text' => array(
        'caption' => 'Ответ',
        'type' => 'textareamini'
    )
);
$settings['templates'] = array(
    'outerTpl' => '[+wrapper+]',
    'rowTpl' => '<li>
                        <div class="accordion-header">
                            <span>[+title+]</span>
                            <div class="icon"><i></i></div>
                        </div>
                        <div class="accordion-body">
                            <p>[+text+]</p>
                        </div>
                    </li>'
);
