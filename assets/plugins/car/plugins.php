<?php
if (!defined('MODX_BASE_PATH')) {
    die('HACK???');
}

$eventName = $modx->event->name;
switch($eventName) {
    case 'OnDocFormSave':
        $table = $modx->getFullTableName('site_content');
        $template_id = $modx->db->getValue(
            $modx->db->query("SELECT template FROM {$table} WHERE id = {$id}")
        );

        if ((int) $template_id === 28) {
            include_once MODX_BASE_PATH."assets/lib/MODxAPI/modResource.php";
            $doc = new modResource($modx);

            $doc->edit($id);

            $parents = $modx->getParentIds($id);
            $parent_list = implode(',', $parents);

            $brands = $modx->db->makeArray(
                $modx->db->query("SELECT * FROM {$table} WHERE id in ({$parent_list}) and template = 17")
            );
            $brand = array_shift($brands);

            $models = $modx->db->makeArray(
                $modx->db->query("SELECT * FROM {$table} WHERE id in ({$parent_list}) and template = 20")
            );
            $model = array_shift($models);

            $doc->set('mark', (string) $brand['pagetitle']);
            $doc->set('model', (string) $model['pagetitle']);
            $doc->save(false, false);
            $doc->close();
        }

        break;
    default:
        break;
}
