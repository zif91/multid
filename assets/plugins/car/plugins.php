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
            $model = new modResource($modx);

            $model->edit($id);

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

            $model->set('mark', (string) $brand['pagetitle']);
            $model->set('model', (string) $model['pagetitle']);
            $model->save(false, false);
            $model->close();
        }

        break;
    default:
        break;
}
