<?php

namespace PHPixie\ORM;

/**
 * Class Database
 * @package PHPixie\ORM
 */
class Database
{
    /**
     * @type \PHPixie\Database
     */
    protected $database;

    /**
     * Database constructor.
     * @param \PHPixie\Database $database
     */
    public function __construct($database)
    {
        $this->database = $database;
    }

    /**
     * @param string $name
     * @return \PHPixie\Database\Driver\PDO\Connection|\PHPixie\Database\Driver\Mongo\Connection
     */
    public function connection($name)
    {
        return $this->database->get($name);
    }

    /**
     * @param $connectionName
     * @return \PHPixie\Database\Driver
     */
    public function connectionDriverName($connectionName)
    {
        return $this->database->connectionDriverName($connectionName);
    }
}