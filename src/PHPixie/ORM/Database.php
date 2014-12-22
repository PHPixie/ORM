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
        return $this->database->connection($name);
    }

    public function subdocumentCondition()
    {
        return $this->database->subdocumentCondition();
    }
    
    public function connectionDriverName($connectionName)
    {
        return $this->database->subdocumentCondition();
    }
}