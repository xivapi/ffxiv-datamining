<?php

/**
 * Libra Database
 *
 * @version 1.0
 * @author Josh Freeman <josh@viion.co.uk>
 */

use LiteDatabase,
    LibraDatabaseTraits;

class LibraDatabase
{
    use LibraDatabaseTraits;

    protected $database;

    function __construct($litefile)
    {
        if (!$litefile) {
            return false;
        }

        $this->database = new LiteDatabase($litefile);
    }

    /**
     * Get this
     *
     * @return $this;
     */
    public function get()
    {
        return $this;
    }

    /**
     * Get the tables from the libra database
     *
     * @return Array - tables
     */
    public function tables()
    {
        $tables = $this->database->sql("SELECT name FROM sqlite_master WHERE type = 'table'");
        $tables = $this->sortDataByColumn($tables, 'name');
        return $tables;
    }

    /**
     * Run SQL on the libra database
     *
     * @param $string - the sql string to run
     * @return Array - array of data
     */
    public function sql($sql)
    {
        return $this->database->sql($sql);
    }
}