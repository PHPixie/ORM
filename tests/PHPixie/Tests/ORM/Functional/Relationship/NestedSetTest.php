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
                    'type' => 'nestedSet',
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
                ->relatedTo('parent', function ($b) {
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
                ->notRelatedTo('parent', function ($b) {
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

        $trixie = $repository->query()->where('name', 'Trixie')->findOne();

        $relatedToSets = array(
            $trixie,
            $trixie->id(),
            $repository->query()->in($trixie)
        );

        foreach ($relatedToSets as $relatedTo) {
            $this->assertNames(
                $map['Trixie'],
                $repository->query()
                    ->relatedTo('parent', $relatedTo)
                    ->find()->asArray()
            );
        }
    }

    public function testAllParentsConditions()
    {
        $this->runTests('allParentsConditions');
    }

    protected function allParentsConditionsTest()
    {
        $map = $this->prepareEntities();
        $all = $this->getAllFromMap($map);

        $repository = $this->orm->repository('fairy');

        $this->assertNames(
            array_merge(
                $map['Pixie'],
                $map['Trixie'],
                $map['Fairy']
            ),
            $repository->query()
                ->relatedTo('allParents', function ($b) {
                    $b->and('name', 'Pixie');
                })
                ->find()->asArray()
        );

        $this->assertNames(
            array_merge(
                $map['Pinkie'],
                $map['Rarity'],
                $map['Apple'],
                array('Pinkie', 'Fluttershy', 'Pixie')
            ),
            $repository->query()
                ->notRelatedTo('allParents', function ($b) {
                    $b->and('name', 'Pixie');
                })
                ->find()->asArray()
        );

        $this->assertNames(
            array_merge(
                $map['Pixie'],
                $map['Trixie'],
                $map['Fairy']
            ),
            $repository->query()
                ->where('allParents.name', 'Pixie')
                ->find()->asArray()
        );


        $expect = $all;
        unset($expect['Pixie']);
        unset($expect['Pinkie']);
        unset($expect['Fluttershy']);

        $this->assertNames(
            array_keys($expect),
            $repository->query()
                ->relatedTo('allParents')
                ->find()->asArray()
        );

        $this->assertNames(
            array('Pixie', 'Pinkie', 'Fluttershy'),
            $repository->query()
                ->notRelatedTo('allParents')
                ->find()->asArray()
        );

        $pixie = $repository->query()->where('name', 'Pixie')->findOne();

        $relatedToSets = array(
            $pixie,
            $pixie->id(),
            $repository->query()->in($pixie)
        );

        foreach ($relatedToSets as $relatedTo) {
            $this->assertNames(
                array_merge(
                    $map['Pixie'],
                    $map['Trixie'],
                    $map['Fairy']
                ),
                $repository->query()
                    ->relatedTo('allParents', $relatedTo)
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
            array(),
            $repository->query()
                ->relatedTo('children', function($q) {
                    $q->where('id', 100);
                })
            ->find()->asArray()
        );
        
        $this->assertNames(
            array('Trixie'),
            $repository->query()
                ->relatedTo('children', function ($b) {
                    $b->and('name', 'Blum');
                })
                ->find()->asArray()
        );

        $expect = $all;
        unset($expect['Trixie']);

        $this->assertNames(
            array_keys($expect),
            $repository->query()
                ->notRelatedTo('children', function ($b) {
                    $b->and('name', 'Blum');
                })
                ->find()->asArray()
        );

        $this->assertNames(
            array('Trixie'),
            $repository->query()
                ->where('children.name', 'Blum')
                ->find()->asArray()
        );

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
            array_merge(
                $map['Trixie'],
                $map['Fairy'],
                $map['Rarity'],
                $map['Apple'],
                array('Fluttershy')
            ),
            $repository->query()
                ->notRelatedTo('children')
                ->find()->asArray()
        );

        $blum = $repository->query()->where('name', 'Blum')->findOne();

        $relatedToSets = array(
            $blum,
            $blum->id(),
            $repository->query()->in($blum)
        );

        foreach ($relatedToSets as $relatedTo) {
            $this->assertNames(
                array('Trixie'),
                $repository->query()
                    ->relatedTo('children', $relatedTo)
                    ->find()->asArray()
            );
        }
    }
    
    public function testAllChildrenConditions()
    {
        $this->runTests('allChildrenConditions');
    }

    protected function allChildrenConditionsTest()
    {
        $map = $this->prepareEntities();
        $all = $this->getAllFromMap($map);

        $repository = $this->orm->repository('fairy');

        $this->assertNames(
            array('Pixie', 'Trixie'),
            $repository->query()
                ->relatedTo('allChildren', function ($b) {
                    $b->and('name', 'Blum');
                })
                ->find()->asArray()
        );

        $expect = $all;
        unset($expect['Trixie']);
        unset($expect['Pixie']);

        $this->assertNames(
            array_keys($expect),
            $repository->query()
                ->notRelatedTo('allChildren', function ($b) {
                    $b->and('name', 'Blum');
                })
                ->find()->asArray()
        );

        $this->assertNames(
            array('Pixie', 'Trixie'),
            $repository->query()
                ->where('allChildren.name', 'Blum')
                ->find()->asArray()
        );

        $this->assertNames(
            array_merge(
                array('Pixie', 'Pinkie'),
                $map['Pixie'],
                $map['Pinkie']
            ),
            $repository->query()
                ->relatedTo('allChildren')
                ->find()->asArray()
        );

        $this->assertNames(
            array_merge(
                $map['Trixie'],
                $map['Fairy'],
                $map['Rarity'],
                $map['Apple'],
                array('Fluttershy')
            ),
            $repository->query()
                ->notRelatedTo('allChildren')
                ->find()->asArray()
        );

        $blum = $repository->query()->where('name', 'Blum')->findOne();

        $relatedToSets = array(
            $blum,
            $blum->id(),
            $repository->query()->in($blum)
        );

        foreach ($relatedToSets as $relatedTo) {
            $this->assertNames(
                array('Trixie', 'Pixie'),
                $repository->query()
                    ->relatedTo('allChildren', $relatedTo)
                    ->find()->asArray()
            );
        }
    }

    public function testQueries()
    {
        $this->runTests('queries');
    }

    protected function queriesTest()
    {
        $map = $this->prepareEntities();
        $repository = $this->orm->repository('fairy');

        $this->assertNames(
            $map['Pixie'],
            $repository->query()
                ->where('name', 'Pixie')->children()
                ->find()->asArray()
        );

        $this->assertNames(
            array('Pixie'),
            $repository->query()
                ->where('name', 'Trixie')->parent()
                ->find()->asArray()
        );

        $this->assertNames(
            array_merge(
                $map['Pixie'],
                $map['Trixie'],
                $map['Fairy']
            ),
            $repository->query()
                ->where('name', 'Pixie')->allChildren()
                ->find()->asArray()
        );

        $this->assertNames(
            array('Trixie', 'Pixie'),
            $repository->query()
                ->where('name', 'Blum')->allParents()
                ->find()->asArray()
        );

    }

    public function testAddSetParent()
    {
        $this->runTests('addSetParent');
    }

    protected function addSetParentTest()
    {
        $this->prepareEntities();
        $data = $this->initialData();
        $this->assertTree($data);

        $trixie = $this->getByName('Trixie');
        $blum = $this->getByName('Blum');

        $trixie->children->add($blum);
        $this->assertTree($data);
    }

    public function testMoveWithinTree()
    {
        $this->runTests('moveWithinTree');
    }

    protected function moveWithinTreeTest()
    {
        $this->prepareEntities();
        $data = $this->initialData();

        $trixie = $this->getByName('Trixie');
        $sprite = $this->getByName('Sprite');

        $trixie->children->add($sprite);

        $data['Trixie'][1]+=2;
        $data['Sprite'][0] = $data['Trixie'][1]-2;
        $data['Sprite'][1] = $data['Trixie'][1]-1;
        $data['Sprite'][3] = $data['Trixie'][3]+1;

        $data['Fairy'][0]+=2;


        $this->assertTree($data);

        $pixie = $this->getByName('Pixie');
        $pixie->children->add($sprite);

        $data['Trixie'][1]-=2;
        $data['Sprite'][0] = $data['Pixie'][1]-2;
        $data['Sprite'][1] = $data['Pixie'][1]-1;
        $data['Sprite'][3] = $data['Pixie'][3]+1;

        foreach(array('Fairy', 'Mermaid') as $name) {
            $data[$name][0]-=2;
            $data[$name][1]-=2;
        }

        $this->assertTree($data);

        $blum = $this->getByName('Blum');
        $pixie->children->add($blum);

        $data['Blum'][0] = $data['Pixie'][1]-2;
        $data['Blum'][1] = $data['Pixie'][1]-1;
        $data['Trixie'][1]-=2;

        foreach(array('Stella', 'Fairy', 'Sprite', 'Mermaid') as $name) {
            $data[$name][0]-=2;
            $data[$name][1]-=2;
        }
    }

    public function testMoveOtherTree()
    {
        $this->runTests('moveOtherTree');
    }

    protected function moveOtherTreeTest()
    {
        $this->prepareEntities();
        $data = $this->initialData();

        $trixie = $this->getByName('Trixie');
        $rarity = $this->getByName('Rarity');

        $trixie->children->add($rarity);

        $offset = $data['Trixie'][1] - $data['Rarity'][0];
        $depthOffset = $data['Trixie'][3]+1 - $data['Rarity'][3];

        foreach(array('Rarity', 'Rainbow', 'Dash') as $name) {
            $data[$name][0]+=$offset;
            $data[$name][1]+=$offset;
            $data[$name][2] = 1;
            $data[$name][3]+= $depthOffset;
        }

        $data['Pixie'][1]+=6;
        $data['Trixie'][1]+=6;
        foreach(array('Fairy', 'Sprite', 'Mermaid') as $name) {
            $data[$name][0]+=6;
            $data[$name][1]+=6;
        }

        $data['Pinkie'][1]-=6;
        foreach(array('Apple', 'Jack', 'Twilight') as $name) {
            $data[$name][0]-=6;
            $data[$name][1]-=6;
        }

        $this->assertTree($data);
    }

    public function testMoveWithoutReload()
    {
        $this->runTests('moveWithoutReload');
    }


    protected function moveWithoutReloadTest()
    {
        $pixie = $this->createEntity('fairy', array('name' => 'Pixie'));
        $trixie = $this->createEntity('fairy', array('name' => 'Trixie'));
        $blum = $this->createEntity('fairy', array('name' => 'Blum'));
        $stella = $this->createEntity('fairy', array('name' => 'Stella'));
        $pinky = $this->createEntity('fairy', array('name' => 'Pinky'));
        
        $pinky->parent->set($stella);
        $stella->parent->set($blum);
        
        $pixie->children->add($trixie);
        $pixie->children->add($blum);
        
        $columns = array('id', 'name', 'left', 'right', 'depth', 'rootId');
        
        $expect = array(
            array('1', 'Pixie', '1', '10', '0', '1'),
            array('2', 'Trixie', '2', '3', '1', '1'),
            array('3', 'Blum', '4', '9', '1', '1'),
            array('4', 'Stella', '5', '8', '2', '1'),
            array('5', 'Pinky', '6', '7', '3', '1'),
        );
        
        foreach($expect as $key => $value) {
            $expect[$key] = array_combine($columns, $value);
        }
        
        $this->assertData('fairy', $expect);
    }

    public function testProperties()
    {
        $this->runTests('properties');
    }

    protected function propertiesTest()
    {
        $entities = $this->getPreloadedEntitiesMap();

        $trixie = $entities['Trixie'];
        $rarity = $entities['Rarity'];
        $pinkie = $entities['Pinkie'];

        $trixie->children->add($rarity);

        $this->assertSame($trixie, $rarity->parent());
        $this->assertTrue(in_array($rarity, $trixie->children()->asArray(), true));
        $this->assertFalse(in_array($rarity, $pinkie->children()->asArray(), true));

        $trixie->parent->set($pinkie);
        $this->assertSame($pinkie, $trixie->parent());
        $this->assertTrue(in_array($trixie, $pinkie->children()->asArray(), true));

        $rarity->parent->remove();
        $this->assertSame(null, $rarity->parent());
        $this->assertFalse(in_array($rarity, $trixie->children()->asArray(), true));
        
        $fairy = $entities['Fairy'];
        $fairy->children->removeAll();
        $this->assertSame(null, $entities['Sprite']->parent());
        $this->assertSame(null, $entities['Mermaid']->parent());
        $this->assertSame(array(), $fairy->children()->asArray(true));

        $this->assertException(function () use($rarity) {
            $rarity->children->add(5);
        }, '\PHPixie\ORM\Exception\Relationship');
    }

    public function testDeleteConstraint()
    {
        $this->runTests('deleteConstraint');
    }

    protected function deleteConstraintTest()
    {
        $this->prepareEntities();
        $data = $this->initialData();

        $delete = array('Sprite', 'Rainbow', 'Fluttershy');
        $this->query('fairy')->where('name', 'in', $delete)->delete();

        foreach($delete as $name) {
            unset($data[$name]);
        }

        foreach(array('Pixie', 'Fairy', 'Pinkie', 'Rarity') as $name) {
            $data[$name][1]-=2;
        }

        foreach(array('Mermaid', 'Dash', 'Apple', 'Jack', 'Twilight') as $name) {
            $data[$name][0]-=2;
            $data[$name][1]-=2;
        }

        $this->assertTree($data);


        $self = $this;

        $this->assertException(function() use($self) {
            $self->getByName('Rarity')->delete();
        }, '\PHPixie\ORM\Exception\Relationship');

        $delete = array('Trixie', 'Blum', 'Stella', 'Rarity', 'Dash');
        $this->query('fairy')->where('name', 'in', $delete)->delete();

        foreach($delete as $name) {
            unset($data[$name]);
        }

        $data['Pixie'][1]-=6;
        $data['Pinkie'][1]-=4;

        foreach(array('Fairy', 'Mermaid') as $name) {
            $data[$name][0]-=6;
            $data[$name][1]-=6;
        }

        foreach(array('Apple', 'Jack', 'Twilight') as $name) {
            $data[$name][0]-=4;
            $data[$name][1]-=4;
        }

        $this->assertTree($data);

        $this->query('fairy')->delete();
        $this->assertTree(array());
    }

    protected function getPreloadedEntitiesMap()
    {
        $this->prepareEntities();

        $entities = $this->query('fairy')->where('depth' ,0)->find(array('children'))->asArray();
        $map = array();

        for($i=0; $i<count($entities); $i++) {
            $map[$entities[$i]->name] = $entities[$i];
            foreach ($entities[$i]->children() as $child) {
                $entities[]= $child;
            }
        }

        return $map;
    }

    public function testAsArray() {
        $this->runTests('asArray');
    }

    protected function asArrayTest()
    {
        $this->prepareEntities();

        //make sure recursion is prevented
        $this->query('fairy')->find(array('children'))->asArray(true);
    }

    public function testMoveParentToChild()
    {
        $this->runTests('moveParentToChild');
    }

    protected function moveParentToChildTest()
    {
        $this->prepareEntities();

        $pixie = $this->getByName('Pixie');
        $sprite = $this->getByName('Sprite');

        $this->assertException(function() use($sprite, $pixie) {
            $sprite->children->add($pixie);
        }, '\PHPixie\ORM\Exception');
    }

    protected function getByName($name)
    {
        return $this->query('fairy')->where('name', $name)->findOne();
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

    public function testRemoveChildren()
    {
        $this->runTests('removeChildren');
    }

    protected function removeChildrenTest()
    {
        $this->prepareEntities();

        $data = $this->initialData();

        $pixie = $this->getByName('Pixie');
        $pixie->children->removeAll();

        $data['Pixie'][1] = 2;

        $data = array_merge($data, array(
            'Trixie' => array(1, 6, 4, 0),
            'Fairy' => array(1, 6, 5, 0),

            'Blum' => array(2, 3, 4, 1),
            'Stella' => array(4, 5, 4, 1),

            'Sprite' => array(2, 3, 5, 1),
            'Mermaid' => array(4, 5, 5, 1),
        ));

        $this->assertTree($data);
    }

    public function testAllQuery()
    {
        $this->runTests('allQuery');
    }

    protected function allQueryTest()
    {
        $this->prepareEntities();

        $pixie = $this->getByName('Trixie');
        $this->assertNames(
            array('Blum', 'Stella'),
            $pixie->children->allQuery()->find()
        );
        
        $blum = $this->getByName('Blum');
        $this->assertNames(
            array('Pixie', 'Trixie'),
            $blum->parent->allQuery()->find()
        );
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
            //'mysql'
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
              `id` INTEGER PRIMARY KEY AUTO_INCREMENT,
              `name` VARCHAR(255),
              `left` INTEGER,
              `right` INTEGER,
              `rootId` INTEGER,
              `depth` INTEGER
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
