<?php
if (!defined('MODX_BASE_PATH')) {
    die('HACK???');
}

$image = isset($image) ? $image : (isset($modx->documentObject['image'][1]) ? $modx->documentObject['image'][1] : '');
$images = !empty($images) ? $images : (isset($modx->documentObject['images'][1]) ? $modx->documentObject['images'][1] : '');
$phpthumb = !empty($phpthumb) ? $phpthumb : '';
$tpl = !empty($tpl) ? $tpl : '@CODE:[+image+]';
$ownerTPL = !empty($ownerTPL) ? $ownerTPL : '@CODE:[+wrap+]';
$rowClass = !empty($rowClass) ? $rowClass : '';
$firstClass = !empty($firstClass) ? $firstClass : '';
$minShow = !empty($minShow) ? (int) $minShow : 1;
$display = !empty($display) ? (int) $display : '';
$splitter = !empty($splitter) ? $splitter : '';
$splitterCount = !empty($splitterCount) ? (int) $splitterCount : 0;

$out = '';
$_ = array();

if ($image) {
    $_[] = array(
        'image' => $image,
        'title' => ''
    );
}

if ($images) {
    $images = json_decode($images, true);
    $images = $images['fieldValue'];
    $_ = array_merge($_, $images);
}

if (count($_) < $minShow) {
    $_ = array();
}

$tpl = $modx->getTpl($tpl);
foreach ($_ as $k => $v) {
    if ($display && $k >= $display) {
        continue;
    }
    $v['class'] = $rowClass;
    if ($k === 0) {
        $v['class'] .= ' '.$firstClass;
    }
    $v['class'] = trim($v['class']);
    $v['classes'] = $v['class'] ? ' class="'.$v['class'].'"' : '';

    if ($phpthumb) {
        $v['image.thumb'] = $modx->runSnippet('phpthumb', array(
            'input' => $v['image'],
            'options' => $phpthumb
        ));
    }
    if (empty($v['image.thumb'])) {
        $v['image.thumb'] = $v['image'];
    }
    $out .= $modx->parseText($tpl, $v);
    if ($splitter && $splitterCount && ((($k + 1) % $splitterCount) == 0)) {
        $out .= $splitter;
    }
}

if ($out) {
    $tpl = $modx->getTpl($ownerTPL);
    $out = $modx->parseText($tpl, array(
        'wrap' => $out
    ));
}

return $out;
