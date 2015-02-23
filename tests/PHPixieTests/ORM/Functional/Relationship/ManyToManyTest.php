<?php

namespace PHPixieTests\ORM\Functional\Relationship;

class ManyToTest extends \PHPixieTests\ORM\Functional\RelationshipTest
{
    public function setUp()
    {
        $this->ormConfigData = array(
            'relationships' => array(
                array(
                    'type'  => 'manyToMany',
                    'left'  => 'fairy',
                    'right' => 'flower'
                )
            )
        );
        
        parent::setUp();
    }

    public function testItemsConditions()
    {
        $this->prepareEntities();
        
        $this->assertNames(
            array('Trixie', 'Blum'),
            $this->orm->get('fairy')->query()
                ->relatedTo('flowers', function($b) {
                    $b->and('name', 'Red');
                })
                ->find()->asArray()
        );
        
        
        $this->assertNames(
            array('Pixie'),
            $this->orm->get('fairy')->query()
                ->notRelatedTo('flowers', function($b) {
                    $b->and('name', 'Red');
                })
                ->find()->asArray()
        );
        
        $this->assertNames(
            array('Blum'),
            $this->orm->get('fairy')->query()
                ->where('flowers'.'.name', 'Yellow')
                ->find()->asArray()
        );
        
        $this->assertNames(
            array('Trixie', 'Blum'),
            $this->orm->get('fairy')->query()
                ->relatedTo('flowers')
                ->find()->asArray()
        );
                
        $this->assertNames(
            array('Pixie'),
            $this->orm->get('fairy')->query()
                ->notRelatedTo('flowers')
                ->find()->asArray()
        );
        
        $red = $this->orm->get('flower')->query()->findOne();
        $this->assertNames(
            array('Trixie', 'Blum'),
            $this->orm->get('fairy')->query()
                ->relatedTo('flowers', $red)
                ->find()->asArray()
        );

    }

    public function testMultipleItemsCondtions()
    {
        $this->prepareEntities();
        
        $red = $this->orm->get('flower')->query()
            ->where('name', 'Red')
            ->findOne();
        
        $green = $this->orm->get('flower')->query()
            ->where('name', 'Green')
            ->findOne();
        
        $yellow = $this->orm->get('flower')->query()
            ->where('name', 'Yellow')
            ->findOne();

        $this->assertNames(
            array('Trixie', 'Blum'),
            $this->orm->get('fairy')->query()
                ->relatedTo('flowers', $red)
                ->relatedTo('flowers', $green)
                ->find()->asArray()
        );
        
        $this->assertNames(
            array('Blum'),
            $this->orm->get('fairy')->query()
                ->relatedTo('flowers', $red)
                ->relatedTo('flowers', $green)
                ->relatedTo('flowers', $yellow)
                ->find()->asArray()
        );
        
        $this->assertNames(
            array('Blum', 'Pixie'),
            $this->orm->get('fairy')->query()
                ->relatedTo('flowers', $yellow)
                ->orNotRelatedTo('flowers')
                ->find()->asArray()
        );

    }

    
    public function testLoadItems()
    {
        list($map, $flowerMap) = $this->prepareEntities();
        
        $fairies = $this->orm->get('fairy')->query()
            ->find()->asArray();
        
        foreach($fairies as $fairy) {
            $this->assertNames($flowerMap[$fairy->name], $fairy->flowers()->asArray());
        }
    }
    
    public function testPreloadItems()
    {
        list($map, $flowerMap) = $this->prepareEntities();
        
        $fairies = $this->orm->get('fairy')->query()
            ->find(array('flowers'))->asArray();
        
        foreach($fairies as $fairy) {
            $this->assertEquals(true, $fairy->flowers->isLoaded());
            $this->assertNames($flowerMap[$fairy->name], $fairy->flowers()->asArray());
        }
    }
    
    public function testAddItems()
    {
        list($trixie, $blum, $red, $green) = $this->getEntities();
        
        $trixie->flowers();
        $blum->flowers();
        $red->fairies();
        $green->fairies();
        
        $trixie->flowers->add($red);
        $blum->flowers->add($red);

        $this->assertSame(array($red), $trixie->flowers()->asArray());
        $this->assertSame(array($red), $blum->flowers()->asArray());
        $this->assertSame(array($trixie, $blum), $red->fairies()->asArray());

        $this->assertPivot(array(
            array(1, 1),
            array(2, 1)
        ));
            
        for($i=0;$i<2;$i++) {
            $green->fairies->add($trixie);
            $green->fairies->add($blum);
            
            $this->assertSame(array($red, $green), $trixie->flowers()->asArray());
            $this->assertSame(array($red, $green), $blum->flowers()->asArray());
            $this->assertSame(array($trixie, $blum), $green->fairies()->asArray());
            $this->assertSame(array($trixie, $blum), $red->fairies()->asArray());

            $this->assertPivot(array(
                array(1, 1),
                array(2, 1),
                array(1, 2),
                array(2, 2),
            ));
        }
    }
    
