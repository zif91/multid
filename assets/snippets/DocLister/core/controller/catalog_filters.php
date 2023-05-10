<?php

/**
 * Class catalog_filtersDocLister
 * @author 64j
 */
require_once(MODX_BASE_PATH . 'assets/snippets/DocLister/core/controller/site_content.php');

class catalog_filtersDocLister extends site_contentDocLister
{
    protected $filters = [];
    protected $request = [];
    protected $f = [];
    protected $filterWhere = '';

    public function __construct($modx, $cfg = array(), $startTime = null)
    {
        parent::__construct($modx, $cfg, $startTime);

        $this->setFilterFromRequest();
        $this->getFilterOptions();
        $this->renderFilterOptions();
        $this->setFilters();
    }

    /**
     * Передаём параметр filters дальше в контроллер
     */
    protected function setFilters()
    {
        if (!empty($this->filters)) {
            $containsAllDelimiter = $this->getCFGDef('filter_delimiter:containsAll', '~');
            $filterDelimiter = $this->getCFGDef('filterDelimiter', '||');

            $this->config->setConfig([
                'filter_delimiter:containsOne' => $containsAllDelimiter,
                'filterDelimiter' => $filterDelimiter,
                'filters' => $this->getCFGDef('filters', '') ? substr($this->getCFGDef('filters'), 0, -1) . ';' . implode(';', $this->filters) . ')' : 'AND(' . implode(';', $this->filters) . ')'
            ]);

            // генерируем новый ключ кэша
            $this->extCache->setCacheKey();

            $this->AddTable = [];

            $this->_filters = $this->getFilters($this->getCFGDef('filters', ''));
        }
    }

    /**
     * Приводим $_REQUEST к единому формату и собираем фильтр
     * два варианта
     * ?f[9]=59|60&f[12]=44065:271150
     * ?f[9][]=59&f[9][]=60&f[12][from]=44065&f[12][to]=271150
     */
    protected function setFilterFromRequest()
    {
        if (!empty($_REQUEST['f']) && is_array($_REQUEST['f']) && empty($this->request)) {
            $this->request = [];
            $filterRangeSeparator = $this->getCFGDef('filterRangeSeparator', ':');
            $filterValuesSeparator = $this->getCFGDef('filterValuesSeparator', '|');

            foreach ($_REQUEST['f'] as $k => $v) {
                if (!is_numeric($k)) {
                    continue;
                }

                if (!is_array($v)) {
                    $this->request[$k] = [];

                    $values = array_map('trim', explode($filterValuesSeparator, $v));
                    foreach ($values as $value) {
                        if (stripos($value, $filterRangeSeparator) !== false) {
                            $value = array_map('trim', explode($filterRangeSeparator, $value));
                            $this->request[$k]['from'] = isset($value[0]) ? $value[0] : 0;
                            $this->request[$k]['to'] = isset($value[1]) ? $value[1] : 1;
                        } else {
                            $this->request[$k][] = str_replace([';', '%2B'], ['&#59', '+'], $value);
                        }
                    }
                } else {
                    $this->request[$k] = $v;
                }
            }

            if (!empty($this->request)) {
                $tvIds = $this->sanitarIn(array_keys($this->request));
                $rs = $this->dbQuery('SELECT * FROM ' . $this->getTable('site_tmplvars') . ' WHERE id IN(' . $tvIds . ')');
                $containsAllDelimiter = $this->getCFGDef('filter_delimiter:containsAll', '~');
                $filterDelimiter = $this->getCFGDef('filterDelimiter', '||');

                while ($row = $this->modx->db->getRow($rs)) {
                    if (isset($this->request[$row['id']]['from'])) {
                        $tvFilter = $row['id'] == $this->getCFGDef('filterPriceId', '') ? 'filter_price' : 'tv';
                        $this->filters[] = $tvFilter . ':' . $row['name'] . ':>=:' . $this->request[$row['id']]['from'];
                        $this->filters[] = $tvFilter . ':' . $row['name'] . ':<=:' . $this->request[$row['id']]['to'];
                    } else {
                        if (is_array($this->request[$row['id']])) {
                            $this->filters[] = 'filter_like:' . $row['name'] . ':containsOne:' . $filterDelimiter . implode($filterDelimiter . $containsAllDelimiter . $filterDelimiter, $this->request[$row['id']]) . $filterDelimiter;
                        } else {
                            $this->filters[] = 'tv:' . $row['name'] . ':=:' . $this->request[$row['id']];
                        }
                    }
                }
            }
        }
    }

