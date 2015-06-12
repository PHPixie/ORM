<?php

namespace PHPixie\ORM;

interface Wrappers
{
    public function databaseRepositories();
    public function databaseQueries();
    public function databaseEntities();
    public function embeddedEntities();
    
    public function databaseRepositoryWrapper($repository);
    public function databaseQueryWrapper($query);
    public function databaseEntityWrapper($entity);
    public function embeddedEntityWrapper($entity);
}