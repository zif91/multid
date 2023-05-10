<?php
$settings['display'] = 'vertical';
$settings['fields'] = array(
    'key' => array(
        'caption' => 'Key',
        'type' => 'text'
    ),
    'value' => array(
        'caption' => 'Value',
        'type' => 'text'
    ));

$settings['templates'] = array(
    'outerTpl' => '<tr class="fuel" data-tr="tab">
                    <td>
                        <div class="feature-tbody__top">
                            Комплектация<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M5.99991 8.08569L9.99991 12.0857L13.9999 8.08569L15.4141 9.49991L9.99991 14.9141L4.58569 9.49991L5.99991 8.08569Z" fill="black" />
                            </svg>
                        </div>
                    </td>
                    <td></td>
                    </tr>
                    [+wrapper+]',
    'rowTpl' => '<tr data-tr="fuel">
                    <td>[+key+]</td>
                    <td>[+value+]</td></tr>
                            ');
