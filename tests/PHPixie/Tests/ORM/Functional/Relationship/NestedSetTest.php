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
            ->find(array('children', 'parent'))
            ->asArray();

        $this->checkChildren($fairies, $map, true, true);
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

        $this->checkChildren($fairies, $map);
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

    public function testParentConditions()
    {
        $this->runTests('parentConditions');
    }

    protected function parentConditionsTest()
    {
        $map = $this->prepareEntities();
        $all = $this->getAllFromMap($map);

        $repository = $this->orm->repository('fairy');

        $this->assertNames(
            $map['Trixie'],
            $repository->query()
                ->relatedTo('parent', function($b) {
                    $b->and('name', 'Trixie');
                })
                ->find()->asArray()
        );

        $expect = $all;
        unset($expect['Blum']);
        unset($expect['Stella']);

        $this->assertNames(
            array_keys($expect),
            $repository->query()
                ->notRelatedTo('parent', function($b) {
                    $b->and('name', 'Trixie');
                })
                ->find()->asArray()
        );

        $this->assertNames(
            $map['Trixie'],
            $repository->query()
                ->where('parent.name', 'Trixie')
                ->find()->asArray()
        );


        $expect = $all;
        unset($expect['Pixie']);
        unset($expect['Pinkie']);
        unset($expect['Fluttershy']);

        $this->assertNames(
            array_keys($expect),
            $repository->query()
                ->relatedTo('parent')
                ->find()->asArray()
        );

        $this->assertNames(
            array('Pixie', 'Pinkie', 'Fluttershy'),
            $repository->query()
                ->notRelatedTo('parent')
                ->find()->asArray()
        );

        $trixie = $repository->query()->findOne();

        $relatedToSets = array(
            $trixie,
            $trixie->id(),
            $repository->query()->in($trixie)
        );

        foreach($relatedToSets as $relatedTo) {
            $this->assertNames(
                $map['Pixie'],
                $repository->query()
                    ->relatedTo('parent', $relatedTo)
                    ->find()->asArray()
            );
        }
    }

    public function testChildrenConditions()
    {
        $this->runTests('childrenConditions');
    }

    protected function childrenConditionsTest()
    {
        $map = $this->prepareEntities();
        $all = $this->getAllFromMap($map);

        $repository = $this->orm->repository('fairy');

        $this->assertNames(
            array('Trixie'),
            $repository->query()
                ->relatedTo('children', function($b) {
                    $b->and('name', 'Blum');
                })
                ->find()->asArray()
        );

        $expect = $all;
        unset($expect['Trixie']);

        $this->assertNames(
            array_keys($expect),
            $repository->query()
                ->notRelatedTo('children', function($b) {
                    $b->and('name', 'Blum');
                })
                ->find()->asArray()
        );

        $this->assertNames(
            $map['Trixie'],
            $repository->query()
                ->where('parent.name', 'Trixie')
                ->find()->asArray()
        );
/*
        $this->assertNames(
            array_merge(
                array('Pixie', 'Pinkie'),
                $map['Pixie'],
                $map['Pinkie']
            ),
            $repository->query()
                ->relatedTo('children')
                ->find()->asArray()
        );


        $this->assertNames(
            array('Pixie', 'Pinkie', 'Fluttershy'),
            $repository->query()
                ->notRelatedTo('parent')
                ->find()->asArray()
        );

*/
        $blum = $repository->query()->where('name', 'Blum')->findOne();

        $relatedToSets = array(
            $blum,
            $blum->id(),
            $repository->query()->in($blum)
        );

        foreach($relatedToSets as $relatedTo) {
            $this->assertNames(
                array('Trixie'),
                $repository->query()
                    ->relatedTo('children', $relatedTo)
                    ->find()->asArray()
            );
        }
    }

    public function testAddSetParent()
    {
        $this->runTests('addSetParent');
    }

    protected function addSetParentTest()
    {
        $this->prepareEntities();
        $this->assertTree($this->initialData());
    }

    public function testRemoveParent()
    {
        $this->runTests('removeParent');
    }

    protected function removeParentTest()
    {
        $this->prepareEntities();

        $data = $this->initialData();

        $stella = $this->query('fairy')->where('name', 'Stella')->findOne();
        $stella->parent->remove();

        $data['Stella'] = array(null, null, null, null);

        //shift right values
        foreach(array('Pixie', 'Trixie', 'Fairy', 'Sprite', 'Mermaid') as $name) {
            $data[$name][1] -= 2;
        }

        //shift left values
        foreach(array('Fairy', 'Sprite', 'Mermaid') as $name) {
            $data[$name][0] -= 2;
        }

        $this->assertTree($data);

        $rarity = $this->query('fairy')->where('name', 'Rarity')->findOne();
        $rarity->parent->remove();

        $data['Rarity'] = array(1, 6, $rarity->id(), 0);
        $data['Rainbow'] = array(2, 3, $rarity->id(), 1);
        $data['Dash'] = array(4, 5, $rarity->id(), 1);

        //shift values
        $data['Pinkie'][1] -= 6;

        foreach(array('Apple', 'Jack', 'Twilight') as $name) {
            $data[$name][0] -= 6;
            $data[$name][1] -= 6;
        }

        $this->assertTree($data);

        $fluttershy = $this->query('fairy')->where('name', 'Fluttershy')->findOne();
        $fluttershy->parent->remove();

        $this->assertTree($data);
    }

    protected function assertTree($data)
    {
        $expect = array();
        foreach($data as $name => $row) {
            $row = array_combine(array('left', 'right', 'rootId', 'depth'), $row);
            $row['name'] = $name;
            $expect[] = $row;
        }

        $this->assertData(
            'fairy',
            $expect,
            'left'
        );
    }

    protected function getAllFromMap($map)
    {
        $all = array();

        foreach($map as $key => $value) {
            $all[]=$key;
            $all = array_merge($all, $value);
        }

        array_unique($all);
        $all = array_fill_keys($all, true);
        return $all;
    }

    protected function checkChildren(
        $fairies,
        $map,
        $assertLoaded = false,
        $assertLoadedParent = false,
        $expectedChildren = null,
        $expectedParent = null
    )
    {
        $names = array();

        foreach($fairies as $fairy) {
            $names[]= $fairy->name;
        }

        if($expectedChildren !== null) {
            $this->assertSame($expectedChildren, $names);
        }

        foreach($fairies as $fairy) {
            $expect = isset($map[$fairy->name]) ? $map[$fairy->name] : array();
            if($assertLoaded) {
                $this->assertTrue($fairy->children->isLoaded());
            }

            $this->checkChildren($fairy->children(), $map, $assertLoaded, $assertLoadedParent, $expect, $fairy->name);

            if($assertLoadedParent) {
                $this->assertTrue($fairy->parent->isLoaded());
                if($expectedParent !== null) {
                    $this->assertSame($expectedParent, $fairy->parent()->name);
                } else {
                    $this->assertSame(null, $fairy->parent());
                }
            }
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
                    $this->assertTrue($fairy->parent->isLoaded());
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
            'Apple' => array('Jack', 'Twilight'),

            'Fluttershy' => array()
        );
        
        $entities = array(
            'Pixie' => $this->createEntity('fairy', array('name' => 'Pixie')),
            'Pinkie' => $this->createEntity('fairy', array('name' => 'Pinkie')),
            'Fluttershy' => $this->createEntity('fairy', array('name' => 'Fluttershy'))
        );

        $toggle = false;

        foreach($map as $parent => $children) {
            $parent = $entities[$parent];
            
            foreach($children as $name) {
                if(!array_key_exists($name, $entities)) {
                    $entities[$name] = $this->createEntity('fairy', array('name' => $name));
                }
                $toggle = !$toggle;
                if($toggle) {
                    $parent->children->add($entities[$name]);
                } else {
                    $entities[$name]->parent->set($parent);
                }

            }
        }

        return $map;
    }

    protected function initialData()
    {
        return array(
            'Pixie' => array(1, 14, 1, 0),
            'Pinkie' => array(1, 14, 2, 0),
            'Fluttershy' => array(null, null, null, null),

            'Trixie' => array(2, 7, 1, 1),
            'Fairy' => array(8, 13, 1, 1),

            'Blum' => array(3, 4, 1, 2),
            'Stella' => array(5, 6, 1, 2),

            'Sprite' => array(9, 10, 1, 2),
            'Mermaid' => array(11, 12, 1, 2),

            'Rarity' => array(2, 7, 2, 1),
            'Apple' => array(8, 13, 2, 1),

            'Rainbow' => array(3, 4, 2, 2),
            'Dash' => array(5, 6, 2, 2),
            'Jack' => array(9, 10, 2, 2),
            'Twilight' => array(11, 12, 2, 2)
        );
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