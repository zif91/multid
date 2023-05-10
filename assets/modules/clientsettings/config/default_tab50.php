<?php
return array (
  'caption' => 'Отправка писем',
  'introtext' => '',
  'settings' => 
  array (
    'email_to' => 
    array (
      'caption' => 'Электронная почта для заявок',
      'type' => 'text',
      'note' => 'Куда отправлять все заявки с сайта',
      'elements' => '',
      'default_text' => '',
    ),
    'smtp_host' => 
    array (
      'caption' => 'SMTP хост',
      'type' => 'text',
      'note' => '',
      'elements' => '',
      'default_text' => '',
    ),
    'smtp_port' => 
    array (
      'caption' => 'SMTP порт',
      'type' => 'text',
      'note' => '',
      'elements' => '',
      'default_text' => '',
    ),
    'smtp_username' => 
    array (
      'caption' => 'SMTP логин',
      'type' => 'text',
      'note' => 'C какой почты отправлять письма',
      'elements' => '',
      'default_text' => '',
    ),
    'smtppw' => 
    array (
      'caption' => 'SMTP пароль',
      'type' => 'custom_tv',
      'note' => '',
      'elements' => '<input type="password" name="tv[+field_id+]" id="tv[+field_id+]" value="[+field_value+]" onchange="document.getElementById(\'tv_[+field_id+]\').value = this.value" readonly onfocus="this.removeAttribute(\'readonly\')">',
      'default_text' => '',
    ),
  ),
);