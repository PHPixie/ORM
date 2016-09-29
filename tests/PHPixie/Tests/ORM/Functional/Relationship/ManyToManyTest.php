<?php

namespace PHPixie\Tests\ORM\Functional\Relationship;

class ManyToTest extends \PHPixie\Tests\ORM\Functional\RelationshipTest
{
    protected $defaultORMConfig = array(
        'relationships' => array(
            array(
                'type'  => 'manyToMany',
                'left'  => 'fairy',
                'right' => 'flower'
            )
        )
    );
    
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
    
    public function testPreloadConditionalItems()
    {
        $this->runTests('preloadConditionalItems');
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
            $this->orm->repository('fairy')->query()
                ->relatedTo('flowers', function($b) {
                    $b->and('name', 'Red');
                })
                ->find()->asArray()
        );
        
        
        $this->assertNames(
            array('Pixie'),
            $this->orm->repository('fairy')->query()
                ->notRelatedTo('flowers', function($b) {
                    $b->and('name', 'Red');
                })
                ->find()->asArray()
        );
        
        $this->assertNames(
            array('Blum'),
            $this->orm->repository('fairy')->query()
                ->where('flowers'.'.name', 'Yellow')
                ->find()->asArray()
        );
        
        $this->assertNames(
            array('Trixie', 'Blum'),
            $this->orm->repository('fairy')->query()
                ->relatedTo('flowers')
                ->find()->asArray()
        );
                
        $this->assertNames(
            array('Pixie'),
            $this->orm->repository('fairy')->query()
                ->notRelatedTo('flowers')
                ->find()->asArray()
        );
        
        $red = $this->orm->repository('flower')->query()->findOne();
        $this->assertNames(
            array('Trixie', 'Blum'),
            $this->orm->repository('fairy')->query()
                ->relatedTo('flowers', $red)
                ->find()->asArray()
        );
        
