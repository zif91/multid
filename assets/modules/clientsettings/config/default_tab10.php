<?php
return array (
  'caption' => 'Основные',
  'introtext' => '',
  'settings' => 
  array (
    'company_name' => 
    array (
      'caption' => 'Название компании',
      'type' => 'text',
      'note' => '',
      'elements' => '',
      'default_text' => '',
    ),
    'company_name_short' => 
    array (
      'caption' => 'Название компании (кратк.)',
      'type' => 'text',
      'note' => '',
      'elements' => '',
      'default_text' => '',
    ),
    'company_slogan' => 
    array (
      'caption' => 'Слоган компании',
      'type' => 'text',
      'note' => '',
      'elements' => '',
      'default_text' => '',
    ),
    'company_phone' => 
    array (
      'caption' => 'Телефон',
      'type' => 'text',
      'note' => '',
      'elements' => '',
      'default_text' => '',
    ),
    'company_phone_second' => 
    array (
      'caption' => 'Телефон (доп.)',
      'type' => 'text',
      'note' => '',
      'elements' => '',
      'default_text' => '',
    ),
    'company_email' => 
    array (
      'caption' => 'Электронная почта',
      'type' => 'text',
      'note' => '',
      'elements' => '',
      'default_text' => '',
    ),
    'company_working_hours' => 
    array (
      'caption' => 'Время работы',
      'type' => 'text',
      'note' => '',
      'elements' => '',
      'default_text' => '',
    ),
    'company_address' => 
    array (
      'caption' => 'Адрес',
      'type' => 'text',
      'note' => '',
      'elements' => '',
      'default_text' => '',
    ),
    'company_coordinates' => 
    array (
      'caption' => 'Координаты для карты',
      'type' => 'custom_tv',
      'note' => '',
      'elements' => '<div class="input-group mb-1">                <span class="input-group-btn">                    <span class="btn" onclick="findCoordinates(\'tvcompany_address\', \'tvcompany_coordinates\')">Найти координаты</span>                </span>            </div>            <input type="text" name="tv[+field_id+]" id="tv[+field_id+]" value="[+field_value+]" onchange="document.getElementById(\'tv_[+field_id+]\').value = this.value">                        <script>                function findCoordinates(id, target)                {                  var geocode = document.getElementById(id).value;                  if (geocode !== \'\') {                    geocode = encodeURIComponent(geocode);                    var xhr = new XMLHttpRequest();                    xhr.responseType = \'json\';                    xhr.open(\'GET\', \'https://geocode-maps.yandex.ru/1.x/?format=json&apikey=ab62c694-5b81-4342-820a-b695ce9b2f90&geocode=\' + geocode, true);                    xhr.onreadystatechange = function() {                      if (this.readyState === 4 && this.response) {                        var coordinates = \'\';                        if (parseInt(this.response.response.GeoObjectCollection.metaDataProperty.GeocoderResponseMetaData.found)) {                          coordinates = this.response.response.GeoObjectCollection.featureMember[0].GeoObject.Point.pos.split(\' \');                          coordinates = coordinates[1] + \', \' + coordinates[0];                        }                        document.getElementById(target).value = coordinates;                      }                    };                    xhr.send();                  }                }            </script>',
      'default_text' => '',
    ),
  ),
);