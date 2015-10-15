<?php

namespace PHPixie\ORM;

class Database
{
    /**
     * @type \PHPixie\Database
     */
    protected $database;
    
    public function __construct($database)
    {
        $this->database = $database;
    }

    /**
     * @param $name
     * @return \PHPixie\Database\Driver\PDO\Connection|\PHPixie\Database\Driver\Mongo\Connection
     */
    public function connection($name)
    {
        return $this->database->get($name);
    }

    public function connectionDriverName($connectionName)
    {
        return $this->database->connectionDriverName($connectionName);
    }
}