<?php

namespace PHPixieTests\ORM\Functional;

abstract class ModelTest extends \PHPixieTests\ORM\FunctionalTest
{
    protected $testCases = array('sql', 'mongo');
    protected $repository;
    
    public function setUp()
    {
        parent::setUp();
        $this->repository = $this->orm->repository('fairy');
    }
    
    protected function createFairies($names)
    {
        $fairies = array();
        
        foreach($names as $name) {
            $fairies[] = $this->createEntity('fairy', array(
                'name' => $name
            ));
        }
        
        return $fairies;
    }
    
    protected function assertFairyNames($names, $fairies)
    {
        $data = array();
        foreach($names as $name) {
            $data[] = array('name' => $name);
        }
        
        $this->assertEntities($data, $fairies);
    }
    
    protected function runTests($name)
    {
        $this->prepareSqlDatabase();
        $method = $name.'Test';
        ///$this->$method();
        
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
    
    protected function runSqlTest($methodName) {
        $this->database = $this->database();
        $this->orm = $this->orm();
        
        $this->prepareSqlDatabase();
        $this->$methodName();
    }
    
    protected function runMongoTest($methodName) {
        $default = $this->databaseConfigData['default'];
        $this->databaseConfigData['default'] = array(
            'driver'   => 'mongo',
            'database' => 'phpixieormtest',
            'user' => 'pixie',
            'password' => 'pixie',
        );
        $this->database = $this->database();
        $this->orm = $this->orm();
        
        $this->prepareMongoDatabase();
        $this->$methodName();
        
        $this->databaseConfigData['default'] = $default;
    }

    protected function prepareSqlDatabase()
    {
        $connection = $this->database->get('default');
        $connection->execute('
            CREATE TABLE fairies (
              id INTEGER PRIMARY KEY,
              name VARCHAR(255)
            )
        ');
    }
    
    protected function prepareMongoDatabase()
    {
        $connection = $this->database->get('default');
        $connection->deleteQuery()
                        ->collection('fairies')
                        ->execute();
    }
}