<?php

namespace PHPixie\Tests\ORM\Functional\Relationship\OneTo;

class ManyTest extends \PHPixie\Tests\ORM\Functional\Relationship\OneToTest
{
    protected $relationshipName = 'oneToMany';
    protected $itemKey = 'items';
    protected $itemProperty = 'flowers';

    public function testAddItem()
    {
        $this->runTests('addItem');
    }
    
    public function testRemoveItems()
    {
        $this->runTests('removeItems');
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
        
    protected function addItemTest()
    {
        $trixie = $this->createEntity('fairy', array(
            'name' => 'Trixie'
        ));
        
        $red = $this->createEntity('flower', array(
            'name' => 'Red'
        ));
        
        $green = $this->createEntity('flower', array(
            'name' => 'Green'
        ));
        
        $trixie->flowers();
        
        $trixie->flowers->add($red);
        $trixie->flowers->add($green);
        
        $this->assertSame($trixie, $red->fairy());
        $this->assertSame($trixie, $green->fairy());
        
        $this->assertSame(array($red, $green), $trixie->flowers()->asArray());
        
        $idField = $this->idField('flower');
        $this->assertData('flower', array(
            array( $idField => $red->id(), 'name' => 'Red', 'fairyId' => $trixie->id()),
            array( $idField => $green->id(), 'name' => 'Green', 'fairyId' => $trixie->id()),
        ));

        
        $blum = $this->createEntity('fairy', array(
            'name' => 'Blum'
        ));
        
        $blum->flowers();
        
        for($i=0;$i<2;$i++) {
            $blum->flowers->add($green);
            $this->assertSame(array($green), $blum->flowers()->asArray());
            
            $this->assertSame($trixie, $red->fairy());
            $this->assertSame($blum, $green->fairy());
            
            $idField = $this->idField('flower');
            $this->assertData('flower', array(
                array( $idField => $red->id(), 'name' => 'Red', 'fairyId' => $trixie->id()),
                array( $idField => $green->id(), 'name' => 'Green', 'fairyId' => $blum->id()),
            ));
        }
        
        $trixie->flowers->add(
            $this->query('flower')->in($green)
        );
        
        $this->assertSame(false, $trixie->flowers->isLoaded());
        
        $blum->flowers->add($green);
        $trixie->flowers->add(
            $green->id()
        );
        
        $this->assertSame(false, $trixie->flowers->isLoaded());
        
        $this->assertData('flower', array(
            array( $idField => $red->id(), 'name' => 'Red', 'fairyId' => $trixie->id()),
            array( $idField => $green->id(), 'name' => 'Green', 'fairyId' => $trixie->id()),
        ));
    }

    protected function removeItemsTest()
    {
        $trixie = $this->createEntity('fairy', array(
            'name' => 'Trixie'
        ));
        
        $red = $this->createEntity('flower', array(
            'name' => 'Red'
        ));
        
        $green = $this->createEntity('flower', array(
            'name' => 'Green'
        ));
        
        $trixie->flowers();
        
        $trixie->flowers->add($red);
        $trixie->flowers->add($green);
        
        $trixie->flowers->remove($green);
        $this->assertSame(array($red), $trixie->flowers()->asArray());
        
        $this->assertSame($trixie, $red->fairy());
        $this->assertSame(null, $green->fairy());
        
        $idField = $this->idField('flower');
        
        $this->assertData('flower', array(
            array( $idField => $red->id(), 'name' => 'Red', 'fairyId' => $trixie->id()),
            array( $idField => $green->id(), 'name' => 'Green', 'fairyId' => null),
        ));
        
        $trixie->flowers->removeAll();
        $this->assertSame(array(), $trixie->flowers()->asArray());
        
        $this->assertSame(null, $red->fairy());

        $this->assertData('flower', array(
            array( $idField => $red->id(), 'name' => 'Red', 'fairyId' => null),
            array( $idField => $green->id(), 'name' => 'Green', 'fairyId' => null),
        ));
        
        $trixie->flowers->add($green);
        $trixie->flowers->remove(
            $this->query('flower')->in($green)
        );
        
        $this->assertSame(false, $trixie->flowers->isLoaded());
        
        $trixie->flowers->add($green);
        $trixie->flowers->remove(
            $green->id()
        );
        
        $this->assertSame(false, $trixie->flowers->isLoaded());
        
        $this->assertData('flower', array(
            array( $idField => $red->id(), 'name' => 'Red', 'fairyId' => null),
            array( $idField => $green->id(), 'name' => 'Green', 'fairyId' => null),
        ));
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

        $this->assertNames(
            array('Trixie'),
            $this->orm->repository('fairy')->query()
                ->relatedTo('flowers', $red)
                ->relatedTo('flowers', $green)
                ->find()->asArray()
        );

    }
    
    protected function loadItemsTest()
    {
        $this->prepareEntities();
        
        $trixie = $this->orm->repository('fairy')->query()
                    ->where('name', 'Trixie')
                    ->findOne();
        
        $this->assertEntities(
            array(
                array('name' => 'Red'),
                array('name' => 'Green'),
            ),
            $trixie->flowers()->asArray()
        );
        
        $pixie = $this->orm->repository('fairy')->query()
                    ->where('name', 'Pixie')
                    ->findOne();
        $this->assertEntities(
            array(),
            $pixie->flowers()->asArray()
        );
    }
    
    protected function preloadItemsTest()
    {
        $map = $this->prepareEntities();
        
        $fairies = $this->orm->repository('fairy')->query()
                        ->find(array('flowers'))
                        ->asArray();
        
        $key = 0;
        foreach($map as $fairyName => $flowerNames) {
            if($fairyName === '') {
                continue;
            }
                
            $fairy = $fairies[$key];
            $this->assertSame($fairyName, $fairy->name);
            
            $this->assertEquals(true, $fairy->flowers->isLoaded());
            $this->assertEquals(count($flowerNames), count($fairy->flowers()->asArray()));

            foreach($fairy->flowers() as $flowerKey => $flower) {
                $this->assertSame($flowerNames[$flowerKey], $flower->name);
            }
            $key++;
        }
    }
    
    protected function prepareEntities($addWithoutOwner = true)
    {
        $map = array(
            'Trixie' => array('Red', 'Green'),
            'Blum'   => array('Yellow'),
            'Pixie'  => array()
        );
        
        if($addWithoutOwner) {
            $map[''] = array('Purple');
        }
        
        foreach($map as $fairyName => $flowerNames) {
            
            if($fairyName !== '') {
                $fairy = $this->createEntity('fairy', array(
                    'name' => $fairyName
                ));
            }
            
            $flowers = array();
            foreach($flowerNames as $flowerName) {
                $flowers[] = $this->createEntity('flower', array(
                    'name' => $flowerName
                ));
            }
            
            if($fairyName !== '') {
                $fairy->flowers->add($flowers);
            }
        }
        
        return $map;
    }
}