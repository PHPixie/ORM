<?php

namespace PHPixieTests\ORM\Functional;

abstract class ModelTest extends \PHPixieTests\ORM\FunctionalTest
{
    protected $repository;
    
    public function setUp()
    {
        parent::setUp();
        $this->repository = $this->orm->get('fairy');
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
        //$this->prepareSQLDatabase();
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
    
    protected function prepareSQLDatabase()
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