<?php

namespace PHPixieTests\ORM\Functional;

abstract class RelationshipTest extends \PHPixieTests\ORM\FunctionalTest
{
    protected $testCases = array('sql', 'multiSql', 'mongo');
    
    protected function runSqlTest($methodName)
    {
        $this->database = $this->database();
        $this->orm = $this->orm();
        
        $this->prepareSqlDatabase();
        $this->$methodName();
    }
    
    protected function runMultiSqlTest($methodName)
    {
        $this->databaseConfigData['second'] = array(
            'driver' => 'pdo',
            'connection' => 'sqlite::memory:'
        );

        $this->ormConfigData['models'] = array(
            'flower' => array(
                'connection' => 'second'
            )
        );

        $this->database = $this->database();
        $this->orm = $this->orm();
        
        $this->prepareSqlDatabase(true);
        $this->$methodName();
        
        unset($this->databaseConfigData['second']);
        unset($this->ormConfigData['models']);
    }
    
    protected function runMongoTest($methodName)
    {
        $default = $this->databaseConfigData['default'];
        
        $this->databaseConfigData['default'] = array(
            'driver'   => 'mongo',
            'database' => 'phpixieormtest',
            'user'     => 'pixie',
            'password' => 'pixie',
        );

        $this->database = $this->database();
        $this->orm = $this->orm();
        $this->prepareMongoDatabase();
        
        $this->$methodName();
        
        $this->databaseConfigData['default'] = $default;
    }

    
    abstract protected function prepareSqlDatabase($multipleConnections = false);
    abstract protected function prepareMongoDatabase();   
}