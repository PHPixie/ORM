<?php

namespace PHPixie\ORM\Models\Type\Database;

interface Repository extends \PHPixie\ORM\Models\Repository
{
    public function connectionName();
    public function connection();
    public function idField();
    
    public function databaseSelectQuery();
    public function databaseUpdateQuery();
    public function databaseDeleteQuery();
    public function databaseInsertQuery();
    public function databaseCountQuery();
}
