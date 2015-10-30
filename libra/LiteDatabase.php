<?php

/**
 * Libra Database
 *
 * @version 1.0
 * @author Josh Freeman <josh@viion.co.uk>
 */

use \PDO;

class LiteDatabase
{
    protected $connection;

    function __construct($sqlite)
    {
        if (!file_exists($sqlite)) {
            return false;
        }

        // create connection
        $this->connection = new PDO('sqlite:'. $sqlite);
        if (!$this->connection) {
            return false;
        }
    }

    private function track($type)
    {
        global $tracking;
        $tracking->update($type);
    }

    public function sql($sql)
    {
        if (!$this->connection) {
            return false;
        }

        $query = $this->connection->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }
}