    /**
     * Загружаем свойства
     * Собираем фильтр
     */
    protected function getFilterOptions()
    {
        if (!empty($this->IDs)) {
            $this->f = [];
            $sanitarInIDs = $this->sanitarIn($this->IDs);

            $tmpWhere = '';
            $where = sqlHelper::trimLogicalOp($this->_filters['where']);

            if ($where) {
                $tmpWhere = $where = ' AND ' . $where;
            }

            // Фильтр по цене, при условии что есть TV с валютой
            if ($this->getCFGDef('filterPriceId', '') && $this->getCFGDef('filterCurrencyId', '')) {
                // Валюта по умолчанию
                $filterCurrencyDefault = $this->getCFGDef('filterCurrencyDefault', 'RUB');

                $rs = $this->dbQuery('
                    SELECT
                    `filter_tv`.`id`, `filter_tv`.`name`, `filter_tv`.`type`, `filter_tv`.`caption`, `filter_tv`.`description`,
                    MIN(ROUND(`filter_tvc_price`.`value` * `filter_currencies`.`value`)) AS min,
                    MAX(ROUND(`filter_tvc_price`.value * `filter_currencies`.value)) AS max,
                    COUNT(*) AS count
                    FROM ' . $this->getTable('site_content', 'c') . '
                    LEFT JOIN ' . $this->getTable('site_tmplvar_contentvalues', 'filter_tvc_price') . ' ON `filter_tvc_price`.`contentid`=`c`.`id` AND `filter_tvc_price`.`tmplvarid`=' . (int)$this->getCFGDef('filterPriceId') . '
                    LEFT JOIN ' . $this->getTable('site_tmplvars', 'filter_tv') . ' ON `filter_tv`.`id`=`filter_tvc_price`.`tmplvarid`
                    LEFT JOIN ' . $this->getTable('site_tmplvar_contentvalues', 'filter_tvc_currency') . ' ON `filter_tvc_currency`.`contentid`=`c`.`id` AND `filter_tvc_currency`.`tmplvarid`=' . (int)$this->getCFGDef('filterCurrencyId') . '
                    LEFT JOIN ' . $this->getTable('commerce_currency', 'filter_currencies') . ' ON `filter_currencies`.`code`=IFNULL(`filter_tvc_currency`.`value`,"' . $filterCurrencyDefault . '")
                    ' . $this->_filters['join'] . '
                    WHERE 1
                    AND `c`.`published`=1
                    AND `c`.`deleted`=0
                    AND `c`.`parent` IN(' . $sanitarInIDs . ')
                    AND `filter_tvc_price`.`value` <> ""
                    ' . $tmpWhere . '
                    GROUP BY `filter_tv`.`id`
                ');

                while ($row = $this->modx->db->getRow($rs)) {
                    $row['type'] = 'number';
                    $row['@type'] = 'number';
                    $row['selected'] = isset($this->request[$row['id']]);
                    $row['from'] = !empty($this->request[$row['id']]['from']) ? $this->request[$row['id']]['from'] : $row['min'];
                    $row['to'] = !empty($this->request[$row['id']]['to']) ? $this->request[$row['id']]['to'] : $row['max'];
                    $row['from_min'] = $row['min'];
                    $row['from_max'] = $row['max'];
                    //$row['step'] = $row['max'] > 100 ? round($row['max'] / 50) : 1;
                    $row['step'] = 1;
                    $row['values'] = [];
                    $this->f[$row['id']] = $row;
                }
            }

            $group = '`filter_tvcv`.`id`';
            $group = 'CASE WHEN `filter_tvcv`.`value` REGEXP "^[0-9]+$" THEN `filter_tv`.`id` ELSE `filter_tvcv`.`id` END';
            $filtersTypes = [];

            // Загружаем типы по полю display_params {"filter": {"type": "checkbox"}}
            if ($this->getCFGDef('filterUseParams', '')) {
                $filterList = [];
                $tmpWhere = [];

                if ($this->getCFGDef('filterList', '')) {
                    $tmpWhere[] = ' `id` IN(' . $this->sanitarIn($this->getCFGDef('filterList')) . ')';
                }

                if ($this->getCFGDef('filterCategory', '')) {
                    $tmpWhere[] = ' `category` = ' . (int)$this->getCFGDef('filterCategory');
                }

                if (!empty($tmpWhere)) {
                    $tmpWhere = ' WHERE (' . implode(' OR ', $tmpWhere) . ')';
                }

                $rs = $this->dbQuery('SELECT * FROM ' . $this->getTable('site_tmplvars') . $tmpWhere);
                while ($row = $this->modx->db->getRow($rs)) {
                    if (!empty($row['display_params'])) {
                        $parse_params = [];
                        $display_params = explode('&', $row['display_params']);
                        foreach ($display_params as $display_param) {
                            if ($display_param) {
                                $display_param = explode('=', $display_param, 2);
                                if ($display_param[0] == 'output') {
                                    $parse_params = json_decode($display_param[1], true);
                                    break;
                                }
                            }
                        }

                        if (!empty($parse_params['filter'])) {
                            $filterList[] = $row['id'] . ':' . $parse_params['filter']['type'];
                        }
                    }
                }

                if (!empty($filterList)) {
                    $this->config->setConfig([
                        'filterList' => implode(';', $filterList)
                    ]);
                }
            }

            if ($this->getCFGDef('filterList', '')) {
                $list = $this->getCFGDef('filterList');
                if (strpos($list, ':') === false) {
                    $list = $this->sanitarIn($list);
                } else {
                    $number_filters = [];
                    $_ = explode(';', $list);
                    $list = [];
                    foreach ($_ as $k => $v) {
                        $v = explode(':', $v);
                        if (!isset($v[1])) {
                            $v[1] = 'text';
                        }
                        $list[] = $v[0];
                        $filtersTypes[$v[0]] = $v[1];
                        if ($v[1] == 'number' || $v[1] == 'range') {
                            $number_filters[] = $v[0];
                        }
                    }
                    $list = $this->sanitarIn($list, ',', false);
                    if (!empty($number_filters)) {
                        $group = 'CASE
                            WHEN `filter_tvcv`.`tmplvarid`=' . implode('
                                THEN `filter_tv`.`id`
                            WHEN `filter_tvcv`.`tmplvarid`=', $number_filters) . '
                                THEN `filter_tv`.`id`
                            ELSE `filter_tvcv`.`id`
                            END';
                    }
                }

                $where .= ' AND `filter_tv`.`id` IN(' . $list . ')';
                $this->filterWhere = '`filter_tv`.`id` IN(' . $list . ')';
            } elseif ($this->getCFGDef('filterCategory', '')) {
                $where .= ' AND `filter_tv`.`category`=' . (int)$this->getCFGDef('filterCategory');
                $this->filterWhere = '`filter_tv`.`category`=' . (int)$this->getCFGDef('filterCategory');
            } else {
                return;
            }

            $this->dbQuery('SET sql_mode = "";');

            $rs = $this->dbQuery('
                SELECT
                `filter_tvcv`.*,
                `filter_tv`.`id`,
                `filter_tv`.`name`,
                `filter_tv`.`type`,
                `filter_tv`.`caption`,
                `filter_tv`.`description`,
                `filter_tv`.`elements`,
                `filter_tv`.`default_text`,
                FLOOR(MIN(`filter_tvcv`.`value` * 1.0)) AS min,
                CEILING(MAX(`filter_tvcv`.`value` * 1.0)) AS max,
                COUNT(`filter_tvcv`.contentid) AS count
                FROM ' . $this->getTable('site_content', 'c') . '
                LEFT JOIN ' . $this->getTable('site_tmplvar_contentvalues', 'filter_tvcv') . ' ON `filter_tvcv`.`contentid`=`c`.`id`
                LEFT JOIN ' . $this->getTable('site_tmplvars', 'filter_tv') . ' ON `filter_tv`.`id`=`filter_tvcv`.`tmplvarid`
                ' . $this->_filters['join'] . '
                WHERE 1
                AND `c`.`published`=1
                AND `c`.`deleted`=0
                AND `c`.`parent` IN(' . $sanitarInIDs . ')
                AND `filter_tvcv`.`value` <> ""
                ' . $where . '
                GROUP BY ' . $group . '
                ORDER BY `filter_tv`.`rank`
            ');

            while ($row = $this->modx->db->getRow($rs)) {

                if (!isset($this->f[$row['id']])) {
                    $u = array_map('trim', explode(',', $row['caption'] . ','));

                   if (is_numeric($row['value']) && $row['type'] == 'text') {
                       $row['type'] = 'number';
                   }

                    $this->f[$row['id']] = [
                        'id' => $row['id'],
                        'name' => $row['name'],
                        'type' => isset($filtersTypes[$row['id']]) ? $filtersTypes[$row['id']] : $row['type'],
                        '@type' => $row['type'],
                        'caption' => $u[0],
                        'unit' => $u[1],
                        'description' => $row['description'],
                        'min' => $row['min'],
                        'max' => $row['max'],
                        'from' => !empty($this->request[$row['id']]['from']) ? $this->request[$row['id']]['from'] : $row['min'],
                        'to' => !empty($this->request[$row['id']]['to']) ? $this->request[$row['id']]['to'] : $row['max'],
                        'from_min' => $row['min'],
                        'from_max' => $row['max'],
                        'step' => 1,
                        'selected' => isset($this->request[$row['id']]),
                        'disabled' => '',
                        'values' => []
                    ];

                    if ($this->f[$row['id']]['type'] == 'range') {
                        $this->f[$row['id']]['@type'] = 'number';
                    }

                    if ($this->getCFGDef('filterCounters', '')) {
                        $this->f[$row['id']]['count'] = $row['count'];
                    }

                    if (stripos($row['elements'], '@SELECT') !== false) {
                        $this->f[$row['id']]['elements'] = [];

                        require_once MODX_MANAGER_PATH . 'includes/tmplvars.commands.inc.php';
                        $rs2 = ProcessTVCommand($row['elements']);
                        while ($r2 = $this->modx->db->getRow($rs2, 'num')) {
                            if (isset($r2[0]) && isset($r2[1])) {
                                $this->f[$row['id']]['elements'][$r2[1]] = $r2[0];
                            }
                        }
                    }
                }

                switch ($this->f[$row['id']]['type']) {
                    case 'number':
                    case 'range':
                        $this->f[$row['id']]['values'] = [];
                        break;

                    case 'checkbox':
                    case 'listbox-multiple':
                        $values = explode('||', $row['value']);

                        foreach ($values as $value) {
                            if (!isset($this->f[$row['id']]['values'][$value])) {
                                $this->f[$row['id']]['values'][$value] = [
                                    'id' => $row['id'],
                                    'name' => $row['name'],
                                    'value' => $value,
                                    'caption' => $value
                                ];
                            }

                            if ($this->getCFGDef('filterCounters', '')) {
                                $this->f[$row['id']]['values'][$value]['count'] = isset($this->f[$row['id']]['values'][$value]['count']) ? $this->f[$row['id']]['values'][$value]['count'] + 1 : 1;
                            }

                            if (isset($this->f[$row['id']]['elements'][$value])) {
                                $this->f[$row['id']]['values'][$value]['caption'] = $this->f[$row['id']]['elements'][$value];
                            }

                            $value_str = str_replace(';', '&#59', $value);

                            if (isset($this->request[$row['id']]) && (is_array($this->request[$row['id']]) && in_array($value_str, $this->request[$row['id']]))) {
                                $this->f[$row['id']]['values'][$value]['selected'] = true;
                            }
                        }
                        break;

                    case 'dropdown':
                    case 'listbox':
                    case 'text':
                    default:
                        if (!isset($this->f[$row['id']]['values'][$row['value']])) {
                            $this->f[$row['id']]['values'][$row['value']] = [];
                        }
                        $this->f[$row['id']]['values'][$row['value']]['id'] = $row['id'];
                        $this->f[$row['id']]['values'][$row['value']]['name'] = $row['name'];
                        $this->f[$row['id']]['values'][$row['value']]['value'] = $row['value'];
                        if ($this->getCFGDef('filterCounters', '')) {
                            $this->f[$row['id']]['values'][$row['value']]['count'] = isset($this->f[$row['id']]['values'][$row['value']]['count']) ? $this->f[$row['id']]['values'][$row['value']]['count'] + 1 : 1;
                        }
                        $this->f[$row['id']]['values'][$row['value']]['caption'] = isset($this->f[$row['id']]['elements'][$row['value']]) ? $this->f[$row['id']]['elements'][$row['value']] : $row['value'];
                        $this->f[$row['id']]['values'][$row['value']]['selected'] = isset($this->request[$row['id']]) && in_array($row['value'], $this->request[$row['id']]);
                        break;
                }
            }
        }
    }

    /**
     * Собираем фильтры
     */
    protected function renderFilterOptions()
    {
        $out = '';

        if (!empty($this->f)) {

            if ($this->getCFGDef('filterCounters', '')) {
                $this->setCountersOptions();
            }

            foreach ($this->f as $k => $v) {
                $v['wrap'] = '';

                // Сортируем значения если есть элементы
                if (!empty($v['values']) && !empty($v['elements'])) {
                    $values = $v['values'];
                    $v['values'] = [];
                    foreach ($v['elements'] as $key => $element) {
                        if (!empty($key) && isset($values[$key])) {
                            $v['values'][$key] = $values[$key];
                        }
                    }
                } else {
                    // uasort($v['values'], function ($a, $b) {
                    //     return ($a['count'] < $b['count']);
                    // });
                }

                switch ($v['type']) {
                    case 'number':
                    case 'range':
                        if ($v['min'] != $v['max']) {
                            $v['hash'] = 'f' . crc32($v['id']);
                            $v['wrap'] = $this->parseChunk($this->getCFGDef('filterTplRange'), $v);
                            $out .= $this->parseChunk($this->getCFGDef('filterOwnerRange'), $v);
                        }

                        break;

                    case 'dropdown':
                    case 'text':
                    case 'listbox-multiple':
                    case 'checkbox':
                    default:
                        if (count($v['values']) > 1) {
                            foreach ($v['values'] as $val) {
                                $val['id'] = $v['id'];
                                $val['name'] = $v['name'];
                                $val['hash'] = 'f' . crc32($v['id'] . $val['value']);
                                $val['checked'] = empty($val['selected']) ? '' : ' checked';
                                $val['disabled'] = isset($val['count']) && empty($val['count']) ? ' disabled' : '';

                                $v['wrap'] .= $this->parseChunk($this->getCFGDef('filterTplCheckbox'), $val);
                            }

                            $out .= $this->parseChunk($this->getCFGDef('filterOwnerCheckbox'), $v);
                        }

                        break;
                }
            }

            $out = $this->parseChunk($this->getCFGDef('filterTpl'), [
                'wrap' => $out
            ]);

            $this->f = [];
        }

        $this->toPlaceholders($out, 1, $this->getCFGDef('filter', 'filter'));
    }

    /**
     * Устанавливаем количество сразу у всех
     */
    protected function setCountersOptions()
    {
        if (!empty($this->f) && !empty($this->request)) {
            $sanitarInIDs = $this->sanitarIn($this->IDs);
            $filterCurrencyDefault = $this->getCFGDef('filterCurrencyDefault', 'RUB');
            $number_filters = [];

            $join = [];

            if (!empty($this->_filters['join'])) {
                $join[] = $this->_filters['join'];
            }

            $where = [];
            $whereRequest = [];
            $group = '`tvcv`.`tmplvarid`';

            foreach ($this->request as $k => $request) {
                $type = isset($this->f[$k]) ? $this->f[$k]['@type'] : '';
                $alias = 'filter_' . $k;

                switch ($type) {
                    case 'range':
                    case 'number':
                        if ($this->getCFGDef('filterPriceId', '') && $this->getCFGDef('filterCurrencyId', '') && $this->getCFGDef('filterPriceId') == $k) {
                            $whereRequest[] = '(`' . $alias . '`.`value` * `currencies`.`value` BETWEEN ' . $request['from'] . ' AND ' . $request['to'] . ')';
                        } else {
                            $whereRequest[] = '(`' . $alias . '`.`value` * 1.0 BETWEEN ' . $request['from'] . ' AND ' . $request['to'] . ')';
                        }
                        break;

                    case 'checkbox':
                    case 'listbox-multiple':
                    case 'option':
                        $whereRequest[] = '(CONCAT("||", `' . $alias . '`.`value`, "||") LIKE "%||' . implode('||%" OR CONCAT("||", `' . $alias . '`.`value`, "||") LIKE "%||', $request) . '||%")';
                        break;

                    case 'dropdown':
                    default:
                        $whereRequest[] = '(`' . $alias . '`.`value` IN (' . $this->sanitarIn($request) . '))';
                        break;
                }
            }

            foreach ($this->f as $k => $option) {
                $type = $option['@type'];
                $alias = 'filter_' . $k;
                $table = $this->getTable('site_tmplvar_contentvalues', $alias);
                $join[] = 'LEFT JOIN ' . $table . ' ON `' . $alias . '`.`contentid`=`c`.`id` AND `' . $alias . '`.`tmplvarid`=' . $k . '';

                switch ($type) {
                    case 'range':
                    case 'number':
                        if ($this->getCFGDef('filterPriceId', '') && $this->getCFGDef('filterCurrencyId', '') && $this->getCFGDef('filterPriceId') == $k) {
                            $tableCurrency = $this->getTable('site_tmplvar_contentvalues', 'currency_' . $alias);
                            $join[] = 'LEFT JOIN ' . $tableCurrency . ' ON `currency_' . $alias . '`.`contentid`=`c`.`id` AND `currency_' . $alias . '`.`tmplvarid`=' . $this->getCFGDef('filterCurrencyId');
                            $join[] = 'LEFT JOIN ' . $this->getTable('commerce_currency', 'currencies') . ' ON `currencies`.`code`=IFNULL(`currency_' . $alias . '`.`value`,"' . $filterCurrencyDefault . '")';
                            $where[] = '(`' . $alias . '`.`value` * `currencies`.`value` BETWEEN ' . $option['from'] . ' AND ' . $option['to'] . ')';
                        } else {
                            $where[] = '(`' . $alias . '`.`value` * 1.0 BETWEEN ' . $option['from'] . ' AND ' . $option['to'] . ')';
                        }
                        $number_filters[] = $k;

                        $this->f[$k]['count'] = 0;
                        break;

                    case 'checkbox':
                    case 'listbox-multiple':
                    case 'option':
                        if (!empty($option['values'])) {
                            $where[] = '(CONCAT("||", `' . $alias . '`.`value`, "||") LIKE "%||' . implode('||%" OR CONCAT("||", `' . $alias . '`.`value`, "||") LIKE "%||', array_column($option['values'], 'value')) . '||%")';

                            foreach ($option['values'] as $key => $value) {
                                $this->f[$k]['values'][$key]['count'] = 0;
                            }
                        }
                        break;

                    case 'dropdown':
                    default:
                        if (!empty($option['values'])) {
                            $where[] = '(`' . $alias . '`.`value` IN (' . $this->sanitarIn(array_column($option['values'], 'value')) . '))';

                            foreach ($option['values'] as $key => $value) {
                                $this->f[$k]['values'][$key]['count'] = 0;
                            }
                        }
                        break;
                }
            }

            if (!empty($number_filters)) {
                $group = '
                CASE
                WHEN `tvcv`.`tmplvarid`=' . implode('
                    THEN `tvcv`.`tmplvarid`
                WHEN `tvcv`.`tmplvarid`=', $number_filters) . '
                    THEN `tvcv`.`tmplvarid`
                ELSE `tvcv`.`id`
                END';
            }

            $filterWhere = '';

            if (!empty($this->_filters['where'])) {
                $filterWhere .= "\n" . 'AND ' . $this->_filters['where'];
            }

            if ($this->filterWhere) {
                if ($this->getCFGDef('filterPriceId', '') && $this->getCFGDef('filterCurrencyId', '')) {
                    $this->filterWhere .= ' OR `tvcv`.`tmplvarid`=' . $this->getCFGDef('filterPriceId', '');
                }

                $filterWhere .= "\n" . 'AND (' . $this->filterWhere . ')';
            }

            $sql = '
                SELECT
                `tvcv`.`tmplvarid`,
                `tvcv`.`value`,
                COUNT(DISTINCT `c`.`id`) AS `count`
                FROM ' . $this->getTable('site_tmplvar_contentvalues', 'tvcv') . '
                LEFT JOIN ' . $this->getTable('site_content', 'c') . ' ON `c`.`id`=`tvcv`.`contentid`
                LEFT JOIN ' . $this->getTable('site_tmplvars', 'filter_tv') . ' ON `filter_tv`.`id`=`tvcv`.`tmplvarid`
                ' . implode("\n", $join) . '
                WHERE
                `c`.`parent` IN (' . $sanitarInIDs . ')
                AND `c`.`published`=1 
                AND `c`.`deleted`=0
                ' . $filterWhere . '
                AND ((' . implode(' AND ', $whereRequest) . ') AND (' . implode(' OR ', $where) . '))
                GROUP BY ' . $group . '
            ';

//            print_r('<pre>');
//            print_r($sql);
//            print_r('</pre>');

            $this->dbQuery('SET sql_mode = "";');

            $rs = $this->dbQuery($sql);

            while ($row = $this->modx->db->getRow($rs)) {
                $k = $row['tmplvarid'];

                if (isset($this->f[$k])) {
                    switch ($this->f[$k]['type']) {
                        case 'range':
                        case 'number':
                            $this->f[$k]['count'] = $row['count'];
                            break;

                        default:
                            $values = explode('||', $row['value']);
                            foreach ($values as $value) {
                                if (isset($this->f[$k]['values'][$value])) {
                                    $this->f[$k]['values'][$value]['count'] += $row['count'];
                                }
                            }
                            break;
                    }
                }
            }
        }
    }
}
