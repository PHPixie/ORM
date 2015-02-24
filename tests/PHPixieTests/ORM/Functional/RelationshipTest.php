<?php

namespace PHPixieTests\ORM\Functional;

abstract class RelationshipTest extends \PHPixieTests\ORM\FunctionalTest
{
    protected function runTests($name)
    {
        $method = $name.'Test';
        //$this->$method();
        /*
        $this->createSQLDatabase();
        
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
        $this->createSQLDatabase(true);
        
        unset($this->databaseConfigData['second']);
        unset($this->ormConfigData['models']);
        */
        $this->databaseConfigData['default'] = array(
            'driver'   => 'mongo',
            'database' => 'phpixieormtest',
            'user' => 'pixie',
            'password' => 'pixie',
        );
        
        $this->database = $this->database();
        $this->orm = $this->orm();
        $this->prepareMongoDatabase();
        
        $this->$method();
        
    }
    
    abstract protected function prepareSQLDatabase($multipleConnections = false);
    abstract protected function prepareMongoDatabase();   
}