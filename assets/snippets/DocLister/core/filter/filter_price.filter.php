<?php
require_once 'tv.filter.php';

/**
 * Filters DocLister results by value price of a given MODx Template Variables (TVs).
 * @author 64j
 */
class filter_price_DL_filter extends tv_DL_filter
{
    /**
     * @return string
     */
    public function get_join(): string
    {
        $join = parent::get_join();

        $alias = $this->DocLister->TableAlias($this->tvName, $this->extTV->tvValuesTable(), $this->getTableAlias());
        $this->setTableAlias($alias);
        $exists = $this->DocLister->checkTableAlias($this->tvName, "site_tmplvars");
$this->field = 'ROUND(CAST(`' . $alias . '`.`value` AS DECIMAL(10,2)) * CAST(`' . $alias . '_currencies`.`value` AS DECIMAL(10,2)))';
        if (!$exists) {
            if (!isset($this->modx->placeholders['tvprice_filter_doclister'])) {
                $this->modx->placeholders['tvprice_filter_doclister'] = true;

                $join = '
                LEFT JOIN ' . $this->DocLister->getTable(
                        $this->extTV->tvValuesTable(),
                        $alias
                    ) . ' ON `' . $alias . '`.`contentid`=`' . content_DL_filter::TableAlias . '`.`id` AND `' . $alias . '`.`tmplvarid`=' . $this->tv_id . '
                    ';

                $join .= '
                LEFT JOIN ' . $this->DocLister->getTable(
                        $this->extTV->tvValuesTable(),
                        $alias . '_currency'
                    ) . ' ON `' . $alias . '_currency`.`contentid`=`' . content_DL_filter::TableAlias . '`.`id` AND `' . $alias . '_currency`.`tmplvarid`=13
                    ';

                $join .= '
                LEFT JOIN ' . $this->DocLister->getTable('commerce_currency', $alias . '_currencies') . ' ON `' . $alias . '_currencies`.code=IFNULL(`' . $alias . '_currency`.`value`,"RUB")
                ';
            }
        } else {
            $this->setTableAlias($alias);
        }

        return $join;
    }
}