    public function testRemoveItems()
    {
        list($trixie, $blum, $red, $green) = $this->getEntities();
        
        $trixie->flowers();
        $blum->flowers();
        $red->fairies();
        $green->fairies();
        
        $trixie->flowers->add($red);
        $blum->flowers->add($red);
        $green->fairies->add($trixie);
        $green->fairies->add($blum);

        $blum->flowers->remove($green);
        $red->fairies->remove($trixie);
        
        $this->assertSame(array($green), $trixie->flowers()->asArray());
        $this->assertSame(array($red), $blum->flowers()->asArray());
        $this->assertSame(array($blum), $red->fairies()->asArray());
        $this->assertSame(array($trixie), $green->fairies()->asArray());

        $this->assertPivot(array(
            array(2, 1),
            array(1, 2)
        ));
        
        $blum->flowers->removeAll();
        
        $this->assertSame(array(), $blum->flowers()->asArray());
        $this->assertSame(array(), $red->fairies()->asArray());  
        $this->assertPivot(array(
            array(1, 2)
        ));
        
        $green->fairies->removeAll();
        
        $this->assertSame(array(), $trixie->flowers()->asArray());
        $this->assertSame(array(), $green->fairies()->asArray());
        $this->assertPivot(array(
            
        ));
        
    }
    
    protected function getEntities()
    {
        $entities = array();
        
        $entities[] = $this->createEntity('fairy', array(
            'name' => 'Trixie'
        ));
        
        $entities[] = $this->createEntity('fairy', array(
            'name' => 'Blum'
        ));
        
        $entities[] = $this->createEntity('flower', array(
            'name' => 'Red'
        ));
        
        $entities[] = $this->createEntity('flower', array(
            'name' => 'Green'
        ));
        
        return $entities;
    }
    
    protected function prepareEntities($addWithoutOwner = true)
    {
        $map = new \SplObjectStorage();
        $map->offsetSet(new \ArrayObject(array(
            'Trixie', 'Blum'
        )), array(
            'Red', 'Green'
        ));
        
        $map->offsetSet(new \ArrayObject(array(
            'Blum'
        )), array(
            'Yellow'
        ));
        
        $map->offsetSet(new \ArrayObject(array(
            'Pixie'
        )), array(
            
        ));
        
        $map->offsetSet(new \ArrayObject(array(
            
        )), array(
            'Purple'
        ));
        
        $instances = array();
        $flowerMap = array();
        
        foreach($map as $fairyNames) {
            $fairies = array();
            foreach($fairyNames as $name) {
                if(!array_key_exists($name, $flowerMap)) {
                    $flowerMap[$name] = array();
                }
                
                $flowerMap[$name] = array_merge($flowerMap[$name], $map[$fairyNames]);
                
                if(!array_key_exists($name, $instances)) {
                    $instances[$name] = $this->createEntity('fairy', array(
                        'name' => $name
                    ));
                }
                
                $fairies[] = $instances[$name];
            }
            
            foreach($map[$fairyNames] as $name) {
                if(!array_key_exists($name, $instances)) {
                    $instances[$name] = $this->createEntity('flower', array(
                        'name' => $name
                    ));
                }
                
                $instances[$name]->fairies->add($fairies);
            }
        }
        
        return array($map, $flowerMap);

    }
    
    protected function assertPivot($data)
    {
        $pivotData = $this->database->get()
                        ->selectQuery()
                        ->table('fairies_flowers')
                        ->execute()->asArray();
        
        $this->assertEquals(count($data), count($pivotData));
        
        foreach($pivotData as $key => $row) {
            $this->assertEquals(array(
                'fairy_id' => $data[$key][0],
                'flower_id' => $data[$key][1]
            ), (array) $row);
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
              name VARCHAR(255)
            )
        ');
        
        $connection->execute('
            CREATE TABLE fairies_flowers (
              fairy_id INTEGER,
              flower_id INTEGER
            )
        ');
    }
}