        $this->assertNames(
            array('Trixie', 'Blum'),
            $this->orm->repository('fairy')->query()
                ->relatedTo('flowers', $red->id())
                ->find()->asArray()
        );

    }

    protected function multipleItemsCondtionsTest()
    {
        $this->prepareEntities();
        
        $red = $this->orm->repository('flower')->query()
            ->where('name', 'Red')
            ->findOne();
        
        $green = $this->orm->repository('flower')->query()
            ->where('name', 'Green')
            ->findOne();
        
        $yellow = $this->orm->repository('flower')->query()
            ->where('name', 'Yellow')
            ->findOne();

        $this->assertNames(
            array('Trixie', 'Blum'),
            $this->orm->repository('fairy')->query()
                ->relatedTo('flowers', $red)
                ->relatedTo('flowers', $green)
                ->find()->asArray()
        );
        
        $this->assertNames(
            array('Blum'),
            $this->orm->repository('fairy')->query()
                ->relatedTo('flowers', $red)
                ->relatedTo('flowers', $green->id())
                ->relatedTo('flowers', $yellow)
                ->find()->asArray()
        );
        
        $this->assertNames(
            array('Blum', 'Pixie'),
            $this->orm->repository('fairy')->query()
                ->relatedTo('flowers', $yellow)
                ->orNotRelatedTo('flowers')
                ->find()->asArray()
        );

    }

    
    protected function loadItemsTest()
    {
        list($map, $flowerMap, $instances) = $this->prepareEntities();
        
        $fairies = $this->orm->repository('fairy')->query()
            ->find()->asArray();
        
        foreach($fairies as $fairy) {
            $this->assertNames($flowerMap[$fairy->name], $fairy->flowers()->asArray());
        }
    }
    
    protected function preloadItemsTest()
    {
        list($map, $flowerMap, $instances) = $this->prepareEntities();
        
        $fairies = $this->orm->repository('fairy')->query()
            ->find(array('flowers'))->asArray();
        
        foreach($fairies as $fairy) {
            $this->assertEquals(true, $fairy->flowers->isLoaded());
            $this->assertNames($flowerMap[$fairy->name], $fairy->flowers()->asArray());
        }
    }
    
    protected function preloadConditionalItemsTest()
    {
        list($map, $flowerMap, $instances) = $this->prepareEntities();
        
        $fairies = $this->orm->repository('fairy')->query()
            ->find(array(
                'flowers' => array(
                    'queryCallback' => function($query) {
                        $query->where('name', 'Red');
                    }
                )
            ))->asArray();
        
        foreach($fairies as $fairy) {
            $this->assertEquals(true, $fairy->flowers->isLoaded());
            $expect = array();
            if(in_array($fairy->name, array('Trixie', 'Blum'))) {
                $expect = array('Red');
            }
            $this->assertNames($expect, $fairy->flowers()->asArray());
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
        
        $pivot = array(
            array($trixie->id(), $red->id()),
            array($blum->id(), $red->id()),
            array($trixie->id(), $green->id()),
            array($blum->id(), $green->id())
        );
        
        for($i=0;$i<2;$i++) {
            $green->fairies->add($trixie);
            $green->fairies->add($blum);
            
            $this->assertSame(array($red, $green), $trixie->flowers()->asArray());
            $this->assertSame(array($red, $green), $blum->flowers()->asArray());
            $this->assertSame(array($trixie, $blum), $green->fairies()->asArray());
            $this->assertSame(array($trixie, $blum), $red->fairies()->asArray());

            $this->assertPivot($pivot);
        }
        
        $green->fairies->add(
            $this->query('fairy')->in($trixie)
        );
        
        $this->assertSame(false, $green->fairies->isLoaded());
        $this->assertSame(true, $blum->flowers->isLoaded());
        
        $blum->flowers->add(
            $green->id()
        );
        
        $this->assertSame(false, $blum->flowers->isLoaded());
        
        $this->assertPivot($pivot);
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
        
        $trixie->flowers->add($red);
        $blum->flowers->add($green);
        
        $trixie->flowers->remove(
            $this->query('flower')->in($green->id())
        );
        
        $this->assertSame(false, $trixie->flowers->isLoaded());
        
        $blum->flowers->remove(
            $green->id()
        );
        
        $this->assertSame(false, $blum->flowers->isLoaded());
        
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
        
        $this->orm->repository('fairy')->query()
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
                        ->$setSourceMethod('fairiesFlowers')
                        ->execute()->asArray();
        
        $this->assertEquals(count($data), count($pivotData));
        
        foreach($pivotData as $key => $row) {
            $this->assertEquals($data[$key][0], $row->fairyId);
            $this->assertEquals($data[$key][1], $row->flowerId);
        }
    }
    
    protected function runTests($name)
    {
        $this->runTestCases($name, array(
            'sqlite',
            //'multiSql',
            //'mysql',
            //'mongo',
        ));
    }
    
    protected function prepareSqlite()
    {
        $this->prepareSqliteDatabase();
        $this->prepareSqliteTables();
        $this->prepareOrm();
    }
    
    protected function prepareMultiSql()
    {
        $this->prepareSqliteDatabase(true);
        $this->prepareSqliteTables(true);
        
        $this->prepareOrm(array(
            'models' => array(
                'flower' => array(
                    'connection' => 'second'
                )
            )
        ));
    }
    
    protected function prepareMysql()
    {
        $this->prepareMysqlDatabase();
        
        $connection = $this->database->get('default');
        
        $connection->execute('
            DROP TABLE IF EXISTS fairies
        ');
        
        $connection->execute('
            CREATE TABLE fairies (
              id INTEGER PRIMARY KEY AUTO_INCREMENT,
              name VARCHAR(255)
            )
        ');
        
        $connection->execute('
            DROP TABLE IF EXISTS fairiesFlowers
        ');
        
        $connection->execute('
            CREATE TABLE fairiesFlowers (
              fairyId INTEGER,
              flowerId INTEGER
            )
        ');
        
        $connection->execute('
            DROP TABLE IF EXISTS flowers
        ');
        
        $connection->execute('
            CREATE TABLE flowers (
              id INTEGER PRIMARY KEY AUTO_INCREMENT,
              name VARCHAR(255)
            )
        ');
        
        $this->prepareOrm();
    }
    
    protected function prepareMongo()
    {
        $this->prepareMongoDatabase();

        $connection = $this->database->get('default');
        $collections = array('fairies', 'flowers', 'fairiesFlowers');
        
        foreach($collections as $collection) {
            $connection->deleteQuery()
                        ->collection($collection)
                        ->execute();
        }
        
        $this->prepareOrm();
    }
    
    protected function prepareSqliteTables($multipleConnections = false)
    {
        $connection = $this->database->get('default');
        $connection->execute('
            CREATE TABLE fairies (
              id INTEGER PRIMARY KEY,
              name VARCHAR(255)
            )
        ');
        
        $connection->execute('
            CREATE TABLE fairiesFlowers (
              fairyId INTEGER,
              flowerId INTEGER
            )
        ');
        
        if($multipleConnections) {
            $connection = $this->database->get('second');
        }
        
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
}