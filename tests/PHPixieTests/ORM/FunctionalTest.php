<?php

namespace PHPixieTests\ORM;

class FunctionalTest extends \PHPixieTests\AbstractORMTest
{
    protected $databaseConfigData = array(
        'default' => array(
            'driver' => 'pdo',
            'connection' => 'sqlite::memory:'
        )
    );
    
    protected $ormConfigData = array(
        'models' => array(
            'fairy' => array(

            ),
            'flower' => array(

            ),
        ),
        'relationships' => array(
            array(
                'type' => 'oneToMany',
                'owner' => 'fairy',
                'items'  => 'flower'
            )
        )
    );
    protected $database;
    protected $wrappers;
    
    protected $orm;
    
    public function setUp()
    {
        $config = new \PHPixie\Config();
        
        $dbConfig = $config->dataStorage($this->databaseConfigData);
        $ormConfig = $config->dataStorage($this->ormConfigData);
        
        $this->database = new \PHPixie\Database($dbConfig);
        $this->orm = new \PHPixie\ORM(
            $this->database,
            $ormConfig,
            $this->wrappers
        );
        
        $this->createDatabase();
        
        $this->fairiesRepository = $this->orm->geT('fairy');
    }
    

    
    protected function createFairy($name, $id = null)
    {
        $data = array('name' => $name);
        if($id !== null) {
            $data['id'] = $id;
        }
        
        return $this->createEntity('fairy', $data);
    }
    
    protected function createFlower($name, $fairy_id = null, $id = null)
    {
        $data = array('name' => $name);
        foreach(array('id', 'fairy_id') as $field) {
            if($$field !== null) {
                $data[$field] = $$field;
            }
        }
        
        return $this->createEntity('flower', $data);
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
    
    protected function assertEntities($modelName, $data, $idField = 'id')
    {
        $entities = $this->orm->get($modelName)->query()
                        ->find()
                        ->asArray();
        
        $this->assertSame(count($data), count($entities));
        
        foreach($entities as $key => $entity) {
            $this->assertEntity($entity, $data[$key], $idField);
        }
    }
    
    protected function assertEntity($entity, $data, $idField = 'id')
    {
        $id = $data[$idField];
        $this->assertEquals($id, $entity->id());

        foreach($data as $field => $value) {
            $this->assertEquals($value, $entity->$field);
        }
    }
    
    protected function createDatabase()
    {
        $connection = $this->database->get('default');
        $connection->execute('
            CREATE TABLE fairies (
              id INTEGER PRIMARY KEY,
              name VARCHAR(255)
            )
        ');
        
        $connection->execute('
            CREATE TABLE flowers (
              id INTEGER PRIMARY KEY,
              name VARCHAR(255),
              fairy_id INTEGER
            )
        ');
    }

}