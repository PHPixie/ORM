<?php

namespace PHPixieTests\ORM\Functional;

class ModelTest extends \PHPixieTests\ORM\FunctionalTest
{
    public function testCreate()
    {
        $fairy = $this->createEntity('fairy', array(
            'name' => 'Trixie'
        ));
        
        $data = array(
            'id' => 1,
            'name' => 'Trixie'
        );
        
        $this->assertData('fairy', array(
            $data
        ));
    }
    
    public function testFind()
    {
        $names = array('Trixie', 'Blum', 'Pixie');
        
        foreach($names as $name) {
            $this->createEntity('fairy', array(
                'name' => $name
            ));
        }
        
        $fairyRepository = $this->orm->get('fairy');
        
        $this->assertFairyNames(
            array('Trixie', 'Blum', 'Pixie'),
            $fairyRepository->query()
                ->find()->asArray()
        );
        
        $this->assertFairyNames(
            array('Trixie'),
            $fairyRepository->query()
                ->limit(1)
                ->find()->asArray()
        );
        
        $this->assertFairyNames(
            array('Blum', 'Pixie'),
            $fairyRepository->query()
                ->offset(1)
                ->limit(2)
                ->find()->asArray()
        );
        
        $this->assertFairyNames(
            array('Blum', 'Pixie', 'Trixie'),
            $fairyRepository->query()
                ->orderAscendingBy('name')
                ->find()->asArray()
        );
        
        $this->assertFairyNames(
            array('Pixie', 'Blum'),
            $fairyRepository->query()
                ->orderDescendingBy('name')
                ->offset(1)
                ->find()->asArray()
        );
    }
    
    public function testUpdate()
    {
        $fairy = $this->createEntity('fairy', array(
            'name' => 'Trixie'
        ));
        
        $fairy->name ='Blum';
        $fairy->save();
        
        $this->assertData('fairy', array(
            array( 'id' => 1, 'name' => 'Blum')
        ));
    }
    
    public function testDelete()
    {
        $fairy = $this->createEntity('fairy', array(
            'name' => 'Trixie'
        ));
        
        $this->createEntity('fairy', array(
            'name' => 'Blum'
        ));
        
        $fairy->delete();
        
        $this->assertData('fairy', array(
            array( 'id' => 2, 'name' => 'Blum')
        ));
    }
    
    protected function assertFairyNames($names, $fairies)
    {
        $data = array();
        foreach($names as $name) {
            $data[] = array('name' => $name);
        }
        
        $this->assertEntities($data, $fairies);
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
    }
}