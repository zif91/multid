<?php
return array (
  'caption' => 'Магазин',
  'introtext' => 'При включении магазина появятся шаблоны Корзина и Товар, а также включатся требуемые сниппеты и плагины. <br/> Методы вызова корзины показаны в Образце (Services / Образец)',
  'settings' => 
  array (
    'commerce_disabled' => 
    array (
      'caption' => 'Модуль магазина',
      'type' => 'dropdown',
      'note' => 'При изменении требуется перезагрузка админки (F5)',
      'elements' => 'Включен==0||Выключен==1',
      'default_text' => '1',
    ),
    'commerce_addedmodal' => 
    array (
      'caption' => 'Название чанка окна Товар добавлен в корзину',
      'type' => 'text',
      'note' => '',
      'elements' => '',
      'default_text' => 'cartAdded',
    ),
    'deliveries' => 
    array (
      'caption' => 'Способы доставки',
      'type' => 'custom_tv:multitv',
      'note' => 'Статичные способы доставки. Динамические добавляются отдельными плагинами.',
      'elements' => '',
      'default_text' => '',
    ),
    'payments' => 
    array (
      'caption' => 'Способы оплаты',
      'type' => 'custom_tv:multitv',
      'note' => 'Статичные способы оплаты. Динамические добавляются отдельными плагинами.',
      'elements' => '',
      'default_text' => '',
    ),
  ),
);