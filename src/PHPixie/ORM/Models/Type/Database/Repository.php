<?php

namespace PHPixie\ORM\Models\Type\Database;

use PHPixie\ORM\Models\Type\Database\Implementation\Query;

interface Repository
{
    public function config();
    public function modelName();
    public function save($entity);
    public function delete($entity);
    public function load($data);
    public function create();

    /**
     * @return Query
     */
    public function query();
    public function connection();
    public function databaseSelectQuery();
    public function databaseUpdateQuery();
    public function databaseDeleteQuery();
    public function databaseInsertQuery();
    public function databaseCountQuery();
}
