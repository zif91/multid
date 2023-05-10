<?php
require_once 'tv.filter.php';

/**
 * Filters DocLister results by value like of a given MODx Template Variables (TVs).
 * @author 64j
 */
class filter_like_DL_filter extends tv_DL_filter
{
    /**
     * @return string
     */
    public function get_join()
    {
        $join = '';
        $exists = $this->DocLister->checkTableAlias($this->tvName, $this->extTV->tvValuesTable());
        $alias = $this->DocLister->TableAlias($this->tvName, $this->extTV->tvValuesTable(), $this->getTableAlias());
        $filterDelimiter = $this->DocLister->getCFGDef('filterDelimiter', '||');
        $this->field = 'CONCAT("' . $filterDelimiter . '",`' . $alias . '`.`value`, "' . $filterDelimiter . '")';
        $this->value = str_replace('&#59', ';', $this->value);
        if (!$exists) {
            $join = 'LEFT JOIN ' . $this->DocLister->getTable(
                    $this->extTV->tvValuesTable(),
                    $alias
                ) . ' ON `' . $alias . '`.`contentid`=`' . content_DL_filter::TableAlias . '`.`id` AND `' . $alias . '`.`tmplvarid`=' . $this->tv_id;
        } else {
            $this->setTableAlias($alias);
        }

        return $join;
    }
}