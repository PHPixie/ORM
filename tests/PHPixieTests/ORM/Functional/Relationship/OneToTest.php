<?php

namespace PHPixieTests\ORM\Functional\Relationship;

abstract class OneToTest extends \PHPixieTests\ORM\Functional\RelationshipTest
{
    protected $ormConfigData = array(
        'relationships' => array(
            array(
                'type' => 'oneToMany',
                'owner' => 'fairy',
                'items'  => 'flower'
            )
        )
    );
    
    public function testOneToMany()
    {
        $trixie = $this->createEntity('fairy', array(
            'name' => 'Trixie'
        ));
        
        $red = $this->createEntity('flower', array(
            'name' => 'Red'
        ));
        
        $green = $this->createEntity('flower', array(
            'name' => 'Green'
        ));

        $trixie->flowers->add($red);
        $trixie->flowers->add($green);
        
        $this->assertSame($trixie, $red->fairy());
        $this->assertSame($trixie, $green->fairy());
        
        $this->assertData('flower', array(
            array( 'id' => 1, 'name' => 'Red', 'fairy_id' => 1),
            array( 'id' => 2, 'name' => 'Green', 'fairy_id' => 1),
        ));
        
        $blum = $this->createEntity('fairy', array(
            'name' => 'Blum'
        ));
        
        $green->fairy->set($blum);
        
        $this->assertSame($trixie, $red->fairy());
        $this->assertSame($blum, $green->fairy());
        
        $this->assertData('flower', array(
            array( 'id' => 1, 'name' => 'Red', 'fairy_id' => 1),
            array( 'id' => 2, 'name' => 'Green', 'fairy_id' => 2),
        ));
        
        $red->fairy->remove();
        
        $this->assertData('flower', array(
            array( 'id' => 1, 'name' => 'Red', 'fairy_id' => null),
            array( 'id' => 2, 'name' => 'Green', 'fairy_id' => 2),
        ));
        
        $blum->delete();
        
        $this->assertData('flower', array(
            array( 'id' => 1, 'name' => 'Red', 'fairy_id' => null),
            array( 'id' => 2, 'name' => 'Green', 'fairy_id' => null),
        ));   
    }
    
    public function testPreloadItems()
    {
        $map = $this->prepareEntities();
        
        $fairies = $this->orm->get('fairy')->query()
                        ->find(array('flowers'))
                        ->asArray();
        
        $key = 0;
        foreach($map as $fairyName => $flowerNames) {
            $fairy = $fairies[$key];
            $this->assertSame($fairyName, $fairy->name);
            
            $this->assertEquals(count($flowerNames), count($fairy->flowers()->asArray()));

            foreach($fairy->flowers() as $flowerKey => $flower) {
                $this->assertSame($flowerNames[$flowerKey], $flower->name);
            }
            $key++;
        }
    }
    
    public function testPreloadOwner()
    {
        $map = $this->prepareEntities();
        
        $flowers = $this->orm->get('flower')->query()
                        ->find(array('fairy'))
                        ->asArray();
        
        $key = 0;
        foreach($map as $fairyName => $flowerNames) {
            foreach($flowerNames as $flowerName) {
                $flower = $flowers[$key];
                
                $this->assertEquals($flowerName, $flower->name);
                $this->assertEquals($fairyName, $flower->fairy()->name);
                
                $key++;
            }
        }
    }
    
    protected function prepareEntities()
    {
        $map = array(
            'Trixie' => array('Red', 'Green'),
            'Blum'   => array('Yellow'),
            'Pixie'  => array(),
        );
        
        foreach($map as $fairyName => $flowerNames) {
            $fairy = $this->createEntity('fairy', array(
                'name' => $fairyName
            ));
            
            $flowers = array();
            foreach($flowerNames as $flowerName) {
                $flowers[] = $this->createEntity('flower', array(
                    'name' => $flowerName
                ));
            }
            
            $fairy->flowers->add($flowers);
        }
        
        return $map;
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