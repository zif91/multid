<?php
return array (
  'caption' => 'Поиск по сайту',
  'introtext' => '',
  'settings' => 
  array (
    'search_disabled' => 
    array (
      'caption' => 'Модуль поиска',
      'type' => 'dropdown',
      'note' => '',
      'elements' => 'Включен==0||Выключен==1',
      'default_text' => '0',
    ),
    'search_field_name' => 
    array (
      'caption' => 'name поля для поиска',
      'type' => 'text',
      'note' => '',
      'elements' => '',
      'default_text' => 'search',
    ),
    'search_includeTmpls' => 
    array (
      'caption' => 'Шаблоны',
      'type' => 'text',
      'note' => 'Поиск только по документам с указанными шаблонами',
      'elements' => '',
      'default_text' => '',
    ),
    'search_excludeTmpls' => 
    array (
      'caption' => 'Исключить шаблоны',
      'type' => 'text',
      'note' => '',
      'elements' => '',
      'default_text' => '',
    ),
    'search_TvNames' => 
    array (
      'caption' => 'TV параметры',
      'type' => 'text',
      'note' => 'Имена ТВ-параметров через запятую, по которым требуется поиск',
      'elements' => '',
      'default_text' => '',
    ),
    'search_rowtpl' => 
    array (
      'caption' => 'Шаблон строки результатов поиска',
      'type' => 'text',
      'note' => 'Для ajax поиска. Название чанка или код шаблона @CODE:',
      'elements' => '',
      'default_text' => '',
    ),
    'search_reindex' => 
    array (
      'caption' => 'Поисковая база',
      'type' => 'custom_tv',
      'note' => '',
      'elements' => '<div class="input-group mb-1">  <span class="input-group-btn">    <span class="btn" id="reindex_rearch_btn" onclick="reindex_rearch()">Переиндексировать</span>  </span></div><div class="w-100" style="height:15px;border: 1px solid #ccc;display:none;" id="reindex_progress_wrapper"><div id="reindex_progress" style="transition: all .5s ease;width:0%;height:100%;background:#5cb85c;"></div></div><span id="reindex_message"></span><script>function reindex_rearch(){  jQuery("#reindex_rearch_btn").hide();  jQuery("#reindex_progress_wrapper").show();  jQuery("#reindex_message").html("");  jQuery.ajax({      url: "/reindex_rearch",      type: "get",      dataType: "json",      success: function(res) {          if(res.progress && res.progress<100) {            jQuery("#reindex_progress").css({"width": res.progress+"%"});            reindex_rearch()          } else {            jQuery("#reindex_progress_wrapper").hide();            jQuery("#reindex_rearch_btn").show();            jQuery("#reindex_progress").css({"width": 0});            jQuery("#reindex_message").html("Переиндексировано "+ res.total +" документов");          }      },      error: function(xhr, ajaxOptions, thrownError) {          alert("Ошибка индексации");          jQuery("#reindex_progress_wrapper").hide();          jQuery("#reindex_rearch_btn").show();          jQuery("#reindex_progress").css({"width": 0});      }  });}</script>',
      'default_text' => '',
    ),
  ),
);