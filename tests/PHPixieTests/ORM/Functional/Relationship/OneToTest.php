<?php

namespace PHPixieTests\ORM\Functional\Relationship;

abstract class OneToTest extends \PHPixieTests\ORM\Functional\RelationshipTest
{
    protected $relationshipName;
    protected $itemKey;
    protected $itemProperty;
    
    public function setUp()
    {
        $this->ormConfigData = array(
            'relationships' => array(
                array(
                    'type'  => $this->relationshipName,
                    'owner' => 'fairy',
                    $this->itemKey => 'flower'
                )
            )
        );
        
        parent::setUp();
    }
    
    public function testPreloadOwner()
    {
        $this->runTests('preloadOwner');
    }
    
    public function testSetOwner()
    {
        $this->runTests('setOwner');
    }
    
    public function testLoadOwner()
    {
        $this->runTests('loadOwner');
    }
    
    public function testRemoveOwner()
    {
        $this->runTests('removeOwner');
    }
    
    public function testItemsConditions()
    {
        $this->runTests('itemsConditions');
    }
    
    public function testOwnerCondtions()
    {
        $this->runTests('ownerCondtions');
    }
    
    public function testCascadeDeleteUpdate()
    {
        $this->runTests('cascadeDeleteUpdate');
    }
    
    public function testCascadeDelete()
    {
        $this->runTests('cascadeDelete');
    }
    
    protected function preloadOwnerTest()
    {
        $map = $this->prepareEntities();
        
        $flowers = $this->orm->repository('flower')->query()
                        ->find(array('fairy'))
                        ->asArray();
        
        $key = 0;
        foreach($map as $fairyName => $flowerNames) {
            foreach($flowerNames as $flowerName) {
                $flower = $flowers[$key];
                
                $this->assertEquals($flowerName, $flower->name);
                $this->assertEquals(true, $flower->fairy->isLoaded());
                
                if($fairyName !== '') {
                    $this->assertEquals($fairyName, $flower->fairy()->name);
                }else{
                    $this->assertEquals(null, $flower->fairy());
                }
                
                $key++;
            }
        }
    }
    
    protected function loadOwnerTest()
    {
        $this->prepareEntities();
        
        $red = $this->orm->repository('flower')->query()
                    ->where('name', 'Red')
                    ->findOne();
        $this->assertEquals('Trixie', $red->fairy()->name);
        
        $purple = $this->orm->repository('flower')->query()
                    ->where('name', 'Purple')
                    ->findOne();
        $this->assertEquals(null, $purple->fairy());
    }
    
    protected function setOwnerTest()
    {
        $trixie = $this->createEntity('fairy', array(
            'name' => 'Trixie'
        ));
        
        $blum = $this->createEntity('fairy', array(
            'name' => 'Blum'
        ));
        
        $red = $this->createEntity('flower', array(
            'name' => 'Red'
        ));
        
        
        $red->fairy->set($trixie);
        $this->assertSame($trixie, $red->fairy());
        
        $red->fairy->set($blum);
        $this->assertSame($blum, $red->fairy());
        
        $idField = $this->idField('flower');
        $this->assertData('flower', array(
            array( $idField => $red->id(), 'name' => 'Red', 'fairy_id' => $blum->id()),
        ));
    }
    
    protected function removeOwnerTest()
    {
        $trixie = $this->createEntity('fairy', array(
            'name' => 'Trixie'
        ));
        
        $red = $this->createEntity('flower', array(
            'name' => 'Red'
        ));
        
        $red->fairy->set($trixie);
        $red->fairy->remove();
        
        $this->assertSame(null, $red->fairy());
        
        $idField = $this->idField('flower');
        $this->assertData('flower', array(
            array( $idField => $red->id(), 'name' => 'Red', 'fairy_id' => null),
        ));
    }
    
