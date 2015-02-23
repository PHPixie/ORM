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
        $map = $this->prepareEntities();
        
        $flowers = $this->orm->get('flower')->query()
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
    
    public function testLoadOwner()
    {
        $this->prepareEntities();
        
        $red = $this->orm->get('flower')->query()
                    ->where('name', 'Red')
                    ->findOne();
        $this->assertEquals('Trixie', $red->fairy()->name);
        
        $purple = $this->orm->get('flower')->query()
                    ->where('name', 'Purple')
                    ->findOne();
        $this->assertEquals(null, $purple->fairy());
    }
    
    public function testSetOwner()
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

        $this->assertData('flower', array(
            array( 'id' => 1, 'name' => 'Red', 'fairy_id' => 2),
        ));
    }
    
    public function testRemoveOwner()
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
        
        $this->assertData('flower', array(
            array( 'id' => 1, 'name' => 'Red', 'fairy_id' => null),
        ));
    }
    
    public function testItemsConditions()
    {
        $this->prepareEntities();
        
        $this->assertNames(
            array('Trixie'),
            $this->orm->get('fairy')->query()
                ->relatedTo($this->itemProperty, function($b) {
                    $b->and('name', 'Red');
                })
                ->find()->asArray()
        );
        
        
        $this->assertNames(
            array('Blum', 'Pixie'),
            $this->orm->get('fairy')->query()
                ->notRelatedTo($this->itemProperty, function($b) {
                    $b->and('name', 'Red');
                })
                ->find()->asArray()
        );
        
        $this->assertNames(
            array('Blum'),
            $this->orm->get('fairy')->query()
                ->where($this->itemProperty.'.name', 'Yellow')
                ->find()->asArray()
        );
        
        $this->assertNames(
            array('Trixie', 'Blum'),
            $this->orm->get('fairy')->query()
                ->relatedTo($this->itemProperty)
                ->find()->asArray()
        );
                
        $this->assertNames(
            array('Pixie'),
            $this->orm->get('fairy')->query()
                ->notRelatedTo($this->itemProperty)
                ->find()->asArray()
        );
        
        $red = $this->orm->get('flower')->query()->findOne();
        $this->assertNames(
            array('Trixie'),
            $this->orm->get('fairy')->query()
                ->relatedTo($this->itemProperty, $red)
                ->find()->asArray()
        );

    }
    
    public function testOwnerCondtions()
    {
        $map = $this->prepareEntities();
        
        $this->assertNames(
            $map['Trixie'],
            $this->orm->get('flower')->query()
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
            $this->orm->get('flower')->query()
                ->notRelatedTo('fairy', function($b) {
                    $b->and('name', 'Trixie');
                })
                ->find()->asArray()
        );
        
        $this->assertNames(
            $map['Trixie'],
            $this->orm->get('flower')->query()
                ->where('fairy.name', 'Trixie')
                ->find()->asArray()
        );
        
        $this->assertNames(
            array_merge(
                $map['Trixie'],
                $map['Blum'],
                $map['Pixie']
            ),
            $this->orm->get('flower')->query()
                ->relatedTo('fairy')
                ->find()->asArray()
        );
        
        $this->assertNames(
            $map[''],
            $this->orm->get('flower')->query()
                ->notRelatedTo('fairy')
                ->find()->asArray()
        );
        
        $trixie = $this->orm->get('fairy')->query()->findOne();
        $this->assertNames(
            $map['Trixie'],
            $this->orm->get('flower')->query()
                ->relatedTo('fairy', $trixie)
                ->find()->asArray()
        );
        
    }
    
    public function testCascadeDeleteUpdate()
    {
        $this->prepareEntities(false);
        $this->orm->get('fairy')->query()
            ->where('name', 'Blum')
            ->delete();
        
        $yellow = $this->orm->get('flower')->query()
                    ->where('name', 'Yellow')
                    ->findOne();
        
        $this->assertEquals(null, $yellow->fairy_id);
    }

    public function testCascadeDelete()
    {
        $this->ormConfigData['relationships'][0][$this->itemKey.'Options'] = array(
            'onOwnerDelete' => 'delete'
        );
        
        $this->orm = $this->orm();
        
        $this->prepareEntities(false);
        $this->orm->get('fairy')->query()
            ->where('name', 'Blum')
            ->delete();
        
        $yellow = $this->orm->get('flower')->query()
                    ->where('name', 'Yellow')
                    ->findOne();
        
        $this->assertEquals(null, $yellow);
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