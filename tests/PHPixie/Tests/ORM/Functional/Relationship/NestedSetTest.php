<?php

namespace PHPixie\Tests\ORM\Functional\Relationship;

class NestedSetTest extends \PHPixie\Tests\ORM\Functional\RelationshipTest
{
    protected $relationshipName;
    protected $itemKey;
    protected $itemProperty;
    
    public function setUp()
    {
        $this->defaultORMConfig = array(
            'relationships' => array(
                array(
                    'type'  => 'nestedSet',
                    'model' => 'fairy'
                )
            )
        );
        
        parent::setUp();
    }
    
    public function testPreloadChildren()
    {
        $this->runTests('preloadChildren');
    }
    

    protected function preloadChildrenTest()
    {
        $map = $this->prepareEntities();
        
        $fairies = $this->orm->repository('fairy')->query()
            ->where('depth', 0)
            ->find(array('children'))
            ->asArray();

        $this->checkChildren($fairies, $map, null, true);
    }

    public function testLoadChildren()
    {
        $this->runTests('loadChildren');
    }

    protected function loadChildrenTest()
    {
        $map = $this->prepareEntities();

        $fairies = $this->orm->repository('fairy')->query()
            ->where('depth', 0)
            ->find()
            ->asArray();

        $this->checkChildren($fairies, $map, null);
    }

    public function testLoadParents()
    {
        $this->runTests('loadParents');
    }

    protected function loadParentsTest()
    {
        $map = $this->prepareEntities();

        $fairies = $this->orm->repository('fairy')->query()
            ->where('depth', 2)
            ->find()
            ->asArray();

        $this->checkParents($fairies, $map);
    }

    public function testPreloadParents()
    {
        $this->runTests('preloadParents');
    }

    protected function preloadParentsTest()
    {
        $map = $this->prepareEntities();

        $fairies = $this->orm->repository('fairy')->query()
            ->where('depth', 2)
            ->find(array('parent'))
            ->asArray();

        $this->checkParents($fairies, $map, true);
    }

    protected function checkChildren($fairies, $map, $expected, $assertLoaded = false)
    {
        $names = array();

        foreach($fairies as $fairy) {
            $names[]= $fairy->name;
        }

        if($expected !== null) {
            $this->assertSame($expected, $names);
        }

        foreach($fairies as $fairy) {
            $expect = isset($map[$fairy->name]) ? $map[$fairy->name] : array();
            if($assertLoaded) {
                $this->assertTrue($fairy->children->isLoaded());
            }
            $this->checkChildren($fairy->children(), $map, $expect, $assertLoaded);
        }
    }

    protected function checkParents($fairies, $map, $assertLoaded = false)
    {
        $parentMap = array();
        foreach($map as $key => $children) {
            foreach($children as $child) {
                $parentMap[$child] = $key;
            }
        }

        foreach($fairies as $fairy) {
            do {
                $expect = isset($parentMap[$fairy->name]) ? $parentMap[$fairy->name] : null;
                if($assertLoaded) {
                    //$this->assertTrue($fairy->parent->isLoaded());
                }
                $parent = $fairy->parent();
                if($expect === null) {
                    $this->assertSame(null, $parent);
                } else {
                    $this->assertSame($expect, $parent->name);
                }

                $fairy = $parent;
            } while($fairy !== null);
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
    
    protected function prepareEntities($addWithoutOwner = true)
    {
        $map = array(
            'Pixie' => array('Trixie', 'Fairy'),
            'Trixie' => array('Blum', 'Stella'),
            'Fairy' => array('Sprite', 'Mermaid'),

            'Pinkie' => array('Rarity', 'Apple'),
            'Rarity' => array('Rainbow', 'Dash'),
            'Apple' => array('Jack', 'Twilight')
        );
        
        $entities = array(
            'Pixie' => $this->createEntity('fairy', array('name' => 'Pixie')),
            'Pinkie' => $this->createEntity('fairy', array('name' => 'Pinkie'))
        );
        
        foreach($map as $parent => $children) {
            $parent = $entities[$parent];
            
            foreach($children as $name) {
                if(!array_key_exists($name, $entities)) {
                    $entities[$name] = $this->createEntity('fairy', array('name' => $name));
                }
                $parent->children->add($entities[$name]);
            }
        }

        return $map;
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
              name VARCHAR(255),
              left INTEGER,
              right INTEGER,
              rootId Integer,
              depth INTEGER
            )
        ');

        $this->prepareOrm();
    }

    protected function prepareSqliteTables($multipleConnections = false)
    {
        $connection = $this->database->get('default');
        
        $connection->execute('
            DROP TABLE IF EXISTS fairies
        ');
        
        $connection->execute('
            CREATE TABLE fairies (
              id INTEGER PRIMARY KEY,
              name VARCHAR(255),
              left INTEGER,
              right INTEGER,
              rootId Integer,
              depth INTEGER
            )
        ');
        
        if($multipleConnections) {
            $connection = $this->database->get('second');
        }
        
        $connection->execute('
            DROP TABLE IF EXISTS flowers
        ');
        
        $connection->execute('
            CREATE TABLE flowers (
              id INTEGER PRIMARY KEY,
              name VARCHAR(255),
              fairyId INTEGER
            )
        ');
    }
}