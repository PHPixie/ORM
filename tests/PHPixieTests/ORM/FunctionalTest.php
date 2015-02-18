<?php

namespace PHPixieTests\ORM;

class FunctionalTest extends \PHPixieTests\AbstractORMTest
{
    protected $database;
    protected $wrappers;
    
    protected $orm;
    
    protected $fairiesRepository;
    
    public function setUp()
    {
        $config = new \PHPixie\Config();
        
        $dbConfig = $config->dataStorage(array(
            'default' => array(
                'driver' => 'pdo',
                'connection' => 'sqlite::memory:'
            )
        ));
        
        $ormConfig = $config->dataStorage(array(
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
        ));
        
        $this->database = new \PHPixie\Database($dbConfig);
        $this->orm = new \PHPixie\ORM(
            $this->database,
            $ormConfig,
            $this->wrappers
        );
        
        $this->createDatabase();
        
        $this->fairiesRepository = $this->orm->geT('fairy');
    }
    
    public function testFind()
    {
        $this->createFairy('Trixie');
        
        $data = array(
            'id' => 1,
            'name' => 'Trixie'
        );
        
        $this->assertEntities('fairy', array(
            $data
        ));
        
        $fairy = $this->fairiesRepository->query()
                        ->findOne();
        
        $this->assertEntity($fairy, $data);
    }
    
    public function testUpdate()
    {
        $fairy = $this->createFairy('Trixie');
        
        $fairy->name ='Blum';
        $fairy->save();
        
        $this->assertEntities('fairy', array(
            array( 'id' => 1, 'name' => 'Blum')
        ));
    }
    
    public function testDelete()
    {
        $fairy = $this->createFairy('Trixie');
        $this->createFairy('Blum');
        
        $fairy->delete();
        
        $this->assertEntities('fairy', array(
            array( 'id' => 2, 'name' => 'Blum')
        ));
    }
    
    public function testOneToMany()
    {
        $trixie = $this->createFairy('Trixie');
        
        $red = $this->createFlower('Red');
        $green = $this->createFlower('Green');
        
        $trixie->flowers->add($red);
        $trixie->flowers->add($green);
        
        $this->assertSame($trixie, $red->fairy());
        $this->assertSame($trixie, $green->fairy());
        
        $this->assertEntities('flower', array(
            array( 'id' => 1, 'name' => 'Red', 'fairy_id' => 1),
            array( 'id' => 2, 'name' => 'Green', 'fairy_id' => 1),
        ));
        
        $blum = $this->createFairy('Blum');
        $green->fairy->set($blum);
        
        $this->assertSame($trixie, $red->fairy());
        $this->assertSame($blum, $green->fairy());
        
        $this->assertEntities('flower', array(
            array( 'id' => 1, 'name' => 'Red', 'fairy_id' => 1),
            array( 'id' => 2, 'name' => 'Green', 'fairy_id' => 2),
        ));
        
        $fairies = $this->orm->get('fairy')->query()
                        ->find(array('flowers'))
                        ->asArray();
        
        $this->assertEntity($fairies[0]->flowers()->getByOffset(0), array(
            'id' => 1
        ));
        
        $this->assertEntity($fairies[1]->flowers()->getByOffset(0), array(
            'id' => 2
        ));

        $red->fairy->remove();
        
        $this->assertEntities('flower', array(
            array( 'id' => 1, 'name' => 'Red', 'fairy_id' => null),
            array( 'id' => 2, 'name' => 'Green', 'fairy_id' => null),
        ));
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