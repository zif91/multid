<?php
define('MODX_API_MODE', true);
include_once(__DIR__ . "/../../../index.php");
$modx->db->connect();

// Получаем токен из запроса
$token = isset($_POST['token']) ? $modx->db->escape($_POST['token']) : '';
// Ищем запись в formdata с совпадающим токеном
$q = $modx->db->query("SELECT `data` FROM {$modx->getFullTableName('formdata')} WHERE `domain` = '{$token}' ORDER BY `id` ASC");
$out = [];
while($row = $modx->db->getRow($q)) {
    $data = json_decode($row['data'], true); // Декодируем JSON в массив
    if (isset($data['mobile_tel'])) { // Проверяем, есть ли поле mobile_tel в JSON
        // Удаляем пробелы, дефисы и скобки из значения поля mobile_tel
        $data['mobile_tel'] = str_replace([' ', '-', '(', ')'], '', $data['mobile_tel']);
        // Кодируем массив обратно в JSON и сохраняем его обратно в базу данных
        $modx->db->update("data='" . $modx->db->escape(json_encode($data)) . "'", "{$modx->getFullTableName('formdata')}", "id='{$row['id']}'");
        $out[] = $data; // Добавляем $data в массив $out
    }
}

$out = json_encode($out); // Кодируем массив $out в JSON
echo $out;

// Если были найдены записи, удаляем их из таблицы formdata
if (!empty($out)) {
    $modx->db->query("DELETE FROM {$modx->getFullTableName('formdata')} WHERE `domain` = '{$token}'");
}

exit;
