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
    $out[] = $row['data'];
}

$out = '[' . implode(',', $out) . ']';
echo $out;

// Если были найдены записи, удаляем их из таблицы formdata
if (!empty($out)) {
    $modx->db->query("DELETE FROM {$modx->getFullTableName('formdata')} WHERE `domain` = '{$token}'");
}

exit;
