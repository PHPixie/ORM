<?php

namespace PHPixieTests\ORM\Functional\Relationship;

abstract class OneToTest extends \PHPixieTests\ORM\Functional\RelationshipTest
{
    protected $relationshipName;
    
    public function setUp()
    {
        $this->ormConfigData = array(
            'relationships' => array(
                array(
                    'type'  => $this->relationshipName,
                    'owner' => 'fairy',
                    'items' => 'flower'
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
    
    public function testAddOwner()
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
    
    public function testOwnerCondtions()
    {
        $this->prepareEntities();
        /*
        $this->assertEntities(
            array(
                array('name' => 'Trixie')
            ),
            $this->orm->get('fairy')->query()
                ->relatedTo('flowers', function($b) {
                    $b->and('name', 'Red');
                })
                ->find()->asArray()
        );
        
        $this->assertEntities(
            array(
                array('name' => 'Blum'),
                array('name' => 'Pixie'),
            ),
            $this->orm->get('fairy')->query()
                ->notRelatedTo('flowers', function($b) {
                    $b->and('name', 'Red');
                })
                ->find()->asArray()
        );
        
        $this->assertEntities(
            array(
                array('name' => 'Blum'),
            ),
            $this->orm->get('fairy')->query()
                ->where('flowers.name', 'Yellow')
                ->find()->asArray()
        );
        
        $this->assertEntities(
            array(
                array('name' => 'Trixie'),
                array('name' => 'Blum'),
            ),
            $this->orm->get('fairy')->query()
                ->relatedTo('flowers')
                ->find()->asArray()
        );
        */
        
        print_r($this->database->get()->selectQuery()->table('flowers')->execute()->asArray());
        print_r($this->database->get()->execute('
            SELECT "fairy_id" FROM "flowers"
        ')->asArray());
        print_r($this->database->get()->execute('
             SELECT "id" FROM "fairies" WHERE "id" NOT IN ( 1, 1, 2, NULL )
        ')->asArray());


        
        $this->assertEntities(
            array(
                array('name' => 'Pixie'),
            ),
            $this->orm->get('fairy')->query()
                ->notRelatedTo('flowers')
                ->find()->asArray()
        );
        
        $red = $this->orm->get('flower')->query()->findOne();
        $this->assertEntities(
            array(
                array('name' => 'Trixie')
            ),
            $this->orm->get('fairy')->query()
                ->relatedTo('flowers', $red)
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
        $this->ormConfigData['relationships'][0]['itemsOptions'] = array(
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