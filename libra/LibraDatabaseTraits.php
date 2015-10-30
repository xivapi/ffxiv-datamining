<?php

/**
 * Libra Database Traits
 * bunch of functions for libra
 *
 * @version 1.0
 * @author Josh Freeman <josh@viion.co.uk>
 */

trait LibraDatabaseTraits
{
    /**
     * Sorts an array by a column within the array,
     * main use is databases results
     *
     * @param $data - the data to resort
     * @param $column - the column to use for sorting
     * @return Array - the new array!
     */
    protected function sortDataByColumn($data, $column)
    {
        $newData = [];
        foreach($data as $k => $v)
        {
            $newKey = $v[$column];
            $newData[$newKey] = $this->removeNumericIndexes($v);
        }

        ksort($newData);
        return $newData;
    }

    /**
     * Removes numeric indexes, for some reason sqlite
     * returns both table column names and an index list,
     * so this helps strip index's and reduce data duplication
     *
     * @param $data - the array to remove from
     * @return Array - the new array, without numeric indexes!
     */
    protected function removeNumericIndexes($data)
    {
        foreach($data as $k => $v)
            if (is_numeric($k))
                unset($data[$k]);

        return $data;
    }
}