<?php
return array (
  'caption' => 'Системные',
  'introtext' => '',
  'settings' => 
  array (
    'devmode' => 
    array (
      'caption' => 'Режим разработки',
      'type' => 'dropdown',
      'note' => 'В режиме разработки показывается только заглушка.',
      'elements' => 'Включен==1||Выключен==0',
      'default_text' => '1',
    ),
    'vue' => 
    array (
      'caption' => 'Поддержка VueJs',
      'type' => 'dropdown',
      'note' => '',
      'elements' => 'Включена==1||Выключена==0',
      'default_text' => '0',
    ),
    'adminmode' => 
    array (
      'caption' => 'Показывать отключенные ресурсы',
      'type' => 'dropdown',
      'note' => '',
      'elements' => 'Показывать==1||Скрывать==0',
      'default_text' => '0',
    ),
    'minify_js' => 
    array (
      'caption' => 'Минифицировать JS',
      'type' => 'dropdown',
      'note' => 'Сжимать подключаемые через assets файлы',
      'elements' => 'Да==1||Нет==0',
      'default_text' => '1',
    ),
    'minify_css' => 
    array (
      'caption' => 'Минифицировать CSS',
      'type' => 'dropdown',
      'note' => '',
      'elements' => 'Да==1||Нет==0',
      'default_text' => '1',
    ),
    'inline_css' => 
    array (
      'caption' => 'Вставлять весь CSS в код страницы',
      'type' => 'dropdown',
      'note' => '',
      'elements' => 'Да==1||Нет==0',
      'default_text' => '1',
    ),
  ),
);