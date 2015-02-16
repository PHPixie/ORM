<?php

namespace PHPixie\ORM;

class Database
{
    protected $database;
    
    public function __construct($database)
    {
        $this->database = $database;
    }
    
    public function connection($name)
    {
        return $this->database->get($name);
    }

    public function connectionDriverName($connectionName)
    {
        return $this->database->connectionDriverName($connectionName);
    }
}