    protected function itemsConditionsTest()
    {
        $this->prepareEntities();
        
        $this->assertNames(
            array('Trixie'),
            $this->orm->repository('fairy')->query()
                ->relatedTo($this->itemProperty, function($b) {
                    $b->and('name', 'Red');
                })
                ->find()->asArray()
        );
        
        
        $this->assertNames(
            array('Blum', 'Pixie'),
            $this->orm->repository('fairy')->query()
                ->notRelatedTo($this->itemProperty, function($b) {
                    $b->and('name', 'Red');
                })
                ->find()->asArray()
        );
        
        $this->assertNames(
            array('Blum'),
            $this->orm->repository('fairy')->query()
                ->where($this->itemProperty.'.name', 'Yellow')
                ->find()->asArray()
        );
        
        $this->assertNames(
            array('Trixie', 'Blum'),
            $this->orm->repository('fairy')->query()
                ->relatedTo($this->itemProperty)
                ->find()->asArray()
        );
                
        $this->assertNames(
            array('Pixie'),
            $this->orm->repository('fairy')->query()
                ->notRelatedTo($this->itemProperty)
                ->find()->asArray()
        );
        
        $red = $this->orm->repository('flower')->query()->findOne();
        $this->assertNames(
            array('Trixie'),
            $this->orm->repository('fairy')->query()
                ->relatedTo($this->itemProperty, $red)
                ->find()->asArray()
        );

    }
    
    protected function ownerCondtionsTest()
    {
        $map = $this->prepareEntities();
        
        $this->assertNames(
            $map['Trixie'],
            $this->orm->repository('flower')->query()
                ->relatedTo('fairy', function($b) {
                    $b->and('name', 'Trixie');
                })
                ->find()->asArray()
        );
        
        $this->assertNames(
            array_merge(
                $map['Blum'],
                $map['Pixie'],
                $map['']
            ),
            $this->orm->repository('flower')->query()
                ->notRelatedTo('fairy', function($b) {
                    $b->and('name', 'Trixie');
                })
                ->find()->asArray()
        );
        
        $this->assertNames(
            $map['Trixie'],
            $this->orm->repository('flower')->query()
                ->where('fairy.name', 'Trixie')
                ->find()->asArray()
        );
        
        $this->assertNames(
            array_merge(
                $map['Trixie'],
                $map['Blum'],
                $map['Pixie']
            ),
            $this->orm->repository('flower')->query()
                ->relatedTo('fairy')
                ->find()->asArray()
        );
        
        $this->assertNames(
            $map[''],
            $this->orm->repository('flower')->query()
                ->notRelatedTo('fairy')
                ->find()->asArray()
        );
        
        $trixie = $this->orm->repository('fairy')->query()->findOne();
        $this->assertNames(
            $map['Trixie'],
            $this->orm->repository('flower')->query()
                ->relatedTo('fairy', $trixie)
                ->find()->asArray()
        );
        
    }
    
    protected function cascadeDeleteUpdateTest()
    {
        $this->prepareEntities(false);
        $this->orm->repository('fairy')->query()
            ->where('name', 'Blum')
            ->delete();
        
        $yellow = $this->orm->repository('flower')->query()
                    ->where('name', 'Yellow')
                    ->findOne();
        
        $this->assertEquals(null, $yellow->fairy_id);
    }
    
    protected function cascadeDeleteTest()
    {
        $this->ormConfigData['relationships'][0][$this->itemKey.'Options'] = array(
            'onOwnerDelete' => 'delete'
        );
        
        $this->orm = $this->orm();
        
        $this->prepareEntities(false);
        $this->orm->repository('fairy')->query()
            ->where('name', 'Blum')
            ->delete();
        
        $yellow = $this->orm->repository('flower')->query()
                    ->where('name', 'Yellow')
                    ->findOne();
        
        $this->assertEquals(null, $yellow);
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
        
        if($multipleConnections) {
            $connection = $this->database->get('second');
        }
        
        $connection->execute('
            CREATE TABLE flowers (
              id INTEGER PRIMARY KEY,
              name VARCHAR(255),
              fairy_id INTEGER
            )
        ');
    }
    
    protected function prepareMongoDatabase()
    {
        $connection = $this->database->get('default');
        $collections = array('fairies', 'flowers');
        
        foreach($collections as $collection) {
            $connection->deleteQuery()
                        ->collection($collection)
                        ->execute();
        }
    }
}