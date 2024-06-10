<?php

namespace Filters\Controllers;

class CoreController
{
    /**
     * @var int
     */
    private int $site_id = 0;

    public function __construct()
    {
        evolutionCMS()->invokeEvent("OnWebPageInit");
        evolutionCMS()->tpl = \DLTemplate::getInstance(evolutionCMS());

        $this->site_id = (int) evolutionCMS()->config['site_start'];
    }

    /**
     * @return string
     */
    public function run(): string
    {
        unset($_GET['f']);

        evolutionCMS()->documentIdentifier = $this->getCatalogId();
        evolutionCMS()->documentObject = $this->getDocumentObject(evolutionCMS()->documentIdentifier);

        if ($this->setParameters()) {
            evolutionCMS()->tpl->loadTwig();
            return evolutionCMS()->tpl->renderDoc(evolutionCMS()->documentIdentifier, true);
        }

        return "";
    }

    /**
     * @return int
     */
    protected function getCatalogId(): int
    {
        $table = evolutionCMS()->getFullTableName("site_tmplvar_contentvalues");

        return evolutionCMS()->db->getValue(
            evolutionCMS()->db->query("SELECT value FROM {$table} WHERE contentid = {$this->site_id} and tmplvarid = 64")
        );
    }

    /**
     * @param  int  $id
     * @return array
     */
    protected function getDocumentObject(int $id): array
    {
        $object = evolutionCMS()->getDocumentObject("id", $id);

        foreach ($object as $key => $value) {
            if (!empty($value) && is_array($value)) {
                $value = $value[1];
            }
            $object[$key] = $value;
        }

        return $object;
    }

    /**
     * @return bool
     */
    protected function setParameters(): bool
    {
        $values = [];

        $url_parts = $this->getUrlParts();
        $whereItems = $this->getWhereItems($url_parts);
        $whereItems = implode(" or ", $whereItems);
        $whereItems = !empty($whereItems) ? "and {$whereItems}" : "";

        $table_tvs = evolutionCMS()->getFullTableName("site_tmplvars");
        $table_tv_values = evolutionCMS()->getFullTableName("site_tmplvar_contentvalues");

        $items = $this->getItems();
        $item_ids = array_keys($items);
        $item_ids = implode(",", $item_ids);

        $item_tvs = evolutionCMS()->db->makeArray(
            evolutionCMS()->db->query("SELECT tv_value.value, tv.name, tv.id FROM {$table_tv_values} AS tv_value LEFT JOIN {$table_tvs} AS tv ON tv.id = tv_value.tmplvarid WHERE tv_value.contentid in ({$item_ids}) {$whereItems}")
        );

        foreach ($item_tvs as $tv) {
            $value = trim($tv['value']);

            if (empty($value)) {
                continue;
            }

            if (!empty($_GET['f'][$tv['id']]) && $_GET['f'][$tv['id']] === $value) {
                continue;
            }

            if (in_array(mb_strtolower($value), $values)) {
                continue;
            }

            $values[] = mb_strtolower($value);
            $_GET['f'][$tv['id']] = $value;
        }
        $_REQUEST = $_GET;

        return $this->checkErrorPage($url_parts);
    }

    /**
     * @return array
     */
    protected function getUrlParts(): array
    {
        $url = $_GET['q'];
        $url_parts = explode("/", $url);

        foreach ($url_parts as $item_key => $item) {
            if (empty($item) || $item === "filters") {
                unset($url_parts[$item_key]);
                continue;
            }

            $item = trim($item);
            $item = strtolower($item);
            $item = mb_strtolower($item);
            $item = str_replace("+", " ", $item);

            $url_parts[$item_key] = $item;
        }

        return $url_parts;
    }

    /**
     * @param $url_parts
     * @return array
     */
    protected function getWhereItems($url_parts): array
    {
        $whereItems = [];

        foreach ($url_parts as $item) {
            if (strpos($item, "range") !== false) {
                $item_parts = explode("-", $item);
                $range = array_shift($item_parts);
                $tv_id = array_shift($item_parts);
                $_GET['f'][$tv_id] = implode(":", $item_parts);
            } else {
                $whereItems[] = "LOWER(TRIM(tv_value.value)) = \"{$item}\"";
            }
        }

        return $whereItems;
    }

    /**
     * @return array
     */
    protected function getTVIds(): array
    {
        $catalog_id = evolutionCMS()->documentIdentifier;
        $table_tv_values = evolutionCMS()->getFullTableName("site_tmplvar_contentvalues");

        $response = evolutionCMS()->db->getValue(
            evolutionCMS()->db->query("SELECT value FROM {$table_tv_values} WHERE contentid = {$catalog_id} and tmplvarid = 72")
        );
        $response = json_decode($response, true);

        $params = (array) $response['fieldValue'];
        return array_column($params, "param_id");
    }

    /**
     * @return array
     */
    protected function getItems(): array
    {
        $items = evolutionCMS()->runSnippet("DocLister", [
            'api' => 1,
            'parents' => evolutionCMS()->documentIdentifier,
            'depth' => 5,
        ]);
        return json_decode($items, true);
    }

    /**
     * @param  array  $whereItems
     * @return bool
     */
    protected function checkErrorPage(array $whereItems): bool
    {
        $whereItemsCount = count($whereItems);
        $getCount = count((array) $_GET['f']);

        if ($whereItemsCount !== $getCount) {
            return false;
        }

        return true;
    }
}
