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
            ->find()
            ->asArray();

        $this->checkChildren($fairies, $map, null);
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
            'Fairy' => array('Sprite', 'Mermaid')
        );
        
        $entities = array(
            'Pixie' => $this->createEntity('fairy', array('name' => 'Pixie'))
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