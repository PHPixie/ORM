<?php

namespace PHPixie\Tests\ORM\Functional;

abstract class Testcase extends \PHPixie\Test\Testcase
{
    protected $databaseConfigData = array();
    protected $ormConfigData = array();
    
    protected $slice;
    protected $database;
    protected $wrappers;
    
    protected $orm;
    
    public function setUp()
    {
        $this->slice = new \PHPixie\Slice();
    }
    
    protected function runTestCases($name, $cases)
    {
        $method = $name.'Test';
        
        foreach($cases as $case) {
            $this->cleanUp();
            $prepareMethod = 'prepare'.ucfirst($case);
            $this->$prepareMethod();
            $this->$method();
        }
    }

    protected function createEntity($name, $data = array(), $save = true)
    {
        $entity = $this->orm->repository($name)->create();
        foreach($data as $field => $value) {
            $entity->$field = $value;
        }
        
        if($save) {
            $entity->save();
        }
        
        return $entity;
    }
    
    protected function query($name)
    {
       return $this->orm->query($name); 
    }
    
    protected function assertEntities($data, $entities, $idField = null)
    {
        $this->assertSame(count($data), count($entities));
        
        foreach($entities as $key => $entity) {
            $this->assertEntity($entity, $data[$key], $idField);
        }
    }
    
    protected function assertData($modelName, $expect)
    {
        $entities = $this->orm->repository($modelName)->query()
            ->find()
            ->asArray(true);
        
        $data = array();

        foreach($entities as $key => $entity) {
            $row = array();
            foreach(array_keys($expect[$key]) as $field) {
                $row[$field] = $entity->$field;
            }

            $data[]= $row;
        }

        $this->assertEquals($expect, $data);
    }
    
    protected function assertDataAsObject($modelName, $data)
    {
        $entityData = $this->orm->repository($modelName)->query()
                        ->find()
                        ->asArray(true);
        
        $this->assertEquals($data, $entityData);
    }
    
    protected function assertEntity($entity, $data, $idField = null)
    {
        if($idField) {
            $id = $data[$idField];
            $this->assertEquals($id, $entity->id());
        }
        
        foreach($data as $field => $value) {
            $this->assertEquals($value, $entity->$field);
        }
    }
    
    protected function assertNames($names, $entities)
    {
        $result = array();
        foreach($entities as $entity) {
            $result[]= $entity->name;
        }

        sort($names);
        sort($result);

        $this->assertEquals($names, $result);
    }
    
    protected function idField($modelName)
    {
        return $this->orm->repository($modelName)->config()->idField;
    }
    
    protected function database()
    {
        $dbConfig = $this->slice->arrayData($this->databaseConfigData);
        return new \PHPixie\Database($dbConfig);
    }
    
    protected function orm()
    {
        $ormConfig = $this->slice->arrayData($this->ormConfigData);
        return new \PHPixie\ORM(
            $this->database,
            $ormConfig,
            $this->wrappers
        );
    }
    

    protected function prepareMysqlDatabase()
    {
        $this->databaseConfigData = array(
            'default' => array(
                'driver' => 'pdo',
                'connection' => 'mysql:host=localhost;dbname=phpixieormtest',
                'user'     => 'pixie',
                'password' => 'pixie'
            )
        );
        
        $this->database = $this->database();
    }
    
    protected function prepareSqliteDatabase($multipleConnections = false)
    {
        $this->databaseConfigData = array(
            'default' => array(
                'driver' => 'pdo',
                'connection' => 'sqlite::memory:'
            )
        );
        
        if($multipleConnections) {
            $this->databaseConfigData['second'] = array(
                'driver' => 'pdo',
                'connection' => 'sqlite::memory:'
            );
        }

        $this->database = $this->database();
    }
    
    protected function prepareMongoDatabase()
    {
        $this->databaseConfigData['default'] = array(
            'driver'   => 'mongo',
            'database' => 'phpixieormtest',
            'user'     => 'pixie',
            'password' => 'pixie',
        );

        $this->database = $this->database();
    }
    
    protected function cleanUp()
    {
    
    }
}