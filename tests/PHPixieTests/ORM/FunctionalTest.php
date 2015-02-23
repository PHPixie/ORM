<?php

namespace PHPixieTests\ORM;

abstract class FunctionalTest extends \PHPixieTests\AbstractORMTest
{
    protected $databaseConfigData = array(
        'default' => array(
            'driver' => 'pdo',
            'connection' => 'sqlite::memory:'
        )
    );
    
    protected $ormConfigData = array();
    
    protected $config;
    protected $database;
    protected $wrappers;
    
    protected $orm;
    
    public function setUp()
    {
        $this->config = new \PHPixie\Config();
        
        $this->database = $this->database();
        $this->orm = $this->orm();
        
        $this->createDatabase();
        
        $this->fairiesRepository = $this->orm->get('fairy');
    }
    

    protected function createEntity($name, $data)
    {
        $entity = $this->orm->get($name)->create();
        foreach($data as $field => $value) {
            $entity->$field = $value;
        }
        $entity->save();
        
        return $entity;
    }
    
    protected function assertEntities($data, $entities, $idField = null)
    {
        $this->assertSame(count($data), count($entities));
        
        foreach($entities as $key => $entity) {
            $this->assertEntity($entity, $data[$key], $idField);
        }
    }
    
    protected function assertData($modelName, $data, $idField = null)
    {
        $entities = $this->orm->get($modelName)->query()
                        ->find()
                        ->asArray();
        
        $this->assertEntities($data, $entities);
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
        $data = array();
        foreach($names as $name) {
            $data[] = array('name' => $name);
        }
        
        $this->assertEntities($data, $entities);
    }
    
    protected function database()
    {
        $dbConfig = $this->config->dataStorage($this->databaseConfigData);
        return new \PHPixie\Database($dbConfig);
    }
    
    protected function orm()
    {
        $ormConfig = $this->config->dataStorage($this->ormConfigData);
        return new \PHPixie\ORM(
            $this->database,
            $ormConfig,
            $this->wrappers
        );
    }
    
    abstract protected function createDatabase();

}