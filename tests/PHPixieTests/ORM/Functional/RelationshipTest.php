<?php

namespace PHPixieTests\ORM\Functional;

abstract class RelationshipTest extends \PHPixieTests\ORM\FunctionalTest
{
    protected function runTests($name)
    {
        $method = $name.'Test';
        //$this->$method();
        
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
        $this->createDatabase(true);
        
        $this->$method();
        
    }
    
}