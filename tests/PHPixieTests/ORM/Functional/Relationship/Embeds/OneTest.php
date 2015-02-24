<?php

namespace PHPixieTests\ORM\Functional\Relationship\OneTo;

class OneTest extends \PHPixieTests\ORM\Functional\Relationship\EmbedsTest
{
    protected $relationshipName = 'embedsOne';
    protected $itemKey = 'item';
    protected $itemProperty = 'flower';
    
    
    public function testCreateItem()
    {
        $this->runTests('createItem');
    }
    
    public function testSetItem()
    {
        $this->runTests('setItem');
    }
    
    /*
    
    public function testRemoveItem()
    {
        $this->runTests('removeItem');
    }
    */
    
    public function testLoadItems()
    {
        $this->runTests('loadItems');
    }
    /*
    public function testPreloadItems()
    {
        $this->runTests('preloadItems');
    }
    */
    
    
    protected function createItemTest()
    {
        $trixie = $this->createEntity('fairy', array(
            'name' => 'Trixie'
        ));
        
        $red = $trixie->flower->create(array('name' => 'Red'));
        
        $this->assertSame('Red', $trixie->flower()->name);
        $this->assertSame($red, $trixie->flower());
        $this->assertSame($trixie, $red->owner());
        $this->assertSame($this->itemProperty, $red->ownerPropertyName());
        
        $trixie->save();
        
        $idField = $this->idField('fairy');
        $this->assertDataAsObject('fairy', array(
            (object) array( $idField => $trixie->id(), 'name' => 'Trixie', 'flower' => (object) array(
                'name' => 'Red'
            )),
        ));
    }
    
    protected function setItemTest()
    {
        $trixie = $this->createEntity('fairy', array(
            'name' => 'Trixie'
        ));
        
        $blum = $this->createEntity('fairy', array(
            'name' => 'Blum'
        ));
        
        $red = $trixie->flower->create(array('name' => 'Red'));
        $green = $blum->flower->create(array('name' => 'Green'));
        
        $trixie->flower->set($green);
        
        $this->assertSame('Green', $trixie->flower()->name);
        $this->assertSame($green, $trixie->flower());
        $this->assertSame($trixie, $green->owner());
        $this->assertSame($this->itemProperty, $green->ownerPropertyName());
        
        $this->assertSame(null, $blum->flower());
        $this->assertSame(null, $red->owner());
        $this->assertSame(null, $red->ownerPropertyName());
        
        $trixie->save();
        $blum->save();
        
        $idField = $this->idField('fairy');
        $this->assertDataAsObject('fairy', array(
            (object) array( $idField => $blum->id(), 'name' => 'Blum'),
            (object) array( $idField => $trixie->id(), 'name' => 'Trixie', 'flower' => (object) array(
                'name' => 'Green'
            )),
        ));
    }
    
    
    protected function loadItemsTest()
    {
        $this->prepareEntities();
        
        $trixie = $this->orm->get('fairy')->query()
                    ->where('name', 'Trixie')
                    ->findOne();
        
        $this->assertEquals('Red', $trixie->flower()->name);
        
        $pixie = $this->orm->get('fairy')->query()
                    ->where('name', 'Pixie')
                    ->findOne();
        
        $this->assertEquals(null, $pixie->flower());
    }
    
    protected function prepareEntities()
    {
        $map = array(
            'Trixie' => array('Red'),
            'Blum'   => array('Yellow'),
            'Pixie'  => array()
        );
        
        foreach($map as $fairyName => $flowerName) {
            
            $fairy = $this->orm->get('fairy')->create();
            $fairy->name = $fairyName;
            
            if(!empty($flowerName)) {
                $flower = $fairy->flower->create();
                $flower->name = $flowerName[0];
            }
            
            $fairy->save();
        }
        
        return $map;
    }
}