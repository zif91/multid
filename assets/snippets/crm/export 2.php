<?php
define('TOKEN', 'TJ1LogHWM');
define('MODX_API_MODE', true);
include_once(__DIR__ . "/../../../index.php");
$modx->db->connect();
if (empty($modx->config)) {
    $modx->getSettings();
}
$modx->invokeEvent("OnWebPageInit");

if(!isset($_POST['token']) || $_POST['token'] !== TOKEN) $modx->sendErrorPage();

$date = isset($_POST['last_request_date']) && is_scalar($_POST['last_request_date']) ? $modx->db->escape($_POST['last_request_date']) : '';
$q = $modx->db->query("SELECT * FROM {$modx->getFullTableName('formdata')}" . ($date ? " WHERE `createdon` >= '{$date}'" : "") . ' ORDER BY `id` ASC');
$out = [];
$ids = [];
while($row = $modx->db->getRow($q)) {
    $ids[] = $row['id'];
    $out[] = $row['data'];
}

$out = '[' . implode(',', $out) . ']';
echo $out;

if ($ids) {
    $ids = implode(',', $ids);
    $modx->db->query("DELETE FROM {$modx->getFullTableName('formdata')} WHERE `id` IN ($ids)");
}

exit;
