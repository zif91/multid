<?php
if (!isset($modx->customTV['morephoto'])) {
    $modx->customTV['morephoto'] = array();
}
if (!isset($modx->loadedjscripts['morephoto'])) {
    $headers = $modx->getTpl('@FILE:assets/tvs/morephoto/headers.tpl');
    $modx->loadedjscripts['morephoto'] = true;
    echo $modx->parseText($headers, ['baseUrl' => $modx->config['base_url']]);
}
?>
<input type='text' style="display:none;" class="morephoto" name='tv<?= $field_id ?>' id='tv<?= $field_id ?>'
       value='<?= $field_value ?>'/>
