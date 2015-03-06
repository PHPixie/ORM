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
        $this->runTests('itemsConditions');
    }
    
    public function testMultipleItemsCondtions()
    {
        $this->runTests('multipleItemsCondtions');
    }
    
    public function testLoadItems()
    {
        $this->runTests('loadItems');
    }
    
    public function testPreloadItems()
    {
        $this->runTests('preloadItems');
    }
    
    public function testAddItems()
    {
        $this->runTests('addItems');
    }
    
    public function testRemoveItems()
    {
        $this->runTests('removeItems');
    }
    
    public function testCascadeDelete()
    {
        $this->runTests('cascadeDelete');
    }
    
    protected function itemsConditionsTest()
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

    protected function multipleItemsCondtionsTest()
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

    
    protected function loadItemsTest()
    {
        list($map, $flowerMap, $instances) = $this->prepareEntities();
        
        $fairies = $this->orm->get('fairy')->query()
            ->find()->asArray();
        
        foreach($fairies as $fairy) {
            $this->assertNames($flowerMap[$fairy->name], $fairy->flowers()->asArray());
        }
    }
    
    protected function preloadItemsTest()
    {
        list($map, $flowerMap, $instances) = $this->prepareEntities();
        
        $fairies = $this->orm->get('fairy')->query()
            ->find(array('flowers'))->asArray();
        
        foreach($fairies as $fairy) {
            $this->assertEquals(true, $fairy->flowers->isLoaded());
            $this->assertNames($flowerMap[$fairy->name], $fairy->flowers()->asArray());
        }
    }
    
    protected function addItemsTest()
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
            array($trixie->id(), $red->id()),
            array($blum->id(), $red->id())
        ));
            
        for($i=0;$i<2;$i++) {
            $green->fairies->add($trixie);
            $green->fairies->add($blum);
            
            $this->assertSame(array($red, $green), $trixie->flowers()->asArray());
            $this->assertSame(array($red, $green), $blum->flowers()->asArray());
            $this->assertSame(array($trixie, $blum), $green->fairies()->asArray());
            $this->assertSame(array($trixie, $blum), $red->fairies()->asArray());

            $this->assertPivot(array(
                array($trixie->id(), $red->id()),
                array($blum->id(), $red->id()),
                array($trixie->id(), $green->id()),
                array($blum->id(), $green->id())
            ));
        }
    }
    
    protected function removeItemsTest()
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
            array($blum->id(), $red->id()),
            array($trixie->id(), $green->id()),
        ));
        
        $blum->flowers->removeAll();
        
        $this->assertSame(array(), $blum->flowers()->asArray());
        $this->assertSame(array(), $red->fairies()->asArray());  
        $this->assertPivot(array(
            array($trixie->id(), $green->id()),
        ));
        
        $green->fairies->removeAll();
        
        $this->assertSame(array(), $trixie->flowers()->asArray());
        $this->assertSame(array(), $green->fairies()->asArray());
        $this->assertPivot(array(
            
        ));
        
    }
    
    protected function cascadeDeleteTest()
    {
        list($map, $flowerMap, $instances) = $this->prepareEntities();
        unset($flowerMap['Trixie']);
        
        $pivot = array();
        foreach($flowerMap as $fairyName => $flowers) {
            foreach($flowers as $flowerName) {
                $pivot[] = array($instances[$fairyName]->id(), $instances[$flowerName]->id());
            }
        }
        
        $this->orm->get('fairy')->query()
            ->where('name', 'Trixie')
            ->delete();
        
        $this->assertPivot($pivot);
        
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
        
        return array($map, $flowerMap, $instances);

    }
    
    protected function assertPivot($data)
    {
        $isMongo = $this->databaseConfigData['default']['driver'] === 'mongo';
        
        $setSourceMethod = $isMongo ? 'collection' : 'table';
        
        $pivotData = $this->database->get()
                        ->selectQuery()
                        ->$setSourceMethod('fairies_flowers')
                        ->execute()->asArray();
        
        $this->assertEquals(count($data), count($pivotData));
        
        foreach($pivotData as $key => $row) {
            $this->assertEquals($data[$key][0], $row->fairy_id);
            $this->assertEquals($data[$key][1], $row->flower_id);
        }
    }
    
    protected function prepareSqlDatabase($multipleConnections = false)
    {
        $connection = $this->database->get('default');
        $connection->execute('
            CREATE TABLE fairies (
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
        
        if($multipleConnections) {
            $connection = $this->database->get('second');
        }
        
        $connection->execute('
            CREATE TABLE flowers (
              id INTEGER PRIMARY KEY,
              name VARCHAR(255)
            )
        ');
    }
    
    protected function prepareMongoDatabase()
    {
        $connection = $this->database->get('default');
        $collections = array('fairies', 'flowers', 'fairies_flowers');
        
        foreach($collections as $collection) {
            $connection->deleteQuery()
                        ->collection($collection)
                        ->execute();
        }
    }
}